<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Rider;
use App\Models\Driver;
class DashboardController extends BaseController
{
    //


     //get dashboard data

     public function dashboardList()
     {
         
         $driver_count = Driver::count();
         $rider_count= Rider::count();
         // $success['token'] =  $vehicle_type->createToken('MyAuthApp')->plainTextToken;
         $success['driver_count'] =  $driver_count;
         $success['rider_count'] =  $rider_count;
         $success['status'] =  "success";
        
         
         return $this->sendResponse($success, 'Dashboard data');
     }
}
