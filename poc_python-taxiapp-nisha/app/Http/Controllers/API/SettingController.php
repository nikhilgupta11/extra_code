<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Setting;


class SettingController extends BaseController
{
    //

    public function createSetting(Request $request)
    {

        $app_url =  url('');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'site_name' => 'required',
            'site_email' => 'required|email',
            'logo' => 'required|mimes:jpeg,png,jpg,gif,svg',
            'favicon' => 'required|mimes:jpeg,png,jpg,gif,svg',
            'copyright_text' => 'required',
            'site_currency' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $setting = Setting::create($input);

        $logo = time(). '.' .$setting->logo->getClientOriginalExtension(); 
        $setting->logo->move(public_path('images/setting_image/'), $logo);
       
        $favicon = time(). '.' .$setting->favicon->getClientOriginalExtension(); 
        $setting->favicon->move(public_path('images/setting_image/'), $favicon);

        $setting->logo = $logo;
        $setting->save();
      
        return $this->sendResponse($setting, 'setting created successfully.');
    }


      /*
    params = $request, $id;
    update Banner
    return view name admin.banners.edit, variables = banner object
    */
    public function updateSetting(Request $request,$id)
    {

        $validator = Validator::make($request->all(), [
            'site_name' => 'required',
            'site_email' => 'required|email',
            'logo' => 'mimes:jpeg,png,jpg,gif,svg',
            'favicon' => 'mimes:jpeg,png,jpg,gif,svg',
            'copyright_text' => 'required',
            'site_currency' => 'required',
            'address' => 'required',
            'miles'=>'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $setting = Setting::find($id);
        if ($request->logo != '') {
            $logo = time(). '.' .$request->logo->getClientOriginalExtension(); 
           
            $request->logo->move(public_path('images/setting_image/'), $logo);

            $setting->logo = $logo;
        }
        if ($request->favicon != '') {
            $favicon = time(). '.' .$request->favicon->getClientOriginalExtension(); 
            $request->favicon->move(public_path('images/setting_image/'), $favicon);

            $setting->favicon = $favicon;
        }


        $setting->site_name = $request->site_name;
        $setting->site_email = $request->site_email;
        $setting->copyright_text = $request->copyright_text;
        $setting->site_currency = $request->site_currency;
        $setting->address = $request->address;
        $setting->miles = $request->miles;
        $setting->save();
        $success['status'] =  true;
        return $this->sendResponse($success, 'Setting updated successfully.');
    }


      /*
    params = $request;
    one record portal content
     return the rest of the columns
    */
    public function settingData(Request $request,$id=null)
    {
        $app_url =  url('');
        $setting_data = Setting::where('id', $id)->first();
        $success['status'] =  true;
        $success['setting_data'] =  $setting_data;
      
        $success['setting_data']['logo'] =  $app_url . '/images/setting_image/'.$setting_data->logo;
        $success['setting_data']['favicon'] =  $app_url . '/images/setting_image/'.$setting_data->favicon;

        
        return $this->sendResponse($success, 'setting content   data.');
    }

}
