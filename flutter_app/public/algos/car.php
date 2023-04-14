<?php
// Car
include('functions.php');
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

$carType = 2;
$carFuel = 30;
$AC = "Yes";
$Traffic = "Heavy traffic";
$carDistance = 600;

$caremmisionFactor = floatval($carType * 10) + floatval($carFuel); //parse float so it adds numbers not concatenation
if ($caremmisionFactor == 11) {
    $caremmisionFactor1 = 0.17589;
}
if ($caremmisionFactor == 12) {
    $caremmisionFactor1 = 0.19542;
}
if ($caremmisionFactor == 13) {
    $caremmisionFactor1 = 0.13247;
}
if ($caremmisionFactor == 50) {
    $caremmisionFactor1 = 0.13247;
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

// echo "<b>carType : </b>" .$carType."<br><br>";
// echo "<b>carFuel : </b>" .$carFuel."<br><br>";
// echo "<b>cartravellers : </b>" .$cartravellers."<br><br>";
// echo "<b>carDistance : </b>" .$carDistance."<br><br>";
// echo "<b>Traffic : </b>" .$Traffic."<br><br>";
// echo "<b>CarRoundTrip : </b>" .$CarRoundTrip."<br><br>";
// echo "<b>caremmisionFactor : </b>" .$caremmisionFactor."<br><br>";
// echo "<b>caremmisionFactor1 : </b>" .$caremmisionFactor1."<br><br>";
// echo "<b>caremmisionFactor2 : </b>" .$caremmisionFactor2."<br><br>";
// echo "<b>AC : </b>" .$AC."<br><br>";
// echo "TotalcarCO2 = ((carDistance * caremmisionFactor1 * caremmisionFactor2) / cartravellers) * CarRoundTrip <br><br>";
// echo "TotalcarCO2 = (($carDistance * $caremmisionFactor1 * $caremmisionFactor2) / $cartravellers) * $CarRoundTrip <br><br>";
// echo "TotalCarCO2 : " .$totalcarCO2;

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
                        <th>AC</th>
                        <td><?= $AC ?></td>
                    </tr>
                    <tr>
                        <th>carType</th>
                        <td><?= $carType ?></td>
                    </tr>
                    <tr>
                        <th>carFuel</th>
                        <td><?= $carFuel ?></td>
                    </tr>
                    <tr>
                        <th>cartravellers</th>
                        <td><?= $cartravellers ?></td>
                    </tr>
                    <tr>
                        <th>carDistance</th>
                        <td><?= $carDistance ?></td>
                    </tr>
                    <tr>
                        <th>Traffic</th>
                        <td><?= $Traffic ?></td>
                    </tr>
                    <tr>
                        <th>CarRoundTrip</th>
                        <td><?= $CarRoundTrip ?></td>
                    </tr>
                    <tr>
                        <th>caremmisionFactor</th>
                        <td><?= $caremmisionFactor ?></td>
                    </tr>
                    <tr>
                        <th>caremmisionFactor1</th>
                        <td><?= $caremmisionFactor1 ?></td>
                    </tr>
                    <tr>
                        <th>caremmisionFactor2</th>
                        <td><?= $caremmisionFactor2 ?></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-8 mt-3">
            <div class="mb-4">
                <h4>Conditions</h4>
                <p class="m-0">(caremmisionFactor == 11) caremmisionFactor1 = 0.17589</p>
                <p class="m-0">(caremmisionFactor == 12) caremmisionFactor1 = 0.19542</p>
                <p class="m-0">(caremmisionFactor == 13) caremmisionFactor1 = 0.13247</p>
                <p class="m-0">(caremmisionFactor == 50) caremmisionFactor1 = 0.13247</p>

                <p class="mt-2 mb-0">(AC == Yes & Traffic == Heavy traffic) caremmisionFactor2 = 1.456</p>
                <p class="m-0">(AC == Yes & Traffic == Normal traffic) caremmisionFactor2 = 1.12</p>
                <p class="m-0">(AC == No & Traffic == Heavy traffic) caremmisionFactor2 = 1.3</p>
            </div>
            <div class="mb-4">
                <h4>Formula</h4>
                <p style="font-size: 15px;">TotalcarCO2 = ((carDistance * caremmisionFactor1 * caremmisionFactor2) / cartravellers) * CarRoundTrip</p>
                <p>TotalcarCO2 = ((<?=$carDistance?> * <?=$caremmisionFactor1?> * <?=$caremmisionFactor2?>) / <?=$cartravellers?>) * <?=$CarRoundTrip?></p>
            </div>
            <div class="mb-4">
                <h4>TotalCarCO2 : <?= $totalcarCO2 ?></h4>
            </div>

        </div>
    </div>
</body>

</html>