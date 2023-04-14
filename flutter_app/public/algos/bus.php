<?php
// Bus

$CoachType = 0;
$CoachFuel = 0;
$CoachDistance = 0;
$CoachemmisionFactor = 1;
$CoachemmisionFactor1 = 1;
$totalCoachCO2 = 0;
$CoachRoundTrip = 1;

$CoachType = '10'; //input_6_5
$CoachFuel = '10'; //input_6_15
$input_6_13 = '15'; //input_6_13

$CoachDistance = floatval($input_6_13);
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

// $CoachRoundTrip = ''; // input_6_18

$totalCoachCO2 = number_format((float) $CoachDistance * $CoachemmisionFactor1 * $CoachRoundTrip,2,'.','');

// echo $totalCoachCO2;

// echo "<b>CoachType : </b>" .$CoachType."<br><br>";
// echo "<b>CoachFuel : </b>" .$CoachFuel."<br><br>";
// echo "<b>CoachDistance : </b>" .$CoachDistance."<br><br>";
// echo "<b>CoachemmisionFactor : </b>" .$CoachemmisionFactor."<br><br>";
// echo "<b>CoachemmisionFactor1 : </b>" .$CoachemmisionFactor1."<br><br>";
// echo "<b>CoachRoundTrip : </b>" .$CoachRoundTrip."<br><br>";

// echo "totalCoachCO2 = CoachDistance * CoachemmisionFactor1 * CoachRoundTrip <br><br>";
// echo "totalCoachCO2 = $CoachDistance * $CoachemmisionFactor1 * $CoachRoundTrip <br><br>";
// echo "totalCoachCO2 : " .$totalCoachCO2;
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
                        <th>CoachType</th>
                        <td><?= $CoachType ?></td>
                    </tr>
                    <tr>
                        <th>CoachFuel</th>
                        <td><?= $CoachFuel ?></td>
                    </tr>
                    <tr>
                        <th>CoachDistance</th>
                        <td><?= $CoachDistance ?></td>
                    </tr>
                    <tr>
                        <th>CoachemmisionFactor</th>
                        <td><?= $CoachemmisionFactor ?></td>
                    </tr>
                    <tr>
                        <th>CoachemmisionFactor1</th>
                        <td><?= $CoachemmisionFactor1 ?></td>
                    </tr>
                    <tr>
                        <th>CoachRoundTrip</th>
                        <td><?= $CoachRoundTrip ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 mt-3">
            <div class="mb-4">
                <h4>Conditions</h4>
                <p class="m-0">CoachemmisionFactor = CoachType * 10 + CoachFuel</p>
                <p class="mt-2 mb-0">(CoachemmisionFactor == 11) CoachemmisionFactor1 = 0.12971</p>
                <p class="m-0">(CoachemmisionFactor == 12) = CoachemmisionFactor1 = 0.0507</p>
                <p class="m-0">(CoachemmisionFactor == 13) = CoachemmisionFactor1 = 0.1329</p>
                <p class="m-0">(CoachemmisionFactor == 14) = CoachemmisionFactor1 = 0.1313</p>
                <p class="m-0">(CoachemmisionFactor == 21) = CoachemmisionFactor1 = 0.0344</p>
                <p class="m-0">(CoachemmisionFactor == 22) = CoachemmisionFactor1 = 0.0105</p>
                <p class="m-0">(CoachemmisionFactor == 23) = CoachemmisionFactor1 = 0.0352</p>
                <p class="m-0">(CoachemmisionFactor == 24) = CoachemmisionFactor1 = 0.0348</p>
                
            </div>
            <div class="mb-4">
                <h4>Formula</h4>
                <p>CoachDistance * CoachemmisionFactor1 * CoachRoundTrip</p>
                <p><?= $CoachDistance ?> * <?= $CoachemmisionFactor1 ?> * <?= $CoachRoundTrip ?></p>
            </div>
            <div class="mb-4">
                <h4>totalCoachCO2 : <?= $totalCoachCO2 ?></h4>
            </div>

        </div>
    </div>
</body>

</html>