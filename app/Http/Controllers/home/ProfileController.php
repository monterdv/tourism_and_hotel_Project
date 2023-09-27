<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    //
    public function profile()
    {
        if (Auth::check()) {
            $user = Auth::guard('api')->user();

            $data = ['user' => $user];

            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
}
