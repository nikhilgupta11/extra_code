<?php

namespace App\Traits;

trait CalculationServiceClass
{

    public function getFromToData($addressFrom,$addressTo)
    {
        // Google API key
        $apiKey = config('app.google_api');

        // Change address format
        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo     = str_replace(' ', '+', $addressTo);

        // Geocoding API request with start address
        $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddrFrom . '&sensor=false&key=' . $apiKey);
        $outputFrom = json_decode($geocodeFrom);
        if (!empty($outputFrom->error_message)) {
            return $outputFrom->error_message;
        }

        // Geocoding API request with end address
        $geocodeTo = file_get_contents('https://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddrTo . '&sensor=false&key=' . $apiKey);
        $outputTo = json_decode($geocodeTo);
        if (!empty($outputTo->error_message)) {
            return $outputTo->error_message;
        }

        // Get latitude and longitude from the geodata
        $latitudeFrom    = $outputFrom->results[0]->geometry->location->lat;
        $longitudeFrom    = $outputFrom->results[0]->geometry->location->lng;
        $latitudeTo        = $outputTo->results[0]->geometry->location->lat;
        $longitudeTo    = $outputTo->results[0]->geometry->location->lng;

        // Calculate distance between latitude and longitude
        $theta    = $longitudeFrom - $longitudeTo;
        $dist    = sin(deg2rad($latitudeFrom)) * sin(deg2rad($latitudeTo)) +  cos(deg2rad($latitudeFrom)) * cos(deg2rad($latitudeTo)) * cos(deg2rad($theta));
        $dist    = acos($dist);
        $dist    = rad2deg($dist);
        $miles    = $dist * 60 * 1.1515;
        return $miles;
    }
    // Get Distance By Air API
    public function getDistance($addressFrom, $addressTo, $via1, $via2, $unit = '')
    {

        if($via1 != '' && $via2 != ''){
            $miles = $this->getFromToData($addressFrom,$via1);
            $miles += $this->getFromToData($via1,$via2);
            $miles += $this->getFromToData($via2,$addressTo);
        }else if(($via1 != '' && $via2 == '') || ($via1 == '' && $via2 != '')){
            $via = ($via1 != '') ? $via1 : $via2;
            $miles = $this->getFromToData($addressFrom,$via);
            $miles += $this->getFromToData($via,$addressTo);
        }else{
            $miles = $this->getFromToData($addressFrom,$addressTo);
        }
        // Convert unit and return distance
        $unit = strtoupper($unit);
        if ($unit == "K") {
            return round($miles * 1.609344, 2);
        } elseif ($unit == "M") {
            return round($miles * 1609.344, 2) . ' meters';
        } else {
            return round($miles, 2) . ' miles';
        }
    }
    // Get Distance By Road API
    public function getDistanceByRoad($addressFrom, $addressTo, $unit = '', $mode = '')
    {
        $apiKey = config('app.google_api');
        // Change address format
        $formattedAddrFrom    = str_replace(' ', '+', $addressFrom);
        $formattedAddrTo     = str_replace(' ', '+', $addressTo);

        // Geocoding API request with start address
        $geocodeFrom = file_get_contents('https://maps.googleapis.com/maps/api/distancematrix/json?origins=' . $formattedAddrFrom . '&destinations=' . $formattedAddrTo . $mode . '&key=' . $apiKey);
        $outputFrom = json_decode($geocodeFrom);
        $kms = str_replace(' km', '', $outputFrom->rows[0]->elements[0]->distance->text);

        $unit = strtoupper($unit);
        if ($unit == "K") {
            return round($kms, 2);
        } elseif ($unit == "M") {
            return round($kms * 1000, 2) . ' meters';
        } else {
            return round($kms * 1000 / 1609, 2)  . ' miles';
        }
    }

    public function calculateCO2($transport, $formData)
    {
        $column = (config('transports.column') == 'transport_type') ? strtolower($transport[config('transports.column')]) : $transport[config('transports.column')];
        if ($column == config('transports.airplane')) {
            return $this->flightCalculation($formData);
        }
        if ($column == config('transports.boat')) {
            return $this->boatCalculation($formData);
        }
        if ($column == config('transports.coach')) {
            return $this->busCalculation($formData);
        }
        if ($column == config('transports.car')) {
            return $this->carCalculation($formData);
        }
        if ($column == config('transports.train')) {
            return $this->trainCalculation($formData);
        }
        if ($column == config('transports.hotel')) {
            return $this->hotelCalculation($formData);
        }
    }

