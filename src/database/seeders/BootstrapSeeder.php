<?php

namespace Database\Seeders;

use App\Models\Status;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BootstrapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('API_USER')],
            ['name' => 'Test User', 'password' => env('API_SECRET')],
        );

        $statuses = [
            ['name' => 'open'],
            ['name' => 'in_progress'],
            ['name' => 'done']
        ];
        foreach ($statuses as $status) {
            Status::updateOrCreate($status);
        }
    }
}
