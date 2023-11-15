<?php

namespace App\Http\Controllers\home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\PasswordResetToken;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{

    public function login(Request $request)
    {
        // Validation
        // return $request;
        $Validation = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Vui lòng nhập địa chỉ email',
            'email.email' => 'Địa chỉ email không hợp lệ',
            'password.required' => 'Vui lòng nhập mật khẩu',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'Email address does not exist'], 422);
        }


        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials)) {
            $user = Auth::user();
            // dd($user);

            $data['token_type'] = 'Bearer';
            $data['access_token'] = $user->createToken('userToken')->accessToken;
            $data['user'] = $user;

            // dd($token);
            return response()->json(['message' => 'Logged in successfully', 'data' => $data, 'user' => $user], 200);
        } else {
            return response()->json(['message' => 'Login unsuccessful'], 401);
        }
    }

    public function processRegistration(Request $request)
    {
        // return $request;
        $data = $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users', // Use Laravel's unique validation rule to check email existence
            'password' => 'required|min:6', // You can add password length validation
            'confirm_Password' => 'required|same:password', // Check if confirm_password matches password
        ], [
            'name.required' => 'Name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Invalid email format',
            'email.unique' => 'Email already exists',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'confirm_Password.required' => 'Confirm Password is required',
            'confirm_Password.same' => 'Confirm Password must match Password',
        ]);

        $data['status_id'] = 1;
        $data['department_id'] = 2;

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'department_id' => $data['department_id'],
            'status_id' => $data['status_id'],
        ]);

        Auth::login($user);
        $token['token_type'] = 'Bearer';
        $token['access_token'] = $user->createToken('userToken')->accessToken;
        $token['user'] = $user;

        return response()->json(['message' => 'User created successfully', 'token' => $token], 200);
    }

    public function forgetPassword(Request $request)
    {
        // return $request;
        if (!filter_var($request->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json(['message' => 'Invalid email format'], 422);
        };

        $existingUser = User::where('email', $request->email)->first();
        if (!$existingUser) {
            return response()->json(['message' => 'Email does not exist'], 422);
        }

        $tokenReset = strtoupper(Str::random(40));
        $user = User::where('email', $request->email)->first();

        $passwordResetToken = PasswordResetToken::where('email', $user->email)->first();


        if ($passwordResetToken) {
            $passwordResetToken->update(['token' => $tokenReset]);
        } else {
            $passwordResetToken = PasswordResetToken::create([
                'email' => $user->email,
                'token' => $tokenReset,
                'created_at' => now()->addMinutes(5),
            ]);
        }

        Mail::send('emails.check_email_forget', compact('user', 'passwordResetToken'), function ($email) use ($user) {
            $email->subject('travel - verification email');
            $email->to($user->email, $user->name);
        });

        return response()->json(['message' => 'Please check your email to complete the password reset process.']);
    }

    public function showResetPasswordForm(user $user, $token)
    {
        $user = User::findOrFail($user->id);

        $passwordResetToken = PasswordResetToken::where('email', $user->email)
            ->where('token', $token)
            ->first();

        if (!$passwordResetToken) {
            return response()->json(['message' => 'not fount'], 404);
        }
    }
    public function processResetPassword(User $user, $token, Request $request)
    {
        // return $request;
        $data = $request->validate([
            'password' => 'required|min:8|',
            'confirm_Password' => 'required|min:8',
        ], [
            'password.required' => 'Password is required',
            'confirm_Password.required' => 'Password confirmation is required',
        ]);

        if ($request->password != $request->confirm_Password) {
            return response()->json([
                'errors' => [
                    'password_confirmation' => ['Password and password confirmation must be same']
                ]
            ], 422);
        }

        $user = User::findOrFail($user->id);

        $passwordResetToken = PasswordResetToken::where('email', $user->email)
            ->where('token', $token)
            //->where('expires_at', '>', now()) // Check if the token is still valid
            ->first();

        if (!$passwordResetToken) {
            return response()->json(['message' => 'Token not found'], 404);
        }

        $user->password = Hash::make($request->password);
        $user->save();


        return response()->json(['message' => 'Password updated successfully']);
    }


    public function logout()
    {
        if (Auth::check()) {

            // Auth::user()->tokens->revoke();

            Auth::user()->tokens->each(function ($token) {
                $token->delete();
            });
            return response()->json(['message' => 'Logged out successfully']);
        } else {
            return response()->json(['error' => 'Chưa xác thực'], 422);
        }
    }
}
