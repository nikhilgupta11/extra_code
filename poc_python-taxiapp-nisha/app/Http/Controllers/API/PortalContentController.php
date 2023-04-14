<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\PortalContent;
use Illuminate\Support\Str;
class PortalContentController extends BaseController
{
    //

    public function createPortalContent(Request $request)
    {
        
        $input = $request->all();

        $validator = Validator::make($request->all(), [
            'page_title' => 'required',
            'image' => 'required|mimes:jpeg,png,jpg,gif',
            
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $portal_content = PortalContent::create($input);
        $id = $portal_content->id;
        $slug = $this->createSlug($portal_content->page_title,$id);
       
        

        $portal_content->slug = $slug;
        if ($request->image != '') {
            $Image = time() . '.' . request()->image->getClientOriginalExtension();

            request()->image->move(public_path('images/portal_content/'), $Image);
            $portal_content->image = $request->image;
        }
        $portal_content->save();
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
        $success['portal_content'] =  $portal_content;

        

        $success['status'] =  true;
       
        return $this->sendResponse($success, 'Portal content created successfully.');
    }

    public function createSlug($title, $id = 0)
    {
        $slug = str_slug($title);
        $allSlugs = $this->getRelatedSlugs($slug, $id);
        if (!$allSlugs->contains('slug', $slug)) {
            return $slug;
        }

        $i = 1;
        $is_contain = true;
        do {
            $newSlug = $slug . '-' . $i;
            if (!$allSlugs->contains('slug', $newSlug)) {
                $is_contain = false;
                return $newSlug;
            }
            $i++;
        } while ($is_contain);
    }
    protected function getRelatedSlugs($slug, $id = 0)
    {
        return PortalContent::select('slug')->where('slug', 'like', $slug . '%')
            ->where('id', '<>', $id)
            ->get();
    }   
   


    /*
    params = $request, $id;
    update portal contetn
    return view name admin.banners.edit, variables = banner object
    */
    public function updatePortalContent(Request $request,$slug)
    {

       
        $validator = Validator::make($request->all(), [
            'page_title' => 'required',
           
            'description' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }

        $portal_content = PortalContent::where('slug',$slug)->first();
        if ($request->image != '') {
            $Image = time() . '.' . request()->image->getClientOriginalExtension();

            request()->image->move(public_path('images/portal_content/'), $Image);
            $portal_content->image = $request->image;
        }


        $portal_content->page_title = $request->page_title;
        $portal_content->description = $request->description;
        if ($request->status != '') {
        $portal_content->status = isset($request->status) ? $request->status : '';
        }
        $portal_content->save();
        
        $portal_content->save();

        $success['status'] =  true;
        return $this->sendResponse($success, 'Portal content updated successfully.');
    }


      /*
    params = $request;
    one record portal content
     return the rest of the columns
    */
    public function portalContentData(Request $request,$slug=null)
    {
       
        $portal_content = PortalContent::where('slug', $slug)->get();
        $success['status'] =  true;
        $success['portal_content'] =  $portal_content;
        return $this->sendResponse($success, 'portal content   data.');
    }


     //get banner type list

     public function portalList()
     {
         $app_url =  url('');
         $portal_list = PortalContent::get();
         $portal_count= PortalContent::count();
         // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
         foreach($portal_list as $portal_data){
             $success['portal_list']=$portal_data;
             $success['portal_list']['image']=$app_url . '/images/portal_content/'.$portal_data->image;
             
         }
         $success['portal_list'] =  $portal_list;
         $success['status'] =  "success";
         $success['portal_count'] =  $portal_count;
         $success['image_url'] =  $app_url . '/images/portal_content/';
         return $this->sendResponse($success, 'Banner list');
     }
 
}
