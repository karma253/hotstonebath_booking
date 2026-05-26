<?php

namespace Database\Seeders;

use App\Models\Availability;
use App\Models\Bath;
use App\Models\BathFacility;
use App\Models\BathImage;
use App\Models\BathService;
use App\Models\Dzongkhag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MultipleOwnersSeeder extends Seeder
{
    /**
     * Seed the database with multiple owner accounts for testing.
     */
    public function run(): void
    {
        $dzongkhags = Dzongkhag::query()->get()->keyBy('name');

        $ownersData = [
            [
                'name' => 'Sonam Tenzin',
                'email' => 'sonam.tenzin@baths.com',
                'phone' => '17611111',
                'password' => 'Password123',
                'bath_name' => 'Himalayan Wellness Center',
                'dzongkhag' => 'Thimphu',
                'address' => 'Kawajangsa, Thimphu',
                'description' => 'Premium wellness center offering traditional hot stone therapy combined with modern spa facilities.',
                'price' => 1200,
                'max_guests' => 6,
            ],

            [
                'name' => 'Tshering Lhamo',
                'email' => 'tshering.lhamo@baths.com',
                'phone' => '17633333',
                'password' => 'Password123',
                'bath_name' => 'Punakha Natural Baths',
                'dzongkhag' => 'Punakha',
                'address' => 'Kawajangsa, Punakha',
                'description' => 'Natural hot spring baths with herbal treatments in the ancient valley capital.',
                'price' => 875,
                'max_guests' => 7,
            ],
            [
                'name' => 'Kinley Wangmo',
                'email' => 'kinley.wangmo@baths.com',
                'phone' => '17644444',
                'password' => 'Password123',
                'bath_name' => 'Bumthang Healing Sanctuary',
                'dzongkhag' => 'Bumthang',
                'address' => 'Jakar, Bumthang',
                'description' => 'Sacred healing sanctuary combining medicinal herbs and traditional stone therapy.',
                'price' => 1050,
                'max_guests' => 4,
            ],
            [
                'name' => 'Phuntsho Gyelpo',
                'email' => 'phuntsho.gyelpo@baths.com',
                'phone' => '17655555',
                'password' => 'Password123',
                'bath_name' => 'Chhukha Mineral Springs',
                'dzongkhag' => 'Chhukha',
                'address' => 'Phuntsholing, Chhukha',
                'description' => 'Thermal baths with natural mineral-rich waters for therapeutic wellness.',
                'price' => 850,
                'max_guests' => 8,
            ],
        ];

        foreach ($ownersData as $data) {
            $dzongkhagId = $dzongkhags->get($data['dzongkhag'], null)?->id;

            if (!$dzongkhagId) {
                echo "⚠️ Dzongkhag '{$data['dzongkhag']}' not found. Skipping owner {$data['name']}.\n";
                continue;
            }

            // Create or get the owner user
            $owner = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'phone' => $data['phone'],
                    'password' => Hash::make($data['password']),
                    'role' => 'owner',
                    'status' => 'pending_verification',
                    'approved_at' => null,
                ]
            );

            echo "✅ Owner created/updated: {$owner->name} ({$owner->email})\n";

            // Create bath for this owner
            $bath = Bath::firstOrCreate(
                ['name' => $data['bath_name']],
                [
                    'owner_id' => $owner->id,
                    'property_type' => 'hot_stone_bath',
                    'dzongkhag_id' => $dzongkhagId,
                    'full_address' => $data['address'],
                    'short_description' => $data['description'],
                    'detailed_description' => $data['description'],
                    'tourism_license_number' => 'LICENSE-' . strtoupper(substr(md5($data['bath_name']), 0, 8)),
                    'issuing_authority' => 'Tourism Bhutan',
                    'license_issue_date' => now()->toDateString(),
                    'license_expiry_date' => now()->addYear()->toDateString(),
                    'license_status' => 'valid',
                    'max_guests' => $data['max_guests'],
                    'price_per_hour' => $data['price'],
                    'price_per_session' => $data['price'],
                    'booking_type' => 'approval_required',
                    'cancellation_policy' => 'Free cancellation up to 24 hours before booking.',
                    'status' => 'pending_verification',
                    'verified_at' => null,
                ]
            );

            echo "   └─ Bath created: {$bath->name}\n";

            // Add day availability
            for ($day = 0; $day <= 6; $day++) {
                Availability::updateOrCreate(
                    ['bath_id' => $bath->id, 'day_of_week' => $day],
                    [
                        'opening_time' => '09:00',
                        'closing_time' => '18:00',
                        'is_open' => true,
                    ]
                );
            } 

            // Add a bath image
            BathImage::firstOrCreate(
                ['bath_id' => $bath->id, 'image_type' => 'bath_area'],
                [
                    'image_path' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1200',
                    'description' => 'Bath facility main area',
                    'order' => 1,
                    'is_primary' => true,
                ]
            );

            // Add default facilities
            foreach (['Changing Room', 'Heated Towels', 'Herbal Blends', 'Relaxation Area'] as $facility) {
                BathFacility::firstOrCreate(
                    ['bath_id' => $bath->id, 'facility_name' => $facility],
                    ['is_available' => true]
                );
            }

            echo "   └─ Service, availability, and facilities configured\n\n";
        }

        echo "🎉 Multiple owners seeding completed!\n";
        echo "\nCreated Owners (use these to login):\n";
        foreach ($ownersData as $data) {
            echo "  📧 {$data['email']} | 🔑 {$data['password']}\n";
        }
    }
}
