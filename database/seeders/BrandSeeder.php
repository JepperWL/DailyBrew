<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Brand::create([
            'id' => 1,
            'name' => 'Kopi Kenangan',
            'logo' => 'kopi-kenangan.jpg',
            'description' => 'null',
        ]);

        Brand::create([
            'id' => 2,
            'name' => 'Djournal',
            'logo' => 'djournal.jpg',
            'description' => 'null',
        ]);

        Brand::create([
            'id' => 3,
            'name' => 'Excelso',
            'logo' => 'Excelso.jpg',
            'description' => 'null',
        ]);
        
        Brand::create([
            'id' => 4,
            'name' => 'Fore',
            'logo' => 'Fore.jpg',
            'description' => 'null',
        ]);

        Brand::create([
            'id' => 5,
            'name' => 'Kopi Soe',
            'logo' => 'kopi-soe.jpg',
            'description' => 'null',
        ]);

        Brand::create([
            'id' => 6,
            'name' => 'Maxx Coffee',
            'logo' => 'maxx-coffee.jpg',
            'description' => 'null',
        ]);

        Brand::create([
            'id' => 7,
            'name' => 'Janji Jiwa',
            'logo' => 'janji-jiwa.jpg',
            'description' => 'null',
        ]);

        Brand::create([
            'id' => 8,
            'name' => 'Starbucks',
            'logo' => 'starbucks.jpg',
            'description' => 'null',
        ]);
        
    }
}
