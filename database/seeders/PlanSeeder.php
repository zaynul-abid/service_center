<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::create(['name' => 'Basic', 'amount' => 100, 'days' => 30]);
        Plan::create(['name' => 'Premium', 'amount' => 300, 'days' => 90]);
        Plan::create(['name' => 'Enterprise', 'amount' => 500, 'days' => 180]);
    }
}
