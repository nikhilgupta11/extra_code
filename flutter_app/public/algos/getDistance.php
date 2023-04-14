<?php 
function getDistance($addressFrom, $addressTo, $unit = '' , $mode = ''){
    // Google API key
    $apiKey = 'AIzaSyANDsDeGl7DmtDWAsdi98MxwP0kVxDzbPI';
    
    // Change address format
    $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
    $formattedAddrTo     = str_replace(' ', '+', $addressTo);
    
    // Geocoding API request with start address
    $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins='.$formattedAddrFrom.'&destinations='.$formattedAddrTo.$mode.'&key='.$apiKey);
    // $outputFrom = json_decode($geocodeFrom);
    // if(!empty($outputFrom->error_message)){
    //     return $outputFrom->error_message;
    // }
    
    // // Geocoding API request with end address
    // $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address='.$formattedAddrTo.'&sensor=false&key='.$apiKey);
    // $outputTo = json_decode($geocodeTo);
    // if(!empty($outputTo->error_message)){
    //     return $outputTo->error_message;
    // }
    
    // // Get latitude and longitude from the geodata
    // $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
    // $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
    // $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
    // $longitudeTo    = $outputTo->results[0]->geometry->location->lng;
    
    // // Calculate distance between latitude and longitude
    // $theta    = $longitudeFrom - $longitudeTo;
    // $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
    // $dist    = acos($dist);
    // $dist    = rad2deg($dist);
    // $miles    = $dist * 60 * 1.1515;
    $outputFrom = json_decode($geocodeFrom);
    $kms = str_replace(' km','',$outputFrom->rows[0]->elements[0]->distance->text);

    $unit = strtoupper($unit);

    if ($unit == "K") {
        echo round($kms, 2);
    } elseif ($unit == "M") {
        echo round($kms * 1000, 2) . ' meters';
    } else {
        echo round($kms * 1000 / 1609, 2)  . ' miles';
    }
}

$mode = '';
$mode = '&mode=transit&transit_mode=train';

echo getDistance("Jaipur","Delhi","K",$mode);
die;