<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\room_number;
use App\Models\BookingHotel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;


class bookinghotelController extends Controller
{
    //
    public function getbooking($slug, $hotelid)
    {
        if (Auth::check()) {
            $user = Auth::guard('api')->user();
            $room = Room::with(['hotel', 'bedtype'])->where("slug", $slug)->first();

            if (!$room) {
                return response()->json(['message' => 'Room not found'], 404);
            }

            if ($room->hotel_id != $hotelid) {
                return response()->json(['message' => 'Room not found for the specified hotel'], 404);
            }

            $data = [
                "room" => $room,
                "user_id" => $user->id
            ];

            return response()->json([
                'data' => $data,
            ]);
        }
    }

    public function checkInformation(Request $request)
    {
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phoneNumber' => 'required|numeric|digits:10',
            'Date_start' => 'required',
            'Date_end' => 'required',
        ], [
            'name.required' => 'Name cannot be null',
            'email.required' => 'Email cannot be null',
            'phoneNumber.required' => 'Phone number cannot be null',
            'Date_start.required' => 'Booking start date cannot be null',
            'Date_end.required' => 'Booking end date cannot be null',
        ]);


        $data['slug'] = $request->slug;
        $data['hotel_id'] = $request->hotelid;

        $room = Room::with(['hotel', 'bedtype'])
            ->where('slug', $data['slug'])
            ->where('hotel_id', $data['hotel_id'])
            ->firstOrFail();

        $availableRoom = room_number::where('room_type_id', $room->id)
            ->where('status', 'available')
            ->first();

        if ($availableRoom) {
            $data['room_id'] = $availableRoom->id;
            return response()->json(['message' => '1', 'data' => $data['room_id']]);
        } else {
            $occupiedRooms = room_number::where('room_type_id', $room->id)
                ->where('status', 'occupied')
                ->get();

            foreach ($occupiedRooms as $roomNumber) {
                $bookingHotel = BookingHotel::where('room_id', $roomNumber->id)
                    ->get();

                $datesOverlap = false;

                foreach ($bookingHotel as $booking) {
                    $bookingStartDate = Carbon::parse($booking->start_date);
                    $bookingEndDate = Carbon::parse($booking->end_date);

                    $requestedStartDate = Carbon::parse($data['Date_start']);
                    $requestedEndDate = Carbon::parse($data['Date_end']);

                    if (
                        ($bookingStartDate <= $requestedStartDate && $requestedStartDate <= $bookingEndDate) ||
                        ($bookingStartDate <= $requestedEndDate && $requestedEndDate <= $bookingEndDate) ||
                        ($requestedStartDate <= $bookingStartDate && $bookingEndDate <= $requestedEndDate)
                    ) {
                        $datesOverlap = true;
                        break;
                    }
                }

                // If no date overlap is found, assign the room ID and return the response
                if (!$datesOverlap) {
                    $data['room_id'] = $roomNumber->id;
                    return response()->json(['message' => '1', 'data' => $data['room_id']]);
                }
            }
        }

        // If no available or overlapping rooms are found
        // return response()->json(['message' => 'No available rooms.']);
        return response()->json([
            'errors' => [
                'Date_start' => ['No available rooms.']
            ]
        ], 422);
    }
}
