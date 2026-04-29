<?php

namespace Tests\Feature;

use App\Models\Booking;
use App\Models\Guest;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookingFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_backup_admin_can_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'backup@resort.com',
            'password' => 'backup1234',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_public_booking_creates_pending_booking(): void
    {
        $room = Room::create([
            'number' => '101',
            'name' => 'Hill View Deluxe',
            'type' => 'Deluxe',
            'price' => 5000,
            'status' => 'available',
        ]);

        $response = $this->post('/book', [
            'room_id' => $room->id,
            'name' => 'Test Guest',
            'phone' => '01700000000',
            'check_in' => '2026-01-10',
            'check_out' => '2026-01-12',
        ]);

        $response->assertRedirect(route('public.book'));
        $response->assertSessionHas('success', 'Booking request sent successfully.');

        $this->assertDatabaseHas('guests', [
            'full_name' => 'Test Guest',
            'phone' => '01700000000',
        ]);

        $this->assertDatabaseHas('bookings', [
            'room_id' => $room->id,
            'status' => 'pending',
        ]);
    }

    public function test_public_booking_blocks_overlapping_dates(): void
    {
        $room = Room::create([
            'number' => '102',
            'name' => 'Couple Suite',
            'type' => 'Suite',
            'price' => 7000,
            'status' => 'available',
        ]);

        $guest = Guest::create([
            'full_name' => 'Existing Guest',
            'phone' => '01800000000',
        ]);

        Booking::create([
            'room_id' => $room->id,
            'guest_id' => $guest->id,
            'check_in' => '2026-01-10',
            'check_out' => '2026-01-15',
            'status' => 'confirmed',
        ]);

        $response = $this->post('/book', [
            'room_id' => $room->id,
            'name' => 'New Guest',
            'phone' => '01900000000',
            'check_in' => '2026-01-12',
            'check_out' => '2026-01-14',
        ]);

        $response->assertRedirect(route('public.book'));
        $response->assertSessionHas('error', 'This room is already booked for the selected dates.');

        $this->assertDatabaseMissing('bookings', [
            'room_id' => $room->id,
            'check_in' => '2026-01-12',
            'check_out' => '2026-01-14',
        ]);
    }
}