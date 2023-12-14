<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingTour;
use App\Models\User;
use App\Models\slot_tour;


class bookingtourController extends Controller
{
    //
    public function getbooking()
    {
        $bookingtour = BookingTour::with(['tour', 'time', 'payments'])->get();
        $data = [
            'bookingtour' => $bookingtour,
        ];

        return response()->json([
            'data' => $data
        ]);
    }

    public function detailbooking($code)
    {
        $booking = BookingTour::with(['tour', 'time', 'payments'])->where('bookings_Code', $code)->first();
        $user = User::where('id', $booking->user_id)->first();
        $slot = slot_tour::with(['nationality'])->where('bookings_tour_id', $booking->id)->get();
        $data = [
            'booking' => $booking,
            'user' => $user,
            'slot' => $slot,
        ];

        return response()->json([
            'data' => $data
        ]);
    }
}
