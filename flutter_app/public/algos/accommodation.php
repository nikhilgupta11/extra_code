<?php 
// Accommodation

$roomsNum = 1;
$stars = 1;
$overnights = 1;
$HotelemmisionFactor = 0;
$totalHotelCO2 = 0;

$roomsNum = 4;
$stars = 3;
$overnights = 3;

$country = 'India';

$countries_data = [
    ["Country" => "Argentina", "1" => 14.0, "2" => 28.0, "3" => 42.0, "4" => 56.0, "5" => 72.8 ],
    ["Country" => "Australia", "1" => 10.7, "2" => 21.3, "3" => 32.0, "4" => 42.6, "5" => 55.4 ],
    ["Country" => "Austria", "1" => 3.5, "2" => 7.0, "3" => 10.4, "4" => 13.9, "5" => 18.1 ],
    ["Country" => "Belgium", "1" => 2.7, "2" => 5.5, "3" => 8.2, "4" => 10.9, "5" => 14.2 ],
    ["Country" => "Brazil", "1" => 3.1, "2" => 6.2, "3" => 9.2, "4" => 12.3, "5" => 16.0 ],
    ["Country" => "Canada", "1" => 4.0, "2" => 8.1, "3" => 12.1, "4" => 16.1, "5" => 20.9 ],
    ["Country" => "Chile", "1" => 7.6, "2" => 15.3, "3" => 22.9, "4" => 30.5, "5" => 39.7 ],
    ["Country" => "China", "1" => 15.7, "2" => 31.5, "3" => 47.2, "4" => 62.9, "5" => 81.8 ],
    ["Country" => "Colombia", "1" => 3.4, "2" => 6.8, "3" => 10.1, "4" => 13.5, "5" => 17.6 ],
    ["Country" => "Costa Rica", "1" => 1.9, "2" => 3.8, "3" => 5.6, "4" => 7.5, "5" => 9.8 ],
    ["Country" => "Czech Republic", "1" => 9.1, "2" => 18.1, "3" => 27.2, "4" => 36.2, "5" => 47.1 ],
    ["Country" => "Egypt", "1" => 14.1, "2" => 28.3, "3" => 42.4, "4" => 56.5, "5" => 73.5 ],
    ["Country" => "Fiji", "1" => 12.0, "2" => 23.9, "3" => 35.9, "4" => 47.8, "5" => 62.1 ],
    ["Country" => "France", "1" => 1.6, "2" => 3.3, "3" => 4.9, "4" => 6.5, "5" => 8.5 ],
    ["Country" => "Germany", "1" => 4.3, "2" => 8.5, "3" => 12.8, "4" => 17.0, "5" => 22.1 ],
    ["Country" => "Greece", "1" => 10.8, "2" => 21.5, "3" => 32.3, "4" => 43.0, "5" => 55.9 ],
    ["Country" => "Hong Kong", "1" => 16.5, "2" => 33.0, "3" => 49.4, "4" => 65.9, "5" => 85.7 ],
    ["Country" => "India", "1" => 18.9, "2" => 37.8, "3" => 56.6, "4" => 75.5, "5" => 98.2 ],
    ["Country" => "Indonesia", "1" => 22.3, "2" => 44.6, "3" => 66.8, "4" => 89.1, "5" => 115.8 ],
    ["Country" => "Ireland", "1" => 6.3, "2" => 12.5, "3" => 18.8, "4" => 25.0, "5" => 32.5 ],
    ["Country" => "Israel", "1" => 13.5, "2" => 27.0, "3" => 40.5, "4" => 54.0, "5" => 70.2 ],
    ["Country" => "Italy", "1" => 5.1, "2" => 10.1, "3" => 15.2, "4" => 20.2, "5" => 26.3 ],
    ["Country" => "Japan", "1" => 15.2, "2" => 30.3, "3" => 45.5, "4" => 60.6, "5" => 78.8 ],
    ["Country" => "Jordan", "1" => 15.6, "2" => 31.2, "3" => 46.8, "4" => 62.4, "5" => 81.1 ],
    ["Country" => "Korea", "1" => 15.3, "2" => 30.6, "3" => 45.9, "4" => 61.2, "5" => 79.6 ],
    ["Country" => "Macau", "1" => 18.9, "2" => 37.8, "3" => 56.7, "4" => 75.6, "5" => 98.3 ],
    ["Country" => "Malaysia", "1" => 20.8, "2" => 41.5, "3" => 62.3, "4" => 83.0, "5" => 107.9 ],
    ["Country" => "Maldives", "1" => 45.8, "2" => 91.7, "3" => 137.5, "4" => 183.3, "5" => 238.3 ],
    ["Country" => "Mexico", "1" => 6.5, "2" => 13.0, "3" => 19.4, "4" => 25.9, "5" => 33.7 ],
    ["Country" => "Netherlands", "1" => 5.2, "2" => 10.5, "3" => 15.7, "4" => 20.9, "5" => 27.2 ],
    ["Country" => "New Zealand", "1" => 2.6, "2" => 5.2, "3" => 7.8, "4" => 10.4, "5" => 13.5 ],
    ["Country" => "Oman", "1" => 28.6, "2" => 57.2, "3" => 85.8, "4" => 114.5, "5" => 148.8 ],
    ["Country" => "Panama", "1" => 5.5, "2" => 11.1, "3" => 16.6, "4" => 22.1, "5" => 28.7 ],
    ["Country" => "Peru", "1" => 5.6, "2" => 11.3, "3" => 16.9, "4" => 22.5, "5" => 29.3 ],
    ["Country" => "Philippines", "1" => 11.1, "2" => 22.1, "3" => 33.2, "4" => 44.2, "5" => 57.5 ],
    ["Country" => "Poland", "1" => 8.3, "2" => 16.6, "3" => 24.9, "4" => 33.2, "5" => 43.2 ],
    ["Country" => "Portugal", "1" => 6.5, "2" => 13.0, "3" => 19.5, "4" => 26.0, "5" => 33.8 ],
    ["Country" => "Qatar", "1" => 31.7, "2" => 63.4, "3" => 95.1, "4" => 126.8, "5" => 164.8 ],
    ["Country" => "Romania", "1" => 6.4, "2" => 12.8, "3" => 19.1, "4" => 25.5, "5" => 33.2 ],
    ["Country" => "Russian Federation", "1" => 8.0, "2" => 15.9, "3" => 23.9, "4" => 31.8, "5" => 41.3 ],
    ["Country" => "Saudi Arabia", "1" => 28.6, "2" => 57.3, "3" => 85.9, "4" => 114.5, "5" => 148.9 ],
    ["Country" => "Singapore", "1" => 9.5, "2" => 18.9, "3" => 28.4, "4" => 37.8, "5" => 49.1 ],
    ["Country" => "South Africa", "1" => 15.3, "2" => 30.5, "3" => 45.8, "4" => 61.0, "5" => 79.3 ],
    ["Country" => "Spain", "1" => 4.7, "2" => 9.4, "3" => 14.0, "4" => 18.7, "5" => 24.3 ],
    ["Country" => "Switzerland", "1" => 1.9, "2" => 3.7, "3" => 5.6, "4" => 7.4, "5" => 9.6 ],
    ["Country" => "Taiwan", "1" => 19.3, "2" => 38.7, "3" => 58.0, "4" => 77.3, "5" => 100.5 ],
    ["Country" => "Thailand", "1" => 12.8, "2" => 25.5, "3" => 38.3, "4" => 51.0, "5" => 66.3 ],
    ["Country" => "Turkey", "1" => 8.4, "2" => 16.8, "3" => 25.2, "4" => 33.6, "5" => 43.7 ],
    ["Country" => "United Arab Emirates", "1" => 28.6, "2" => 57.2, "3" => 85.8, "4" => 114.4, "5" => 148.7 ],
    ["Country" => "United Kingdom", "1" => 4.9, "2" => 9.9, "3" => 14.8, "4" => 19.7, "5" => 25.6 ],
    ["Country" => "United States", "1" => 8.0, "2" => 16.0, "3" => 24.0, "4" => 32.0, "5" => 41.6 ],
    ["Country" => "Vietnam", "1" => 13.0, "2" => 25.9, "3" => 38.9, "4" => 51.8, "5" => 67.3]
];