    public function flightCalculation($formData)
    {
        $journey_type = 1;
        $total_km = 0;
        $airclass = 'Economy';
        $airclassFactor = 1;
        $emmisionFactor = 0;
        $airoundTrip = 1;
        $totalAirCO2 = 0;
        $from = 'London, ENG';
        $to = 'New York, US';

        $via1 = '';
        $via2 = '';

        foreach ($formData as $key => $field) {
            $name = $field['name'] ?? '';
            $val = $field['value'] ?? '';
            if ($name == 'from') {
                $from = $val;
            }
            if ($name == 'to') {
                $to = $val;
            }
            if ($name == 'class') {
                $airclass = $val;
            }
            if ($name == 'round_trip') {
                $airoundTrip = (int)$val;
            }
            if ($name == 'via_1') {
                $via1 = $val;
            }
            if ($name == 'via_2') {
                $via2 = $val;
            }
        }
        $total_km = $this->getDistance($from, $to, $via1, $via2, "K");
        $total_km = $total_km + 90;

        if ($total_km > 500 && $total_km < 3700) {
            $journey_type = 2;
        } else if ($total_km >= 3700) {
            $journey_type = 3;
        }

        if ($journey_type == 3) {
            if ($airclass == 'Economy') {
                $airclassFactor = 1;
            }
            if ($airclass == 'Business Economy') {
                $airclassFactor = 1.6;
            }
            if ($airclass == 'Business') {
                $airclassFactor = 2.9;
            }
            if ($airclass == 'First') {
                $airclassFactor = 4;
            }
        } else {
            if ($airclass == 'Economy' || $airclass == 'Premium Economy') {
                $airclassFactor = 1;
            } else {
                $airclassFactor = 1.5;
            }
        }

        if ($journey_type == 3) {
            $emmisionFactor = 0.1662;
        } else if ($journey_type == 2) {
            $emmisionFactor = 0.1728;
        } else {
            $emmisionFactor = 0.2828;
        }

        $totalAirCO2 = number_format((float)($total_km * $emmisionFactor * $airclassFactor * $airoundTrip), 2, '.', '');
        return $totalAirCO2;
    }

    public function boatCalculation($formData)
    {
        $passengerType = "Foot passenger";
        $BoatType = "Small Passenger Ferry";
        $timeTravel = 1;
        $emmisionBFactor = 1;
        $totalBoatCO2 = 1;
        $BoatroundTrip = 1;

        $passengerType = 'Car passenger';
        $BoatType = 'Cruiseferry';
        $timeTravel = 10;
        $BoatroundTrip = 1;

        foreach ($formData as $key => $field) {
            $name = $field['name'];
            $val = $field['value'];
            if ($name == 'time') {
                $timeTravel = $val;
            }
            if ($name == 'passenger_type') {
                $passengerType = $val;
            }
            if ($name == 'boat_type') {
                $BoatType = $val;
            }
            if ($name == 'round_trip') {
                $BoatroundTrip = (int)$val;
            }
        }

        if ($passengerType == "Foot passenger" && $BoatType == "Small Passenger Ferry") {
            $emmisionBFactor = 6.878;
        }
        if ($passengerType == "Foot passenger" && $BoatType == "Cruiseferry") {
            $emmisionBFactor = 0.805;
        }
        if ($passengerType == "Foot passenger" && $BoatType == "High speed ferry") {
            $emmisionBFactor = 8.677;
        }
        if ($passengerType == "Car passenger" && $BoatType == "Small Passenger Ferry") {
            $emmisionBFactor = 39.06704;
        }
        if ($passengerType == "Car passenger" && $BoatType == "Cruiseferry") {
            $emmisionBFactor = 4.5724;
        }
        if ($passengerType == "Car passenger" && $BoatType == "High speed ferry") {
            $emmisionBFactor = 49.28536;
        }

        $totalBoatCO2 = number_format((float)($emmisionBFactor * $timeTravel * $BoatroundTrip), 2, '.', '');
        return $totalBoatCO2;
    }

