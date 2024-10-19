<?php

// Required to connect to the f1 database
require_once "includes/config.inc.php";
require_once "includes/db-classes.inc.php";

try {
    // Connect and retrieve data from RaaceDB
    $conn = DatabaseHelper::createConnection(DBCONNSTRING);
    $raceGateway = new RaceDB($conn);
    $qualifyingGateway = new QualifyingDB($conn);
    $resultGateway = new ResultDB($conn);

    // Markup for main content
    $allRaces = $raceGateway->getAll();
    $main = "";
    if ($allRaces) {
        $main .=
            "<!-- Race details container -->
            <section class='container'>
            <form method='get' action='" . $_SERVER['REQUEST_URI'] . "'>
                <label for='ref'>Select Race: </label>
                <select name='ref' id='ref' required>
                    <option value='' disabled selected>-- Select a Race --</option>";
        foreach ($allRaces as $row) {
            $main .= "<option value='" . htmlspecialchars($row['raceId']) . "'>" .
                htmlspecialchars($row['name']) . "</option>";
        }
        $main .= "</select>
                <button type='submit'>Search</button>
            </form>";

        // Retrieve race details
        if (isset($_GET['ref']) && !empty($_GET['ref'])) {
            $race = $raceGateway->getRace($_GET['ref']);
            // Grab element values and set them in variables
            $raceName = htmlspecialchars($race['raceName']);
            $round = htmlspecialchars($race['round']);
            $url = htmlspecialchars($race['url']);
            $circuitName = htmlspecialchars($race['circuitName']);
            $location = htmlspecialchars($race['location']);
            $country = htmlspecialchars($race['country']);
            // Format Date
            $dateObject = new DateTime($race['date']);
            $formattedDate = date_format($dateObject, "F j, Y");
            $date = htmlspecialchars($formattedDate);


            // Output the race information
            $main .=
                "<h2>$raceName</h2>
                    <p><strong>Round #: </strong>$round</p>
                    <p><strong>Circuit: </strong>$circuitName</p>
                    <p><strong>Location: </strong>$location</p>
                    <p><strong>Country: </strong>$country</p>
                    <p><strong>Date: </strong>$date</p>
                    <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>
                </section>";

            // Retrieve qualified drivers
            $qualifiers = $qualifyingGateway->getQualifiers($_GET['ref']);
            $main .=
                "<!-- Qualifying drivers container -->
                    <section class='container'>
                        <h2>Qualifying</h2>
                        <table border='1'>
                            <thead>
                                <tr>
                                    <th>Position</th>
                                    <th>Driver</th>
                                    <th>Constructor</th>
                                    <th>Q1</th>
                                    <th>Q2</th>
                                    <th>Q3</th>
                                </tr>
                            </thead>
                        <tbody>";
            foreach ($qualifiers as $row) {
                // Grab element values and set them in variables
                $position = htmlspecialchars($row['position']);
                $driverRef = htmlspecialchars($row['driverRef']);
                $driver = htmlspecialchars($row['forename']) . ' ' . htmlspecialchars($row['surname']);
                $constructorRef = htmlspecialchars($row['constructorRef']);
                $constructor = htmlspecialchars($row['constructorName']);
                $q1 = htmlspecialchars($row['q1']);
                $q2 = htmlspecialchars($row['q2']);
                $q3 = htmlspecialchars($row['q3']);

                // Output qualifying drivers for race
                $main .= "<tr>
                            <td>$position</td>
                            <td><a href='driver-page.php?ref=$driverRef'>$driver</a></td>
                            <td><a href='constructor-page.php?ref=$constructorRef'>$constructor</a></td>
                            <td>$q1</td>
                            <td>$q2</td>
                            <td>$q3</td>
                        </tr>";
            }
            $main .= "</tbody>
                    </table>
                </section>";

            // Retrieve results from race
            $results = $resultGateway->getResultsFromRace($_GET['ref']);
            $main .=
                "<!-- Race results container -->
                        <section class='container placement'>
                        <h2>Results</h2>
                            <table border='1'>
                                <thead>
                                    <tr>
                                        <th>Position</th>
                                        <th>Driver</th>
                                        <th>Laps</th>
                                        <th>Points</th>
                                    </tr>
                                </thead>
                            <tbody>";
            foreach ($results as $row) {
                // Grab element values and set them in variables
                $position = htmlspecialchars($row['positionText']);
                $driver = htmlspecialchars($row['forename']) . ' ' . htmlspecialchars($row['surname']);
                $laps = htmlspecialchars($row['laps']);
                $points = htmlspecialchars($row['points']);

                // Output race results
                $main .= "<tr>
                            <td>$position</td>
                            <td>$driver</td>
                            <td>$laps</td>
                            <td>$points</td>
                        </tr>";
            }
            $main .= "</tbody>
                    </table>
                </section>";
        } else {
            $main .= "<!-- No content message -->
            <section class='no-content'>
                <h1>Please select a race to view contents</h1>
            </section>";
        }
    } else {
        $race = null;
        $qualifiers = null;
        $results = null;
        $main .= "No data to retrieve. Please reconfigure connection to database.";
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Page</title>
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
    <main class="content">
        <!-- Displays content if race selected -->
        <?php echo $main ?>
    </main>
</body>

</html>