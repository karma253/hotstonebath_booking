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

class WebDemoSeeder extends Seeder
{
    /**
     * Seed the application's database with demo web data.
     */
    public function run(): void
    {
        $admin = User::query()->firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'System Admin',
                'phone' => '17111111',
                'address' => 'Thimphu',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );

        $owner = User::query()->firstOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Demo Bath Owner',
                'phone' => '17222222',
                'address' => 'Paro',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'status' => 'active',
                'approved_at' => now(),
            ]
        );

        User::query()->firstOrCreate(
            ['email' => 'guest@example.com'],
            [
                'name' => 'Demo Guest',
                'phone' => '17333333',
                'address' => 'Punakha',
                'password' => Hash::make('password'),
                'role' => 'guest',
                'status' => 'active',
            ]
        );

        // Get all required dzongkhags
        $dzongkhagNames = ['Thimphu', 'Paro', 'Punakha', 'Wangdue Phodrang', 'Chhukha', 'Bumthang', 'Trongsa', 'Mongar'];
        $dzongkhags = Dzongkhag::query()->whereIn('name', $dzongkhagNames)->get()->keyBy('name');

        if ($dzongkhags->isEmpty()) {
            return;
        }

        $sampleBaths = [
            [
                'dzongkhag_name' => 'Thimphu',
                'name' => 'Thimphu Wellness Stone Spa',
                'short_description' => 'Modern wellness center with traditional hot stone therapy.',
                'full_address' => 'Changangkha, Thimphu',
                'price' => 1100,
                'max_guests' => 8,
                'image' => 'https://images.unsplash.com/photo-1544161515-81aae3ff8d23?w=1400',
            ],
            [
                'dzongkhag_name' => 'Paro',
                'name' => 'Paro Traditional Hot Stone Bath',
                'short_description' => 'Authentic river-stone heated bath with herbal blend.',
                'full_address' => 'Taktsang Road, Paro',
                'price' => 900,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1400',
            ],
            [
                'dzongkhag_name' => 'Punakha',
                'name' => 'Punakha Valley Herbal Bath',
                'short_description' => 'Peaceful valley bath house with natural herbs and warm stone tubs.',
                'full_address' => 'Khuruthang, Punakha',
                'price' => 850,
                'max_guests' => 5,
                'image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=1400',
            ],
            [
                'dzongkhag_name' => 'Wangdue Phodrang',
                'name' => 'Wangdue Riverside Hot Stone Bath',
                'short_description' => 'Tranquil bath sanctuary overlooking the river valley.',
                'full_address' => 'Riverside, Wangdue Phodrang',
                'price' => 950,
                'max_guests' => 7,
                'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1400',
            ],
            [
                'dzongkhag_name' => 'Chhukha',
                'name' => 'Chhukha Mineral Bath House',
                'short_description' => 'Relaxing bath experience with natural mineral-rich waters.',
                'full_address' => 'Phuntsholing, Chhukha',
                'price' => 875,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=1400',
            ],
            [
                'dzongkhag_name' => 'Bumthang',
                'name' => 'Bumthang Premium Stone Bath',
                'short_description' => 'Exclusive hot stone bath with panoramic mountain views.',
                'full_address' => 'Jakar, Bumthang',
                'price' => 1050,
                'max_guests' => 7,
                'image' => 'https://images.unsplash.com/photo-1544161515-81aae3ff8d23?w=1400',
            ],
            [
                'dzongkhag_name' => 'Trongsa',
                'name' => 'Trongsa Heritage Bath Center',
                'short_description' => 'Traditional bath experience honoring cultural heritage.',
                'full_address' => 'Trongsa Town, Trongsa',
                'price' => 900,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=1400',
            ],
            [
                'dzongkhag_name' => 'Mongar',
                'name' => 'Mongar Wellness Retreat',
                'short_description' => 'Serene wellness retreat with traditional therapies.',
                'full_address' => 'Mongar Town, Mongar',
                'price' => 925,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1400',
            ],
        ];

        foreach ($sampleBaths as $index => $data) {
            $dzongkhagId = $dzongkhags->get($data['dzongkhag_name'])?->id;
            
            if (! $dzongkhagId) {
                continue;
            }

            $bath = Bath::query()->firstOrCreate(
                ['name' => $data['name']],
                [
                    'owner_id' => $owner->id,
                    'property_type' => 'hot_stone_bath',
                    'dzongkhag_id' => $dzongkhagId,
                    'full_address' => $data['full_address'],
                    'short_description' => $data['short_description'],
                    'detailed_description' => $data['short_description'],
                    'tourism_license_number' => 'DEMO-LICENSE-' . ($index + 1),
                    'issuing_authority' => 'Tourism Council',
                    'license_issue_date' => now()->toDateString(),
                    'license_expiry_date' => now()->addYear()->toDateString(),
                    'license_status' => 'valid',
                    'max_guests' => $data['max_guests'],
                    'price_per_hour' => $data['price'],
                    'price_per_session' => $data['price'],
                    'booking_type' => 'approval_required',
                    'cancellation_policy' => 'Cancel up to 24 hours before session.',
                    'status' => 'active',
                    'verified_at' => now(),
                ]
            );

            // Add multiple bath services
            $services = [
                [
                    'service_type' => 'Traditional Hotstone Bath',
                    'description' => 'Classic hot stone bath experience with traditional heated river stones. Perfect for experiencing authentic Bhutanese wellness traditions. Available at ' . $data['name'] . ', ' . optional($bath->dzongkhag)->name . '.',
                    'duration_minutes' => 60,
                    'price' => $data['price'],
                    'max_guests' => $data['max_guests'],
                ],
                [
                    'service_type' => 'Herbal Hotstone',
                    'description' => 'Hot stone bath infused with traditional herbal blends for enhanced relaxation and rejuvenation. Located in ' . optional($bath->dzongkhag)->name . '. Ideal for wellness seekers.',
                    'duration_minutes' => 75,
                    'price' => $data['price'] + 200,
                    'max_guests' => $data['max_guests'],
                ],
                [
                    'service_type' => 'Medicinal Water Bath',
                    'description' => 'Therapeutic bath with medicinal water and minerals for wellness benefits. Experience traditional healing at ' . $data['name'] . ' in ' . optional($bath->dzongkhag)->name . '.',
                    'duration_minutes' => 60,
                    'price' => $data['price'] + 100,
                    'max_guests' => $data['max_guests'],
                ],
                [
                    'service_type' => 'Oil Bath',
                    'description' => 'Luxurious bath with aromatic oils and hot stone massage therapy. Premium wellness experience at ' . $data['name'] . '. A must-try Bhutanese spa treatment.',
                    'duration_minutes' => 90,
                    'price' => $data['price'] + 300,
                    'max_guests' => $data['max_guests'] - 1,
                ],
                [
                    'service_type' => 'Herbal Steam / Wellness Bath',
                    'description' => 'Rejuvenating steam bath combined with herbal therapies. Located at ' . $data['name'] . ' in ' . optional($bath->dzongkhag)->name . '. Perfect for complete wellness.',
                    'duration_minutes' => 75,
                    'price' => $data['price'] + 150,
                    'max_guests' => $data['max_guests'],
                ],
                [
                    'service_type' => 'Foot Bath',
                    'description' => 'Relaxing foot soak with hot stones and therapeutic herbs. Enjoy this soothing treatment at ' . $data['name'] . ' in ' . optional($bath->dzongkhag)->name . '.',
                    'duration_minutes' => 45,
                    'price' => $data['price'] - 300,
                    'max_guests' => $data['max_guests'],
                ],
                [
                    'service_type' => 'Relaxing Hot Stone Bath',
                    'description' => 'Premium hot stone bath designed for ultimate relaxation and stress relief. Experience tranquility at ' . $data['name'] . ' in ' . optional($bath->dzongkhag)->name . '.',
                    'duration_minutes' => 120,
                    'price' => $data['price'] + 500,
                    'max_guests' => $data['max_guests'] - 2,
                ],
                [
                    'service_type' => 'Detox Steam Bath',
                    'description' => 'Detoxifying steam bath with natural herbs and minerals. Purify your body and mind at ' . $data['name'] . ' in ' . optional($bath->dzongkhag)->name . '.',
                    'duration_minutes' => 60,
                    'price' => $data['price'] + 250,
                    'max_guests' => $data['max_guests'],
                ],
            ];

            foreach ($services as $serviceData) {
                BathService::query()->firstOrCreate(
                    ['bath_id' => $bath->id, 'service_type' => $serviceData['service_type']],
                    array_merge($serviceData, [
                        'is_available' => true,
                    ])
                );
            }

            foreach (['Changing Room', 'Towels', 'Herbal Bath', 'Private Area'] as $facility) {
                BathFacility::query()->firstOrCreate([
                    'bath_id' => $bath->id,
                    'facility_name' => $facility,
                ], [
                    'description' => null,
                    'is_available' => true,
                ]);
            }

            for ($day = 0; $day <= 6; $day++) {
                Availability::query()->updateOrCreate(
                    ['bath_id' => $bath->id, 'day_of_week' => $day],
                    [
                        'opening_time' => '09:00:00',
                        'closing_time' => '18:00:00',
                        'is_open' => true,
                    ]
                );
            }

            BathImage::query()->firstOrCreate(
                ['bath_id' => $bath->id, 'is_primary' => true],
                [
                    'image_path' => $data['image'],
                    'image_type' => 'bath_area',
                    'description' => 'Demo featured image',
                    'order' => 1,
                ]
            );
        }
    }
}
