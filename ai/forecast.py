"""Train a seven-day occupancy forecast from synthetic development history."""

import argparse
import json
from datetime import datetime, timedelta, timezone
from pathlib import Path

import numpy as np
import pandas as pd
from sklearn.ensemble import RandomForestRegressor
from sklearn.metrics import mean_absolute_error


def load_history(path):
    frame = pd.read_csv(path)

    required = {
        "date",
        "occupied_rooms",
        "total_rooms",
        "data_source",
    }

    if not required.issubset(frame.columns):
        raise ValueError(
            "CSV needs date, occupied_rooms, "
            "total_rooms and data_source columns."
        )

    if not frame["data_source"].eq("synthetic").all():
        raise ValueError(
            "This script expects the labelled synthetic dataset."
        )

    frame["date"] = pd.to_datetime(
        frame["date"],
        format="%Y-%m-%d",
        errors="raise",
    )

    for name in ["occupied_rooms", "total_rooms"]:
        frame[name] = pd.to_numeric(
            frame[name],
            errors="raise",
        )

        if (
            not np.isfinite(frame[name]).all()
            or (frame[name] % 1 != 0).any()
        ):
            raise ValueError(
                f"{name} must contain whole, finite numbers."
            )

    frame = frame.sort_values("date").reset_index(drop=True)

    if len(frame) < 180:
        raise ValueError(
            "Provide at least 180 consecutive days "
            "for model evaluation."
        )

    if (
        frame["date"].duplicated().any()
        or not frame["date"]
        .diff()
        .iloc[1:]
        .eq(pd.Timedelta(days=1))
        .all()
    ):
        raise ValueError(
            "Dates must be unique and daily, with no missing days."
        )

    capacity = int(frame.iloc[0]["total_rooms"])

    if (
        capacity < 1
        or not frame["total_rooms"].eq(capacity).all()
    ):
        raise ValueError(
            "This version requires one positive, constant room capacity."
        )

    if not frame["occupied_rooms"].between(0, capacity).all():
        raise ValueError(
            "Occupied rooms must be between zero and total rooms."
        )

    return frame, capacity


def features(day, history):
    return [
        day.day_of_week,
        day.month,
        np.sin(2 * np.pi * day.day_of_year / 365.25),
        np.cos(2 * np.pi * day.day_of_year / 365.25),
        history[-1],
        history[-7],
        float(np.mean(history[-7:])),
        float(np.mean(history[-28:])),
    ]


def train(frame):
    history = frame["occupied_rooms"].astype(float).tolist()

    x = [
        features(frame.iloc[i]["date"], history[:i])
        for i in range(28, len(frame))
    ]

    model = RandomForestRegressor(
        n_estimators=120,
        max_depth=9,
        min_samples_leaf=5,
        random_state=42,
        n_jobs=2,
    )

    model.fit(
        np.asarray(x),
        np.asarray(history[28:]),
    )

    return model


def predict_week(model, frame, start, capacity):
    history = frame["occupied_rooms"].astype(float).tolist()
    predictions = []

    for offset in range(7):
        day = pd.Timestamp(start) + pd.Timedelta(days=offset)

        value = float(
            model.predict(
                np.asarray([features(day, history)])
            )[0]
        )

        value = float(np.clip(value, 0, capacity))
        predictions.append(value)

        # Later horizons use predictions, never future actual occupancy.
        history.append(value)

    return predictions


