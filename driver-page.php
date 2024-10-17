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

    // now retrieve  paintings ... either all or a subset based on querystring
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $driverGateway = new DriverDB($conn);
        $driver = $driverGateway->getDriverInfo($_GET['ref']);
    } else {
        $driver = null;
    }
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
        <form method="get" action="<?= $_SERVER['REQUEST_URI'] ?>">
            <label for="ref">Driver Reference: </label>
            <input type="text" name="ref" id="ref">
            <button type="submit">Search</button>
        </form>
        <?php
        if ($driver) {
            // Output the driver information
            echo "<h2>Driver Information</h2>";
            echo "<p><strong>Driver Ref: </strong>" . htmlspecialchars($driver['driverRef']) . "</p>";
            echo "<p><strong>Name: </strong>" . htmlspecialchars($driver['forename'] . " " . $driver['surname']) . "</p>";
            echo "<p><strong>Number: </strong>" . htmlspecialchars($driver['number']) . "</p>";
            echo "<p><strong>Code: </strong>" . htmlspecialchars($driver['code']) . "</p>";
            echo "<p><strong>Date of Birth: </strong>" . htmlspecialchars($driver['dob']) . "</p>";
            echo "<p><strong>Nationality: </strong>" . htmlspecialchars($driver['nationality']) . "</p>";
            echo "<p><strong>Profile URL: </strong><a href='" . htmlspecialchars($driver['url']) . "'>" . htmlspecialchars($driver['url']) . "</a></p>";
        } else {
            echo "<p>No driver information available. Please search using the driver reference.</p>";
        }
        ?>
    </main>

</body>