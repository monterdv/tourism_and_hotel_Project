<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\BookingTour;
use App\Models\BookingHotel;
use App\Models\Tour_path;

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

    public function profileChange(Request $request)
    {
        // return $request;
        $user = User::find(Auth::user()->id);

        $data = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'name is required',
        ]);

        // if ($request->hasFile('file')) {

        //     $oldAvatarPath = $user->avatar;

        //     if ($oldAvatarPath && file_exists(public_path($oldAvatarPath))) {
        //         unlink(public_path($oldAvatarPath));
        //     }

        //     $uploadedFile = $request->file('file');
        //     $destinationPath = public_path('assets/img/avatars');
        //     $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
        //     $uploadedFile->move($destinationPath, $newFileName);

        //     $ImgPath = '/assets/img/avatars/' . $newFileName;
        //     $data['avatar'] = $ImgPath;
        // }

        // if (isset($data['avatar'])) {
        //     $user->avatar = $data['avatar'];
        // }
        $user->name = $data['name'];
        $phone = $request->phone;
        if ($phone) {
            if ($phone && preg_match('/^\d{10}$/', $phone)) {
                $user->phone = $phone;
            } else {
                return response()->json([
                    'errors' => [
                        'phone' => ['Invalid phone number format.']
                    ]
                ], 422);
            }
        }

        $user->save();

        $currentPassword = $request->password;
        $newPassword = $request->password_new;
        $confirmNewPassword = $request->password_confirmation;

        if ($currentPassword || $newPassword || $confirmNewPassword) {
            $validatePassword = $request->validate([
                'password' => 'required|string|min:8',
                'password_new' => 'required|string|min:8',
                'password_confirmation' => 'required',
            ], [
                'password.required' => 'Password is required',
                'password_new.required' => 'password_new is required',
                'password_confirmation.required' => 'Password confirmation is required',
            ]);

            if ($newPassword != $confirmNewPassword) {
                return response()->json([
                    'errors' => [
                        'password_confirmation' => ['Password and password confirmation must be same']
                    ]
                ], 422);
            }

            if (!Hash::check($currentPassword, $user->password)) {
                return response()->json([
                    'errors' => [
                        'password' => ['Current password is incorrect.']
                    ]
                ], 422);
            }

            $user->password = Hash::make($newPassword);
            $user->save();
            return response()->json(['message' => 'updated successfully']);
        }

        return response()->json(['message' => 'updated successfully']);
    }

    public function uploadAvatar(Request $request)
    {
        $user = User::find(Auth::user()->id);
        $data = []; // Define $data array

        if ($request->hasFile('file')) {
            $oldAvatarPath = $user->avatar;

            if ($oldAvatarPath && file_exists(public_path($oldAvatarPath))) {
                unlink(public_path($oldAvatarPath));
            }

            $uploadedFile = $request->file('file');
            $destinationPath = public_path('assets/img/avatars');
            $newFileName = uniqid() . '_' . $uploadedFile->getClientOriginalName();
            $uploadedFile->move($destinationPath, $newFileName);

            $ImgPath = '/assets/img/avatars/' . $newFileName;
            $data['avatar'] = $ImgPath;
        }

        if (isset($data['avatar'])) {
            $user->avatar = $data['avatar'];
        }

        $user->save();
    }

    public function getbookingtour()
    {
        if (Auth::check()) {
            $user = Auth::guard('api')->user();

            $TourUpcoming = BookingTour::with(['tour', 'time', 'payments'])->where("user_id", $user->id)->where("status_booking", "upcoming")->get();
            foreach ($TourUpcoming as $item) {
                $img = Tour_path::where("tour_id", $item->tour_id)->first();
                $item->img = $img->path;
            }
            $TourInProgress = BookingTour::with(['tour', 'time', 'payments'])->where("user_id", $user->id)->where("status_booking", "in_progress")->get();
            foreach ($TourInProgress as $item) {
                $img = Tour_path::where("tour_id", $item->tour_id)->first();
                $item->img = $img->path;
            }
            $TourCompleted = BookingTour::with(['tour', 'time', 'payments'])->where("user_id", $user->id)->where("status_booking", "completed")->get();
            foreach ($TourCompleted as $item) {
                $img = Tour_path::where("tour_id", $item->tour_id)->first();
                $item->img = $img->path;
            }
            $data = [
                'TourUpcoming' => $TourUpcoming,
                'TourInProgress' => $TourInProgress,
                'TourCompleted' => $TourCompleted,
            ];

            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
    public function deletetour($id)
    {
        $item = BookingTour::find($id);

        if (!$item) {
            return response()->json(['message' => 'Tour not found'], 422);
        }

        $tourTime = $item->time;

        if ($tourTime) {
            $tourTime->slots_booked -= $item->adults + $item->children;
            $tourTime->save();
        }

        $item->slots()->delete();
        $item->delete();

        return response()->json(['message' => 'Successfully Delete']);
    }

    //hotel 
    public function getbookinghotel()
    {
        if (Auth::check()) {
            $user = Auth::guard('api')->user();
            // with(['numberRoom', 'room_type'])
            $HotelUpcoming = BookingHotel::with(['numberRoom', 'room_type', 'hotel'])->where("user_id", $user->id)->where("status_booking", "upcoming")->get();
            foreach ($HotelUpcoming as $item) {
            }
            $HotelInProgress = BookingHotel::with(['numberRoom', 'room_type', 'hotel'])->where("user_id", $user->id)->where("status_booking", "in_progress")->get();
            $HotelCompleted = BookingHotel::with(['numberRoom', 'room_type', 'hotel'])->where("user_id", $user->id)->where("status_booking", "completed")->get();

            $data = [
                'HotelUpcoming' => $HotelUpcoming,
                'HotelInProgress' => $HotelInProgress,
                'HotelCompleted' => $HotelCompleted,
            ];

            return response()->json(['data' => $data]);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
}
