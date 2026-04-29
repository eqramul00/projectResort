<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'backup@resort.com'],
            [
                'name' => 'Backup Admin',
                'password' => bcrypt('backup1234'),
                'role' => 'admin',
            ]
        );

        $this->call([
            RoomSeeder::class,
        ]);
    }
}