$other_country = ["1" => 11.8, "2" => 23.6, "3" => 35.4, "4" => 47.2, "5" => 61.3 ];
$res = [];
foreach ($countries_data as $key => $array) {
    if($country == $array['Country']){
        $res = $array;
    }
}
if ($res) {
    $HotelemmisionFactor = $res[$stars];
} else {
    $HotelemmisionFactor = $other_country[$stars];
}

$totalHotelCO2 = $roomsNum * $overnights * $HotelemmisionFactor;

// echo $totalHotelCO2;


// echo "<b>roomsNum : </b>" .$roomsNum."<br><br>";
// echo "<b>stars : </b>" .$stars."<br><br>";
// echo "<b>overnights : </b>" .$overnights."<br><br>";
// echo "<b>country : </b>" .$country."<br><br>";
// echo "<b>HotelemmisionFactor : </b>" .$HotelemmisionFactor."<br><br>";
// echo "totalHotelCO2 = roomsNum * overnights * HotelemmisionFactor <br><br>";
// echo "totalHotelCO2 = $roomsNum * $overnights * $HotelemmisionFactor <br><br>";
// echo "totalHotelCO2 : " .$totalHotelCO2;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
</head>

<body class="container">
    <div class="row">
        <div class="col-md-4 mt-3">
            <table class="table table-bordered">
                <tbody>
                    <tr class="bg-light text-center">
                        <th colspan="2">Data</th>
                    </tr>
                    <tr>
                        <th>roomsNum</th>
                        <td><?= $roomsNum ?></td>
                    </tr>
                    <tr>
                        <th>stars</th>
                        <td><?= $stars ?></td>
                    </tr>
                    <tr>
                        <th>overnights</th>
                        <td><?= $overnights ?></td>
                    </tr>
                    <tr>
                        <th>country</th>
                        <td><?= $country ?></td>
                    </tr>
                    <tr>
                        <th>HotelemmisionFactor</th>
                        <td><?= $HotelemmisionFactor ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 mt-3">
            <div class="mb-4">
                <h4>Conditions</h4>
                <div class="d-flex">
                    <div class="col-4">
                        <p class="mb-0">Default Star Data</p>   
                        <?php foreach ($other_country as $key => $value) {
                            echo "<p class='mb-0'> Star : $key = value : $value</p>";
                        }?>
                    </div>
                    <div class="col">
                        <?php foreach ($res as $key => $value) {
                            if($key == "Country"){
                                echo "<p class='mb-0'> $key = $value</p>";
                            }else{
                                echo "<p class='mb-0'> Star : $key = value : $value</p>";
                            }
                        }?>
                    </div>
                </div>
                <p class="mt-3">(country NotEmpty) HotelemmisionFactor = <?= $HotelemmisionFactor ?></p>
                <p class="mt-3">(country Empty) HotelemmisionFactor = DefaultStars[<?=$stars?>]</p>
            </div>
            <div class="mb-4">
                <h4>Formula</h4>
                <p>roomsNum * overnights * HotelemmisionFactor</p>
                <p><?= $roomsNum ?> * <?= $overnights ?> * <?= $HotelemmisionFactor ?></p>
            </div>
            <div class="mb-4">
                <h4>totalHotelCO2 : <?= $totalHotelCO2 ?></h4>
            </div>

        </div>
    </div>
</body>

</html>