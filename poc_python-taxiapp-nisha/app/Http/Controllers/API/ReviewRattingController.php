<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DriverReview;
use App\Models\RiderReview;
use Illuminate\Support\Facades\Validator;

class ReviewRattingController extends BaseController
{
    //create review ratting

    public function createReviewRatting(Request $request)
    {

        $app_url =  url('');
        $input = $request->all();
        $validator = Validator::make($request->all(), [
            'ride_id' => 'required',
            'review_by' => 'required',
            'review_to' => 'required',
            'rating' => 'required',
            'description' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendError('Error validation', $validator->errors());
        }
        if(!empty($request->user_type)){
            if($request->user_type == "rider"){
                $input = $request->all();
                $riderreview = RiderReview::create($input);
                $riderreview->status = 0;
                $riderreview->save();
               
            }else{
                $input = $request->all();
                $driverreview = DriverReview::create($input);
                $driverreview->status = 0;
                $driverreview->save();
            }
            $success['status'] =  true;
            return $this->sendResponse($success, 'Review created successfully.');
        }else{
            $success['status'] =  false;
            return $this->sendResponse($success, 'Please select user type.');
        }
        
       
       
        // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
         
    }


      //get banner type list

      public function reviewRatingList(Request $request)
      {
        if(!empty($request->user_type)){
            if($request->user_type == "rider"){
                $rider_review = RiderReview::get();
                $rider_review_count= RiderReview::count();
                $success['rider_review'] =  $rider_review;
                $success['status'] =  "success";
                $success['rider_review_count'] =  $rider_review_count;
            }else{
                $driver_review = DriverReview::get();
                $driver_review_count= DriverReview::count();
                $success['driver_review'] =  $driver_review;
                $success['status'] =  "success";
                $success['driver_review_count'] =  $driver_review_count;
            }
         
          return $this->sendResponse($success, 'Review rating list');
      }
    }
}
