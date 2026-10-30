<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\OccupancyForecastFile;
use Carbon\CarbonImmutable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use RuntimeException;
use Symfony\Component\Process\Process;
use Throwable;

class OccupancyForecastController extends Controller
{
    public function refresh(
        Request $request,
        OccupancyForecastFile $forecastFile
    ): RedirectResponse {
        $user = $request->user();

        abort_unless(
            $user instanceof User
                && $user->hasAnyRole(['admin', 'manager']),
            403
        );

        $lock = null;
        $acquired = false;
        $runDirectory = null;
        $stage = 'starting the refresh';

        try {
            $lock = Cache::lock(
                'hotel:occupancy-forecast-refresh',
                180
            );

            if (!$lock->get()) {
                return redirect()
                    ->route('dashboard')
                    ->with(
                        'forecast_error',
                        'A forecast refresh is already running. Try again shortly.'
                    );
            }

            $acquired = true;

            if (function_exists('set_time_limit')) {
                set_time_limit(120);
            }

            $python = PHP_OS_FAMILY === 'Windows'
                ? base_path('.venv/Scripts/python.exe')
                : base_path('.venv/bin/python');

            $script = base_path('ai/forecast.py');

            if (!is_file($python) || !is_file($script)) {
                return redirect()
                    ->route('dashboard')
                    ->with(
                        'forecast_error',
                        'The Python environment or ai/forecast.py is missing. Check the installation steps.'
                    );
            }

            $stage = 'exporting booking history';

            [
                $csv,
                $trainingEnd,
                $trainingDays,
                $capacity,
            ] = $this->exportHistory();

            $runDirectory = storage_path(
                'app/forecast_runs/' . bin2hex(random_bytes(12))
            );

            File::ensureDirectoryExists($runDirectory);

            $inputPath = $runDirectory . '/occupancy_history.csv';

            if (File::put($inputPath, $csv, true) === false) {
                throw new RuntimeException(
                    'Cannot write the occupancy export.'
                );
            }

            $stage = 'running Python';

            $process = new Process(
                [
                    $python,
                    $script,
                    '--input',
                    $inputPath,
                    '--output-dir',
                    $runDirectory,
                ],
                base_path(),
                [
                    'PYTHONUTF8' => '1',
                    'PYTHONIOENCODING' => 'utf-8',
                ]
            );

            $process->setTimeout(90);
            $process->mustRun();

            $stage = 'validating the forecast';

            $candidatePath = $runDirectory
                . '/occupancy_forecast.json';

            $candidate = $forecastFile->load($candidatePath);

            if (!$candidate['ready']) {
                throw new RuntimeException(
                    'Python returned an invalid forecast.'
                );
            }

            $expectedStart = $trainingEnd
                ->addDay()
                ->toDateString();

            if (
                $candidate['days'][0]['date'] !== $expectedStart
                || $candidate['training_days'] !== $trainingDays
                || $candidate['capacity'] !== $capacity
            ) {
                throw new RuntimeException(
                    'Forecast does not match the exported history.'
                );
            }

            $stage = 'saving the forecast';

            $dataDirectory = base_path('ai/data');

            $outputDirectory = base_path(
                'ai/csv_forecast_output'
            );

            File::ensureDirectoryExists($dataDirectory);
            File::ensureDirectoryExists($outputDirectory);

            File::replace(
                $dataDirectory . '/occupancy_history.csv',
                $csv
            );

            foreach (
                ['evaluation.json', 'synthetic_backtest.csv']
                as $filename
            ) {
                File::replace(
                    $outputDirectory . '/' . $filename,
                    File::get(
                        $runDirectory . '/' . $filename
                    )
                );
            }

            // Publish only after Python succeeds and validation passes.
            File::replace(
                $outputDirectory . '/occupancy_forecast.json',
                File::get($candidatePath)
            );

            return redirect()
                ->route('dashboard')
                ->with(
                    'forecast_success',
                    'Forecast refreshed successfully using '
                        . number_format($trainingDays)
                        . ' days of synthetic booking history.'
                );
        } catch (Throwable $exception) {
            report($exception);

            return redirect()
                ->route('dashboard')
                ->with(
                    'forecast_error',
                    'Forecast refresh failed while '
                        . $stage
                        . '. The previous forecast was kept. '
                        . 'Check storage/logs/laravel.log.'
                );
        } finally {
            try {
                if (
                    $runDirectory !== null
                    && is_dir($runDirectory)
                ) {
                    File::deleteDirectory($runDirectory);
                }
            } catch (Throwable $exception) {
                report($exception);
            }

            if ($acquired && $lock !== null) {
                try {
                    $lock->release();
                } catch (Throwable $exception) {
                    report($exception);
                }
            }
        }
    }

    private function exportHistory(): array
    {
        $end = CarbonImmutable::yesterday();
        $windowStart = $end->subDays(729);

        // This transaction only reads existing records.
        [$roomIds, $records] = DB::transaction(
            function () use ($end, $windowStart) {
                $roomIds = DB::table('rooms')
                    ->pluck('id')
                    ->all();

                $records = DB::table('bookings')
                    ->whereIn('room_id', $roomIds)
                    ->whereIn(
                        'status',
                        ['checked_in', 'checked_out']
                    )
                    ->where(
                        'check_in',
                        '<',
                        $end->addDay()->toDateString()
                    )
                    ->where(
                        'check_out',
                        '>',
                        $windowStart->toDateString()
                    )
                    ->orderBy('check_in')
                    ->get([
                        'room_id',
                        'check_in',
                        'check_out',
                    ]);

                return [$roomIds, $records];
            }
        );

        $capacity = count($roomIds);

        if ($capacity < 1 || $records->isEmpty()) {
            throw new RuntimeException(
                'No qualifying occupancy history is available.'
            );
        }

        $firstCheckIn = CarbonImmutable::parse(
            (string) $records->first()->check_in
        )->startOfDay();

        $start = $firstCheckIn->greaterThan($windowStart)
            ? $firstCheckIn
            : $windowStart;

        $trainingDays = (int) $start->diffInDays($end) + 1;

        if ($trainingDays < 180) {
            throw new RuntimeException(
                'At least 180 days of history are required.'
            );
        }

        $occupied = [];

        foreach ($records as $record) {
            $first = CarbonImmutable::parse(
                (string) $record->check_in
            )->startOfDay();

            $last = CarbonImmutable::parse(
                (string) $record->check_out
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

                $occupied[$date][(int) $record->room_id] = true;
            }
        }

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

        return [
            $csv,
            $end,
            $trainingDays,
            $capacity,
        ];
    }
}