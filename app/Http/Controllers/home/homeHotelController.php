<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class homeHotelController extends Controller
{
    public function searchHotel($search = null, $date = null)
    {
        $data = [
            'search' => $search,
            'date' => $date,
        ];
        return response()->json(['data' => $data], 200);
    }
}
