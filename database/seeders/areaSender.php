<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class areaSender extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $area = [
            ['name' => 'domestic', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'international', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('area')->insert($area);
    }
}
