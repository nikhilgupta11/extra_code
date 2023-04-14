<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Mail\MailOtpVerifyMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $user = User::create([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'name'       => $request->first_name . ' ' . $request->last_name,
            'email'      => $request->email,
            'password'   => Hash::make($request->password),
        ]);
        $token = $user->createToken('API TOKEN')->plainTextToken;
        // username
        $username = config('app.initial_username') + $user->id;
        $user->username = $username;
        $user->save();
        return $this->successResponse(['user' => $user, 'token' => $token], 'User Registered Successfully!', 200);
    }

    public function forgotPassword(Request $request)
    {
        $rules = ['email' => 'required|email'];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $otp = rand(100000, 999999);
        $user = User::where('email', '=', $request->email)->first();
        if ($user == null) {
            return $this->errorResponse('Email Not Found!', 404);
        }
        $user->update(['otp' => $otp]);
        Mail::to($request->email)->send(new MailOtpVerifyMail($user));
        return $this->successResponse('Email Sent!', 200);
    }

    public function verifyOtp(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'otp' => 'required|max:6',
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $user = User::where('email', '=', $request->email)->first();
        if ($user == null) {
            return $this->errorResponse('Email Not Found!', 404);
        }
        if ($user->otp != $request->otp) {
            return $this->errorResponse('OTP Not Matched!', 404);
        }
        $user->email_verified_at = now()->toDateTimeString();
        $user->save();
        return $this->successResponse('Otp Matched Successfully!', 200);
    }

    public function resetPassword(Request $request)
    {
        $rules = [
            'email' => 'required|email',
            'password' => ['required', 'min:8', Rules\Password::defaults()],
            'confirm_password' => 'required_with:password|same:password|min:8'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        $user = User::where('email', '=', $request->email)->first();
        if ($user == null) {
            return $this->errorResponse('Email Not Found!', 404);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return $this->successResponse(['user' => $user], 'Password Reset Successfuly', 200);
    }
}
