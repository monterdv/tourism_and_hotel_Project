<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingHotel;

class BookingHotelController extends Controller
{
    //
    public function getbooking()
    {
        $bookinghotel = BookingHotel::with(['numberRoom', 'room_type', 'hotel'])->get();

        // $Tour = Tour::select('id as value', 'title as label')->get();
        // $time = tour_Time::select('id as value', 'date as label')->get();
        $status = [
            ['value' => 'upcoming', 'label' => 'Upcoming'],
            ['value' => 'in_progress', 'label' => 'In Progress'],
            ['value' => 'completed', 'label' => 'Completed'],
        ];
        $data = [
            // 'Tour' => $Tour,
            // 'time' => $time,
            // 'status' => $status,
            'bookinghotel' => $bookinghotel,
        ];

        return response()->json([
            'data' => $data
        ]);
    }
    public function getdetail($id)
    {
        $Detailbooking = BookingHotel::where("id", $id)->with(['numberRoom', 'room_type', 'hotel', 'user'])->first();
        $data = [
            'Detailbooking' => $Detailbooking,
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}
