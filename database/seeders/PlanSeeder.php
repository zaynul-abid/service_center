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
        $names = ['Basic', 'Standard', 'Premium', 'Pro', 'Enterprise', 'Ultimate'];
        $suffixes = ['', ' Plus', ' Pro', ' Max', ' 2024'];

        for ($i = 0; $i < 30; $i++) {
            Plan::create([
                'name' => $names[array_rand($names)] . $suffixes[array_rand($suffixes)],
                'amount' => rand(5, 200) * 10 - 0.01,
                'days' => [7, 30, 90, 180, 365][array_rand([7, 30, 90, 180, 365])],
                'status' => rand(0, 1)
            ]);
        }
    }
}
