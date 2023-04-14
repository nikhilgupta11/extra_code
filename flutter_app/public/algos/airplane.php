<?php
// Airplane

$journey_type = 1;
$total_km = 0;
$airclass = 'Economy';
$airclassFactor = 1;
$emmisionFactor = 0;
$totalAirCO2 = 0;
$airoundTrip = 1;

$total_km = 500;
$total_km = $total_km + 90;
$airoundTrip = 1000;
$airclass = 'Economy';

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

// echo $totalAirCO2;

// echo "<b>total_km : </b>" .$total_km."<br><br>";
// echo "<b>airoundTrip : </b>" .$airoundTrip."<br><br>";
// echo "<b>airclass : </b>" .$airclass."<br><br>";
// echo "<b>journey_type : </b>" .$journey_type."<br><br>";
// echo "<b>airclassFactor : </b>" .$airclassFactor."<br><br>";
// echo "<b>emmisionFactor : </b>" .$emmisionFactor."<br><br>";
// echo "totalAirCO2 = total_km * emmisionFactor * airclassFactor * airoundTrip <br><br>";
// echo "totalAirCO2 = $total_km * $emmisionFactor * $airclassFactor * $airoundTrip <br><br>";
// echo "totalAirCO2 : " .$totalAirCO2;
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
        <div class="col-md-6 mt-3">
            <table class="table table-bordered">
                <tbody>
                    <tr class="bg-light text-center">
                        <th colspan="2">Data</th>
                    </tr>
                    <tr>
                        <th>total_km</th>
                        <td><?= $total_km ?></td>
                    </tr>
                    <tr>
                        <th>airoundTrip</th>
                        <td><?= $airoundTrip ?></td>
                    </tr>
                    <tr>
                        <th>airclass</th>
                        <td><?= $airclass ?></td>
                    </tr>
                    <tr>
                        <th>journey_type</th>
                        <td><?= $journey_type ?></td>
                    </tr>
                    <tr>
                        <th>airclassFactor</th>
                        <td><?= $airclassFactor ?></td>
                    </tr>
                    <tr>
                        <th>emmisionFactor</th>
                        <td><?= $emmisionFactor ?></td>
                    </tr>
                </tbody>
            </table>

            <div class="mb-4">
                <h4>Formula</h4>
                <p>total_km * emmisionFactor * airclassFactor * airoundTrip</p>
                <p><?= $total_km ?> * <?= $emmisionFactor ?> * <?= $airclassFactor ?> * <?= $airoundTrip ?></p>
            </div>
            <div class="mb-4">
                <h4>totalAirCO2 : <?= $totalAirCO2 ?></h4>
            </div>
        </div>
        <div class="col-md-6 mt-3">
            <div class="mb-4">
                <h4>Conditions</h4>
                <div class="mb-3">
                    <p class="m-0">(total_km > 500 & total_km < 3700) journey_type=2</p>
                    <p class="m-0">(total_km >= 3700) journey_type = 3</p>
                </div>
                <div class="mb-3">
                    <p class="m-0">(journey_type == 3)</p>
                    <ul class="mb-0">
                        <li>(airclass == 'Economy')
                            <ul>
                                <li>airclassFactor = 1</li>
                            </ul>
                        </li>
                        <li>(airclass == 'Business Economy')
                            <ul>
                                <li>airclassFactor = 1.6</li>
                            </ul>
                        </li>
                        <li>
                            (airclass == 'Business')
                            <ul>
                                <li>airclassFactor = 2.9</li>
                            </ul>
                        </li>
                        <li>
                            (airclass == 'First')
                            <ul>
                                <li>airclassFactor = 4</li>
                            </ul>
                        </li>
                    </ul>
                    <p class="m-0">(Else journey_type)</p>
                    <ul class="mb-0">
                        <li>(airclass == 'Economy' OR airclass == 'Premium Economy')
                            <ul>
                                <li>airclassFactor = 1</li>
                            </ul>
                        </li>
                        <li>(Else airclass)
                            <ul>
                                <li>airclassFactor = 1.5</li>
                            </ul>
                        </li>
                    </ul>
                </div>
                <div class="mb-3">
                    <p class="m-0">(journey_type == 3) emmisionFactor = 0.1662</p>
                    <p class="m-0">(journey_type == 2) emmisionFactor = 0.1728</p>
                    <p class="m-0">(else journey_type) emmisionFactor = 0.2828</p>
                </div>
            </div>

        </div>
    </div>
</body>

</html>