    public function busCalculation($formData)
    {
        $CoachType = 0;
        $CoachFuel = 0;
        $CoachDistance = 0;
        $CoachemmisionFactor = 1;
        $CoachemmisionFactor1 = 1;
        $totalCoachCO2 = 0;
        $CoachRoundTrip = 1;
        $from = 'London, ENG';
        $to = 'New York, US';

        foreach ($formData as $key => $field) {
            $name = $field['name'];
            $val = $field['value'];
            if ($name == 'from') {
                $from = $val;
            }
            if ($name == 'to') {
                $to = $val;
            }
            if ($name == 'type') {
                $CoachType = (int)$val;
            }
            if ($name == 'fuel') {
                $CoachFuel = (int)$val;
            }
            if ($name == 'round_trip') {
                $CoachRoundTrip = (int)$val;
            }
        }

        $CoachDistance = $this->getDistanceByRoad($from, $to, "K");
        $CoachemmisionFactor = floatval($CoachType * 10) + floatval($CoachFuel);

        if ($CoachemmisionFactor == 11) {
            $CoachemmisionFactor1 = 0.12971;
        }
        if ($CoachemmisionFactor == 12) {
            $CoachemmisionFactor1 = 0.0507;
        }
        if ($CoachemmisionFactor == 13) {
            $CoachemmisionFactor1 = 0.1329;
        }
        if ($CoachemmisionFactor == 14) {
            $CoachemmisionFactor1 = 0.1313;
        }
        if ($CoachemmisionFactor == 21) {
            $CoachemmisionFactor1 = 0.0344;
        }
        if ($CoachemmisionFactor == 22) {
            $CoachemmisionFactor1 = 0.0105;
        }
        if ($CoachemmisionFactor == 23) {
            $CoachemmisionFactor1 = 0.0352;
        }
        if ($CoachemmisionFactor == 24) {
            $CoachemmisionFactor1 = 0.0348;
        }

        $totalCoachCO2 = number_format((float) $CoachDistance * $CoachemmisionFactor1 * $CoachRoundTrip, 2, '.', '');
        return $totalCoachCO2;
    }

    public function carCalculation($formData)
    {
        $carType = 0;
        $carFuel = 0;
        $carDistance = 1;
        $caremmisionFactor = 0;
        $caremmisionFactor1 = 0;
        $caremmisionFactor2 = 0;
        $cartravellers = 1;
        $totalcarCO2 = 0;
        $AC = 0;
        $Traffic = 0;
        $CarRoundTrip = 1;
        $from = 'London, ENG';
        $to = 'New York, US';
        foreach ($formData as $key => $field) {
            $name = $field['name'];
            $val = $field['value'];
            if ($name == 'from') {
                $from = $val;
            }
            if ($name == 'to') {
                $to = $val;
            }
            if ($name == 'car_passengers') {
                $cartravellers = (int)$val;
            }
            if ($name == 'car_type') {
                $carType = $val;
            }
            if ($name == 'ac') {
                $AC = $val;
            }
            if ($name == 'fuel') {
                $carFuel = $val;
            }
            if ($name == 'round_trip') {
                $CarRoundTrip = (int)$val;
            }
            if ($name == 'traffic_type') {
                $Traffic = $val;
            }
        }

        $carDistance = $this->getDistanceByRoad($from, $to, "K");
        $caremmisionFactor = floatval($carType * 10) + floatval($carFuel); //parse float so it adds numbers not concatenation

        if ($caremmisionFactor == 11) {
            $caremmisionFactor1 = 0.17589;
        }
        if ($caremmisionFactor == 12) {
            $caremmisionFactor1 = 0.195420;
        }
        if ($caremmisionFactor == 13) {
            $caremmisionFactor1 = 0.132470;
        }
        if ($caremmisionFactor == 14) {
            $caremmisionFactor1 = 0.192480;
        }
        if ($caremmisionFactor == 15) {
            $caremmisionFactor1 = 0.203340;
        }
        if ($caremmisionFactor == 16) {
            $caremmisionFactor1 = 0.043460;
        }
        if ($caremmisionFactor == 17) {
            $caremmisionFactor1 = 0.188480;
        }
        if ($caremmisionFactor == 21) {
            $caremmisionFactor1 = 0.211300;
        }
        if ($caremmisionFactor == 22) {
            $caremmisionFactor1 = 0.244500;
        }
        if ($caremmisionFactor == 23) {
            $caremmisionFactor1 = 0.136590;
        }
        if ($caremmisionFactor == 24) {
            $caremmisionFactor1 = 0.192480;
        }
        if ($caremmisionFactor == 25) {
            $caremmisionFactor1 = 0.203340;
        }
        if ($caremmisionFactor == 26) {
            $caremmisionFactor1 = 0.095430;
        }
        if ($caremmisionFactor == 27) {
            $caremmisionFactor1 = 0.226770;
        }
        if ($caremmisionFactor == 31) {
            $caremmisionFactor1 = 0.259530;
        }
        if ($caremmisionFactor == 32) {
            $caremmisionFactor1 = 0.359890;
        }
        if ($caremmisionFactor == 33) {
            $caremmisionFactor1 = 0.164490;
        }
        if ($caremmisionFactor == 34) {
            $caremmisionFactor1 = 0.282610;
        }
        if ($caremmisionFactor == 35) {
            $caremmisionFactor1 = 0.299330;
        }
        if ($caremmisionFactor == 36) {
            $caremmisionFactor1 = 0.104990;
        }
        if ($caremmisionFactor == 37) {
            $caremmisionFactor1 = 0.289570;
        }
        if ($caremmisionFactor == 41) {
            $caremmisionFactor1 = 0.210240;
        }
        if ($caremmisionFactor == 43) {
            $caremmisionFactor1 = 0.164490;
        }
        if ($caremmisionFactor == 47) {
            $caremmisionFactor1 = 0.210240;
        }
        if ($caremmisionFactor == 51) {
            $caremmisionFactor1 = 0.214710;
        }
        if ($caremmisionFactor == 52) {
            $caremmisionFactor1 = 0.229950;
        }
        if ($caremmisionFactor == 53) {
            $caremmisionFactor1 = 0.143910;
        }
        if ($caremmisionFactor == 54) {
            $caremmisionFactor1 = 0.211880;
        }
        if ($caremmisionFactor == 55) {
            $caremmisionFactor1 = 0.224010;
        }
        if ($caremmisionFactor == 56) {
            $caremmisionFactor1 = 0.096110;
        }
        if ($caremmisionFactor == 57) {
            $caremmisionFactor1 = 0.222330;
        }

        if ($AC == "Yes" && $Traffic == "Heavy traffic") {
            $caremmisionFactor2 = 1.456;
        }
        if ($AC == "Yes" && $Traffic == "Normal traffic") {
            $caremmisionFactor2 = 1.12;
        }
        if ($AC == "No" && $Traffic == "Heavy traffic") {
            $caremmisionFactor2 = 1.3;
        }

        $totalcarCO2 = number_format((float) (($carDistance * $caremmisionFactor1 * $caremmisionFactor2) / $cartravellers) * $CarRoundTrip, 2, '.', '');
        return $totalcarCO2;
    }

