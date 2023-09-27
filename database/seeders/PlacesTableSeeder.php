<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Places;

class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 10; $i++) {
            $places = [
                [
                    'country' => 'Country ' . ($i + 1),
                    'slug' => 'place-' . ($i + 1),
                    'area' => rand(0, 1) ? 'international' : 'domestic',
                ],
                // Add more data as needed
            ];

            foreach ($places as $placeData) {
                Places::create($placeData);
            }
        }
    }
}
