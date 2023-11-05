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

    public function profileChange(Request $request)
    {
        // return $request;
        $user = User::find(Auth::user()->id);

        $data = $request->validate([
            'name' => 'required',
        ], [
            'name.required' => 'name is required',
        ]);

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

        $user->name = $data['name'];
        if (isset($data['avatar'])) {
            $user->avatar = $data['avatar'];
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
                    'message' => 'Current password is incorrect.',
                ], 422);
            }

            $user->password = Hash::make($newPassword);
            $user->save();
            return response()->json(['message' => 'updated successfully']);
        }

        return response()->json(['message' => 'updated successfully']);
    }
}
