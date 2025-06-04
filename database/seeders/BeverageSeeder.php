<?php

namespace Database\Seeders;

use App\Models\Beverage;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BeverageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Beverage::create([
            'name' => 'Matcha Latte',
            'price' => 50000,
            'brand_id' => 1,
            'category_id' => 2,
            'description' => 'Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua. Ut enim ad
             minim veniam, quis nostrud exercitation ullamco laboris nisi ut
              aliquip ex ea commodo consequat. Duis aute irure dolor in 
              reprehenderit in voluptate velit esse cillum dolore eu fugiat 
              nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
              sunt in culpa qui officia deserunt mollit anim id est laborum',
            'image' => 'beverages/matcha.jpg',
            'is_available' => true,
        ]);

        Beverage::create([
            'name' => 'Ice Chocolate',
            'price' => 45000,
            'brand_id' => 4,
            'category_id' => 2,
            'description' => 'Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua. Ut enim ad
             minim veniam, quis nostrud exercitation ullamco laboris nisi ut
              aliquip ex ea commodo consequat. Duis aute irure dolor in 
              reprehenderit in voluptate velit esse cillum dolore eu fugiat 
              nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
              sunt in culpa qui officia deserunt mollit anim id est laborum',
            'image' => 'beverages/chocolate.jpg',
            'is_available' => true,
        ]);

        Beverage::create([
            'name' => 'Ice Milo',
            'price' => 40000,
            'brand_id' => 4,
            'category_id' => 2,
            'description' => 'Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua. Ut enim ad
             minim veniam, quis nostrud exercitation ullamco laboris nisi ut
              aliquip ex ea commodo consequat. Duis aute irure dolor in 
              reprehenderit in voluptate velit esse cillum dolore eu fugiat 
              nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
              sunt in culpa qui officia deserunt mollit anim id est laborum',
            'image' => 'beverages/milo.jpg',
            'is_available' => true,
        ]);

        Beverage::create([
            'name' => 'Salted Caramel',
            'price' => 60000,
            'brand_id' => 4,
            'category_id' => 1,
            'description' => 'Lorem ipsum dolor sit amet, 
            consectetur adipiscing elit, sed do eiusmod tempor 
            incididunt ut labore et dolore magna aliqua. Ut enim ad
             minim veniam, quis nostrud exercitation ullamco laboris nisi ut
              aliquip ex ea commodo consequat. Duis aute irure dolor in 
              reprehenderit in voluptate velit esse cillum dolore eu fugiat 
              nulla pariatur. Excepteur sint occaecat cupidatat non proident, 
              sunt in culpa qui officia deserunt mollit anim id est laborum',
            'image' => 'beverages/saltedcaramel.jpg',
            'is_available' => true,
        ]);

        
    }
}
