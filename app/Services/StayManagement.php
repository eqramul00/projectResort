<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingStayPayment;
use App\Models\Room;
use Illuminate\Support\Carbon;
use Illuminate\Validation\ValidationException;

class StayManagement
{
    public function deadline(string $date): Carbon
    {
        return Carbon::parse(
            $date,
            config('hotel.timezone', 'Europe/London')
        )->setTimeFromTimeString(
            config('hotel.checkout_time', '12:00')
        );
    }

    public function summary(Booking $booking): array
    {
        $timezone = config('hotel.timezone', 'Europe/London');

        $start = Carbon::parse($booking->check_in, $timezone)
            ->startOfDay();

        $end = Carbon::parse($booking->check_out, $timezone)
            ->startOfDay();

        $priced = $booking->agreed_rate !== null
            && is_numeric($booking->agreed_rate)
            && (float) $booking->agreed_rate >= 0
            && $end->greaterThan($start);

        $rate = $priced
            ? $this->minor($booking->agreed_rate)
            : 0;

        $nights = $end->greaterThan($start)
            ? (int) $start->diffInDays($end)
            : 0;

        $deadline = $this->deadline($booking->check_out);

        // A historical closed booking must not accrue fees using today's date.
        $departure = $booking->actual_departed_at !== null
            ? Carbon::parse($booking->actual_departed_at)
                ->setTimezone($timezone)
            : (
                $booking->status === 'checked_in'
                    ? Carbon::now($timezone)
                    : null
            );

        $lateNights = 0;

        if (
            in_array(
                $booking->status,
                ['checked_in', 'checked_out'],
                true
            )
            && $departure !== null
            && $departure->greaterThan($deadline)
        ) {
            // Count local calendar days, including UK clock changes.
            $days = (int) $deadline->copy()
                ->startOfDay()
                ->diffInDays(
                    $departure->copy()->startOfDay()
                );

            $boundary = $deadline->copy()->addDays($days);

            $lateNights = max(
                1,
                $days + (
                    $departure->greaterThan($boundary)
                        ? 1
                        : 0
                )
            );
        }

        $originalPaid = $booking->payment_status === 'verified'
            ? max(0, $this->minor($booking->paid_amount ?? 0))
            : 0;

        $additionalPaid = $this->minor(
            $booking->stayPayments()
                ->whereNotNull('verified_at')
                ->sum('amount')
        );

        $scheduled = $priced ? $rate * $nights : null;
        $lateCharge = $priced ? $rate * $lateNights : null;
        $total = $priced ? $scheduled + $lateCharge : null;
        $paid = $originalPaid + $additionalPaid;

        return [
            'priced' => $priced,
            'rate' => $rate,
            'nights' => $nights,
            'late_nights' => $lateNights,
            'deadline' => $deadline,
            'scheduled' => $scheduled,
            'late_charge' => $lateCharge,
            'total' => $total,
            'original_paid' => $originalPaid,
            'additional_paid' => $additionalPaid,
            'paid' => $paid,
            'balance' => $priced
                ? max($total - $paid, 0)
                : null,
            'credit' => $priced
                ? max($paid - $total, 0)
                : null,
        ];
    }

