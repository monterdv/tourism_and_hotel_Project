<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class nationalitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $data = [
            'English',
            'Danish',
            'Estonian',
            'Finnish',
            'Icelandic',
            'Irish',
            'Austrian',
            'Belgian',
            'French',
            'German',
            'Dutch',
            'Swiss',
            'Russian',
            'Polish',
            'Canadian',
            'Mexican',
            'American',
            'Chinese',
            'Japanese',
            'South Korean',
            'Taiwanese',
            'Vietnamese',
            'Cambodian',
            'Indonesian',
            'Laotian',
            'Malaysian',
            'Burmese',
            'Filipino',
            'Singaporean',
            'Thai',
        ];
        foreach ($data as $item) {
            DB::table('nationality')->insert([
                'name' => $item,
            ]);
        }
    }
}
