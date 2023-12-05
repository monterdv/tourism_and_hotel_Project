<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cart_tour;
use App\Models\Tour_path;
use App\Models\Tour;
use App\Models\tour_Time;
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

            $total = null;

            foreach ($cart as $cartItem) {
                $tourId = optional($cartItem->tour)->id; // Use optional() to handle potential null values
                $img = Tour_path::where("tour_id", $tourId)->first();
                $total += $cartItem->total;
                // Add data for each item in the cart
                $cartItem->image = $img->path;
            }

            $data = [
                'total' => $total,
                'cart' => $cart,
            ];

            return response()->json([
                'data' => $data
            ]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
    public function customerInformation(Request $request)
    {
        $data = $request->validate([
            'customer.*.name' => 'required',
            'customer.*.phone' => 'required',
            'customer.*.email' => 'required|email',
            'customer.*.address' => 'required',
            'customer.*.passport' => 'required',
            'customer.*.nationality' => 'required',
        ], [
            'customer.*.name.required' => 'The name field is required.',
            'customer.*.phone.required' => 'The phone field is required.',
            'customer.*.email.required' => 'The email field is required.',
            'customer.*.address.required' => 'The address field is required.',
            'customer.*.passport.required' => 'The passport field is required.',
            'customer.*.nationality.required' => 'The nationality field is required.',
            'customer.*.email.email' => 'invalidate.',
        ]);


        return response()->json(['message' => 'Data validated successfully', 'data' => $data]);
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

            $tourTime = tour_Time::where("id", $time_id)->first();

            $total = $tourTime->price_adults * $Adult + $tourTime->price_children * $Children;

            if (!$time_id) {
                return response()->json([
                    'error' => 'This tour doesn`t have time'
                ], 422);
            }
            $existingCart = cart_tour::where('user_id', $user->id)
                ->where('tour_id', $tour_id)
                ->where('tours_time_id', $time_id)
                ->first();

            if ($existingCart) {
                return response()->json([
                    'message' => 'This tour is already in your cart.', "status" => 1
                ]);
            }

            $cart = cart_tour::create([
                'user_id' => $user->id,
                'tour_id' => $tour_id,
                'tours_time_id' => $time_id,
                'adults' => $Adult,
                'children' => $Children,
                'total' => $total,
            ]);

            if ($cart) {
                return response()->json([
                    'message' => 'add to cart successfully'
                ]);
            }
            return response()->json([
                'error' => 'Failed to add to cart', 422
            ]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }

    public function booking($id, $adults, $children)
    {
        if (!$id && !$adults && !$children) {
            return response()->json(['error' => 'Invalid parameters'], 400);
        }

        $booking_detail = cart_tour::with(['tour', 'tours_times'])->find($id);

        if (!$booking_detail) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $tour_Time = tour_Time::find($booking_detail->tours_time_id);

        // return $tour_Time;

        if (!$tour_Time) {
            return response()->json(['error' => 'Tour time not found'], 404);
        }

        $slots_remaining = $tour_Time->slots_remaining - $tour_Time->slots_booked;
        $slots_booking = $adults + $children;

        if ($slots_booking > $slots_remaining) {
            return response()->json(['error' => 'Slots booking cannot exceed available slots'], 400);
        }

        $total =  $tour_Time->price_adults * $adults + $tour_Time->price_children * $children;
        $totalslot = $adults + $children;

        $img = Tour_path::where(
            "tour_id",
            $booking_detail->tour_id
        )->first();
        $booking_detail->image = $img->path;
        $data = [
            'total' => $total,
            'totalslot' => $totalslot,
            'booking_detail' => $booking_detail,
            'adults' => $adults,
            'children' => $children,
        ];

        return response()->json(['data' => $data]);
    }


    public function delete($id)
    {
        $record = cart_tour::find($id);

        if ($record) {
            $record->delete();
            return response()->json(['message' => 'successfully removed from cart']);
        } else {
            // If the record is not found, return an error response
            return response()->json(['error' => 'tour not found'], 404);
        }
    }
}
