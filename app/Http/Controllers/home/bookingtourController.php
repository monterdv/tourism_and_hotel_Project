<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cart_tour;
use App\Models\Tour_path;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class bookingtourController extends Controller
{
    //
    public function getbooking()
    {
        if (Auth::check()) {
            $user = Auth::guard('api')->user();
            $cart = cart_tour::with(['tour', 'tours_times'])->where('user_id', $user->id)->get();

            foreach ($cart as $cartItem) {
                $tourId = optional($cartItem->tour)->id; // Use optional() to handle potential null values
                $img = Tour_path::where("tour_id", $tourId)->first();

                // Add data for each item in the cart
                $cartItem->image = $img->path;
            }

            $data = [
                'cart' => $cart,
            ];

            return response()->json([
                'data' => $data
            ]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
    public function addtocar(Request $request)
    {
        // return $request;
        if (Auth::check()) {
            $user = Auth::guard('api')->user();

            // return $request;
            $tour_id = $request->tour_id;
            $time_id = $request->time_id;
            $Adult = $request->Adult;
            $Children = $request->Children;

            $cart = cart_tour::create([
                'user_id' => $user->id,
                'tour_id' => $tour_id,
                'tours_time_id' => $time_id,
                'adults' => $Adult,
                'children' => $Children,
            ]);

            if ($cart) {
                return response()->json([
                    'message' => 'add to cart successfully',
                ]);
            }
            return response()->json([
                'error' => 'Failed to add to cart', 422
            ]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
}
