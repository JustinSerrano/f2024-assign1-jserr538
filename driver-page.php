<?php
/* 
This file includes code guided with the help of ChatGPT (OpenAI) and W3School.
Assistance was provided in structuring the race dashboard and database interaction.
*/

// Required to connect to the f1 database
require_once "includes/config.inc.php";
require_once "includes/db-classes.inc.php";

try {
    // Connect and retrieve data from DriverDB
    $conn = DatabaseHelper::createConnection(DBCONNSTRING);
    $driverGateway = new DriverDB($conn);
    $resultGateway = new ResultDB($conn);
    $content = "";

    // Retrieve driver details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $driver = $driverGateway->getDriver($_GET['ref']);
        if ($driver) {
            // Grab element values and set them in variables
            $fullname = htmlspecialchars($driver['forename']) . ' ' . htmlspecialchars($driver['surname']);
            $nationality = htmlspecialchars($driver['nationality']);
            $url = htmlspecialchars($driver['url']);

            // Format DOB
            $dateObject = new DateTime($driver['dob']);
            $formattedDate = date_format($dateObject, "F j, Y");
            $dob = htmlspecialchars($formattedDate);

            // Output the driver information
            $content .=
                "<!-- Driver details and race results layout -->
                <div class='row'>
                <!-- Driver details container -->
                <section>
                    <h2>Driver Information</h2>
                    <div class='grid-simple'>
                    <img src='#' alt='Image File of Driver' height='25px' weight='25px'/>
                    <p><strong>Name: </strong>$fullname</p>
                    <p><strong>Date of Birth: </strong>$dob</p>
                    <p><strong>Nationality: </strong>$nationality</p>
                    <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>
                </div>
                </section>";
        }

        // Get race results for driver
        $results = $resultGateway->getResultsFromDriver($_GET['ref']);
        if ($results) {
            $content .=
                "<!-- Race results container -->
                <section>
                <h2>Race Results</h2>
                    <table border='1'>
                    <thead>
                        <tr>
                            <th>Round #</th>
                            <th>Circuit</th>
                            <th>Position</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($results as $row) {
                // Grab element values and set them in variables
                $round = htmlspecialchars($row['round']);
                $raceName = htmlspecialchars($row['raceName']);
                $position = htmlspecialchars($row['positionText']);
                $points = htmlspecialchars($row['points']);

                // Output race results
                $content .= "<tr>
                            <td>$round</td>
                            <td>$raceName</td>
                            <td>$position</td>
                            <td>$points</td>
                        </tr>";
            }
            $content . "</tbody>
                    </table>
                </section>
            </div>";
        }
    } else {
        $driver = null;
        $results = null;
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
        <div class="title">
            <img src="https://logos-world.net/wp-content/uploads/2023/12/F1-Logo-500x281.png" height="100px" width="150px" />
            <h1>Dashboard Project</h1>
        </div>
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