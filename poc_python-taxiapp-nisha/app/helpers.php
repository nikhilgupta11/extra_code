<?php

//app/Helpers/helpers.php

//function to convert date format dd/mm/yyyy to yyyy-mm-dd

use App\Models\Driver;
use App\Models\Rider;
use App\Models\VehicleType;
use Carbon\Carbon;

if (!function_exists('create_slug')) {
function create_slug($title,$id){
   
    $slug = str_slug($title);
    $allSlugs = getRelatedSlugs($slug, $id);
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
}

 function getRelatedSlugs($slug, $id = 0)
{
    return Driver::select('slug')->where('slug', 'like', $slug . '%')
        ->where('id', '<>', $id)
        ->get();
}   

if (!function_exists('get_rider_name')) {
    function get_rider_name($id){
     $name =   Rider::where('id',$id)->pluck('first_name')->toArray();
     
     return isset($name[0])?$name[0]:'';
    }
}

if (!function_exists('get_vehicle_name')) {
    function get_vehicle_name($id){
     $name =   VehicleType::where('id',$id)->pluck('name')->toArray();
     
     return isset($name[0])?$name[0]:'';
    }
}