<?php

namespace App\Http\Controllers\API;

use url;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\VehicleType;
use App\Models\vehicleTypeOption;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;

class VehicleController extends BaseController
{

    //to create vehicle type
    public function createVehicleType(Request $request)
    {

        $app_url =  url('');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $vehicle_type = VehicleType::create($input);
       
        $vehicle_type->status = isset($request->status)?$request->status:1;
        $vehicle_type->have_option = isset($request->have_option)?$request->have_option:0;
        $file_name = time(). '.' .$vehicle_type->image->getClientOriginalExtension();
       
        
        $vehicle_type->image->move(public_path('images/vehicle_image/'), $file_name);
       

        $vehicle_type->image = $file_name;
        $vehicle_type->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['name'] =  $vehicle_type->name;
        $success['image'] =  $app_url . '/images/vehicle_image/' . $file_name;
        $success['description'] =  $vehicle_type->description;
        $success['status'] =  $vehicle_type->status;
        return $this->sendResponse($success, 'Vechile type created successfully.');
    }



    //get vehicle type list

    public function vehicleTypeList()
    {
        $app_url =  url('');
        $vehicle_type_list = VehicleType::get();
        $vehicle_type_count= VehicleType::count();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
     

        foreach($vehicle_type_list as $vehicle_type_data){
           
            
            $success['vehicle_type_list']=$vehicle_type_data;
            $success['vehicle_type_list']['image']=$app_url . '/images/vehicle_image/'.$vehicle_type_data->image;
            
        }

        $success['vehicle_type_list'] =  $vehicle_type_list;
        $success['status'] =  "success";
        $success['vehicle_type_count'] =  $vehicle_type_count;
  
        $success['image_url'] =  $app_url . '/images/vehicle_image/';
        return $this->sendResponse($success, 'Vehicle list');
    }

    /*
    params = $request, $id;
    update vehicle type
    return view name admin.banners.edit, variables = banner object
    */
    public function updateVehicleType(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'status'=>'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        

        $vechicle_type = VehicleType::find($id);
        if ($request->image != '') {
            $Image = time() . '.' . request()->image->getClientOriginalExtension();

            request()->image->move(public_path('images/vehicle_image/'), $Image);
            $vechicle_type->image = $request->image;
        }


        $vechicle_type->name = $request->name;
        $vechicle_type->description = $request->description;
        if ($request->status != '') {
        $vechicle_type->status = isset($request->status) ? $request->status : '';
        }
        if ($request->have_option != '') {
            $vechicle_type->have_option = isset($request->have_option) ? $request->have_option : '';
            }
        $vechicle_type->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Vechile type updated successfully.');
    }

    /*
    params = $request;
    delete the vehicle type
    return the rest of the columns
    */
    public function deleteVehicleType(Request $request,$id)
    {
       
           // $id = isset($request->id)?$request->id:'';
       
        $vehicle_type = VehicleType::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Vechile type deleted successfully.');
    }

     /*
    params = $request;
    one record vehicle type
    return the rest of the columns
    */
    public function vehicleTypeData(Request $request,$id=null)
    {
        $app_url =  url('');
        $vehicle_type_data = VehicleType::where('id', $id)->first();
        $success['status'] =  true;
        $success['vehicle_type_data'] =  $vehicle_type_data;
        $success['vehicle_type_data']['image'] = $app_url . '/images/vehicle_image/'.$vehicle_type_data->image;
        return $this->sendResponse($success, 'Vechile type data.');
    }

    public function createVehicleTypeOption(Request $request)
    {

        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required',
            'name' => 'required',
            
            'waiting_time' => 'required',
            'waiting_charge' => 'required|numeric',
            'capicity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $vehicle_type = vehicleTypeOption::create($input);
        //$vehicle_type->status = 1;

        $vehicle_type->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['vehicle_type'] = $vehicle_type->vehicle_type;
        $success['name'] = $vehicle_type->name;
        $success['per_km_price'] = $vehicle_type->per_km_price;
        $success['waiting_time'] = $vehicle_type->waiting_time;
        $success['waiting_charge'] = $vehicle_type->waiting_charge;
        return $this->sendResponse($success, 'Vechile type option created successfully.');
    }

    /*
    params = $request;
    delete the vehicle type
    return the rest of the columns
    */
    public function deleteVehicleTypeOption($id = null)
    {
        $vehicle_type = vehicleTypeOption::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Vechile type option deleted successfully.');
    }

     /*
    to get vehicle option list
    */
    public function vehicleTypeOptionList()
    {

        $vehicle_type_option_list = vehicleTypeOption::get();

        foreach($vehicle_type_option_list as $vehicle_type_option_data){
            
           $vehicle_id = ($vehicle_type_option_data->vehicle_type);
           $success['vehicle_type_option_list'] =  $vehicle_type_option_data;

            $vehicle_name = VehicleType::where('id',$vehicle_id)->pluck('name')->toArray();
            $vehicle_name=($vehicle_name[0])?$vehicle_name[0]:'';
            $success['vehicle_type_option_list']['vehicle_type_name'] = $vehicle_name;
            
        }
        
       
    


        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['vehicle_type_option_list'] =  $vehicle_type_option_list;
        $success['status'] =  "success";
        return $this->sendResponse($success, 'Vehicle type option list');
    }


      /*
    params = $request;
    one record vehicle type option
    return the rest of the columns
    */
    public function vehicleTypeOptionData($id=null)
    {
       
        $vehicle_type_option_data = vehicleTypeOption::where('id', $id)->get();
        $success['status'] =  true;
        $success['vehicle_type_data'] =  $vehicle_type_option_data;
        return $this->sendResponse($success, 'Vechile type option data.');
    }



     /*
    params = $request, $id;
    update vehicle type
    return view name admin.banners.edit, variables = banner object
    */
    public function updateVehicleTypeOption(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required',
            'name' => 'required',
          
            'waiting_time' => 'required',
            'waiting_charge' => 'required|numeric',
            'capicity' => 'required|numeric',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        

        $vechicle_type = vehicleTypeOption::find($id);
        $vechicle_type->vehicle_type = $request->vehicle_type;
        $vechicle_type->name = $request->name;
        $vechicle_type->waiting_time = $request->waiting_time;
        $vechicle_type->waiting_charge = $request->waiting_charge;
        $vechicle_type->capicity = $request->capicity;
        if ($request->status != '') {
        $vechicle_type->status = isset($request->status) ? $request->status : '';
        }
        $vechicle_type->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Vechile type option updated successfully.');
    }

      /*getVehicleTypeOptionName
    update vehicle type
    return view name admin.banners.edit, variables = banner object
    */
    public function getVehicleTypeOptionName(Request $request,$id)
    {
      
        $vehicle_name = vehicleTypeOption::where('vehicle_type',$id)->select('id','name')->get();
        $success['status'] =  true;
        $success['vehicle_name'] =  $vehicle_name;
        return $this->sendResponse($success, 'Vechile type options.');
    }
}
