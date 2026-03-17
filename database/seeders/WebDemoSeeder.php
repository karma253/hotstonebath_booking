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
                'story' => 'In the vibrant heart of Bhutan\'s capital city, Thimphu Wellness Stone Spa was born from a vision to blend contemporary wellness with ancient traditions. Built near the sacred Changangkha Monastery, our therapeutic sanctuary honors centuries-old hot stone healing practices while embracing modern comfort. For generations, Bhutanese locals have believed that heated river stones possess healing energy. Today, we continue this wisdom—each session is crafted to help urban visitors reconnect with nature and find peace in the midst of city life.',
                'full_address' => 'Changangkha, Thimphu',
                'price' => 1100,
                'max_guests' => 8,
                'image' => 'https://images.unsplash.com/photo-1544161515-81aae3ff8d23?w=1400',
            ],
            [
                'dzongkhag_name' => 'Paro',
                'name' => 'Paro Traditional Hot Stone Bath',
                'short_description' => 'Experience natural hot stone bathing where heated stones warm the water creating a peaceful healing environment. Herbal blends enhance relaxation and wellness benefits.',
                'story' => 'Nestled in the sacred Paro Valley, beneath the iconic Taktsang Monastery perched on the clifftops, our traditional bath house carries the spiritual essence of Bhutan\'s most visited destination. For centuries, pilgrims traveling to the "Tiger\'s Nest" have sought healing through hot stone bathing. Our story began when local families recognized the therapeutic power of these ancient practices and decided to preserve them. Using stones from the nearby streams and herbs from the sacred valley, we create an authentic wellness experience that connects guests with Bhutan\'s spiritual heritage.',
                'full_address' => 'Taktsang Road, Paro',
                'price' => 900,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1400',
            ],
            [
                'dzongkhag_name' => 'Punakha',
                'name' => 'Punakha Valley Herbal Bath',
                'short_description' => 'Peaceful valley bath house with natural herbs and warm stone tubs.',
                'story' => 'Surrounded by the majestic mountains of Punakha, Bhutan\'s ancient capital, our valley bath house rests where rivers meet and ancient energies flow. This sacred valley was once the seat of power, and the healing traditions rooted here run deep. Our bath house was established to honor the royal lineage\'s commitment to wellness and natural medicine. The valley\'s unique climate nurtures rare medicinal herbs that have been used for centuries by royal healers. Today, we continue this legacy by combining hot stone therapy with locally-sourced herbs.',
                'full_address' => 'Khuruthang, Punakha',
                'price' => 850,
                'max_guests' => 5,
                'image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=1400',
            ],
            [
                'dzongkhag_name' => 'Wangdue Phodrang',
                'name' => 'Wangdue Riverside Hot Stone Bath',
                'short_description' => 'Tranquil bath sanctuary overlooking the river valley.',
                'story' => 'Perched above the flowing Punatsangchhu River, our riverside sanctuary in Wangdue Phodrang was created by descendants of local craftsmen who have worked along these waters for generations. The river has always been a source of healing, its waters carrying ancient wisdom from the mountains. When our founders noticed that visitors sought respite from the bustling Wangdue Bazaar, they envisioned a place where the river\'s healing energy could be channeled through hot stone therapy. Using stones smoothed by centuries of river currents and herbs from the surrounding forests, we create an experience where the rhythm of the river becomes a rhythm of wellness.',
                'full_address' => 'Riverside, Wangdue Phodrang',
                'price' => 950,
                'max_guests' => 7,
                'image' => 'https://images.unsplash.com/photo-1540555700478-4be289fbecef?w=1400',
            ],
            [
                'dzongkhag_name' => 'Chhukha',
                'name' => 'Chhukha Mineral Bath House',
                'short_description' => 'Relaxing bath experience with natural mineral-rich waters.',
                'story' => 'In southern Bhutan\'s Chhukha region, where the border meets India, our mineral bath house draws from a unique geographical blessing—naturally mineral-rich hot springs that have flowed from the earth for millennia. Local communities have long recognized these waters as sacred, blessed with natural minerals that nourish the body. Our bath house was founded to share this natural treasure with the world while preserving traditions that date back centuries. The minerals in our waters—iron, sulfur, and trace elements—work alongside heated stones to create a therapeutic experience unlike any other in Bhutan.',
                'full_address' => 'Phuntsholing, Chhukha',
                'price' => 875,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=1400',
            ],
            [
                'dzongkhag_name' => 'Bumthang',
                'name' => 'Bumthang Premium Stone Bath',
                'short_description' => 'Exclusive hot stone bath with panoramic mountain views.',
                'story' => 'High in Bhutan\'s spiritual heartland, Bumthang cradles some of the nation\'s holiest temples and most pristine mountain valleys. Our premium bath sanctuary was created by devotees who believe that this sacred altitude amplifies the healing power of hot stone therapy. Bumthang\'s unique geography—surrounded by mountains that touch the sky—creates a natural energy that visitors feel immediately upon arrival. The region\'s ancient Buddhist teachings speak of the harmony between earth, water, fire, and sky; our bath house embodies this philosophy.',
                'full_address' => 'Jakar, Bumthang',
                'price' => 1050,
                'max_guests' => 7,
                'image' => 'https://images.unsplash.com/photo-1544161515-81aae3ff8d23?w=1400',
            ],
            [
                'dzongkhag_name' => 'Trongsa',
                'name' => 'Trongsa Heritage Bath Center',
                'short_description' => 'Traditional bath experience honoring cultural heritage.',
                'story' => 'Trongsa, home to the iconic Trongsa Dzong fortress, has long been Bhutan\'s cultural and trade crossroads. Merchants, pilgrims, and travelers passing through this strategic valley have for centuries sought the healing of hot stone baths to recover from their journeys. Our heritage center honors this rich history by maintaining the exact practices that served these travelers generations ago. The bathhouse design reflects Bhutanese architectural principles, and our therapists carry knowledge passed down through families of traditional healers. Every stone was selected from local streams; every herb grows in Trongsa\'s soil.',
                'full_address' => 'Trongsa Town, Trongsa',
                'price' => 900,
                'max_guests' => 6,
                'image' => 'https://images.unsplash.com/photo-1596178065887-1198b6148b2b?w=1400',
            ],
            [
                'dzongkhag_name' => 'Mongar',
                'name' => 'Mongar Wellness Retreat',
                'short_description' => 'Serene wellness retreat with traditional therapies.',
                'story' => 'In eastern Bhutan\'s peaceful Mongar, where mountains create a protective embrace and communities live in harmony with nature, our wellness retreat represents the quiet strength of Bhutanese healing traditions. Mongar has always been known for maintaining authentic wellness practices without the modern pressures that affect other regions. Our retreat was established by local masters of traditional medicine who realized that the world needed a place to experience genuine, undiluted healing wisdom. Far from city noise, immersed in green forests and cool mountain air, visitors discover a wellness that goes beyond the body.',
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
                    'detailed_description' => $data['story'],
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
                    'description' => 'A traditional hot stone bath is a natural way of bathing where heated stones are placed in water to make it warm. People sit in the warm water to relax their body and relieve stress or body pain. Sometimes herbs are added to make it more soothing. It is simple, relaxing, and good for health.',
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
