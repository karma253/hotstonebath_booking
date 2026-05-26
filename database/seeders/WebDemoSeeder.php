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
                'image' => '/image/Thimphu Wellness Stone Spa.jpg',
            ],
            [
                'dzongkhag_name' => 'Paro',
                'name' => 'Paro Traditional Hot Stone Bath',
                'short_description' => 'Experience natural hot stone bathing where heated stones warm the water creating a peaceful healing environment. Herbal blends enhance relaxation and wellness benefits.',
                'story' => 'Nestled in the sacred Paro Valley, beneath the iconic Taktsang Monastery perched on the clifftops, our traditional bath house carries the spiritual essence of Bhutan\'s most visited destination. For centuries, pilgrims traveling to the "Tiger\'s Nest" have sought healing through hot stone bathing. Our story began when local families recognized the therapeutic power of these ancient practices and decided to preserve them. Using stones from the nearby streams and herbs from the sacred valley, we create an authentic wellness experience that connects guests with Bhutan\'s spiritual heritage.',
                'full_address' => 'Taktsang Road, Paro',
                'price' => 900,
                'max_guests' => 6,
                'image' => '/image/Paro Traditional Hot Stone Bath.jpg',
            ],
            [
                'dzongkhag_name' => 'Paro',
                'name' => 'COMO UMA Paro',
                'short_description' => 'Luxury hot stone spa experience nestled in scenic Paro Valley.',
                'story' => 'COMO UMA Paro is a premier wellness destination located in the serene Paro Valley, showcasing the finest in Bhutanese hospitality and traditional healing arts. Our luxury spa facility combines ancient hot stone bathing traditions with modern wellness amenities. Overlooking the sacred Tiger\'s Nest Monastery, our sanctuary provides a tranquil retreat where guests experience authentic Bhutanese therapeutic practices. Each session is personalized to balance the body\'s natural elements using carefully selected heated stones and premium aromatic herbs sourced from the pristine Paro region.',
                'full_address' => 'Paro Valley, Paro',
                'price' => 1200,
                'max_guests' => 8,
                'image' => '/image/COMO UMA Paro.jpg',
            ],
            [
                'dzongkhag_name' => 'Punakha',
                'name' => 'Punakha Valley Herbal Bath',
                'short_description' => 'Peaceful valley bath house with natural herbs and warm stone tubs.',
                'story' => 'Surrounded by the majestic mountains of Punakha, Bhutan\'s ancient capital, our valley bath house rests where rivers meet and ancient energies flow. This sacred valley was once the seat of power, and the healing traditions rooted here run deep. Our bath house was established to honor the royal lineage\'s commitment to wellness and natural medicine. The valley\'s unique climate nurtures rare medicinal herbs that have been used for centuries by royal healers. Today, we continue this legacy by combining hot stone therapy with locally-sourced herbs.',
                'full_address' => 'Khuruthang, Punakha',
                'price' => 850,
                'max_guests' => 5,
                'image' => '/image/Punakha Valley Herbal Bath.jpg',
            ],
            [
                'dzongkhag_name' => 'Wangdue Phodrang',
                'name' => 'Wangdue Riverside Hot Stone Bath',
                'short_description' => 'Tranquil bath sanctuary overlooking the river valley.',
                'story' => 'Perched above the flowing Punatsangchhu River, our riverside sanctuary in Wangdue Phodrang was created by descendants of local craftsmen who have worked along these waters for generations. The river has always been a source of healing, its waters carrying ancient wisdom from the mountains. When our founders noticed that visitors sought respite from the bustling Wangdue Bazaar, they envisioned a place where the river\'s healing energy could be channeled through hot stone therapy. Using stones smoothed by centuries of river currents and herbs from the surrounding forests, we create an experience where the rhythm of the river becomes a rhythm of wellness.',
                'full_address' => 'Riverside, Wangdue Phodrang',
                'price' => 950,
                'max_guests' => 7,
                'image' => '/image/Wangdue Riverside Hot Stone Bath.jpg',
            ],
            [
                'dzongkhag_name' => 'Chhukha',
                'name' => 'Chhukha Mineral Bath House',
                'short_description' => 'Relaxing bath experience with natural mineral-rich waters.',
                'story' => 'In southern Bhutan\'s Chhukha region, where the border meets India, our mineral bath house draws from a unique geographical blessing—naturally mineral-rich hot springs that have flowed from the earth for millennia. Local communities have long recognized these waters as sacred, blessed with natural minerals that nourish the body. Our bath house was founded to share this natural treasure with the world while preserving traditions that date back centuries. The minerals in our waters—iron, sulfur, and trace elements—work alongside heated stones to create a therapeutic experience unlike any other in Bhutan.',
                'full_address' => 'Phuntsholing, Chhukha',
                'price' => 875,
                'max_guests' => 6,
                'image' => '/image/Chhukha Mineral Bath House.jpg',
            ],
            [
                'dzongkhag_name' => 'Bumthang',
                'name' => 'Bumthang Premium Stone Bath',
                'short_description' => 'Exclusive hot stone bath with panoramic mountain views.',
                'story' => 'High in Bhutan\'s spiritual heartland, Bumthang cradles some of the nation\'s holiest temples and most pristine mountain valleys. Our premium bath sanctuary was created by devotees who believe that this sacred altitude amplifies the healing power of hot stone therapy. Bumthang\'s unique geography—surrounded by mountains that touch the sky—creates a natural energy that visitors feel immediately upon arrival. The region\'s ancient Buddhist teachings speak of the harmony between earth, water, fire, and sky; our bath house embodies this philosophy.',
                'full_address' => 'Jakar, Bumthang',
                'price' => 1050,
                'max_guests' => 7,
                'image' => '/image/Bumthang Premium Stone Bath.jpg',
            ],
            [
                'dzongkhag_name' => 'Bumthang',
                'name' => 'Amankora Bumthang Lodge',
                'short_description' => 'Traditional healing bath house nestled in Bumthang valley.',
                'story' => 'Amankora Bumthang Lodge is a sacred healing sanctuary dedicated to preserving the ancient hot stone bathing traditions of Bumthang Valley. Located near the spiritual temples of this holiest region in Bhutan, our bath house practices time-honored therapeutic methods passed down through generations of local healers. Each session is designed to balance body, mind, and spirit using naturally heated stones and aromatic herbs collected from the pristine Bumthang mountains. We believe in the power of authentic Bhutanese wellness traditions.',
                'full_address' => 'Amankora, Bumthang',
                'price' => 950,
                'max_guests' => 6,
                'image' => '/image/Amankora bumthang lodge.jpg',
            ],
            [
                'dzongkhag_name' => 'Trongsa',
                'name' => 'Trongsa Heritage Bath Center',
                'short_description' => 'Traditional bath experience honoring cultural heritage.',
                'story' => 'Trongsa, home to the iconic Trongsa Dzong fortress, has long been Bhutan\'s cultural and trade crossroads. Merchants, pilgrims, and travelers passing through this strategic valley have for centuries sought the healing of hot stone baths to recover from their journeys. Our heritage center honors this rich history by maintaining the exact practices that served these travelers generations ago. The bathhouse design reflects Bhutanese architectural principles, and our therapists carry knowledge passed down through families of traditional healers. Every stone was selected from local streams; every herb grows in Trongsa\'s soil.',
                'full_address' => 'Trongsa Town, Trongsa',
                'price' => 900,
                'max_guests' => 6,
                'image' => '/image/Trongsa Heritage Bath Center.jpg',
            ],
            [
                'dzongkhag_name' => 'Mongar',
                'name' => 'Mongar Wellness Retreat',
                'short_description' => 'Serene wellness retreat with traditional therapies.',
                'story' => 'In eastern Bhutan\'s peaceful Mongar, where mountains create a protective embrace and communities live in harmony with nature, our wellness retreat represents the quiet strength of Bhutanese healing traditions. Mongar has always been known for maintaining authentic wellness practices without the modern pressures that affect other regions. Our retreat was established by local masters of traditional medicine who realized that the world needed a place to experience genuine, undiluted healing wisdom. Far from city noise, immersed in green forests and cool mountain air, visitors discover a wellness that goes beyond the body.',
                'full_address' => 'Mongar Town, Mongar',
                'price' => 925,
                'max_guests' => 6,
                'image' => '/image/Mongar Wellness Retreat.jpg',
            ],
            [
                'dzongkhag_name' => 'Chhukha',
                'name' => 'Chhukha Natural Hot Spring Sanctuary',
                'short_description' => 'Experience the healing power of natural hot spring water with mineral-rich therapeutic benefits.',
                'story' => 'Deep in the valleys of Chhukha, ancient natural hot springs have flowed from the earth for thousands of years. Local communities have revered these waters as sacred, blessed with natural minerals that heal and rejuvenate. Our sanctuary was created to celebrate and share this natural treasure. Unlike heated stone baths, our natural hot springs contain naturally occurring minerals including sulfur, iron, and magnesium—elements that have been proven by traditional Bhutanese medicine to cure various ailments and promote overall wellness. Every bath session connects you with the earth\'s ancient healing energy.',
                'full_address' => 'Hot Spring Valley, Chhukha',
                'price' => 1000,
                'max_guests' => 10,
                'image' => '/image/Duenmang Hot Spring.jpg',
            ],
            [
                'dzongkhag_name' => 'Bumthang',
                'name' => 'Bumthang Medicinal Healing Bath House',
                'short_description' => 'Traditional medicinal water bath blended with rare healing herbs for therapeutic wellness.',
                'story' => 'Bumthang is known throughout Bhutan as the land of medicinal traditions. Our healing bath house sits where ancient medicinal plants grow wild and where traditional healers have apprenticed for generations. We specialize in medicinal water baths—where purified water is enhanced with rare herbs, minerals, and natural remedies collected from Bumthang\'s pristine mountains. Unlike other bath houses, our focus is purely therapeutic healing. Each session is customized based on guest needs, using time-tested herbal combinations that have served Bhutanese families for centuries. Our healers are trained in traditional Bhutanese medicine practices.',
                'full_address' => 'Healing Valley, Bumthang',
                'price' => 1150,
                'max_guests' => 5,
                'image' => '/image/Medicinal Water Bath.jpg',
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

            // Services will be added by owners in their dashboard

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
