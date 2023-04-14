<?php
// Train

$trainType = 0;
$trainClass = 0;
$trainDistance = 0;
$TrainemmisionFactor = 0;
$totalTrainCO2 = 0;
$TrainRoundTrip = 1;

$trainType = 'Transit Rail';
$trainClass = 'Economy';
$trainDistance = 1200;
$TrainRoundTrip = 2400;

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

// echo $totalTrainCO2;

// echo "<b>trainType : </b>" .$trainType."<br><br>";
// echo "<b>trainClass : </b>" .$trainClass."<br><br>";
// echo "<b>trainDistance : </b>" .$trainDistance."<br><br>";
// echo "<b>TrainemmisionFactor : </b>" .$TrainemmisionFactor."<br><br>";
// echo "<b>TrainRoundTrip : </b>" .$TrainRoundTrip."<br><br>";

// echo "totalTrainCO2 = TrainemmisionFactor * trainDistance * TrainRoundTrip <br><br>";
// echo "totalTrainCO2 = $TrainemmisionFactor * $trainDistance * $TrainRoundTrip <br><br>";
// echo "totalTrainCO2 : " .$totalTrainCO2;
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
                        <th>trainType</th>
                        <td><?= $trainType ?></td>
                    </tr>
                    <tr>
                        <th>trainClass</th>
                        <td><?= $trainClass ?></td>
                    </tr>
                    <tr>
                        <th>trainDistance</th>
                        <td><?= $trainDistance ?></td>
                    </tr>
                    <tr>
                        <th>TrainemmisionFactor</th>
                        <td><?= $TrainemmisionFactor ?></td>
                    </tr>
                    <tr>
                        <th>TrainRoundTrip</th>
                        <td><?= $TrainRoundTrip ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-4">
                <h4>Formula</h4>
                <p style="font-size: 14px;">TrainemmisionFactor * trainDistance * TrainRoundTrip</p>
                <p><?= $TrainemmisionFactor ?> * <?= $trainDistance ?> * <?= $TrainRoundTrip ?></p>
            </div>
            <div class="mb-4">
                <h4>totalTrainCO2 : <?= $totalTrainCO2 ?></h4>
            </div>

        </div>
        <div class="col-md-8 mt-3">
            <div class="mb-4">
                <h4>Conditions</h4>
                <p class="mb-2">(trainType == 'Transit Rail' & trainClass == 'Economy')
                    <br>TrainemmisionFactor = 0.044
                </p>
                <p class="mb-2">(trainType == 'Transit Rail' & trainClass == 'Business - Luxury')
                    <br>TrainemmisionFactor = 0.0704
                </p>
                <p class="mb-2">(trainType == 'Commuter Rail' & trainClass == 'Economy')
                    <br>TrainemmisionFactor = 0.138
                </p>
                <p class="mb-2">(trainType == 'Commuter Rail' & trainClass == 'Business - Luxury')
                    <br>TrainemmisionFactor = 0.2208
                </p>
                <p class="mb-2">(trainType == 'Inter-city / National Rail' & trainClass == 'Economy')
                    <br>TrainemmisionFactor = 0.049
                </p>
                <p class="mb-2">(trainType == 'Inter-city / National Rail' & trainClass == 'Business - Luxury')
                    <br>TrainemmisionFactor = 0.0784
                </p>
                <p class="mb-2">(trainType == 'High-speed National / International Rail' & trainClass == 'Economy')
                    <br>TrainemmisionFactor = 0.021
                </p>
                <p class="mb-2">(trainType == 'High-speed National / International Rail' & trainClass == 'Business - Luxury')
                    <br>TrainemmisionFactor = 0.0336
                </p>
            </div>
            
        </div>
    </div>
</body>

</html>