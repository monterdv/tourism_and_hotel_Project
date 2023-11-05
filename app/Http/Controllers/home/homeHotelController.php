<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Room_widgets;
use App\Models\Places;
use App\Models\hotel_paths;
use Illuminate\Support\Facades\DB;


class homeHotelController extends Controller
{
    public function indexHotel()
    {
        $placesDomestic = Places::select('places.*', DB::raw('(SELECT COUNT(*) FROM hotels WHERE hotels.place_id = places.id) AS total_hotels'))
            ->where('area_id', 1)
            ->take(8)
            ->get();


        $placesInternational = Places::select('places.*', DB::raw('(SELECT COUNT(*) FROM hotels WHERE hotels.place_id = places.id) AS total_hotels'))
            ->where('area_id', 2)
            ->take(8)
            ->get();

        $data = [
            'placesDomestic' => $placesDomestic,
            'placesInternational' => $placesInternational,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function searchHotel($search = null)
    {
        // Tìm địa điểm dựa trên từ khóa tìm kiếm
        $places = Places::where('country', 'like', '%' . $search . '%')->first();

        // Tạo câu truy vấn ban đầu với mối quan hệ 'place'
        $query = Hotel::with(['place']);

        // Sử dụng 'orWhereHas' để thực hiện tìm kiếm liên quan
        $query->orWhere(function ($q) use ($search, $places) {
            $q->where('title', 'like', '%' . $search . '%');

            if ($places) {
                $q->orWhere('place_id', $places->id);
            }
        });

        // Lấy danh sách khách sạn
        $hotels = $query->paginate(10);

        // Duyệt qua từng khách sạn và thêm thông tin phòng và hình ảnh
        foreach ($hotels as $hotel) {
            $room = Room::where('hotel_id', $hotel->id)->first();
            $image = hotel_paths::where('hotel_id', $hotel->id)->first();

            // Kiểm tra xem room và image có tồn tại trước khi gán
            if ($room) {
                $hotel->price = $room->base_price;
            }

            if ($image) {
                $hotel->image = $image->path;
            }
        }

        $data = [
            'hotels' => $hotels,
        ];

        return response()->json(['data' => $data], 200);
    }


    public function hoteldetail($slug)
    {
        $hotel = Hotel::where('slug', $slug)->with(['place', 'hotelPaths'])->first();

        $roomType = Room::where('hotel_id', $hotel->id)->get();

        $hotelRelevant = Hotel::where(function ($query) use ($hotel) {
            $query->where('place_id', $hotel->place_id)
                ->where('id', '!=', $hotel->id);
        })->limit(3)->get();

        foreach ($hotelRelevant as $item) {
            $room = Room::where('hotel_id', $item->id)->first();
            $image = hotel_paths::where('hotel_id', $item->id)->first();

            // Kiểm tra xem room và image có tồn tại trước khi gán
            if ($room) {
                $hotel->price = $room->base_price;
            }

            if ($image) {
                $item->image = $image->path;
            }
        }

        $data = [
            'hotel' => $hotel,
            'roomType' => $roomType,
            'hotelRelevant' => $hotelRelevant,
        ];

        return response()->json(['data' => $data], 200);
    }
}
