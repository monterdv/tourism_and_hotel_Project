<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Room_widgets;
use App\Models\Places;


class homeHotelController extends Controller
{
    public function indexHotel()
    {
        $places = Places::select('country as name')->get();

        $data = [
            'places' => $places,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function searchHotel($search = null)
    {
        $places = Places::where('country', 'like', '%' . $search . '%')->first();


        $query = Hotel::with(['place', 'hotelPaths']);

        if ($places) {
            $query->orWhere('place_id', $places->id);
        }

        $query->orWhere('title', 'like', '%' . $search . '%');

        $hotels = $query->get();

        $data = [
            'hotels' => $hotels,
        ];
        return response()->json(['data' => $data], 200);
    }

    public function hoteldetail($slug)
    {
        $hotel = Hotel::where('slug', $slug)->with(['place', 'hotelPaths'])->first();

        $roomType = Room::where('hotel_id', $hotel->id)->get();

        $data = [
            'hotel' => $hotel,
            'roomType' => $roomType,
        ];

        return response()->json(['data' => $data], 200);
    }
}
