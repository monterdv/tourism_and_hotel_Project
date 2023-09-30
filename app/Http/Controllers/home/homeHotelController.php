<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Hotel;
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

        $hotels = [];
        $hotel1 = null;

        if ($places) {
            $hotelplaces = Hotel::where('place_id', $places->id)->get();
            $hotels[] = $hotelplaces;
        }

        $hotel = Hotel::where('title', 'like', '%' . $search . '%')->get();

        $hotels[] = $hotel;
        $hotels = $hotel->concat($hotelplaces)->unique('id')->values()->all();

        $data = [
            'hotels' => $hotels,
        ];
        return response()->json(['data' => $data], 200);
    }
}
