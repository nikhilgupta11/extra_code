<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\EmailTemplate;
class EmailTemplateController extends BaseController

{
    //

    public function createEmailTemplate(Request $request)
    {
        
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $email_template = EmailTemplate::create($input);
       
        $email_template->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $email_template->name;
       
        $success['subjet'] =  $email_template->subjet;
        $success['body'] =  $email_template->body;
        $success['status'] =  "success";
        return $this->sendResponse($success, 'Email Template created successfully.');
    }

    //get banner type list

    public function emailTemplateList()
    {
        
        $email_template_list = EmailTemplate::get();
        $email_template_count= EmailTemplate::count();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['email_template_list'] =  $email_template_list;
        $success['status'] =  "success";
        $success['email_template_count'] =  $email_template_count;
        
        return $this->sendResponse($success, 'Email template count');
    }


    /*
    params = $request, $id;
    update email template
    return view name admin.banners.edit, variables = banner object
    */
    public function updateEmailTemplate(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'subject' => 'required',
            'body' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $emailTemplate = EmailTemplate::find($id);
       


        $emailTemplate->name = $request->name;
        $emailTemplate->subject = $request->subject;
        $emailTemplate->body = $request->body;
      
        $emailTemplate->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Email Template updated successfully.');
    }


      /*
    params = $request;
    delete the faq
    return the rest of the columns
    */
    public function deleteEmailTemplate(Request $request,$id)
    {
       
           // $id = isset($request->id)?$request->id:'';
       
        $faq = EmailTemplate::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Email Template deleted successfully.');
    }


      /*
    params = $request;
    one record email template
     return the rest of the columns
    */
    public function emailTemplateData(Request $request,$id=null)
    {
       
        $email_template = EmailTemplate::where('id', $id)->get();
        $success['status'] =  true;
        $success['email_template'] =  $email_template;
        return $this->sendResponse($success, 'Email Template  data.');
    }
}