    public function trainCalculation($formData)
    {
        $trainType = 0;
        $trainClass = 0;
        $trainDistance = 0;
        $TrainemmisionFactor = 0;
        $totalTrainCO2 = 0;
        $TrainRoundTrip = 1;

        $from = 'London, ENG';
        $to = 'New York, US';
        foreach ($formData as $key => $field) {
            $name = $field['name'];
            $val = $field['value'];
            if ($name == 'from') {
                $from = $val;
            }
            if ($name == 'to') {
                $to = $val;
            }
            if ($name == 'type') {
                $trainType = $val;
            }
            if ($name == 'class') {
                $trainClass = $val;
            }
            if ($name == 'round_trip') {
                $TrainRoundTrip = (int)$val;
            }
        }

        $trainDistance = $this->getDistanceByRoad($from, $to, "K", "&mode=transit&transit_mode=train");

        if ($trainType == 'Transit Rail' && $trainClass == 'Economy') {
            $TrainemmisionFactor = 0.044;
        }
        if ($trainType == 'Transit Rail' && $trainClass == 'Business - Luxury') {
            $TrainemmisionFactor = 0.0704;
        }
        if ($trainType == 'Commuter Rail' && $trainClass == 'Economy') {
            $TrainemmisionFactor = 0.138;
        }
        if ($trainType == 'Commuter Rail' && $trainClass == 'Business - Luxury') {
            $TrainemmisionFactor = 0.2208;
        }
        if ($trainType == 'Inter-city / National Rail' && $trainClass == 'Economy') {
            $TrainemmisionFactor = 0.049;
        }
        if ($trainType == 'Inter-city / National Rail' && $trainClass == 'Business - Luxury') {
            $TrainemmisionFactor = 0.0784;
        }
        if ($trainType == 'High-speed National / International Rail' && $trainClass == 'Economy') {
            $TrainemmisionFactor = 0.021;
        }
        if ($trainType == 'High-speed National / International Rail' && $trainClass == 'Business - Luxury') {
            $TrainemmisionFactor = 0.0336;
        }
        $totalTrainCO2 = number_format((float)($TrainemmisionFactor * $trainDistance * $TrainRoundTrip), 2, '.', '');
        return $totalTrainCO2;
    }

