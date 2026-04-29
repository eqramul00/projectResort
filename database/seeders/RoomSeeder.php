<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Room;

class RoomSeeder extends Seeder
{
    public function run(): void
    {
        $rooms = [
            ['number' => '101', 'name' => 'Hill View Deluxe', 'type' => 'Deluxe', 'price' => 5000, 'status' => 'available'],
            ['number' => '102', 'name' => 'Couple Suite', 'type' => 'Suite', 'price' => 7000, 'status' => 'available'],
            ['number' => '103', 'name' => 'Family Room', 'type' => 'Family', 'price' => 8500, 'status' => 'available'],
            ['number' => '104', 'name' => 'Premium Cottage', 'type' => 'Premium', 'price' => 10000, 'status' => 'available'],
            ['number' => '105', 'name' => 'Standard Room', 'type' => 'Standard', 'price' => 3000, 'status' => 'available'],
            ['number' => '106', 'name' => 'Executive Suite', 'type' => 'Executive', 'price' => 12000, 'status' => 'available'],
            ['number' => '107', 'name' => 'Garden View Room', 'type' => 'Garden View', 'price' => 4000, 'status' => 'available'],
            ['number' => '108', 'name' => 'Penthouse Suite', 'type' => 'Penthouse', 'price' => 15000, 'status' => 'available'],
            ['number' => '109', 'name' => 'Business Room', 'type' => 'Business', 'price' => 6000, 'status' => 'available'],
            ['number' => '110', 'name' => 'Luxury Villa', 'type' => 'Villa', 'price' => 20000, 'status' => 'available'],
        ];

        foreach ($rooms as $room) {
            Room::firstOrCreate(['number' => $room['number']], $room);
        }
    }
}