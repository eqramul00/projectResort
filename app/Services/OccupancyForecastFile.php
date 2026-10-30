<?php

namespace App\Services;

use DateTimeImmutable;
use RuntimeException;
use Throwable;

class OccupancyForecastFile
{
    public function load(?string $path = null): array
    {
        $unavailable = [
            'ready' => false,
            'message' => 'No forecast is available yet. An admin or manager can refresh it.',
            'last_updated' => null,
        ];

        if ($path === null) {
            $path = base_path(
                'ai/csv_forecast_output/occupancy_forecast.json'
            );

            if (!is_file($path)) {
                $path = base_path(
                    'ai/csv_forecast_output/demo_forecast.json'
                );
            }
        }

        clearstatcache(true, $path);

        if (!is_file($path)) {
            return $unavailable;
        }

        try {
            if (
                !is_readable($path)
                || filesize($path) > 1048576
            ) {
                throw new RuntimeException(
                    'Unreadable forecast file.'
                );
            }

            $contents = file_get_contents($path);

            if ($contents === false) {
                throw new RuntimeException(
                    'Cannot read forecast file.'
                );
            }

            $data = json_decode(
                $contents,
                true,
                512,
                JSON_THROW_ON_ERROR
            );

            if (
                !is_array($data)
                || !in_array(
                    $data['mode'] ?? null,
                    ['demo', 'synthetic'],
                    true
                )
                || ($data['data_source'] ?? null) !== 'synthetic'
                || ($data['is_real_hotel_forecast'] ?? null) !== false
                || !is_array($data['forecast'] ?? null)
                || !array_is_list($data['forecast'])
                || count($data['forecast']) !== 7
            ) {
                throw new RuntimeException(
                    'Unexpected forecast format.'
                );
            }

            $parseDate = static function ($value): DateTimeImmutable {
                if (!is_string($value)) {
                    throw new RuntimeException(
                        'Missing forecast date.'
                    );
                }

                $date = DateTimeImmutable::createFromFormat(
                    '!Y-m-d',
                    $value
                );

                if (
                    !$date
                    || $date->format('Y-m-d') !== $value
                ) {
                    throw new RuntimeException(
                        'Invalid forecast date.'
                    );
                }

                return $date;
            };

            $isNumber = static fn ($value): bool =>
                (is_int($value) || is_float($value))
                && is_finite((float) $value);

            $asOf = $parseDate(
                $data['as_of'] ?? null
            );

            $trainingStart = $parseDate(
                $data['training_start'] ?? null
            );

            $trainingEnd = $parseDate(
                $data['training_end'] ?? null
            );

            $trainingDays = $data['training_days'] ?? null;

            if (
                !is_int($trainingDays)
                || $trainingDays < 180
                || $trainingEnd < $trainingStart
                || $trainingEnd->modify('+1 day') != $asOf
                || (
                    (int) $trainingStart->diff($trainingEnd)->days + 1
                ) !== $trainingDays
            ) {
                throw new RuntimeException(
                    'Invalid training period.'
                );
            }

            $capacity = $data['forecast'][0]['total_rooms'] ?? null;

            if (!is_int($capacity) || $capacity < 1) {
                throw new RuntimeException(
                    'Invalid forecast capacity.'
                );
            }

            $days = [];

            foreach ($data['forecast'] as $index => $row) {
                if (!is_array($row)) {
                    throw new RuntimeException(
                        'Invalid forecast row.'
                    );
                }

                $date = $asOf->modify("+{$index} days");

                $rooms = $row['expected_occupied_rooms'] ?? null;
                $percent = $row['predicted_occupancy_percent'] ?? null;

                if (
                    ($row['date'] ?? null) !== $date->format('Y-m-d')
                    || ($row['total_rooms'] ?? null) !== $capacity
                    || !$isNumber($rooms)
                    || $rooms < 0
                    || $rooms > $capacity
                    || !$isNumber($percent)
                    || $percent < 0
                    || $percent > 100
                    || abs(
                        $percent - 100 * $rooms / $capacity
                    ) > 0.1
                ) {
                    throw new RuntimeException(
                        'Invalid forecast values.'
                    );
                }

                $days[] = [
                    'date' => $date->format('Y-m-d'),
                    'label' => $date->format('D, d M'),
                    'expected_rooms' => (float) $rooms,
                    'percent' => (float) $percent,
                    'confirmed_rooms' => null,
                ];
            }

            $metrics = $data['evaluation'] ?? [];

            if (!is_array($metrics)) {
                throw new RuntimeException(
                    'Invalid evaluation.'
                );
            }

            foreach (
                ['model_mae_rooms', 'baseline_mae_rooms']
                as $key
            ) {
                if (
                    !$isNumber($metrics[$key] ?? null)
                    || $metrics[$key] < 0
                ) {
                    throw new RuntimeException(
                        'Invalid evaluation values.'
                    );
                }
            }

            return [
                'ready' => true,
                'last_updated' =>
                    \Illuminate\Support\Carbon::createFromTimestamp(
                        filemtime($path),
                        config('app.timezone')
                    )->format('d M Y, H:i:s T'),
                'days' => $days,
                'capacity' => $capacity,
                'training_days' => $trainingDays,
                'training_period' =>
                    $trainingStart->format('d M Y')
                    . ' – '
                    . $trainingEnd->format('d M Y'),
                'period' =>
                    $asOf->format('d M Y')
                    . ' – '
                    . $asOf->modify('+6 days')->format('d M Y'),
                'starts_today' =>
                    $asOf->format('Y-m-d')
                    === now()->toDateString(),
                'model_mae' =>
                    (float) $metrics['model_mae_rooms'],
                'baseline_mae' =>
                    (float) $metrics['baseline_mae_rooms'],
            ];
        } catch (Throwable $exception) {
            report($exception);

            $unavailable['message'] =
                'Forecast file could not be loaded or validated. '
                . 'Refresh it again or check the application log.';

            return $unavailable;
        }
    }
}