    public function hotelCalculation($formData)
    {
        $roomsNum = 1;
        $stars = 1;
        $overnights = 1;
        $HotelemmisionFactor = 0;
        $totalHotelCO2 = 0;
        $country = 'India';

        $countries_data = [
            ["Country" => "Argentina", "1" => 14.0, "2" => 28.0, "3" => 42.0, "4" => 56.0, "5" => 72.8],
            ["Country" => "Australia", "1" => 10.7, "2" => 21.3, "3" => 32.0, "4" => 42.6, "5" => 55.4],
            ["Country" => "Austria", "1" => 3.5, "2" => 7.0, "3" => 10.4, "4" => 13.9, "5" => 18.1],
            ["Country" => "Belgium", "1" => 2.7, "2" => 5.5, "3" => 8.2, "4" => 10.9, "5" => 14.2],
            ["Country" => "Brazil", "1" => 3.1, "2" => 6.2, "3" => 9.2, "4" => 12.3, "5" => 16.0],
            ["Country" => "Canada", "1" => 4.0, "2" => 8.1, "3" => 12.1, "4" => 16.1, "5" => 20.9],
            ["Country" => "Chile", "1" => 7.6, "2" => 15.3, "3" => 22.9, "4" => 30.5, "5" => 39.7],
            ["Country" => "China", "1" => 15.7, "2" => 31.5, "3" => 47.2, "4" => 62.9, "5" => 81.8],
            ["Country" => "Colombia", "1" => 3.4, "2" => 6.8, "3" => 10.1, "4" => 13.5, "5" => 17.6],
            ["Country" => "Costa Rica", "1" => 1.9, "2" => 3.8, "3" => 5.6, "4" => 7.5, "5" => 9.8],
            ["Country" => "Czech Republic", "1" => 9.1, "2" => 18.1, "3" => 27.2, "4" => 36.2, "5" => 47.1],
            ["Country" => "Egypt", "1" => 14.1, "2" => 28.3, "3" => 42.4, "4" => 56.5, "5" => 73.5],
            ["Country" => "Fiji", "1" => 12.0, "2" => 23.9, "3" => 35.9, "4" => 47.8, "5" => 62.1],
            ["Country" => "France", "1" => 1.6, "2" => 3.3, "3" => 4.9, "4" => 6.5, "5" => 8.5],
            ["Country" => "Germany", "1" => 4.3, "2" => 8.5, "3" => 12.8, "4" => 17.0, "5" => 22.1],
            ["Country" => "Greece", "1" => 10.8, "2" => 21.5, "3" => 32.3, "4" => 43.0, "5" => 55.9],
            ["Country" => "Hong Kong", "1" => 16.5, "2" => 33.0, "3" => 49.4, "4" => 65.9, "5" => 85.7],
            ["Country" => "India", "1" => 18.9, "2" => 37.8, "3" => 56.6, "4" => 75.5, "5" => 98.2],
            ["Country" => "Indonesia", "1" => 22.3, "2" => 44.6, "3" => 66.8, "4" => 89.1, "5" => 115.8],
            ["Country" => "Ireland", "1" => 6.3, "2" => 12.5, "3" => 18.8, "4" => 25.0, "5" => 32.5],
            ["Country" => "Israel", "1" => 13.5, "2" => 27.0, "3" => 40.5, "4" => 54.0, "5" => 70.2],
            ["Country" => "Italy", "1" => 5.1, "2" => 10.1, "3" => 15.2, "4" => 20.2, "5" => 26.3],
            ["Country" => "Japan", "1" => 15.2, "2" => 30.3, "3" => 45.5, "4" => 60.6, "5" => 78.8],
            ["Country" => "Jordan", "1" => 15.6, "2" => 31.2, "3" => 46.8, "4" => 62.4, "5" => 81.1],
            ["Country" => "Korea", "1" => 15.3, "2" => 30.6, "3" => 45.9, "4" => 61.2, "5" => 79.6],
            ["Country" => "Macau", "1" => 18.9, "2" => 37.8, "3" => 56.7, "4" => 75.6, "5" => 98.3],
            ["Country" => "Malaysia", "1" => 20.8, "2" => 41.5, "3" => 62.3, "4" => 83.0, "5" => 107.9],
            ["Country" => "Maldives", "1" => 45.8, "2" => 91.7, "3" => 137.5, "4" => 183.3, "5" => 238.3],
            ["Country" => "Mexico", "1" => 6.5, "2" => 13.0, "3" => 19.4, "4" => 25.9, "5" => 33.7],
            ["Country" => "Netherlands", "1" => 5.2, "2" => 10.5, "3" => 15.7, "4" => 20.9, "5" => 27.2],
            ["Country" => "New Zealand", "1" => 2.6, "2" => 5.2, "3" => 7.8, "4" => 10.4, "5" => 13.5],
            ["Country" => "Oman", "1" => 28.6, "2" => 57.2, "3" => 85.8, "4" => 114.5, "5" => 148.8],
            ["Country" => "Panama", "1" => 5.5, "2" => 11.1, "3" => 16.6, "4" => 22.1, "5" => 28.7],
            ["Country" => "Peru", "1" => 5.6, "2" => 11.3, "3" => 16.9, "4" => 22.5, "5" => 29.3],
            ["Country" => "Philippines", "1" => 11.1, "2" => 22.1, "3" => 33.2, "4" => 44.2, "5" => 57.5],
            ["Country" => "Poland", "1" => 8.3, "2" => 16.6, "3" => 24.9, "4" => 33.2, "5" => 43.2],
            ["Country" => "Portugal", "1" => 6.5, "2" => 13.0, "3" => 19.5, "4" => 26.0, "5" => 33.8],
            ["Country" => "Qatar", "1" => 31.7, "2" => 63.4, "3" => 95.1, "4" => 126.8, "5" => 164.8],
            ["Country" => "Romania", "1" => 6.4, "2" => 12.8, "3" => 19.1, "4" => 25.5, "5" => 33.2],
            ["Country" => "Russian Federation", "1" => 8.0, "2" => 15.9, "3" => 23.9, "4" => 31.8, "5" => 41.3],
            ["Country" => "Saudi Arabia", "1" => 28.6, "2" => 57.3, "3" => 85.9, "4" => 114.5, "5" => 148.9],
            ["Country" => "Singapore", "1" => 9.5, "2" => 18.9, "3" => 28.4, "4" => 37.8, "5" => 49.1],
            ["Country" => "South Africa", "1" => 15.3, "2" => 30.5, "3" => 45.8, "4" => 61.0, "5" => 79.3],
            ["Country" => "Spain", "1" => 4.7, "2" => 9.4, "3" => 14.0, "4" => 18.7, "5" => 24.3],
            ["Country" => "Switzerland", "1" => 1.9, "2" => 3.7, "3" => 5.6, "4" => 7.4, "5" => 9.6],
            ["Country" => "Taiwan", "1" => 19.3, "2" => 38.7, "3" => 58.0, "4" => 77.3, "5" => 100.5],
            ["Country" => "Thailand", "1" => 12.8, "2" => 25.5, "3" => 38.3, "4" => 51.0, "5" => 66.3],
            ["Country" => "Turkey", "1" => 8.4, "2" => 16.8, "3" => 25.2, "4" => 33.6, "5" => 43.7],
            ["Country" => "United Arab Emirates", "1" => 28.6, "2" => 57.2, "3" => 85.8, "4" => 114.4, "5" => 148.7],
            ["Country" => "United Kingdom", "1" => 4.9, "2" => 9.9, "3" => 14.8, "4" => 19.7, "5" => 25.6],
            ["Country" => "United States", "1" => 8.0, "2" => 16.0, "3" => 24.0, "4" => 32.0, "5" => 41.6],
            ["Country" => "Vietnam", "1" => 13.0, "2" => 25.9, "3" => 38.9, "4" => 51.8, "5" => 67.3]
        ];
        $other_country = ["1" => 11.8, "2" => 23.6, "3" => 35.4, "4" => 47.2, "5" => 61.3];

        foreach ($formData as $key => $field) {
            $name = $field['name'];
            $val = $field['value'];
            if ($name == 'country') {
                $country = $val;
            }
            if ($name == 'overnights') {
                $overnights = $val;
            }
            if ($name == 'rooms') {
                $roomsNum = $val;
            }
            if ($name == 'stars') {
                $stars = $val;
            }
        }

        $res = [];
        foreach ($countries_data as $key => $array) {
            if ($country == $array['Country']) {
                $res = $array;
            }
        }
        if ($res) {
            $HotelemmisionFactor = $res[$stars];
        } else {
            $HotelemmisionFactor = $other_country[$stars];
        }

        $totalHotelCO2 = $roomsNum * $overnights * $HotelemmisionFactor;
        return $totalHotelCO2;
    }
}
