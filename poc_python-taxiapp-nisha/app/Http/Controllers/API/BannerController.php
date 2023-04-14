<?php

namespace App\Http\Controllers\API;
use url;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use Illuminate\Support\Facades\Validator;
use App\Models\Banner;
class BannerController  extends BaseController
{
    public function createBanner(Request $request)
    {

        $app_url =  url('');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'status' => 'required',
            'description' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif,svg',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $Banner = Banner::create($input);
       
        $Banner->status = $request->status;

        $file_name = time(). '.' .$Banner->image->getClientOriginalExtension();
       
        
        $Banner->image->move(public_path('images/banner_image/'), $file_name);
       

        $Banner->image = $file_name;
        $Banner->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['title'] =  $Banner->title;
        $success['image'] =  $app_url . '/images/banner_image/' . $file_name;
        $success['description'] =  $Banner->description;
        $success['status'] =  1;
        return $this->sendResponse($success, 'Banner created successfully.');
    }

    //get banner type list

    public function bannerList()
    {
        $app_url =  url('');
        $banner_list = Banner::get();
        $banner_count= Banner::count();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        foreach($banner_list as $banner_data){
            $success['banner_list']=$banner_data;
            $success['banner_list']['image']=$app_url . '/images/banner_image/'.$banner_data->image;
            
        }
        $success['banner_list'] =  $banner_list;
        $success['status'] =  "success";
        $success['banner_count'] =  $banner_count;
        $success['image_url'] =  $app_url . '/images/banner_image/';
        return $this->sendResponse($success, 'Banner list');
    }


    /*
    params = $request, $id;
    update Banner
    return view name admin.banners.edit, variables = banner object
    */
    public function updateBanner(Request $request,$id)
    {
        $app_url =  url('');
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'description' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $banner = Banner::find($id);
        if ($request->image != '') {
            $Image = time() . '.' . request()->image->getClientOriginalExtension();

            request()->image->move(public_path('images/banner_image/'), $Image);
            $banner->image = $Image;
        }
        

        $banner->title = $request->title;
        $banner->description = $request->description;
        if ($request->status != '') {
        $banner->status = isset($request->status) ? $request->status : '';
        }
        $banner->save();
        $success['status'] =  true;
        $success['banner_image'] =  $app_url . '/images/banner_image/'.$banner->image;;
        return $this->sendResponse($success, 'banner updated successfully.');
    }


      /*
    params = $request;
    delete the banner
    return the rest of the columns
    */
    public function deleteBanner(Request $request,$id)
    {
       
           // $id = isset($request->id)?$request->id:'';
       
        $banner = Banner::where('id', $id)->delete();
        $success['status'] =  true;
        return $this->sendResponse($success, 'banner deleted successfully.');
    }

     /*
    params = $request;
    one record banner
     return the rest of the columns
    */
    public function bannerData(Request $request,$id=null)
    {
        $app_url =  url('');
        $banner = Banner::where('id', $id)->first();
        $success['status'] =  true;
        $success['banner'] =  $banner;
        $success['banner']['image'] = $app_url . '/images/banner_image/'.$banner->image;
        return $this->sendResponse($success, 'banner  data.');
    }
}
