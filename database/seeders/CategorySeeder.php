<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Adventure'],
            ['name' => 'City Tour'],
            ['name' => 'Beach Vacation'],
            ['name' => 'Mountain Trek'],
            ['name' => 'Cultural Heritage'],
            ['name' => 'Wildlife Safari'],
            ['name' => 'Island Hopping'],
            ['name' => 'Food and Culinary'],
            ['name' => 'Historical Sites'],
            ['name' => 'Luxury Getaway'],
            ['name' => 'Shopping Spree'],
            ['name' => 'Wellness Retreat'],
            ['name' => 'Educational Tour'],
            ['name' => 'Photography Expedition'],
            ['name' => 'Festival and Events'],
            ['name' => 'Family Vacation'],
            ['name' => 'Romantic Escapade'],
            ['name' => 'Road Trip'],
            ['name' => 'Winter Wonderland'],
        ];
        DB::table('category')->insert($categories);
    }
}