    public function extend(
        Booking $booking,
        Room $room,
        array $data,
        int $actorId,
        BookingAvailability $availability
    ): void {
        $this->ensureCheckedIn($booking);

        if ($booking->actual_departed_at) {
            $this->fail(
                'A departed guest cannot extend this stay.'
            );
        }

        if ($booking->check_out !== $data['expected_checkout']) {
            $this->fail(
                'The checkout date changed. Refresh and review the new quote.'
            );
        }

        $summary = $this->summary($booking);

        if (!$summary['priced']) {
            $this->fail(
                'The agreed rate or booking dates require review.'
            );
        }

        if ($booking->payment_status !== 'verified') {
            $this->fail(
                'Verify the original payment before extending the stay.'
            );
        }

        if ($summary['paid'] < $summary['scheduled']) {
            $this->fail(
                'Settle the existing scheduled stay balance first.'
            );
        }

        if (
            $booking->stayPayments()
                ->where('type', 'overstay')
                ->exists()
        ) {
            $this->fail(
                'An overstay settlement has already been recorded. Do not convert it into an extension.'
            );
        }

        $oldDate = Carbon::parse($booking->check_out)
            ->startOfDay();

        $newDate = Carbon::parse($data['new_checkout'])
            ->startOfDay();

        if (!$newDate->greaterThan($oldDate)) {
            $this->fail(
                'The new checkout date must be after the current checkout date.'
            );
        }

        if (!$this->deadline($data['new_checkout'])->isFuture()) {
            $this->fail(
                'The new checkout deadline must still be in the future.'
            );
        }

        $availability->ensureAvailable(
            $room,
            $booking->check_out,
            $data['new_checkout'],
            $booking->id
        );

        $extraNights = (int) $oldDate->diffInDays($newDate);
        $required = $summary['rate'] * $extraNights;

        $this->ensureExactPayment(
            $required,
            $data['amount']
        );

        $this->ensureUnusedOperation(
            $data['operation_key']
        );

        $booking->stayPayments()->create([
            'recorded_by' => $actorId,
            'operation_key' => $data['operation_key'],
            'type' => 'extension',
            'previous_check_out' => $booking->check_out,
            'new_check_out' => $data['new_checkout'],
            'extra_nights' => $extraNights,
            'nightly_rate' => $booking->agreed_rate,
            'amount' => number_format(
                $required / 100,
                2,
                '.',
                ''
            ),
            'payment_method' => $data['payment_method'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'notes' => $data['notes'] ?? null,
            'verified_at' => Carbon::now(),
        ]);

        $booking->check_out = $data['new_checkout'];
        $booking->save();
    }

    public function settle(
        Booking $booking,
        array $data,
        int $actorId
    ): void {
        $this->ensureCheckedIn($booking);

        $summary = $this->summary($booking);

        if (!$summary['priced']) {
            $this->fail(
                'The agreed rate or booking dates require review.'
            );
        }

        if ($summary['balance'] <= 0) {
            $this->fail(
                'There is no outstanding balance to collect.'
            );
        }

        $this->ensureExactPayment(
            $summary['balance'],
            $data['amount']
        );

        $this->ensureUnusedOperation(
            $data['operation_key']
        );

        $booking->stayPayments()->create([
            'recorded_by' => $actorId,
            'operation_key' => $data['operation_key'],
            'type' => $summary['late_nights'] > 0
                ? 'overstay'
                : 'settlement',
            'previous_check_out' => $booking->check_out,
            'extra_nights' => $summary['late_nights'],
            'nightly_rate' => $booking->agreed_rate,
            'amount' => number_format(
                $summary['balance'] / 100,
                2,
                '.',
                ''
            ),
            'payment_method' => $data['payment_method'],
            'transaction_id' => $data['transaction_id'] ?? null,
            'notes' => $data['notes'] ?? null,
            'verified_at' => Carbon::now(),
        ]);
    }

    public function recordDeparture(
        Booking $booking,
        Room $room
    ): void {
        $this->ensureCheckedIn($booking);

        if (!$booking->actual_departed_at) {
            $booking->actual_departed_at = Carbon::now();
            $booking->save();
        }

        $this->releaseRoom($booking, $room);
    }

    public function completeCheckout(
        Booking $booking,
        Room $room
    ): void {
        $this->ensureCheckedIn($booking);

        $summary = $this->summary($booking);

        if (!$summary['priced']) {
            $this->fail(
                'The bill requires rate/date review before financial checkout.'
            );
        }

        if ($summary['balance'] > 0) {
            $this->fail(
                'Checkout cannot be completed. Outstanding balance: BDT '
                . number_format($summary['balance'] / 100, 2)
                . '. Open Stay Management to settle it.'
            );
        }

        if (!$booking->actual_departed_at) {
            $booking->actual_departed_at = Carbon::now();
        }

        $booking->checkout_completed_at = Carbon::now();
        $booking->status = 'checked_out';
        $booking->save();

        $this->releaseRoom($booking, $room);
    }

    private function releaseRoom(
        Booking $booking,
        Room $room
    ): void {
        if ($room->status !== 'occupied') {
            return;
        }

        $anotherGuest = Booking::where('room_id', $room->id)
            ->where('id', '!=', $booking->id)
            ->where('status', 'checked_in')
            ->whereNull('actual_departed_at')
            ->lockForUpdate()
            ->first();

        if (!$anotherGuest) {
            $room->update([
                'status' => 'available',
            ]);
        }
    }

    private function ensureCheckedIn(Booking $booking): void
    {
        if ($booking->status !== 'checked_in') {
            $this->fail(
                'This action is available only for checked-in stays.'
            );
        }
    }

    private function ensureUnusedOperation(string $key): void
    {
        if (
            BookingStayPayment::where('operation_key', $key)
                ->exists()
        ) {
            $this->fail(
                'This payment was already processed. Refresh the page.'
            );
        }
    }

    private function ensureExactPayment(
        int $required,
        string|int|float $amount
    ): void {
        if ($this->minor($amount) !== $required) {
            $this->fail(
                'The required amount is '
                . number_format($required / 100, 2)
                . '. Refresh the quote before recording payment.'
            );
        }
    }

    private function minor(string|int|float $amount): int
    {
        return (int) round((float) $amount * 100);
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages([
            'stay' => $message,
        ]);
    }
}