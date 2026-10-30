<?php

namespace App\Console\Commands;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Throwable;

class GenerateOccupancyHistory extends Command
{
    protected $signature = 'hotel:generate-occupancy-history';

    protected $description =
        'Generate synthetic historical stays and export occupancy data';

    public function handle(): int
    {
        $end = CarbonImmutable::yesterday();
        $start = $end->subDays(729);

        try {
            [$created, $csv] = DB::transaction(function () use (
                $start,
                $end
            ) {
                $rooms = Room::orderBy('id')
                    ->lockForUpdate()
                    ->get();

                if ($rooms->isEmpty()) {
                    throw new \RuntimeException(
                        'Add at least one room first.'
                    );
                }

                foreach ($rooms as $room) {
                    if (
                        !is_numeric($room->price)
                        || $room->price < 0
                    ) {
                        throw new \RuntimeException(
                            'Every room needs a valid non-negative price.'
                        );
                    }
                }

                $guest = Guest::firstOrCreate(
                    [
                        'email' => 'sample-history@example.invalid',
                    ],
                    [
                        'full_name' => 'Sample History Guest',
                        'phone' => 'SAMPLE-DATA',
                    ]
                );

                $blocked = [];
                $occupied = [];

                $existing = Booking::whereIn(
                    'room_id',
                    $rooms->modelKeys()
                )
                    ->where(
                        'check_in',
                        '<',
                        $end->addDay()->toDateString()
                    )
                    ->where(
                        'check_out',
                        '>',
                        $start->toDateString()
                    )
                    ->lockForUpdate()
                    ->get();

                foreach ($existing as $booking) {
                    $first = CarbonImmutable::parse(
                        (string) $booking->check_in
                    )->startOfDay();

                    $last = CarbonImmutable::parse(
                        (string) $booking->check_out
                    )->startOfDay();

                    $first = $first->lessThan($start)
                        ? $start
                        : $first;

                    $last = $last->greaterThan($end->addDay())
                        ? $end->addDay()
                        : $last;

                    for (
                        $day = $first;
                        $day->lessThan($last);
                        $day = $day->addDay()
                    ) {
                        $date = $day->toDateString();
                        $roomId = (int) $booking->room_id;

                        if ($booking->status !== 'cancelled') {
                            $blocked[$date][$roomId] = true;
                        }

                        if (
                            in_array(
                                $booking->status,
                                ['checked_in', 'checked_out'],
                                true
                            )
                        ) {
                            $occupied[$date][$roomId] = true;
                        }
                    }
                }

                $created = 0;

                for (
                    $day = $start;
                    $day->lessThanOrEqualTo($end);
                    $day = $day->addDay()
                ) {
                    $date = $day->toDateString();

                    // Synthetic weekly and seasonal patterns.
                    $weekend = in_array(
                        $day->dayOfWeek,
                        [5, 6],
                        true
                    );

                    $probability = 0.52
                        + ($weekend ? 0.20 : 0.0)
                        + 0.12 * sin(
                            2 * M_PI * $day->dayOfYear / 365.25
                        );

                    foreach ($rooms as $room) {
                        $roomId = (int) $room->id;

                        // Deterministic selection makes reruns repeatable.
                        $hash = substr(
                            hash(
                                'sha256',
                                'sample-history|'
                                    . $date
                                    . '|'
                                    . $roomId
                            ),
                            0,
                            8
                        );

                        $draw = hexdec($hash) / 4294967296;

                        if (
                            isset($blocked[$date][$roomId])
                            || $draw >= $probability
                        ) {
                            continue;
                        }

                        $sample = new Booking([
                            'room_id' => $roomId,
                            'guest_id' => $guest->id,
                            'check_in' => $date,
                            'check_out' => $day
                                ->addDay()
                                ->toDateString(),
                            'status' => 'checked_out',
                            'payment_status' => 'verified',
                            'payment_method' => 'cash',
                            'paid_amount' => $room->price,
                        ]);

                        $departure = $day
                            ->addDay()
                            ->setTime(11, 0);

                        $sample->actual_departed_at = $departure;
                        $sample->checkout_completed_at = $departure;

                        $sample->created_at = $day
                            ->subDays(7)
                            ->setTime(12, 0);

                        $sample->updated_at = $departure;

                        // Your model sets agreed_rate when saving.
                        $sample->save();

                        $blocked[$date][$roomId] = true;
                        $occupied[$date][$roomId] = true;

                        $created++;
                    }
                }

                $capacity = $rooms->count();

                $csv = 'date,occupied_rooms,total_rooms,'
                    . 'occupancy_percent,data_source,is_synthetic'
                    . "\n";

                for (
                    $day = $start;
                    $day->lessThanOrEqualTo($end);
                    $day = $day->addDay()
                ) {
                    $date = $day->toDateString();
                    $count = count($occupied[$date] ?? []);

                    $percent = number_format(
                        100 * $count / $capacity,
                        2,
                        '.',
                        ''
                    );

                    $csv .= "$date,$count,$capacity,"
                        . "$percent,synthetic,true\n";
                }

                return [$created, $csv];
            });
        } catch (Throwable $exception) {
            report($exception);

            $this->error(
                'History was not committed. '
                . 'Check storage/logs/laravel.log.'
            );

            return self::FAILURE;
        }

        $path = base_path('ai/data/occupancy_history.csv');

        try {
            File::ensureDirectoryExists(dirname($path));

            if (File::put($path, $csv, true) === false) {
                throw new \RuntimeException('CSV write failed.');
            }
        } catch (Throwable $exception) {
            report($exception);

            $this->error(
                'Bookings were saved, but CSV export failed. '
                . 'Fix folder permissions and rerun.'
            );

            return self::FAILURE;
        }

        $this->info('Occupancy history generated successfully.');

        $this->line('New historical bookings: ' . $created);
        $this->line('Days exported: 730');

        $this->line(
            'Period: '
            . $start->toDateString()
            . ' to '
            . $end->toDateString()
        );

        $this->line('CSV: ' . $path);

        $this->line(
            'Training source: synthetic historical stays.'
        );

        return self::SUCCESS;
    }
}
