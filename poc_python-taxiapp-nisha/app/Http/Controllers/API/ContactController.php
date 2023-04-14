<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Mail\DemoMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Contact;
use App\Models\Setting;
use App\Models\EmailTemplate;
use Mail;
class ContactController extends BaseController
{
    
    //create contact 

    public function createContact(Request $request)
    {
       
       
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_no'=>'required|integer|regex:/[0-9]{10}/',
            'description'=>'required'
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

       $contact = Contact::create($input);
        $contact->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['contact'] =  $contact;
        //mail to customer
        $name = $request->name; 
        $email = $request->email; 
        $mobile_no = $request->mobile_no; 
        $template = EmailTemplate::where('name','front_contact_mail')->first();
        $template_data = $template->body;
        $olddata=['[USER]'];
        $newdata=[$name];
        $mail_data[]=str_replace($olddata,$newdata,$template_data);
        $mail_data['subject'] = $template->subject;  
        $mail_data['name'] = $template->name;  
        Mail::to($email)->send(new DemoMail($mail_data));
        //mail to customer

        //mail to admin

        //mail to admin
        $data = Setting::first();
        $admin_eamil = $data->site_email;
        $name = $request->name; 
        $email = $request->email; 
        $template = EmailTemplate::where('name','admin_contact_mail')->first();
        $template_data = $template->body;
        $olddata=['[USER]','[EMAIL]','[PHONE]'];
        $newdata=[$name,$email,$mobile_no];
        $mail_data[]=str_replace($olddata,$newdata,$template_data);
        $mail_data['subject'] = $template->subject;  
        $mail_data['name'] = $template->name;  
      
        Mail::to($admin_eamil)->send(new DemoMail($mail_data));
        //mail to customer
        return $this->sendResponse($success, 'Conatact created successfully.');
    }

    //get contact  list

    public function ContactList()
    {
        
        $contact_list = Contact::get();
        $contact_count= Contact::count();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['contact_list'] =  $contact_list;
        $success['status'] =  "success";
        $success['contact_count'] =  $contact_count;
        
        return $this->sendResponse($success, 'Contact list');
    }


    /*
    to update contact
    */
    public function updateContact(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'mobile_no'=>'required|integer',
            'description'=>'required'
            

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $contact = Contact::find($id);
       


        $contact->name = $request->name;
        $contact->email = $request->email;
        $contact->mobile_no = $request->mobile_no;
        $contact->description = $request->description;
       
        $contact->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Contact updated successfully.');
    }


      /*
   to delete contact us
    */
    public function deleteContact(Request $request,$id)
    {
       
           // $id = isset($request->id)?$request->id:'';
       
        $faq = Contact::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Contact deleted successfully.');
    }

     /*
    to get contact single data
    */
    public function contactData(Request $request,$id=null)
    {
       
        $contact = Contact::where('id', $id)->get();
        $success['status'] =  "success";
        $success['faq'] =  $contact;
        return $this->sendResponse($success, 'contact  data.');
    }

}
