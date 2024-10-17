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

    // Retrieve driver details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $driverGateway = new DriverDB($conn);
        $driver = $driverGateway->getDriverInfo($_GET['ref']);
        $raceResults = $driverGateway->getDriverRaceResults($_GET['ref']);
    } else {
        $driver = null;
        $raceResults = null;
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
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <header>
        <h1>F1 Dashboard Project</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="browse-page.php">Browse</a>
            <a href="api-page.php">APIs</a>
        </nav>
    </header>
    <main>
        <section id="driver-details">
            <?php
            if ($driver) {
                // Output the driver information
                echo "<h2>Driver Information</h2>";
                echo "<p><strong>Name: </strong>" . htmlspecialchars($driver['forename'] . " " . $driver['surname']) . "</p>";
                echo "<p><strong>Date of Birth: </strong>" . htmlspecialchars($driver['dob']) . "</p>";
                echo "<p><strong>Nationality: </strong>" . htmlspecialchars($driver['nationality']) . "</p>";
                echo "<p><strong>Profile URL: </strong><a href='" . htmlspecialchars($driver['url']) . "'>" . htmlspecialchars($driver['url']) . "</a></p>";
            } else {
                echo "<p>No driver information available. Please search using the driver reference.</p>";
            }
            ?>
        </section>

        <section id="race-results">
            <?php
            if ($raceResults) {
                echo "<h2>Race Results</h2>";
                echo "<table border='1'>
                        <tr>
                            <th>Rnd</th>
                            <th>Circuit</th>
                            <th>Pos</th>
                            <th>Points</th>
                        </tr>";
                foreach ($raceResults as $result) {
                    echo "<tr>
                            <td>" . htmlspecialchars($result['round']) . "</td>
                            <td>" . htmlspecialchars($result['name']) . "</td>
                            <td>" . htmlspecialchars($result['position']) . "</td>
                            <td>" . htmlspecialchars($result['points']) . "</td>
                        </tr>";
                }
                echo "</table>";
            } else {
                echo "<p>No race results found for the driver.</p>";
            }
            ?>
        </section>
    </main>

</body>