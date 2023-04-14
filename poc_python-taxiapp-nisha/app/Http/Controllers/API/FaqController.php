<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Faq;

class FaqController extends BaseController
{
    public function createFaq(Request $request)
    {
        
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',
            
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $faq = Faq::create($input);
       
        $faq->status = 1;

       
        $faq->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['question'] =  $faq->question;
       
        $success['answer'] =  $faq->answer;
        $success['status'] =  1;
        return $this->sendResponse($success, 'Faq created successfully.');
    }

    //get banner type list

    public function faqList()
    {
        
        $faq_list = Faq::get();
        $faq_count= Faq::count();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['faq_list'] =  $faq_list;
        $success['status'] =  "success";
        $success['faq_count'] =  $faq_count;
        
        return $this->sendResponse($success, 'Faq list');
    }


    /*
    params = $request, $id;
    update faq
    return view name admin.banners.edit, variables = banner object
    */
    public function updateFaq(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'question' => 'required',
            'answer' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $faq = Faq::find($id);
       


        $faq->question = $request->question;
        $faq->answer = $request->answer;
        if ($request->status != '') {
        $faq->status = isset($request->status) ? $request->status : '';
        }
        $faq->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'faq updated successfully.');
    }


      /*
    params = $request;
    delete the faq
    return the rest of the columns
    */
    public function deleteFaq(Request $request,$id)
    {
       
           // $id = isset($request->id)?$request->id:'';
       
        $faq = Faq::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Faq deleted successfully.');
    }

     /*
    params = $request;
    one record faq
     return the rest of the columns
    */
    public function faqData(Request $request,$id=null)
    {
       
        $faq = Faq::where('id', $id)->get();
        $success['status'] =  true;
        $success['faq'] =  $faq;
        return $this->sendResponse($success, 'Faq  data.');
    }
}
