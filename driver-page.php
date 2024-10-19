<?php

// Required to connect to the f1 database
require_once "includes/config.inc.php";
require_once "includes/db-classes.inc.php";

try {
    // Connect and retrieve data from DriverDB
    $conn = DatabaseHelper::createConnection(DBCONNSTRING);
    $driverGateway = new DriverDB($conn);
    $content = "";

    // Retrieve driver details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $driver = $driverGateway->getDriver($_GET['ref']);
        if ($driver) {
            // Grab element values and set them in variables
            $fullname = htmlspecialchars($driver['forename']). ' '. htmlspecialchars($driver['surname']);
            $nationality = htmlspecialchars($driver['nationality']);
            $url = htmlspecialchars($driver['url']);

            // Format DOB
            $dateObject = new DateTime($driver['dob']);
            $formattedDate = date_format($dateObject, "F j, Y");
            $dob = htmlspecialchars($formattedDate);

            // Output the driver information
            $content .=
                "<!-- Driver details and race results layout -->
                <div class='content'>
                <!-- Driver details container -->
                <section class='sidebar'><h2>Driver Information</h2>
                    <p><strong>Name: </strong>$fullname</p>
                    <p><strong>Date of Birth: </strong>$dob</p>
                    <p><strong>Nationality: </strong>$nationality</p>
                    <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>
                </section>";
        }

        // Get race results for driver
        $raceResults = $driverGateway->getDriverRaceResults($_GET['ref']);
        if ($raceResults) {
            $content .= "
                <!-- Race results container -->
                <section class='results'>
                <h2>Race Results</h2>
                    <table border='1'>
                        <tr>
                            <th>Round #</th>
                            <th>Circuit</th>
                            <th>Position</th>
                            <th>Points</th>
                        </tr>";
            foreach ($raceResults as $row) {
                // Grab element values and set them in variables
                $round = htmlspecialchars($row['round']);
                $name = htmlspecialchars($row['name']);
                $position = htmlspecialchars($row['position']);
                if (empty($position)) {
                    $position = "DNF";
                }
                $points = htmlspecialchars($row['points']);

                // Output race results
                $content .= "<tr>
                            <td>$round</td>
                            <td>$name</td>
                            <td>$position</td>
                            <td>$points</td>
                        </tr>";
            }
            $content . "</table>
                    </section>
                </div>";
        }
    } else {
        $driver = null;
        $raceResults = null;
        $content .=
            "<div class='no-content'>
                <h1>No results. Return to <a href='browser-page.php'>Browser Page</a> for results.</h1>
            </div>";
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
        <?php echo $content ?>
    </main>
</body>

</html>