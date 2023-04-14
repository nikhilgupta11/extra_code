<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Faker\Provider\Base;
use Illuminate\Database\Console\Migrations\BaseCommand;
use Illuminate\Http\Request;

use App\Models\Driver;
use App\Models\ManageVehicleInfromation;
use App\Models\EmailTemplate;
use App\Models\vehicleTypeOption;
use App\Models\DriverAvailibility;
use Hash;
use Exception;
use Twilio\Rest\Client;
use Str;
use Mail;
use App\Mail\DemoMail;
use App\Models\AuthToken;
use App\Models\VehicleType;
use App\Models\DriverReview;
use Illuminate\Support\Facades\Validator;
class DriverController extends BaseController
{
    //
    public function signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:drivers',
            'mobile_number'=>'numeric|required',
            'password'=>'required|min:6|max:12',
            'confirm_password'=>'required|same:password',
        ]);

    if($validator->fails()){
        return $this->sendError('Error validation', $validator->errors());       
    }
    $otp = mt_rand(1000,9999);
    $token = Str::random(64);
    $input = $request->all();
    $user = Driver::create($input);
    $user->password = Hash::make($user->password);
    $slug=create_slug($user->first_name,$user->id);
    
    $user->status = 0;
    $user->token = $token;
    $user->user_type = "driver";
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
        $user->save();
        $success['token'] =  $token;
        $success['auth_header_token'] =  $auth_token->token;
        $success['driver_id'] = $user->id;
        $success['user_type'] = $user->user_type;
    return $this->sendResponse($success, 'Driver signed in');   
    
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
        $driver = Driver::where('token', $token)->first();
        $driver_otp = Driver::where('token', $token)->where('otp',$otp)->first(); 
        if(!$driver){
            $success = "False";
            $message = "Token not found .";
            return $this->sendResponse( $success,$message);

        }elseif(!$driver_otp){
            $success = "False";
            $message = "Otp not found.";
            return $this->sendResponse( $success,$message);
        }else{
           
                $driver_otp->is_otp_verify = 1;
                $driver_otp->otp = null;
                $driver_otp->save();
                $success = "True";
                $message = "Otp verify successfully.";
                return $this->sendResponse( $success,$message);
                
          
        }
       
       

    }



    /**
     * Write code on Method
     *
     * @return response()
     */
    public function uploadDocument(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'vehicle_number' => 'required',
            'license_number' => 'required',
           

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

       $token = $request->token;
        $user = Driver::where('token', $token)->first();
        $user->vehicle_number = $request->vehicle_number;
        $user->license_number = $request->license_number;
        $user->document_status = 0;
        $user->is_document_upload  = 1;
        if(!empty($request->insurance_document)&& isset($request->insurance_document)){
            $file_name = time() . '.' . $request->insurance_document->getClientOriginalExtension();
            $request->insurance_document->move(public_path('images/driver_document/'), $file_name);
            $user->insurance_document = $file_name;
        }
       // $file_name = time(). '.' .$user->insurance_document->getClientOriginalExtension();
        //$user->insurance_document->move(public_path('images/driver_document/'), $file_name);
        if(!empty($request->license_document)&& isset($request->license_document)){
            $file_name1 = time() . '.' . $request->license_document->getClientOriginalExtension();
            $request->license_document->move(public_path('images/driver_document/'), $file_name1);
            $user->license_document = $file_name1;
        }
        if(!empty($request->vehicle_rc)&& isset($request->vehicle_rc)){
            $file_name2 = time() . '.' . $request->vehicle_rc->getClientOriginalExtension();
            $request->vehicle_rc->move(public_path('images/driver_document/'), $file_name2);
            $user->vehicle_rc = $file_name2;
        }
      $user->save();

        $success['status'] =  true;
        return $this->sendResponse($success, 'driver profile added successfully.');
        
       
    }



     //get driver list

     public function driverList()
     {
         $app_url =  url('');
         $driver_list = Driver::get();
         $driver_count= Driver::count();
         // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
         foreach($driver_list as $driver_data){
             $success['driver_list']=$driver_data;
             $success['driver_list']['insurance_document']=$app_url . '/images/driver_document/'.$driver_data->insurance_document;
             $success['driver_list']['license_document']=$app_url . '/images/driver_document/'.$driver_data->license_document;
             $success['driver_list']['vehicle_rc']=$app_url . '/images/driver_document/'.$driver_data->vehicle_rc;

             
         }
         $success['driver_list'] =  $driver_list;
         $success['status'] =  "success";
         $success['driver_count'] =  $driver_count;
         $success['image_url'] =  $app_url . '/images/driver_document/';
         return $this->sendResponse($success, 'Driver list');
     }



      /*
    params = $request;
    one record driver
     return the rest of the columns
    */
    public function viewDriver($id=null)
    {
        $app_url =  url('');  
        $driver = Driver::where('id', $id)->first();
          $manage_vehicle_info    =  ManageVehicleInfromation::where('driver_id',$id)->first();
          if(!empty($manage_vehicle_info)){
            $vehicle_id = $manage_vehicle_info->vehicle_type_id;
            $vehicle_tye_option  = vehicleTypeOption::where('vehicle_type',$vehicle_id)->first();
            $vehicle_name =$manage_vehicle_info->vehicle_name ;
            $vehicle_type_name = get_vehicle_name($manage_vehicle_info->vehicle_type_id );
            if(!empty($vehicle_tye_option)){
                $waiting_time =  $vehicle_tye_option->waiting_time;
                $waiting_charge =  $vehicle_tye_option->waiting_charge;
                $capicity =  $vehicle_tye_option->capicity;
                $per_km_price =  $vehicle_tye_option->per_km_price;
            }

          }else{
            $vehicle_name ='';
            $vehicle_type_name = '';
          }
            
         
        $driver_review = DriverReview::where('review_to', $id)->get();
        foreach($driver_review as $driver_data){
            $success['driver_review'] = $driver_data;
            $success['driver_review']['rider_name'] = get_rider_name($driver_data->review_by);
        }
        $success['status'] =  true;
        $success['driver_review'] =  $driver_review;
        $success['driver'] =  $driver;
        $success['driver']['vehicle_name'] = $vehicle_name;
        $success['driver']['vehicle_type_name'] = $vehicle_type_name;
        $success['driver']['waiting_time'] = isset($waiting_time)?$waiting_time:'';
        $success['driver']['waiting_charge'] = isset($waiting_charge)?$waiting_charge:'';
        $success['driver']['capicity'] = isset($capicity)?$capicity:'';
        $success['driver']['per_km_price'] = isset($per_km_price)?$per_km_price:'';
        if(!empty($driver->insurance_document)){
            $success['driver']['insurance_document'] =  $app_url . '/images/driver_document/'.$driver->insurance_document;

        }
        if(!empty($driver->license_document)){
        
        $success['driver']['license_document'] =  $app_url . '/images/driver_document/'.$driver->license_document;
        }
        if(!empty($driver->vehicle_rc)){
            $success['driver']['vehicle_rc'] =  $app_url . '/images/driver_document/'.$driver->vehicle_rc;

        }

        return $this->sendResponse($success, 'driver  data.');
    }



     /**
     * Write code on Method
     *
     * @return response()
     */
    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'gender'=>'required',
         ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
        $token = isset($request->token)?$request->token:'';
        $user = Driver::where('token', $token)->first();
        if(!empty($user)){
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->gender = $request->gender;
            $user->save();
            $success = True;
            return $this->sendResponse($success, 'Driver basic profile updated.');
        }else{
            $success = False;
            return $this->sendResponse($success, 'Driver not found.');
        }     
    }


        /**
     * Write code on Method
     *
     * @return response()
     */
    public function updateEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            
         ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
        $token = isset($request->token)?$request->token:'';
        $user = Driver::where('token', $token)->first();
        if(!empty($user)){
            $email_count = Driver::where('token', $token)->where('email', $request->email)->count();
            if($email_count>0){
                $success = False;
                return $this->sendResponse($success, 'Email already exist.');
            }else{
                $user->email = $request->email;
                $user->is_email_verify = 0;
                $user->save();
                //verify account
                $template = EmailTemplate::where('name', 'verify_account')->first();
                $template_data = $template->body;
                $olddata=['[USER]'];
                $newdata=[$user->first_name];
                $mail_data[]=str_replace($olddata,$newdata,$template_data);
                $mail_data['subject'] = $template->subject;  
                $mail_data['name'] = $template->name;
                $mail_data['token'] = $token;
                $mail_data['user_type'] = "driver";
                Mail::to($user->email)->send(new DemoMail($mail_data));

                $success = True;
                return $this->sendResponse($success, 'Driver email updated please verify your account.');
                
            }   
        }else{
            $success = False;
            return $this->sendResponse($success, 'Driver not found.');
        }
    }



        /**
     * Write code on Method
     *
     * @return response()
     */
    public function updatePhone(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric',
            
         ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
        $token = isset($request->token)?$request->token:'';
        $user = Driver::where('token', $token)->first();
        if(!empty($user)){
            $mobile_count = Driver::where('token', $token)->where('mobile_number', $request->mobile_number)->count();
            if($mobile_count>0){
                $success = False;
                return $this->sendResponse($success, 'phone no already exist.');
            }else{
                $otp = mt_rand(1000,9999);
                $user->mobile_number = $request->mobile_number;
                $user->is_otp_verify = 0;
                $user->otp = $otp;
                $user->save();
                //verify account
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

                $success = True;
                return $this->sendResponse($success, 'Driver phone no updated please verify otp');
                
            }   
        }else{
            $success = False;
            return $this->sendResponse($success, 'Driver not found.');
        }

  
        
    }

  /*
    params = $request;
   to check email verify or mobile number verify or not
     return the rest of the columns
    */
    public function checkEmailMobile(Request $request)
    {
       
       if(!empty($request->token)) {
        $driver = Driver::where('token',$request->token)->first();

        $success['status'] =  true;
        $success['is_email_verify'] =  $driver->is_email_verify;
        $success['is_otp_verify'] =  $driver->is_otp_verify ;
        $success['document_status'] =  $driver->document_status;
        $success['is_document_upload'] =  isset($driver->is_document_upload)?$driver->is_document_upload:'';
       }else{
        $success['status'] =  false;
        $success['message'] = "email mobile token not found.";
       }
       
        

        return $this->sendResponse($success, 'driver  data.');
    }


  /*
    params = $request;
  to add bank detail
     return the rest of the columns
    */
    public function addBankDetail(Request $request)
    {
     
        $validator = Validator::make($request->all(), [
            'account_number' => 'required|numeric',
            'ifsc_code' => 'required',
            'bank_account_name' => 'required',
            'bank_name' => 'required',
            'bank_branch_name' => 'required',
            'bank_branch_address' => 'required',
            
            
         ]);

        if($validator->fails()){
            return $this->sendError('Error validation', $validator->errors());       
        }
        $driver = Driver::where('token',$request->token)->first();
        if($driver){
            $driver->account_number =  $request->account_number;
            $driver->ifsc_code  =  $request->ifsc_code;
            $driver->bank_account_name  =  $request->bank_account_name;
            $driver->bank_name  =  $request->bank_name;
            $driver->bank_branch_name =  $request->bank_branch_name;
            $driver->bank_branch_address=  $request->bank_branch_address;
            $driver->wallet_balance=  0;
            $driver->save();
            $success['status'] =  true;
            return $this->sendResponse($success, 'driver bank detail added successfulley.');
        }else{
            $success['status'] =  false;
            return $this->sendResponse($success, 'driver not found.');
        }
        
    }

     /*
    params = $request, $id;
    create manage vehicle info
    return view name admin.banners.edit, variables = banner object
    */
    public function createManageVehicleInformation(Request $request)
    {

       
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'driver_id' => 'required',
            'vehicle_type_id' => 'required',
            
            'vehicle_type_option_id' => 'required',
            'waiting_time' => 'required',
            'waiting_charge' => 'required|numeric',
            'vehicle_name' => 'required',
            'vehicle_number' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
       $manageinfo =  ManageVehicleInfromation::where('driver_id',$request->driver_id)->first();
       if(!empty($manageinfo)){
            $success['status'] =  false;
            return $this->sendResponse($success, 'Vehicle info for this drive is already added.');
       }else{
        $manage_vehicle_info = ManageVehicleInfromation::create($input);
       
        $manage_vehicle_info->driver_id = $request->driver_id;
        $manage_vehicle_info->vehicle_type_id = $request->vehicle_type_id;
        $manage_vehicle_info->vehicle_type_option_id = $request->vehicle_type_option_id;
        $manage_vehicle_info->waiting_time = $request->waiting_time;
        $manage_vehicle_info->waiting_charge = $request->waiting_charge;
        $manage_vehicle_info->vehicle_name = $request->vehicle_name;
        $manage_vehicle_info->vehicle_number = $request->vehicle_number;
        $manage_vehicle_info->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        
        $success['status'] =  true;
        return $this->sendResponse($success, 'Manage vehicle info created successfully.');
       }
        
    }
    
       /*
    params = $request, $id;
    update manage vehicle info
    return view name admin.banners.edit, variables = banner object
    */
    public function updateManageVehicleInformation(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required',
            'driver_id' => 'required',
            
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $manage_vehicle_info = ManageVehicleInfromation::find($id);
       

        $manage_vehicle_info->driver_id = $request->driver_id;
        $manage_vehicle_info->vehicle_type_id = $request->vehicle_type_id;
        $manage_vehicle_info->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Manage vehicle info updated successfully.');
    }
 

       /*
    params = $request;
    one record manage vehicle
     return the rest of the columns
    */
    public function getManageVehicle(Request $request,$id=null)
    {
       
        $vehicle_info = ManageVehicleInfromation::where('driver_id',$id)->get();

        foreach($vehicle_info as $vehicle_type_option_data){
            
            $vehicle_id = ($vehicle_type_option_data->vehicle_type_id);
            $success['vehicle_info'] =  $vehicle_type_option_data;
 
             $vehicle_name = VehicleType::where('id',$vehicle_id)->pluck('name')->toArray();
             $vehicle_name=($vehicle_name[0])?$vehicle_name[0]:'';
             $success['vehicle_info']['vehicle_type_name'] = $vehicle_name;
             
         }
      
        $success['status'] =  true;
        $success['vehicle_info'] =  $vehicle_info;
        return $this->sendResponse($success, 'Manage vehicle info  data.');
    }


        /*
    params = $request;
    one record manage vehicle
     return the rest of the columns
    */
    public function manageVehicleList(Request $request,$id=null)
    {
       
        $vehicle_info = ManageVehicleInfromation::get();
        $success['status'] =  true;
        $success['vehicle_info'] =  $vehicle_info;
        return $this->sendResponse($success, 'Manage vehicle info  data.');
    }

     /*
    params = $request, $id;
    driver availibility
    return view name admin.banners.edit, variables = banner object
    */
    public function driverAvailibility(Request $request)
    {
       
       // $json_string_in_array = ['{"a":1,"b":2,"c":3,"d":4,"e":5}'];
      //  $json_array = json_decode($json_string_in_array[0]);

        $json_array = json_encode($request->all());
        
        $decodedArray = json_decode($json_array);
        $driver_id = ($decodedArray->driver_id);
       
       $query =  DriverAvailibility::where('driver_id',$driver_id)->delete();
      
        foreach($decodedArray->days as $data){
          
            $driver_availibility = new DriverAvailibility;
            $driver_availibility->day =  $data->day;
            $driver_availibility->start_time =  $data->start_time;
            $driver_availibility->end_time =  $data->end_time;
            if($data->status == "true"){
                $driver_availibility->status =  1;
            }else{
                $driver_availibility->status =  0;
            }
            
            $driver_availibility->driver_id =  $data->driver_id;
            $driver_availibility->save();
        }
        $success['status'] =  true;
        return $this->sendResponse($success, 'Driver availibility save successfully.');
       
    }


       /*
    params = $request;
    driver availibility list
     return the rest of the columns
    */
    public function getDriverAvailibility(Request $request,$id)
    {
        $driver_availibility = DriverAvailibility::where('driver_id',$id)->get();
        $success['status'] =  true;
        foreach($driver_availibility as $driver_data){
            $success['driver_availibility'] =  $driver_data;
            if($driver_data->status == 1){
                $success['driver_availibility']['status'] =  true;

            }else{
                $success['driver_availibility']['status'] =  false;
            }
        }
        $success['driver_availibility'] =  $driver_availibility;
        return $this->sendResponse($success, 'Driver availibility info  data.');
    }



     /*
    params = $request, $id;
    driver availibility
    return view name admin.banners.edit, variables = banner object
    */
    public function isDriverAvailable(Request $request)
    {
      
        $validator = Validator::make($request->all(), [
            'token' => 'required',
            'is_available' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
       
        $driver_availibility = Driver::where('token',$request->token)->first();
        $driver_availibility->is_available =  $request->is_available;
        $driver_availibility->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Driver availibility status has been updated successfully.');
       
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
        $user = Driver::where('token', $token)->first();
        if(!empty($user)){
            $user->latitude = $request->latitude;
            $user->longitude = $request->longitude;
            $user->save();
            
            $success['latitude'] =   $request->latitude;
            $success['longitude'] =   $request->longitude;
            $success['success'] = True;
            return $this->sendResponse($success, 'Lat long save successfully.');
        }else{
            $success = False;
            return $this->sendResponse($success, 'Driver not found.');
        }     
    }
}