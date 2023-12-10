<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class payment extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('payment')->insert([
            'title' => "pay by credit paypal",
            'description' => "After booking and paying successfully, the itinerary will send your electronic ticket valuable customer emails",
        ]);
        DB::table('payment')->insert([
            'title' => "Pay in cash at the office",
            'description' => "Please come to the office at XXX to pay and receive tickets",
        ]);
        DB::table('payment')->insert([
            'title' => "pay by credit card",
            'description' => "After booking and paying successfully, the itinerary will send your electronic ticket valuable customer emails",
        ]);
    }
}
