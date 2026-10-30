<?php

namespace App\Console\Commands;

use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Throwable;

class ExportOccupancyHistory extends Command
{
    protected $signature = 'hotel:export-occupancy
                            {--from= : First date, YYYY-MM-DD}
                            {--to= : Last date, YYYY-MM-DD; defaults to yesterday}';

    protected $description =
        'Export historical room nights from checked-in and checked-out bookings';

    public function handle(): int
    {
        $fromOption = $this->option('from');

        if (!is_string($fromOption) || $fromOption === '') {
            $this->error('Enter the first date using --from=YYYY-MM-DD.');

            return self::FAILURE;
        }

        $toOption = $this->option('to');

        if (!is_string($toOption) || $toOption === '') {
            $toOption = CarbonImmutable::yesterday()->format('Y-m-d');
        }

        try {
            $from = $this->parseDate($fromOption);
            $to = $this->parseDate($toOption);
        } catch (Throwable) {
            $this->error('Use valid dates in YYYY-MM-DD format.');

            return self::FAILURE;
        }

        if ($from->greaterThan($to)) {
            $this->error('The first date must be before or equal to the last date.');

            return self::FAILURE;
        }

        if ($to->greaterThanOrEqualTo(CarbonImmutable::today())) {
            $this->error('Choose yesterday or an earlier date.');

            return self::FAILURE;
        }

        if ($from->diffInDays($to) > 3660) {
            $this->error('Export no more than ten years at a time.');

            return self::FAILURE;
        }

        $fromDate = $from->format('Y-m-d');
        $toDate = $to->format('Y-m-d');

        $bookings = DB::table('bookings')
            ->select(['room_id', 'check_in', 'check_out'])
            ->whereIn('status', ['checked_in', 'checked_out'])
            ->where('check_in', '<', $to->addDay()->format('Y-m-d'))
            ->where('check_out', '>', $fromDate)
            ->get();

        $roomsByDate = [];

        foreach ($bookings as $booking) {
            $checkIn = $this->parseDate(
                substr((string) $booking->check_in, 0, 10)
            );

            $checkOut = $this->parseDate(
                substr((string) $booking->check_out, 0, 10)
            );

            $firstNight = $checkIn->lessThan($from) ? $from : $checkIn;

            $endExclusive = $checkOut->greaterThan($to->addDay())
                ? $to->addDay()
                : $checkOut;

            for (
                $night = $firstNight;
                $night->lessThan($endExclusive);
                $night = $night->addDay()
            ) {
                $date = $night->format('Y-m-d');

                $roomsByDate[$date][(int) $booking->room_id] = true;
            }
        }

        $directory = storage_path('app/occupancy');

        if (
            !is_dir($directory)
            && !mkdir($directory, 0755, true)
            && !is_dir($directory)
        ) {
            $this->error('The export folder could not be created.');

            return self::FAILURE;
        }

        $filename = sprintf(
            'recorded_room_nights_%s_to_%s_%s.csv',
            $fromDate,
            $toDate,
            bin2hex(random_bytes(4))
        );

        $path = $directory . DIRECTORY_SEPARATOR . $filename;
        $handle = fopen($path, 'xb');

        if ($handle === false) {
            $this->error('The CSV file could not be opened.');

            return self::FAILURE;
        }

        $rows = 0;
        $roomNights = 0;

        try {
            $this->writeRow($handle, [
                'date',
                'recorded_occupied_rooms',
                'data_source',
                'measurement_basis',
                'history_completeness',
            ]);

            for (
                $day = $from;
                $day->lessThanOrEqualTo($to);
                $day = $day->addDay()
            ) {
                $date = $day->format('Y-m-d');
                $count = count($roomsByDate[$date] ?? []);

                $this->writeRow($handle, [
                    $date,
                    $count,
                    'hotel_database',
                    'checked_in_and_checked_out_booking_dates',
                    'not_verified',
                ]);

                $rows++;
                $roomNights += $count;
            }
        } catch (Throwable $exception) {
            fclose($handle);
            unlink($path);

            report($exception);

            $this->error('Export failed. The incomplete file was removed.');

            return self::FAILURE;
        }

        fclose($handle);

        $this->info('Occupancy records exported.');
        $this->line('Days exported: ' . $rows);
        $this->line('Recorded room nights: ' . $roomNights);
        $this->line('File: ' . $path);

        $this->warn(
            'A zero means no qualifying booking was recorded; '
            . 'it does not prove the hotel was empty.'
        );

        $this->warn(
            'Historical room capacity and completeness are not verified. '
            . 'Review this file before using it for model training.'
        );

        return self::SUCCESS;
    }

    private function parseDate(string $value): CarbonImmutable
    {
        $date = CarbonImmutable::createFromFormat('!Y-m-d', $value);

        if ($date === false || $date->format('Y-m-d') !== $value) {
            throw new \InvalidArgumentException('Invalid date.');
        }

        return $date;
    }

    /**
     * @param resource $handle
     * @param array<int, int|string> $values
     */
    private function writeRow($handle, array $values): void
    {
        if (fputcsv($handle, $values, ',', '"', '') === false) {
            throw new \RuntimeException('CSV write failed.');
        }
    }
}