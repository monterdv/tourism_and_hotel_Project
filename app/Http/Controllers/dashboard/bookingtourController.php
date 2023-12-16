<?php

namespace App\Http\Controllers\dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BookingTour;
use App\Models\User;
use App\Models\slot_tour;
use App\Models\tour_Time;
use App\Models\Tour;


class bookingtourController extends Controller
{
    //
    public function getbooking()
    {
        $bookingtour = BookingTour::with(['tour', 'time', 'payments'])->get();

        $Tour = Tour::select('id as value', 'title as label')->get();
        $time = tour_Time::select('id as value', 'date as label')->get();
        $status = [
            ['value' => 'upcoming', 'label' => 'Upcoming'],
            ['value' => 'in_progress', 'label' => 'In Progress'],
            ['value' => 'completed', 'label' => 'Completed'],
        ];
        $data = [
            'Tour' => $Tour,
            'time' => $time,
            'status' => $status,
            'bookingtour' => $bookingtour,
        ];

        return response()->json([
            'data' => $data
        ]);
    }
    public function search(Request $request)
    {
        // return $request;
        $code = $request->code;
        $tour = $request->tour;
        $date = $request->date;
        $status = $request->status;
        $query = BookingTour::query();
        $query->with(['tour', 'time', 'payments']);
        if ($code) {
            $query->where('bookings_Code', $code);
        }

        if ($tour) {
            $query->where('tour_id', $tour);
        }

        if ($date) {
            $query->where('tourTime_id', $date);
        }

        if ($status) {
            $query->where('status_booking', $status);
        }

        $results = $query->get();
        $data = [
            'results' => $results,
        ];

        return response()->json(['data' => $data], 200);
    }

    public function checktour($id = null)
    {
        // Log the received $id
        $query = tour_Time::query();

        if ($id) {
            $query->where('tour_id', $id);
        }

        $time = $query->select('id as value', 'date as label')->get();

        $data = [
            'time' => $time,
        ];

        return response()->json(['data' => $data], 200);
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

    public function confirm($id)
    {
        $booking = BookingTour::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        $booking->status_payment = 'paid';

        try {
            $booking->save();
            return response()->json(['message' => 'Booking payment status updated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update status_payment'], 500);
        }
    }

    public function abort($id)
    {
        $booking = BookingTour::find($id);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found'], 404);
        }

        try {
            $time = tour_Time::where('id', $booking->tourTime_id)->first();
            $time->slots_booked -= $booking->adults + $booking->children;
            $time->save();
            $booking->slots()->delete();
            $booking->delete();
            return response()->json(['message' => 'Booking deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete booking', 'message' => $e->getMessage()], 500);
        }
    }
}
