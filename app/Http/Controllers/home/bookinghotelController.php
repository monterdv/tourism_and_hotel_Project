<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;


class bookinghotelController extends Controller
{
    //
    public function getbooking($slug, $hotelid)
    {
        $room = Room::with(['hotel', 'bedtype'])->where("slug", $slug)->first();

        if (!$room) {
            return response()->json(['message' => 'Room not found'], 404);
        }

        if ($room->hotel_id != $hotelid) {
            return response()->json(['message' => 'Room not found for the specified hotel'], 404);
        }

        $data = [
            "room" => $room
        ];

        return response()->json([
            'data' => $data,
        ]);
    }

    public function checkInformation(Request $request){
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required',
            'phoneNumber' => 'required',
            'Date_start' => 'required',
            'Date_end' => 'required',
        ], [
            'name.required' => 'name cannot be null',
            'email.required' => 'email cannot be null',
            'phoneNumber.required' => 'phone Number cannot be null',
            'Date_start.required' => 'day booking cannot be null',
            'Date_end.required' => 'day booking cannot be null',
        ]);
        return $request;
    }
}