def evaluate(frame, capacity):
    records = []

    # Eight chronological seven-day tests using only earlier observations.
    for origin in range(len(frame) - 56, len(frame), 7):
        past = frame.iloc[:origin].copy()
        actual = frame.iloc[origin:origin + 7]

        model = train(past)

        predicted = predict_week(
            model,
            past,
            actual.iloc[0]["date"],
            capacity,
        )

        baseline = (
            past["occupied_rooms"]
            .tail(7)
            .astype(float)
            .tolist()
        )

        for horizon in range(7):
            records.append({
                "forecast_origin": (
                    actual.iloc[0]["date"].date().isoformat()
                ),
                "date": (
                    actual.iloc[horizon]["date"].date().isoformat()
                ),
                "horizon_days": horizon + 1,
                "actual_rooms": int(
                    actual.iloc[horizon]["occupied_rooms"]
                ),
                "model_rooms": predicted[horizon],
                "baseline_rooms": baseline[horizon],
                "data_source": "synthetic",
            })

    results = pd.DataFrame(records)

    model_mae = float(
        mean_absolute_error(
            results["actual_rooms"],
            results["model_rooms"],
        )
    )

    baseline_mae = float(
        mean_absolute_error(
            results["actual_rooms"],
            results["baseline_rooms"],
        )
    )

    metrics = {
        "data_source": "synthetic",
        "evaluation": "8 expanding-window tests, 7 days each",
        "test_days": len(results),
        "model_mae_rooms": round(model_mae, 4),
        "baseline_mae_rooms": round(baseline_mae, 4),
        "model_mae_percentage_points": round(
            100 * model_mae / capacity,
            4,
        ),
        "baseline_mae_percentage_points": round(
            100 * baseline_mae / capacity,
            4,
        ),
        "model_beats_baseline_on_synthetic_history": (
            model_mae < baseline_mae
        ),
        "limitation": (
            "Synthetic test results do not measure "
            "accuracy on real hotel stays."
        ),
    }

    return results, metrics


def main():
    script_directory = Path(__file__).resolve().parent

    parser = argparse.ArgumentParser(description=__doc__)

    parser.add_argument(
        "--input",
        type=Path,
        default=(
            script_directory
            / "data"
            / "occupancy_history.csv"
        ),
    )

    parser.add_argument(
        "--output-dir",
        type=Path,
        default=script_directory / "csv_forecast_output",
    )

    args = parser.parse_args()

    try:
        frame, capacity = load_history(args.input)
    except (OSError, ValueError) as error:
        parser.error(str(error))

    as_of = frame.iloc[-1]["date"].date() + timedelta(days=1)

    output = args.output_dir
    output.mkdir(parents=True, exist_ok=True)

    print(
        "AI Occupancy Forecast - "
        "training source: synthetic development history."
    )
    print(f"Loaded {len(frame)} days; capacity {capacity} rooms.")
    print(f"Forecast starts {as_of}; the day after the CSV ends.")
    print("Evaluating eight seven-day forecasts...")

    backtest, metrics = evaluate(frame, capacity)

    backtest.to_csv(
        output / "synthetic_backtest.csv",
        index=False,
    )

    (output / "evaluation.json").write_text(
        json.dumps(
            metrics,
            indent=2,
            allow_nan=False,
        ),
        encoding="utf-8",
    )

    final_model = train(frame)

    predicted = predict_week(
        final_model,
        frame,
        as_of,
        capacity,
    )

    baseline = (
        frame["occupied_rooms"]
        .tail(7)
        .astype(float)
        .tolist()
    )

    days = []

    for offset, value in enumerate(predicted):
        days.append({
            "date": (
                as_of + timedelta(days=offset)
            ).isoformat(),
            "expected_occupied_rooms": round(value, 2),
            "total_rooms": capacity,
            "predicted_occupancy_percent": round(
                100 * value / capacity,
                2,
            ),
            "baseline_occupied_rooms": baseline[offset],
        })

    payload = {
        "mode": "synthetic",
        "data_source": "synthetic",
        "generated_at": datetime.now(timezone.utc).isoformat(),
        "is_real_hotel_forecast": False,
        "as_of": as_of.isoformat(),
        "input_file": args.input.name,
        "training_start": (
            frame.iloc[0]["date"].date().isoformat()
        ),
        "training_end": (
            frame.iloc[-1]["date"].date().isoformat()
        ),
        "training_days": len(frame),
        "assumptions": [
            "Development booking history labelled synthetic",
            "Current room inventory treated as constant throughout history",
            "Recorded booking dates; historical inventory changes are not reconstructed",
        ],
        "model": "RandomForestRegressor",
        "baseline": "Same weekday last week",
        "evaluation": metrics,
        "forecast": days,
    }

    (output / "occupancy_forecast.json").write_text(
        json.dumps(
            payload,
            indent=2,
            allow_nan=False,
        ),
        encoding="utf-8",
    )

    print(f"Model MAE: {metrics['model_mae_rooms']:.2f} rooms")
    print(f"Baseline MAE: {metrics['baseline_mae_rooms']:.2f} rooms")
    print("These are synthetic-data errors, not real-world accuracy.")
    print(pd.DataFrame(days).to_string(index=False))
    print(f"Files saved to: {output}")


if __name__ == "__main__":
    main()