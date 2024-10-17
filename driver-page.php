<?php

// Required to connect to the f1 database
require_once "config.inc.php";
require_once "db-classes.inc.php";

try {
    $conn = DatabaseHelper::createConnection(array(
        DBCONNSTRING,
        DBUSER,
        DBPASS
    ));

    // Retrieve drivers
    $driverGateway = new DriverDB($conn);
    $drivers = $driverGateway->getAll();
} catch (Exception $e) {
    die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Page</title>
    <!-- <link rel="stylesheet" href="style.css" type="text/css"> -->
</head>

<body>
    <header>

    </header>
    <main>
        <?php
        foreach ($drivers as $row) {
            echo "<p>".$row['driverRef']."</p>";
        }
        ?>

    </main>

</body>