<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Widget;


class WidgetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $widgets = [
            'Non-allergenic rooms',
            'Wireless internal communication system',
            'Express check-in/check-out',
            '24-hour room service',
            'Private check-in/check-out',
            '24-hour front desk',
            'Smoke-free rooms',
            '24-hour security',
            'Pet-friendly accommodations',
            'Soundproof rooms',
            'Air conditioning',
            'Balcony/terrace',
            'Light-blocking curtains',
            'Coffee/tea maker',
            'Daily housekeeping',
            'Work desk',
            'Dishwasher',
            'DVD/CD player',
            'Full kitchen',
            'Hairdryer',
            'Iron in room',
            'LAN Internet in room (surcharge)',
            'Microwave oven',
            'Mini fridge in room',
            'On-demand movies',
            'Private bathroom',
            'Refrigerator',
            'Cable/satellite TV',
            'In-room air purification system',
            'Sofa bed',
            'Soundproofed rooms',
            'Telephone',
            'Toiletries',
            'Washing machine',
            'Wi-Fi (surcharge)',
            'Free Wi-Fi',
            'Cleaning products',
            'In-room safe',
            'Private balcony',
            'Handicapped facilities',
            'Paid parking lot',
        ];

        foreach ($widgets as $widgetName) {
            Widget::create(['name' => $widgetName]);
        }
    }
}
