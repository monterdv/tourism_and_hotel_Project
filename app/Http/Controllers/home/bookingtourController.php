<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\cart_tour;
use App\Models\Tour_path;
use App\Models\Tour;
use App\Models\tour_Time;
use App\Models\BookingTour;
use App\Models\Payment;
use App\Models\nationality;
use Illuminate\Support\Facades\Auth;
use App\Models\slot_tour;

use App\Models\User;
use Illuminate\Support\Facades\Session;

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
        // return $request;
        $validationRules = [
            'customer.*.name' => 'required',
        ];

        for ($i = 0; $i < $request->adults; $i++) {
            // Dynamically add validation rules for each adult
            $validationRules["customer.$i.phone"] = 'required';
            // $validationRules["customer.$i.email"] = 'required|email';
            $validationRules["customer.$i.email"] = 'required';
            // Add more rules for other fields if needed
            $validationRules["customer.$i.passport"] = 'required';
            $validationRules["customer.$i.nationality"] = 'required';
        }

        $data = $request->validate($validationRules, [
            'customer.*.name.required' => 'The name field is required.',
            'customer.*.phone.required' => 'The phone field is required.',
            'customer.*.email.required' => 'The email field is required.',
            // 'customer.*.email.email' => 'Invalid email format.',
            'customer.*.passport.required' => 'The passport field is required.',
            'customer.*.nationality.required' => 'The nationality field is required.',
        ]);


        $customers = [];
        for ($i = 0; $i < count($data['customer']); $i++) {
            $customers[$i] = $data['customer'][$i];
        }

        $payment_id = $request->payment_id;
        if (!$payment_id) {
            return response()->json([
                'message' => 'Please select a payment method',
            ], 400);
        }

        if ($payment_id == 1) {
            return response()->json(['message' => '1']);
        } else {
            $code = $this->generateUniqueTourCode();
            $BookingTour = new BookingTour;
            $BookingTour->bookings_Code = $code;
            $BookingTour->tour_id = $request->tour_id;
            $BookingTour->tourTime_id = $request->tourTime_id;
            $BookingTour->adults = $request->adults;
            $BookingTour->children = $request->children;
            $BookingTour->total_price = $request->totalPrice;
            $BookingTour->user_id = $request->user_id;
            $BookingTour->payment_id = $request->payment_id;
            $BookingTour->status_payment = "unpaid";
            $BookingTour->status_booking = "upcoming";
            $BookingTour->save();

            $tourTime = tour_Time::find($BookingTour->tourTime_id);
            $tourTime->slots_booked += $request->adults + $request->children;
            $tourTime->save();

            $customerData = $request->customer;
            for ($i = 0; $i < count($customerData); $i++) {
                $slot_tour = new slot_tour;
                $slot_tour->name = $customerData[$i]['name'];
                $slot_tour->email = $customerData[$i]['email'];
                $slot_tour->phone = $customerData[$i]['phone'];
                $slot_tour->passport = $customerData[$i]['passport'];
                $slot_tour->nationality_id = $customerData[$i]['nationality'];
                $slot_tour->bookings_tour_id = $BookingTour->id;
                if ($i < $request->adults) {
                    $slot_tour->type = 'adult';
                }
                $slot_tour->save();
            };
            $record = cart_tour::find($request->cart_id);
            $record->delete();

            return response()->json(['code' => $BookingTour->bookings_Code], 200);
        };
    }
    public function generateUniqueTourCode()
    {
        do {
            $letters = "BK";
            $digits = str_pad(mt_rand(1, 99999), 5, '0', STR_PAD_LEFT);
            $code = $letters . $digits;
        } while (BookingTour::where('bookings_Code', $code)->exists());

        return $code;
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

        $payment = payment::get();

        $booking_detail->image = $img->path;
        $nationality = nationality::select('id as value', 'name as label')->get();

        $data = [
            'total' => $total,
            'payment' => $payment,
            'nationality' => $nationality,
            'totalslot' => $totalslot,
            'booking_detail' => $booking_detail,
            'adults' => $adults,
            'children' => $children,
        ];

        return response()->json(['data' => $data]);
    }

    public function checkout($code)
    {
        // return "ok";
        $BookingTour = BookingTour::where('bookings_Code', $code)->first();
        if (!$BookingTour) {
            return response()->json(['error' => 'not found'], 404);
        }
        $payment = payment::where('id', $BookingTour->payment_id)->first();
        $data = [
            'payment' => $payment,
            'code' => $BookingTour->bookings_Code,
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
    //payment tour
}
