<?php

namespace Database\Seeders;

use App\Models\Company;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompanySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */


        public function run()
    {
        $durations = [
            ['days' => 30, 'desc' => 'Premium Monthly'],
            ['days' => 90, 'desc' => 'Premium Quarterly'],
            ['days' => 180, 'desc' => 'Premium Bi-Annual'],
            ['days' => 365, 'desc' => 'Premium Annual']
        ];

        $japaneseAddresses = [
            '1-1-1 Marunouchi, Chiyoda-ku, Tokyo',
            '2-6-1 Nihonbashi, Chuo-ku, Tokyo',
            '3-5-1 Umeda, Kita-ku, Osaka',
            '4-1-1 Meieki, Nakamura-ku, Nagoya',
            '5-2-1 Tenjin, Chuo-ku, Fukuoka',
            '6-3-1 Odori, Chuo-ku, Sapporo',
            '7-4-1 Kokusai-dori, Chuo-ku, Yokohama',
            '8-5-1 Asahidori, Chuo-ku, Kobe',
            '9-6-1 Ekimae-dori, Naka-ku, Hiroshima',
            '10-7-1 Ote-machi, Chiyoda-ku, Sendai'
        ];

        for ($i = 1; $i <= 20; $i++) {
            $duration = $durations[array_rand($durations)];
            $startDate = Carbon::now()->subDays(rand(1, 30));
            $endDate = $startDate->copy()->addDays($duration['days']);

            $status = $endDate->isFuture() ? 'active' : 'expired';

            Company::create([
                'company_key' => 'COMP' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'company_name' => $this->generateCompanyName(),
                'contact_number' => $this->generatePhoneNumber(),
                'address' => $japaneseAddresses[array_rand($japaneseAddresses)], // Added address field
                'plan_id' => 2,
                'registration_number' => 'RC' . rand(10000000, 99999999),
                'plan_amount' => 300,
                'discount' => 50,
                'final_price' => 250,
                'status' => $status,
                'plan_description' => $duration['desc'],
                'subscription_start_date' => $startDate,
                'subscription_end_date' => $endDate,
                'reserve_1' => null,
                'reserve_2' => null,
                'reserve_3' => null,
                'reserve_4' => null,
                'reserve_5' => null,
                'deleted_at' => null,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }

        private function generateCompanyName()
    {
        $cities = ['Tokyo', 'Osaka', 'Nagoya', 'Fukuoka', 'Sapporo'];
        $industries = ['Tech', 'Solutions', 'Systems', 'Digital', 'Networks'];
        $suffixes = ['Inc.', 'Ltd.', 'Co.', 'Corp.', 'KK'];

        return $cities[array_rand($cities)] . ' ' .
            $industries[array_rand($industries)] . ' ' .
            $suffixes[array_rand($suffixes)];
    }

        private function generatePhoneNumber()
    {
        return '+81' . rand(3, 9) . rand(1000, 9999) . rand(1000, 9999);
    }
}
