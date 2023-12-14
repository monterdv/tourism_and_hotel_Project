<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingTour;


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
        return $code;
    }
}
