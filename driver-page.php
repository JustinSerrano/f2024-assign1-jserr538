<?php

// Required to connect to the f1 database
include "f2024-assign1\config.inc.php";
include "f2024-assign1\db-classes.inc.php";

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
            echo $row . ['driverRef'];
        }
        ?>

    </main>

</body>