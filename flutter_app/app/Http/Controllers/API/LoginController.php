<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $reqData = $request->only('email', 'password');
        $reqData['type'] = 1;
        $reqData['status'] = 1;
        if (!Auth::attempt($reqData)) {
            throw new HttpResponseException($this->errorResponse('Email Or Password is wrong !!!', 401));
        }
        $token = User::where('email', $request->email)->first()->createToken("API TOKEN")->plainTextToken;
        return $this->successResponse(['user' => auth()->user(), 'token' => $token], 'User LoggedIn Successfully!', 200);
    }

    public function update(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'integer',
            'email' => 'required|email|unique:users,email,'.$request->user()->id,
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        try {
            $user = User::findOrFail($request->user()->id);
            $data = $request->all();
            if ($request->hasFile('avatar')) {
                if ($user->getMedia('users')->first()) {
                    $user->getMedia('users')->first()->delete();
                }
                $media = $user->addMedia($request->file('avatar'))->toMediaCollection('users');
                $data['avatar'] = $media->getUrl();
            }
            $data['name'] = $data['first_name'] . " " . $data['last_name'];
            $user->fill($data);
            $user->profile()->updateOrCreate(['user_id' => $user->id], $data);
            $user->save();
            return $this->successResponse(['user' => $user], 'User Profile Updated Successfully!', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 201);
        }
    }

    public function logout(Request $request)
    {
        $token = $request->user()->tokens();
        $token->delete();
        $response = ['status' => 'success', 'message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function changePassword(Request $request)
    {
        $rules = [
            'password' => ['required', 'min:8', Rules\Password::defaults()],
            'confirm_password' => 'required_with:password|same:password|min:8'
        ];
        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return $this->errorResponse('Validation Error!', 422, $validator->errors());
        }
        try {
            $user = User::findOrFail($request->user()->id);
            if (!Hash::check($request->old_password, $user->password)) {
                return $this->errorResponse('Old Password is wrong !!!', 401);
            }
            $user->password = Hash::make($request->password);
            $user->save();
            return $this->successResponse(['user' => $user], 'Password Changed Successfully!', 200);
        } catch (\Exception $e) {
            return $this->errorResponse($e->getMessage(), 201);
        }
    }
}
