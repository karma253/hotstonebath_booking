<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Dzongkhag;

class DzongkhagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $dzongkhags = [
            ['name' => 'Thimphu', 'bhutanese_name' => 'ཐིམ་ཕུ།'],
            ['name' => 'Paro', 'bhutanese_name' => 'སྤ་རོ།'],
            ['name' => 'Punakha', 'bhutanese_name' => 'སྤུ་ནག་ཁ།'],
            ['name' => 'Wangdue Phodrang', 'bhutanese_name' => 'དབང་སྡུད་ཕོ་བྲང་།'],
            ['name' => 'Trongsa', 'bhutanese_name' => 'སྣོན་ཙགས།'],
            ['name' => 'Bumthang', 'bhutanese_name' => 'བུམ་ཐང་།'],
            ['name' => 'Haa', 'bhutanese_name' => 'ཧ།'],
            ['name' => 'Phobjikha', 'bhutanese_name' => 'ཕོབ་བརྗིད་ཁ།'],
            ['name' => 'Gasa', 'bhutanese_name' => 'གཆ་ས།'],
            ['name' => 'Dagana', 'bhutanese_name' => 'དག་ཡར་ན།'],
            ['name' => 'Samdrup Jongkhar', 'bhutanese_name' => 'བསམ་གྲུབ་མདོ་སྡགས།'],
            ['name' => 'Tashi Yangtse', 'bhutanese_name' => 'བཀྲ་ཤིས་ཡང་རྩེ།'],
            ['name' => 'Mongar', 'bhutanese_name' => 'མོན་གར།'],
            ['name' => 'Lhuentse', 'bhutanese_name' => 'ལྷུན་རྩེ།'],
            ['name' => 'Chhukha', 'bhutanese_name' => 'ཆུ་ཁའ།'],
            ['name' => 'Zhemgang', 'bhutanese_name' => 'བྱེ་མགོན།'],
            ['name' => 'Sambang', 'bhutanese_name' => 'སྦ་མང་།'],
        ];

        foreach ($dzongkhags as $dzongkhag) {
            Dzongkhag::firstOrCreate($dzongkhag);
        }
    }
}
