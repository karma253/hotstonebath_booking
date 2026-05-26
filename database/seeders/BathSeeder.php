<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bath;
use App\Models\BathFacility;
use App\Models\Dzongkhag;
use App\Models\User;

class BathSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all dzongkhags
        $dzongkhags = Dzongkhag::all();

        // Sample bath data for each Dzongkhag
        $bathData = [
            'Bumthang' => [
                'Tshechu Farmhouse',
                'Bumthang Valley Hot Stone Bath',
                'Jakar Traditional Menchu House',
            ],
            'Chhukha' => [
                'Phuntsholing Wellness Bath',
                'Riverside Stone Bath',
                'Herbal Hot Spring Retreat',
            ],
            'Dagana' => [
                'Dagana Mountain Bath House',
                'Traditional Stone Spa',
                'Local Menchu Center',
            ],
            'Gasa' => [
                'Gasa Hot Spring Bath',
                'Northern Wellness Retreat',
                'Alpine Bath House',
            ],
            'Haa' => [
                'Haa Valley Menchu House',
                'Traditional Stone Bath',
                'Heritage Bath Facility',
            ],
            'Lhuentse' => [
                'Lhuentse Stone Bath House',
                'Eastern Wellness Center',
                'Traditional Menchu Spa',
            ],
            'Mongar' => [
                'Mongar Hot Spring Bath',
                'Central Dzongkhag Menchu House',
                'Stone Bath Wellness Center',
            ],

            'Pemagatshel' => [
                'Pemagatshel Stone Bath House',
                'Traditional Wellness Center',
                'Menchu Spa Retreat',
            ],
            'Punakha' => [
                'Chencho Farmhouse Hotstone Bath',
                'Sacred River Bath House',
            ],
            'Samdrup Jongkhar' => [
                'Eastern Border Stone Bath',
                'Samdrup Wellness House',
                'Traditional Menchu Center',
            ],
            'Samtse' => [
                'Samtse Hot Spring Bath',
                'Southern Gateway Menchu House',
                'Wellness Retreat Center',
            ],
            'Sarpang' => [
                'Sarpang Forest Bath House',
                'Traditional Stone Spa',
                'Menchu Wellness Center',
            ],
            'Thimphu' => [
                'Thimphu Capital Hot Stone Bath',
                'Urban Wellness Spa',
                'Traditional Menchu House',
            ],
            'Trashigang' => [
                'Dhongphangma Menchu',
                'Khabtey Menchu',
                'Trashigang Eastern Bath House',
            ],
            'Trashiyangtse' => [
                'Trashiyangtse Stone Bath House',
                'Traditional Menchu Wellness',
                'Heritage Bath Center',
            ],
            'Trongsa' => [
                'Trongsa Central Bath House',
                'Traditional Stone Menchu',
                'Wellness Retreat Spa',
            ],
            'Tsirang' => [
                'Tsirang Hot Spring Bath',
                'Traditional Menchu Center',
                'Stone Bath Wellness House',
            ],
            'Wangdue Phodrang' => [
                'Wangdue Phodrang Hot Stone Bath',
                'River Valley Menchu House',
                'Traditional Wellness Spa',
            ],
            'Zhemgang' => [
                'Duenmang Hot Spring',
            ],
        ];

        // Create a demo user to be the owner of baths
        $owner = User::firstOrCreate(
            ['email' => 'bathowner@demo.com'],
            [
                'name' => 'Demo Bath Owner',
                'phone' => '17123456',
                'role' => 'owner',
                'password' => bcrypt('password123'),
                'status' => 'active',
            ]
        );

        // Create baths for each Dzongkhag
        foreach ($dzongkhags as $dzongkhag) {
            if (isset($bathData[$dzongkhag->name])) {
                foreach ($bathData[$dzongkhag->name] as $bathName) {
                    Bath::firstOrCreate(
                        [
                            'name' => $bathName,
                            'dzongkhag_id' => $dzongkhag->id,
                        ],
                        [
                            'owner_id' => $owner->id,
                            'short_description' => 'Authentic hot stone bath experience in ' . $dzongkhag->name,
                            'full_address' => $bathName . ', ' . $dzongkhag->name,
                            'latitude' => 27.5 + rand(-100, 100) / 1000,
                            'longitude' => 89.5 + rand(-100, 100) / 1000,
                            'tourism_license_number' => 'TLN-' . strtoupper($dzongkhag->name) . '-' . rand(1000, 9999),
                            'issuing_authority' => 'DTCA',
                            'license_issue_date' => now()->subYear(),
                            'license_expiry_date' => now()->addYear(),
                            'license_status' => 'valid',
                            'status' => 'active',
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    );
                }
            }
        }
    }
}
