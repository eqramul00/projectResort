<?php

namespace App\Mail;

use App\Models\Booking;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BookingReceiptMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking->load(['room', 'guest']);
    }

    public function build()
    {
        $pdf = Pdf::loadView('bookings.invoice', [
            'booking' => $this->booking,
        ]);

        return $this->subject('Your Booking Receipt - Resort Sajek')
            ->view('emails.booking-receipt')
            ->with([
                'booking' => $this->booking,
            ])
            ->attachData(
                $pdf->output(),
                'booking-receipt-' . $this->booking->id . '.pdf',
                [
                    'mime' => 'application/pdf',
                ]
            );
    }
}