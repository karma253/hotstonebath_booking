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
            ['name' => 'Bumthang', 'bhutanese_name' => 'བུམ་ཐང་།'],
            ['name' => 'Chhukha', 'bhutanese_name' => 'ཆུ་ཁའ།'],
            ['name' => 'Dagana', 'bhutanese_name' => 'དག་ཡར་ན།'],
            ['name' => 'Gasa', 'bhutanese_name' => 'གཆ་ས།'],
            ['name' => 'Haa', 'bhutanese_name' => 'ཧ།'],
            ['name' => 'Lhuentse', 'bhutanese_name' => 'ལྷུན་རྩེ།'],
            ['name' => 'Mongar', 'bhutanese_name' => 'མོན་གར།'],
            ['name' => 'Paro', 'bhutanese_name' => 'སྤ་རོ།'],
            ['name' => 'Pemagatshel', 'bhutanese_name' => 'པདྨ་གའ་ཙེལ།'],
            ['name' => 'Punakha', 'bhutanese_name' => 'སྤུ་ནག་ཁ།'],
            ['name' => 'Samdrup Jongkhar', 'bhutanese_name' => 'བསམ་གྲུབ་མདོ་སྡགས།'],
            ['name' => 'Samtse', 'bhutanese_name' => 'སམ་རྩེ།'],
            ['name' => 'Sarpang', 'bhutanese_name' => 'ས་སྤང་།'],
            ['name' => 'Thimphu', 'bhutanese_name' => 'ཐིམ་ཕུ།'],
            ['name' => 'Trashigang', 'bhutanese_name' => 'བཀྲ་ཤིས་སྒང་།'],
            ['name' => 'Trashiyangtse', 'bhutanese_name' => 'བཀྲ་ཤིས་ཡང་རྩེ།'],
            ['name' => 'Trongsa', 'bhutanese_name' => 'སྣོན་ཙགས།'],
            ['name' => 'Tsirang', 'bhutanese_name' => 'ཚི་རངས།'],
            ['name' => 'Wangdue Phodrang', 'bhutanese_name' => 'དབང་སྡུད་ཕོ་བྲང་།'],
            ['name' => 'Zhemgang', 'bhutanese_name' => 'བྱེ་མགོན།'],
        ];

        foreach ($dzongkhags as $dzongkhag) {
            Dzongkhag::firstOrCreate($dzongkhag);
        }
    }
}
