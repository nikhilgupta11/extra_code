<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\ManageCommission;

class ManageCommissionController extends BaseController
{
    //

    public function createManageCommission(Request $request)
    {
        
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'commission_type' => 'required',
            'value' => 'required|between:0,99.99',   
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $manage_commission = ManageCommission::create($input);
       
        $manage_commission->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['manage_commission'] =  $manage_commission;
        $success['status'] =  true;
       
        return $this->sendResponse($success, 'manage  commission created successfully.');
    }

   


    /*
    params = $request, $id;
    update manage commission
    
    */
    public function updateManageCommission(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'commission_type' => 'required',
            'value' => 'required|between:0,99.99',  

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $manage_commission = ManageCommission::find($id);
        
        $manage_commission->commission_type = $request->commission_type;
        $manage_commission->value = $request->value;
        
        $manage_commission->save();

        $success['status'] =  true;
        return $this->sendResponse($success, 'Manage commission updated successfully.');
    }


    /*
    params = $request;
    one record banner
     return the rest of the columns
    */
    public function manageCommissioData(Request $request,$id=null)
    {
       
        $manage_commission = ManageCommission::where('id', $id)->get();
        $success['status'] =  true;
        $success['manage_commission'] =  $manage_commission;
        return $this->sendResponse($success, 'manage commission  data.');
    }
}
