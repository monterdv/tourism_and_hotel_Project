<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\bed_type;


class BedTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $bedTypes = [
            ['name' => 'Single Bed'],
            ['name' => 'Double Bed'],
            ['name' => 'King Size Bed'],
            // Add more bed types as needed
            ['name' => 'Queen Bed'],
            ['name' => 'Twin Bed'],
            ['name' => 'Sofa Bed'],
            ['name' => 'Futon'],
            ['name' => 'Murphy Bed'],
            ['name' => 'Daybed'],
            ['name' => 'Bunk Bed'],
        ];
        foreach ($bedTypes as $bedType) {
            bed_type::create($bedType);
        }
    }
}
