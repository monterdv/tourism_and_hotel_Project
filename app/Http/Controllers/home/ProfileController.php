<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

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

    public function passwordChange(Request $request)
    {
        return $request;
        $user = User::find(Auth::user()->id);
        $data = $request->validate([
            'password' => 'required|string|min:8',
            'password_new' => 'required|string|min:8',
            'password_confirmation' => 'required',
        ], [
            'password.required' => 'Password is required',
            'password_new.required' => 'password_new is required',
            'password_confirmation.required' => 'Password confirmation is required',
        ]);

        $currentPassword = $request->password;
        $newPassword = $request->password_new;
        $confirmNewPassword = $request->password_confirmation;

        if ($newPassword != $confirmNewPassword) {
            return response()->json(['message' => 'Password and password confirmation must be same'], 422);
        }

        if (!Hash::check($currentPassword, $user->password)) {
            return response()->json([
                'message' => 'Current password is incorrect.',
            ], 422);
        }

        $user->password = Hash::make($newPassword);
        $user->save();

        return response()->json(['message' => 'Upload successfully', 'user' => $user]);
    }

    public function profileChange(Request $request){
        return $request;
    }
}
