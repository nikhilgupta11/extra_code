<?php
// Boat
$passengerType = "Foot passenger";
$BoatType = "Small Passenger Ferry";
$timeTravel = 1;
$emmisionBFactor = 1;
$totalBoatCO2 = 1;
$BoatroundTrip = 1;

$passengerType = 'Car passenger';
$BoatType = 'Cruiseferry';
$timeTravel = 10;

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

$BoatroundTrip = 5;

$totalBoatCO2 = number_format((float)($emmisionBFactor * $timeTravel * $BoatroundTrip), 2, '.', '');
// echo $totalBoatCO2;

// echo "<b>passengerType : </b>" .$passengerType."<br><br>";
// echo "<b>BoatType : </b>" .$BoatType."<br><br>";
// echo "<b>timeTravel : </b>" .$timeTravel."<br><br>";
// echo "<b>emmisionBFactor : </b>" .$emmisionBFactor."<br><br>";
// echo "<b>BoatroundTrip : </b>" .$BoatroundTrip."<br><br>";
// echo "totalBoatCO2 = emmisionBFactor * timeTravel * BoatroundTrip <br><br>";
// echo "totalBoatCO2 = $emmisionBFactor * $timeTravel * $BoatroundTrip <br><br>";
// echo "totalBoatCO2 : " .$totalBoatCO2;
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
                        <th>passengerType</th>
                        <td><?= $passengerType ?></td>
                    </tr>
                    <tr>
                        <th>BoatType</th>
                        <td><?= $BoatType ?></td>
                    </tr>
                    <tr>
                        <th>timeTravel</th>
                        <td><?= $timeTravel ?></td>
                    </tr>
                    <tr>
                        <th>emmisionBFactor</th>
                        <td><?= $emmisionBFactor ?></td>
                    </tr>
                    <tr>
                        <th>BoatroundTrip</th>
                        <td><?= $BoatroundTrip ?></td>
                    </tr>
                </tbody>
            </table>
            <div class="mb-4">
                <h4>Formula</h4>
                <p>emmisionBFactor * timeTravel * BoatroundTrip</p>
                <p><?= $emmisionBFactor ?> * <?= $timeTravel ?> * <?= $BoatroundTrip ?> </p>

            </div>
            <div class="mb-4">
                <h4>totalBoatCO2 : <?= $totalBoatCO2 ?></h4>
            </div>

        </div>
        <div class="col-md-8 mt-3">
            <div class="mb-4">
                <h4>Conditions</h4>
                <p class="mb-2">(passengerType == "Foot passenger" & BoatType == "Small Passenger Ferry")
                    <br> emmisionBFactor = 6.878
                </p>
                <p class="mb-2">(passengerType == "Foot passenger" & BoatType == "Cruiseferry")
                    <br> emmisionBFactor = 0.805
                </p>
                <p class="mb-2">(passengerType == "Foot passenger" & BoatType == "High speed ferry")
                    <br> emmisionBFactor = 8.677
                </p>
                <p class="mb-2">(passengerType == "Car passenger" & BoatType == "Small Passenger Ferry")
                    <br> emmisionBFactor = 39.06704
                </p>
                <p class="mb-2">(passengerType == "Car passenger" & BoatType == "Cruiseferry")
                    <br> emmisionBFactor = 4.5724
                </p>
                <p class="mb-2">(passengerType == "Car passenger" & BoatType == "High speed ferry")
                    <br> emmisionBFactor = 49.28536
                </p>
            </div>
        </div>
    </div>
</body>

</html>