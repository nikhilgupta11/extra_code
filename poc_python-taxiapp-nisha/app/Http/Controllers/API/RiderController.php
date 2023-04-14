<?php

namespace App\Http\Controllers\API;
   
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Auth;
use App\Rules\MatchOldPassword;
use App\Models\User;
use App\Models\Rider;
use App\Models\AuthToken;
use App\Models\Driver;
use App\Models\EmailTemplate;
use App\Models\UserVerify;
use App\Models\RiderReview;
use App\Models\ManageVehicleInfromation;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Exception;
use Twilio\Rest\Client;
use Illuminate\Support\Str;
use Mail; 
use Illuminate\Support\Facades\Validator;
use App\Mail\DemoMail;
use App\Models\vehicleTypeOption;
use DB;

class RiderController extends BaseController
{
    //


    public function signup(Request $request)
    {
      
        if(!empty($request->email)){
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'email|unique:riders',
                'mobile_number'=>'numeric|required',
                'password'=>'required|min:6|max:12',
                'confirm_password'=>'required|same:password',
            ]);
        }else{
            $validator = Validator::make($request->all(),[
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number'=>'numeric|required',
                'password'=>'required|min:6|max:12',
                'confirm_password'=>'required|same:password',
            ]);
        }


    if($validator->fails()){
        return $this->sendError('Error validation', $validator->errors());       
    }
    $otp = mt_rand(1000,9999);
    $token = Str::random(64);
    $input = $request->all();
    $user = Rider::create($input);
    $user->password = Hash::make($user->password);
    $slug=create_slug($user->first_name,$user->id);
    
    $user->status = 1;
    $user->token = $token;
    $user->user_type = "rider";
    $user->is_email_verify = 0;
    $user->otp = $otp;
    $user->slug = $slug;
    
            //send sms
            
           
            $receiverNumber = $user->mobile_number;
            $account_sid = getenv("TWILIO_SID");
            $auth_token = getenv("TWILIO_TOKEN");
           // $twilio_number = getenv("TWILIO_FROM");

            $client = new Client($account_sid, $auth_token);
            $client->messages->create($receiverNumber, [
                'from' => getenv("TWILIO_FROM"),
                'body' => $otp]);
            //send sms

              //verify account
          if(!empty($user->email)){   
            
            $template = EmailTemplate::where('name', 'verify_account')->first();
            $auth_token = AuthToken::first();
            $template_data = $template->body;
            $olddata=['[USER]'];
            $newdata=[$user->first_name];
            $mail_data[]=str_replace($olddata,$newdata,$template_data);
            $mail_data['subject'] = $template->subject;  
            $mail_data['name'] = $template->name;
            $mail_data['token'] = $token;
            $mail_data['user_type'] = $user->user_type;

            Mail::to($user->email)->send(new DemoMail($mail_data));
          }
        $user->save();
        $success['token'] =  $token;
        $success['auth_header_token'] =  $auth_token->token;
        $success['rider_id'] = $user->id;
        $success['user_type'] = $user->user_type;

    return $this->sendResponse($success, 'Rider register successfully');   
    
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
        $rider = Rider::where('token', $token)->first();
        $rider_otp = Rider::where('token', $token)->where('otp',$otp)->first(); 
        if(!$rider){
            $success = "False";
            $message = "Token not found .";
            return $this->sendResponse( $success,$message);

        }elseif(!$rider_otp){
            $success = "False";
            $message = "Otp not found.";
            return $this->sendResponse( $success,$message);
        }else{
           
                $rider_otp->is_otp_verify = 1;
                $rider_otp->otp = null;
                $rider_otp->save();
                $success = "True";
                $message = "Otp verify successfully.";
                return $this->sendResponse( $success,$message);
                
          
        }
    }


         /**
     * rider list
     *
     * @return response()
     */

        public function riderList()
        {
            $app_url =  url('');
            $rider_list = Rider::get();
            $rider_count= Rider::count();
            $success['rider_list'] =  $rider_list;
            $success['status'] =  "success";
            $success['banner_count'] =  $rider_count;
            return $this->sendResponse($success, 'Rider list');
        }

      /*
    params = $request;
    one record rider
     return the rest of the columns
    */
    public function riderData(Request $request,$id=null)
    {
        $app_url =  url('');
        $rider = Rider::where('id', $id)->first();
        $rider_review = RiderReview::where('review_to',$id)->get();
        foreach($rider_review as $driver_data){
            $success['rider_review'] = $driver_data;
            $success['rider_review']['rider_name'] = get_rider_name($driver_data->review_by);
        }
        
        $success['status'] =  true;
        $success['rider'] =  $rider;
        $success['rider_review'] =  $rider_review;
        return $this->sendResponse($success, 'Rider  data.');
    }


      /*
    params = $request;
    delete the rider
    return the rest of the columns
    */
    public function deleteRider(Request $request,$id)
    {
       
           // $id = isset($request->id)?$request->id:'';
       
        $rider = Rider::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Rider deleted successfully.');
    }


    public function updateRider(Request $request,$id)
    {
        $user = Rider::where('id',$id)->first();
        if(empty($user)){
            $success['status'] =  false;
            return $this->sendResponse($success, 'Rider not found.');
        }

       if(!empty($request->email)){
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'email|unique:riders,email,'.$id,
           
            'mobile_number'=>'required|numeric',
            
        ]);
       }else{ 
            $validator = Validator::make($request->all(), [
                'first_name' => 'required',
                'last_name' => 'required',
                'mobile_number'=>'required|numeric',
                
            ]);
       }

            if($validator->fails()){
                return $this->sendError('Error validation', $validator->errors());       
            }
          
            
            $user->first_name = isset($request->first_name)?$request->first_name:'';
            $user->last_name = isset($request->last_name)?$request->last_name:'';
            $user->email = isset($request->email)?$request->email:'';
            $user->gender = isset($request->gender)?$request->gender:'';
            $user->mobile_number = isset($request->mobile_number)?$request->mobile_number:'';
           
            $user->save();
        
            $success['first_name'] =  $user->first_name;
            $success['last_name'] =  $user->last_name;
            $success['user_type'] =  $user->user_type;
            $success['gender'] =  $user->gender;
            $success['mobile_number'] =  $user->mobile_number;
            $success['status'] =  1;
            return $this->sendResponse($success, 'Rider updated successfully.');
           

    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function saveLatLong(Request $request)
    {
      
        
        $validator = Validator::make($request->all(), [
            'latitude' => 'required',
            'longitude' => 'required',
            'token' => 'required',
         ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
        $token = isset($request->token)?$request->token:'';
        $user = Rider::where('token', $token)->first();
        if(!empty($user)){
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
            $success['success'] = True;
            $success['latitude'] =   $user->latitude;
            $success['longitude'] =   $user->longitude;
            return $this->sendResponse($success, 'Lat long save successfully.');
        }else{
            $success = False;
            return $this->sendResponse($success, 'Rider not found.');
        }     
    }

/**
     * show near by loaction
     *
     * @return response()
     */
    public function showNearByLocation(Request $request)
    {
        $setting_data = Setting::where('id',1)->first();
        $miles = $setting_data->miles;
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $distance = $miles;
        $mile = $distance*3959;

        $haversine = "(
            $mile * acos(
                cos(radians(" .$latitude. "))
                * cos(radians(`latitude`))
                * cos(radians(`longitude`) - radians(" .$longitude. "))
                + sin(radians(" .$latitude. ")) * sin(radians(`latitude`))
            )
        )";

        $drivers = Driver::select('*')->where('is_available',1)
            ->selectRaw("$haversine AS distance")
            ->having("distance", "<=", $distance)
            ->orderby("distance", "desc")
            ->get();
        foreach($drivers as $driver_data){
            $success['drivers'] = $driver_data;
            if(!empty($driver_data->id)){
              
                $manage_vehicle_info    =  ManageVehicleInfromation::where('driver_id',$driver_data->id)->first();

                if(!empty($manage_vehicle_info)){
                    $vehicle_id = $manage_vehicle_info->vehicle_type_id;
                    $vehicle_tye_option  = vehicleTypeOption::where('vehicle_type',$vehicle_id)->first();
                    $success['drivers']['vehicle_name'] =$manage_vehicle_info->vehicle_name ;
                    $success['drivers']['vehicle_type_name'] = get_vehicle_name($manage_vehicle_info->vehicle_type_id );
                    $success['drivers']['waiting_time'] =  $vehicle_tye_option->waiting_time;
                    $success['drivers']['waiting_charge'] =  $vehicle_tye_option->waiting_charge;
                    $success['drivers']['capicity'] =  $vehicle_tye_option->capicity;
                    $success['drivers']['per_km_price'] =  $vehicle_tye_option->per_km_price;
                   
                }
               
            }
               
        }   
           
            $success['success'] = True;
            $success['drivers'] = $drivers;
        
            return $this->sendResponse($success, 'Lat long detail.');
          
    }

}      
