<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Places;
use Illuminate\Support\Str;


class PlacesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $international = ['Denmark', 'England', 'Finland', 'France', 'Germany', 'Italy'];
        $domesticVietnam = [
            'Hanoi',
            'Ho Chi Minh City',
            'Da Nang',
            'Hoi An',
            'Nha Trang',
            'Phu Quoc',
            // Thêm các địa điểm khác nếu cần
        ];

        foreach ($international as $country) {
            Places::create([
                'country' => $country,
                'slug' => Str::slug($country), // Tạo slug từ tên quốc gia
                'area_id' => '1',
                'image' => 'image',
            ]);
        }

        foreach ($domesticVietnam as $city) {
            Places::create([
                'country' => $city, // Sử dụng tên thành phố cho trường "country"
                'slug' => Str::slug($city), // Tạo slug từ tên thành phố
                'area_id' => '2',
                'image' => 'image',
            ]);
        }
    }
}
