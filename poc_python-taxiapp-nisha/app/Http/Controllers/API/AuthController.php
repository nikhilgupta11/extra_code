<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use App\Models\User;
use App\Models\Rider;
use App\Models\Driver;
use App\Models\EmailTemplate;
use App\Models\UserVerify;
use App\Models\AuthToken;
use Illuminate\Support\Facades\Hash;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Mail;
use Illuminate\Support\Facades\Validator;
use App\Mail\DemoMail;
use DB;
use Illuminate\Support\Facades\File;

class AuthController extends BaseController
{
    public function signin(Request $request)
    {
        $auth_header_token = AuthToken::first();
        $email = isset($request->email) ? $request->email : '';
        $password = isset($request->password) ? $request->password : '';
        $mobile_number = isset($request->mobile_number) ? $request->mobile_number : '';
        if (!empty($email)) {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required',

            ]);
        } else {
            $validator = Validator::make($request->all(), [
                'mobile_number' => 'required',
            ]);
        }


        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }



        if ($request->user_type == "driver") {
            if (!empty($email)) {
                $driver =  Driver::where(['email' => $email])->first();
                if ($driver) {
                    $password =  $driver->password;
                    $pass_check = Hash::check($request->password, $password);

                    //$driver =  Driver::where(['password'=>$request->password])->first();

                    if (Hash::check($request->password, $password)) {
                        if ($driver->is_email_verify == 0) {

                            $template = EmailTemplate::where('name', 'verify_account')->first();
                            $template_data = $template->body;
                            $olddata = ['[USER]'];
                            $newdata = [$driver->first_name];
                            $mail_data[] = str_replace($olddata, $newdata, $template_data);
                            $mail_data['subject'] = $template->subject;
                            $mail_data['name'] = $template->name;
                            $mail_data['token'] = $driver->token;
                            $mail_data['user_type'] = $request->user_type;


                            Mail::to($driver->email)->send(new DemoMail($mail_data));
                            $success['auth_header_token'] = $auth_header_token->token;
                            $success['token'] = $driver->token;
                            $success['driver_id'] = $driver->id;
                            $success['is_email_verify'] = $driver->is_email_verify;
                            $success['is_otp_verify'] = $driver->is_otp_verify;
                            $success['document_status'] = $driver->is_email_verify;
                            $success['user_type'] = $request->user_type;
                            return $this->sendError($success, ['error' => 'Please verify email']);
                        } else if ($driver->is_otp_verify == 0) {
                            //send sms

                            $otp = $driver->otp;
                            $receiverNumber = '+' . $driver->mobile_number;
                            $account_sid = getenv("TWILIO_SID");
                            $auth_token = getenv("TWILIO_TOKEN");
                            // $twilio_number = getenv("TWILIO_FROM");

                            $client = new Client($account_sid, $auth_token);
                            $client->messages->create($receiverNumber, [
                                'from' => getenv("TWILIO_FROM"),
                                'body' => $otp
                            ]);
                            //send sms
                            $success['auth_header_token'] = $auth_header_token->token;
                            $success['token'] = $driver->token;
                            $success['driver_id'] = $driver->id;
                            $success['is_email_verify'] = $driver->is_email_verify;
                            $success['is_otp_verify'] = $driver->is_otp_verify;
                            $success['document_status'] = $driver->is_email_verify;
                            $success['user_type'] = $driver->user_type;
                            return $this->sendError($success, ['error' => 'Please verify otp']);
                        } else {
                            $driver->latitude = isset($request->latitude) ? $request->latitude : '';
                            $driver->longitude = isset($request->longitude) ? $request->longitude : '';
                            $driver->save();
                            $authUser = $driver;

                            $success['token'] = $driver->token;
                            $success['auth_header_token'] = $auth_header_token->token;
                            $success['driver_id'] = $driver->id;
                            $success['is_email_verify'] = $driver->is_email_verify;
                            $success['is_otp_verify'] = $driver->is_otp_verify;
                            $success['document_status'] = $driver->is_email_verify;
                            $success['user_type'] = $driver->user_type;

                            return $this->sendResponse($success, 'Driver signed in');
                        }
                    } else {
                        return $this->sendError('Unauthorised.', ['error' => 'Password not matched']);
                    }
                } else {
                    return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                }
            } else if (!empty($mobile_number)) {

                $driver =  Driver::where(['mobile_number' => $mobile_number])->first();
                if ($driver) {
                    $otp = mt_rand(1000, 9999);
                    $driver->otp = $otp;
                    $driver->save();
                    $receiverNumber = '+' . $driver->mobile_number;
                    $account_sid = getenv("TWILIO_SID");
                    $auth_token = getenv("TWILIO_TOKEN");
                    // $twilio_number = getenv("TWILIO_FROM");

                    $client = new Client($account_sid, $auth_token);
                    $client->messages->create($receiverNumber, [
                        'from' => getenv("TWILIO_FROM"),
                        'body' => $otp
                    ]);
                    //send sms
                    $success['token'] = isset($driver->token) ? $driver->token : '';
                    $success['auth_header_token'] = $auth_header_token->token;
                    $success['is_email_verify'] = $driver->is_email_verify;
                    $success['is_otp_verify'] = $driver->is_otp_verify;
                    $success['is_available'] = $driver->is_available;
                    $success['document_status'] = $driver->document_status;
                    $success['is_doument_upload'] = $driver->is_doument_upload;
                    $success['user_type'] = $request->user_type;
                    $success['driver_id'] = $driver->id;
                    return $this->sendError($success, ['error' => 'Please verify otp']);
                } else {
                    return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
                }
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        } elseif ($request->user_type == "rider") {

            $rider_with_email =  Rider::where(['email' => $email])->first();
            $rider_with_mobile =  Rider::where(['mobile_number' => $mobile_number])->first();

            if (!empty($rider_with_email)) {

                $password =  $rider_with_email->password;

                if (Hash::check($request->password, $password)) {
                    if ($rider_with_email->is_otp_verify == 0) {
                        $otp = $rider_with_email->otp;
                        $receiverNumber = '+' . $rider_with_email->mobile_number;
                        $account_sid = getenv("TWILIO_SID");
                        $auth_token = getenv("TWILIO_TOKEN");
                        // $twilio_number = getenv("TWILIO_FROM");

                        $client = new Client($account_sid, $auth_token);
                        $client->messages->create($receiverNumber, [
                            'from' => getenv("TWILIO_FROM"),
                            'body' => $otp
                        ]);
                        //send sms
                        $success['token'] = isset($rider_with_email->token) ? $rider_with_email->token : '';
                        $success['auth_header_token'] = $auth_header_token->token;
                        $success['rider_id'] = $rider_with_email->id;
                        $success['is_email_verify'] = $rider_with_email->is_email_verify;
                        $success['is_otp_verify'] = $rider_with_email->is_otp_verify;

                        $success['user_type'] = $rider_with_email->user_type;


                        return $this->sendError($success, ['error' => 'Please verify otp']);
                    } else {
                        //$authUser           = $rider_with_email; 
                        $rider_with_email->latitude = isset($request->latitude) ? $request->latitude : '';
                        $rider_with_email->longitude = isset($request->longitude) ? $request->longitude : '';
                        $rider_with_email->save();
                        $success['token'] = isset($rider_with_email->token) ? $rider_with_email->token : '';
                        $success['auth_header_token'] = $auth_header_token->token;
                        $success['is_email_verify'] = $rider_with_email->is_email_verify;
                        $success['is_otp_verify'] = $rider_with_email->is_otp_verify;
                        $success['user_type'] = $request->user_type;
                        $success['rider_id'] = $rider_with_email->id;
                        return $this->sendResponse($success, 'Rider signed in');
                    }
                }
            } elseif (!empty($rider_with_mobile)) {
                $otp = mt_rand(1000, 9999);
                $rider_with_mobile->otp = $otp;
                $rider_with_mobile->save();
                $receiverNumber = '+' . $rider_with_mobile->mobile_number;
                $account_sid = getenv("TWILIO_SID");
                $auth_token = getenv("TWILIO_TOKEN");
                // $twilio_number = getenv("TWILIO_FROM");

                $client = new Client($account_sid, $auth_token);
                $client->messages->create($receiverNumber, [
                    'from' => getenv("TWILIO_FROM"),
                    'body' => $otp
                ]);
                $success['token']   =  $rider_with_mobile->token;
                $success['auth_header_token']   =  $auth_header_token->token;
                $success['is_email_verify'] = $rider_with_mobile->is_email_verify;
                $success['is_otp_verify'] = $rider_with_mobile->is_otp_verify;
                $success['user_type'] = $request->user_type;
                $success['rider_id'] = $rider_with_mobile->id;
                return $this->sendResponse($success, 'Please verify otp');
            } else {
                return $this->sendError('Unauthorised.', ['error' => 'email or password is incorrect']);
            }
        } else {

            $credentials = [
                'email' =>  $request->email,
                'password' =>  $request->password,
            ];
            if (Auth::attempt($credentials)) {
                $user = Auth::user();
                $user->latitude = isset($request->latitude) ? $request->latitude : '';
                $user->longitude = isset($request->longitude) ? $request->longitude : '';
                $user->save();
                $authUser = Auth::user();
                $success['token'] =  $auth_header_token->token;
                $success['name'] =  $authUser->name;

                return $this->sendResponse($success, 'User signed in');
            } else {

                return $this->sendError('Unauthorised.', ['error' => 'Unauthorised']);
            }
        }
    }
    public function signup(Request $request)
    {

        $input = $request->all();
        $token = Str::random(64);

        $random_password = Str::random(10);


        if (isset($input['user_type']) && $input['user_type'] == "rider") {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'email|unique:riders',


                'gender' => 'required',
                'mobile_number' => 'required|numeric',

            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', $validator->errors());
            }
            $password_for_email = $random_password;

            $input['password'] = Hash::make($random_password);
            $user = Rider::create($input);
            $user->status = 1;
            $user->is_email_verify = 1;
            $user->is_otp_verify = 1;
            $user->save();
            $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
            $success['first_name'] =  $user->first_name;
            //$success['last_name'] =  $user->last_name;
            $success['user_type'] =  $user->user_type;
            // $success['gender'] =  $user->gender;
            $success['mobile_number'] =  $user->mobile_number;
            $success['status'] =  1;
            UserVerify::create([
                'user_id' => $user->id,
                'token' => $token
            ]);

            //user credential send working
            $template = EmailTemplate::where('name', 'user_credential')->first();
            $template_data = $template->body;
            $olddata = ['[USER]', '[EMAIL]', '[PASSWORD]'];
            $newdata = [$user->first_name, $user->email, $password_for_email];
            $mail_data[] = str_replace($olddata, $newdata, $template_data);
            $mail_data['subject'] = $template->subject;
            Mail::to($user->email)->send(new DemoMail($mail_data));

            return $this->sendResponse($success, 'Rider created successfully.');
        } elseif (isset($input['user_type']) && $input['user_type']  == "driver") {
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|email|unique:drivers',
                //'password' => 'required',
                // 'confirm_password' => 'required|same:password',
                'user_type' => 'required',
                'gender' => 'required',
                'mobile_number' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return $this->sendError('Error validation', $validator->errors());
            }

            $password_for_email = $random_password;
            $input['password'] = Hash::make($random_password);

            $user = Driver::create($input);

            $user->status = 1;
            $user->is_email_verify = 1;
            $user->is_otp_verify = 1;
            $user->save();
            $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;
            $success['first_name'] =  $user->first_name;
            $success['last_name'] =  $user->last_name;
            $success['user_type'] =  $user->user_type;
            $success['gender'] =  $user->gender;
            $success['mobile_number'] =  $user->mobile_number;
            $success['status'] =  1;
            UserVerify::create([
                'user_id' => $user->id,
                'token' => $token
            ]);

            //user credential send working
            $template = EmailTemplate::where('name', 'user_credential')->first();
            $template_data = $template->body;
            $olddata = ['[USER]', '[EMAIL]', '[PASSWORD]'];
            $newdata = [$user->first_name, $user->email, $password_for_email];
            $mail_data[] = str_replace($olddata, $newdata, $template_data);
            $mail_data['subject'] = $template->subject;
            Mail::to($user->email)->send(new DemoMail($mail_data));

            return $this->sendResponse($success, 'Driver created successfully.');
        } else {
            $error = "error";
            return $this->sendResponse($error, 'Please select user type');
        }
    }


    /**

     * Write code on Method

     *

     * @return response()

     */

    public function verifyAccount($token)

    {

        $verifyUser = UserVerify::where('token', $token)->first();



        $message = 'Sorry your email cannot be identified.';



        if (!is_null($verifyUser)) {

            $user = $verifyUser->user;



            if (!$user->is_email_verified) {

                $verifyUser->user->is_email_verified = 1;

                $verifyUser->user->save();

                $message = "Your e-mail is verified. You can now login.";
            } else {

                $message = "Your e-mail is already verified. You can now login.";
            }
        }
        $success['token'] =  $user->createToken('MyAuthApp')->plainTextToken;

        return $this->sendResponse($success, $message);
        //return redirect()->route('login')->with('message', $message);


    }

    /**
     * verify otp
     *
     * @return response()
     */
    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'otp' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }


        $token = $request->token;
        $otp = $request->otp;
        $driver = Driver::where('token', $token)->where('otp', $otp)->first();

        if ($driver) {
            $driver->is_otp_verify = 1;
            $driver->save();
            $success = "True";
            $message = "Otp verify successfully.";
            return $this->sendResponse($success, $message);
        } else {
            $success = "False";
            $message = "Driver not match .";
            return $this->sendResponse($success, $message);
        }
    }



    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6',
            'new_confirm_password' => 'required|same:new_password',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
        $driver =  Driver::where('token', $request->token)->first();

        if ($driver) {

            if (!Hash::check($request->old_password, $driver->password)) {
                $success = False;
                return $this->sendResponse($success, "Old Password Doesn't match!");
            }
            Driver::where('token', $request->token)->update(['password' => Hash::make($request->new_password)]);
            $success = True;
            return $this->sendResponse($success, "Password changed successfully");
        } else {
            $success = False;
            return $this->sendResponse($success, "driver not found");
        }

        //dd('Password change successfully.');
    }


    /**
     * Write code on Method
     *
     * @return response()
     */
    public function emailverifyAccount($token, $type)
    {
        if ($type == "driver") {
            $user = Driver::where('token', $token)->first();
        } else {
            $user = Rider::where('token', $token)->first();
        }


        $message = 'Sorry your email cannot be identified.';
        if (!$user->is_email_verify) {
            $user->is_email_verify = 1;
            $user->save();
            $message = "Your e-mail is verified. You can now login.";
        } else {
            $message = "Your e-mail is already verified. You can now login.";
        }

        return $message;
    }



    /**

     * Write code on Method

     *

     * @return response()

     */

    public function submitForgetPasswordForm(Request $request)
    {
        echo 1;
        print_r($request->all());
        die;
        $user_type = $request->user_type;
        if ($request->forget_type == "mobile") {
        } else {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
            ]);
        }
        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $token = Str::random(64);



        DB::table('password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
        ]);


        if ($user_type == "driver") {
            $user =  Driver::where('email', $email)->first();
            //user credential send working
            $template = EmailTemplate::where('name', 'forget_password')->first();
            $template_data = $template->body;
            $olddata = ['[USER]'];
            $newdata = [$user->first_name];
            $mail_data[] = str_replace($olddata, $newdata, $template_data);
            $mail_data['subject'] = $template->subject;
            $mail_data['name'] = $template->name;

            Mail::to($user->email)->send(new DemoMail($mail_data));
            return back()->with('message', 'We have e-mailed your password reset link!');
        } else if ($user_type == "rider") {

            $user =  Rider::where('email', $email)->first();
            //user credential send working
            $template = EmailTemplate::where('name', 'forget_password')->first();
            $template_data = $template->body;
            $olddata = ['[USER]'];
            $newdata = [$user->first_name];
            $mail_data[] = str_replace($olddata, $newdata, $template_data);
            $mail_data['subject'] = $template->subject;
            $mail_data['name'] = $template->name;
            Mail::to($user->email)->send(new DemoMail($mail_data));
            return back()->with('message', 'We have e-mailed your password reset link!');
        } else if ($user_type == "admin") {
            $user =  User::where('email', $email)->first();
            //user credential send working
            $template = EmailTemplate::where('name', 'forget_password')->first();
            $template_data = $template->body;
            $olddata = ['[USER]'];
            $newdata = [$user->first_name];
            $mail_data[] = str_replace($olddata, $newdata, $template_data);
            $mail_data['subject'] = $template->subject;
            $mail_data['name'] = $template->name;
            Mail::to($user->email)->send(new DemoMail($mail_data));
            return back()->with('message', 'We have e-mailed your password reset link!');
        } else {
            return back()->with('message', 'user not found');
        }
    }
}
