<?php

namespace Database\Seeders;

use App\Models\Status;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BootstrapSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
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
