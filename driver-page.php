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

    // Retrieve all drivers
    $driverGateway = new DriverDB($conn);
    $allDrivers = $driverGateway->getAll();

    // Retrieve driver details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
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
        <!-- Driver details and race results layout -->
        <div class="content-container">
            <!-- Driver details container -->
            <section class="sidebar-details">
                <?php
                if ($driver) {
                    // Grab element values and set them in variables
                    $fullname = htmlspecialchars($driver['fullname']);
                    $nationality = htmlspecialchars($driver['nationality']);
                    $url = htmlspecialchars($driver['url']);

                    $dateObject = new DateTime($driver['dob']);
                    $formattedDate = date_format($dateObject, "F j, Y");
                    $dob = htmlspecialchars($formattedDate);

                    // Output the driver information
                    echo "<h2>Driver Information</h2>
                            <p><strong>Name: </strong>$fullname</p>
                            <p><strong>Date of Birth: </strong>$dob</p>
                            <p><strong>Nationality: </strong>$nationality</p>
                            <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>";
                } else {
                    echo "<h3>No driver information available. Please search using the driver reference.</h3>";
                }
                ?>
            </section>

            <!-- Race results container -->
            <section class="race-results">
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
                        // Grab element values and set them in variables
                        $round = htmlspecialchars($result['round']);
                        $name = htmlspecialchars($result['name']);
                        $position = htmlspecialchars($result['position']);
                        $points = htmlspecialchars($result['points']);
                        
                        // Output race results
                        echo "<tr>
                            <td>$round</td>
                            <td>$name</td>
                            <td>$position</td>
                            <td>$points</td>
                        </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<h3>No race results found for the driver.</h3>";
                }
                ?>
            </section>
        </div>
    </main>
</body>

</html>