<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Rider;
use App\Models\Driver;
use App\Models\EmailTemplate;
use Hash;
use Illuminate\Support\Str;
use Mail; 
use Illuminate\Support\Facades\Validator;
use Redirect;
use App\Mail\DemoMail;
use Twilio\Rest\Client;

class ForgotPasswordController extends BaseController
{
    //

    /**
       * Write code on Method
       *
       * @return response()
       */
      public function submitForgetPassword(Request $request)
      {
        
        $validator = Validator::make($request->all(),[
          'forget_type' => 'required',
          'user_type' => 'required',
        ]);
        if($validator->fails()){
          return $this->sendError('Error validation', $validator->errors()); 
        }

        $user_type = $request->user_type;
        if($request->forget_type == "email"){
     
          $validator = Validator::make($request->all(), [
            'email' => 'required|email',
          ]);
          if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
           
            if($user_type == "admin"){
              $user  = User::where('email',$request->email)->first();
             
              if(!empty($user)){
                $token = $user->token;
               }


               if(!empty($user)){
                $first_name = $user->first_name; 
                $template = EmailTemplate::where('name','reset_password')->first();
                $template_data = $template->body;
                $olddata=['[USER]'];
                $newdata=[$user->first_name];
                $mail_data[]=str_replace($olddata,$newdata,$template_data);
                $mail_data['subject'] = $template->subject;  
                $mail_data['name'] = $template->name;  
                $mail_data['token'] = $token;  
                $mail_data['user_type'] = "admin";  
              
                Mail::to($user->email)->send(new DemoMail($mail_data));
    
                $success = true;
            
                return $this->sendResponse($success, 'We have e-mailed your password reset link!');
              }else{
                $success = false;
                return $this->sendError($success, ['error'=>'User not found with this email.']);
               
              }

            }elseif($user_type == "rider"){
              $user  = Rider::where('email',$request->email)->first();
              
              if(!empty($user)){
                $token = $user->token;
               }

               if(!empty($user)){
                $first_name = $user->first_name; 
                $template = EmailTemplate::where('name','reset_password')->first();
                $template_data = $template->body;
                $olddata=['[USER]'];
                $newdata=[$user->first_name];
                $mail_data[]=str_replace($olddata,$newdata,$template_data);
                $mail_data['subject'] = $template->subject;  
                $mail_data['name'] = $template->name;  
                $mail_data['token'] = $token;  
                $mail_data['user_type'] = "rider";  
              
                Mail::to($user->email)->send(new DemoMail($mail_data));
    
                $success = true;
            
                return $this->sendResponse($success, 'We have e-mailed your password reset link!');
              }else{
                $success = false;
                return $this->sendError($success, ['error'=>'User not found with this email.']);
               
              }

              
            }else{
              $user  = Driver::where('email',$request->email)->first();
             if(!empty($user)){
              $token = $user->token;
             }

             if(!empty($user)){
              $first_name = $user->first_name; 
              $template = EmailTemplate::where('name','reset_password')->first();
              $template_data = $template->body;
              $olddata=['[USER]'];
              $newdata=[$user->first_name];
              $mail_data[]=str_replace($olddata,$newdata,$template_data);
              $mail_data['subject'] = $template->subject;  
              $mail_data['name'] = $template->name;  
              $mail_data['token'] = $token;  
              $mail_data['user_type'] = "driver"; 
            
              Mail::to($user->email)->send(new DemoMail($mail_data));
  
              $success = true;
          
              return $this->sendResponse($success, 'We have e-mailed your password reset link!');
            }else{
              $success = false;
              return $this->sendError($success, ['error'=>'User not found with this email.']);
              
            }

             
            }

        }else if($request->forget_type == "mobile_number"){
         
          if($user_type == "admin"){
            $data = User::where('mobile_number',$request->mobile_number)->first();
            $token = $data->token;

            $reset_password_link = "your reset password link:".env('ADMIN_RESET_PASSWORD_LINK').$token;
            $receiverNumber = '+'.$request->mobile_number;
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
          // $twilio_number = getenv("TWILIO_FROM");

          $client = new Client($account_sid, $auth_token);
          $client->messages->create($receiverNumber,[
              'from' => getenv("TWILIO_FROM"),
                  'body' => $reset_password_link]);
          $success['token']   =  $token;
          return $this->sendResponse($success, 'We have sent reset password link on your mobile number.');

          }elseif($user_type == "rider"){
            $data = Rider::where('mobile_number',$request->mobile_number)->first();
            $token = $data->token;
            $reset_password_link = "your reset password link:".env('RIDER_RESET_PASSWORD_LINK').$token;
          $receiverNumber = '+'.$request->mobile_number;
          $account_sid = getenv("TWILIO_SID");
          $auth_token = getenv("TWILIO_TOKEN");
          // $twilio_number = getenv("TWILIO_FROM");

          $client = new Client($account_sid, $auth_token);
          $client->messages->create($receiverNumber,[
              'from' => getenv("TWILIO_FROM"),
                  'body' => $reset_password_link]);
          $success['token']   =  $token;
          return $this->sendResponse($success, 'We have sent reset password link on your mobile number.');
          }else{
            $data = Driver::where('mobile_number',$request->mobile_number)->first();
            $token = $data->token;

            $reset_password_link = "your reset password link:".env('DRIVER_RESET_PASSWORD_LINK').$token;
          $receiverNumber = '+'.$request->mobile_number;
          $account_sid = getenv("TWILIO_SID");
          $auth_token = getenv("TWILIO_TOKEN");
          // $twilio_number = getenv("TWILIO_FROM");

          $client = new Client($account_sid, $auth_token);
          $client->messages->create($receiverNumber,[
              'from' => getenv("TWILIO_FROM"),
                  'body' => $reset_password_link]);
          $success['token']   =  $token;
          return $this->sendResponse($success, 'We have sent reset password link on your mobile number.');
          }
        
        }
          
      }

            /**

       * Write code on Method

       *

       * @return response()

       */

      public function showResetPasswordForm($token,$user_type) { 
       
        if($user_type == "driver"){
          $url = env('DRIVER_RESET_PASSWORD_LINK').$token;
        }elseif($user_type == "rider"){
          $url = env('RIDER_RESET_PASSWORD_LINK').$token;
        }else{
          $url = env('ADMIN_RESET_PASSWORD_LINK').$token;
        }
        
        return Redirect::to($url);
               // return \Redirect::route('http://localhost:5000/forgetpassword/change',$token);
        
      }


          /**

       * Write code on Method

       *

       * @return response()

       */

      public function submitResetPasswordForm(Request $request)
      {
       
        $validator = Validator::make($request->all(),[
          'token' => 'required',
          'user_type' => 'required',
          'password' => 'required|string|min:6',
          'confirm_password' => 'required|same:password'

          ]);

          if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors()); 
          }
          if(!empty($request->user_type)){
            if($request->user_type == "driver"){
              $pass = Hash::make($request->password);
             
                $user = Driver::where('token', $request->token)->update(['password' => Hash::make($request->password)]);
            }elseif($request->user_type == "rider"){
              
                $user = Rider::where('token', $request->token)->update(['password' => Hash::make($request->password)]);
            }elseif($request->user_type == "admin"){
                $user = Admin::where('token', $request->token)->update(['password' => Hash::make($request->password)]);
            }

            $success = True;
            return $this->sendResponse( $success,"Password reset successfully");
          }else{
            $success = False;
            return $this->sendResponse( $success,"User not found");
          }

      }

}
