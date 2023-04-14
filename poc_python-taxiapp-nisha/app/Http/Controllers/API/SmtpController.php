<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Smtp;
class SmtpController extends BaseController
{
    //


    public function createSmtp(Request $request)
    {
        
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_from_name' => 'required',
            'mail_from_address' => 'required',
           
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $smtp = Smtp::create($input);
       
        $smtp->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $smtp;
        $success['status'] =  true;
       
        return $this->sendResponse($success, 'Smtp created successfully.');
    }

   


    /*
    params = $request, $id;
    update email template
    return view name admin.banners.edit, variables = banner object
    */
    public function updateSmtp(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'mail_host' => 'required',
            'mail_port' => 'required',
            'mail_username' => 'required',
            'mail_password' => 'required',
            'mail_from_name' => 'required',
            'mail_from_address' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $smtp = Smtp::find($id);
        
        $smtp->mail_host = $request->mail_host;
        $smtp->mail_port = $request->mail_port;
        $smtp->mail_username = $request->mail_username;
        $smtp->mail_password = $request->mail_password;
        $smtp->mail_from_name = $request->mail_from_name;
        $smtp->mail_from_address = $request->mail_from_address;
        $smtp->save();

        $success['status'] =  true;
        return $this->sendResponse($success, 'Smtp updated successfully.');
    }

        /*
    params = $request;
    one record smtp content
     return the rest of the columns
    */
    public function smtpData(Request $request,$id=null)
    {
       
        $smtp_content = Smtp::where('id', $id)->get();
        $success['status'] =  true;
        $success['smtp_content'] =  $smtp_content;
        return $this->sendResponse($success, 'smtp content   data.');
    }
}
