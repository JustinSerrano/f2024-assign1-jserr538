<?php

// Required to connect to the f1 database
require_once "includes/config.inc.php";
require_once "includes/db-classes.inc.php";

try {
    // Connect and retrieve data from ConstructorDB
    $conn = DatabaseHelper::createConnection(DBCONNSTRING);
    $constructorGateway = new ConstructorDB($conn);
    $resultGateway = new ResultDB($conn);
    $content = "";

    // Retrieve constructor details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $constructor = $constructorGateway->getConstructor($_GET['ref']);
        if ($constructor) {
            // Grab element values and set them in variables
            $name = htmlspecialchars($constructor['name']);
            $nationality = htmlspecialchars($constructor['nationality']);
            $url = htmlspecialchars($constructor['url']);

            // Output the constructor information
            $content .=
                "<!-- Constructor details and race results layout -->
                <div class='row'>
                    <!-- Constructor details container -->
                    <section>
                        <h2>Constructor Information</h2>
                        <div class='grid-simple'>
                        <img src='#' alt='Image File of Constructor Logo' height='25px' weight='25px'/>
                        <p><strong>Name: </strong>$name</p>
                        <p><strong>Nationality: </strong>$nationality</p>
                        <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>
                    </div>
                    </section>";
        }

        $results = $resultGateway->getResultsFromConstructor($_GET['ref']);
        if ($results) {
            $content .=
                "<!-- Race results container -->
                <section class='constructor'>
                <h2>Race Results</h2>
                    <table border='1'>
                    <thead>
                        <tr>
                            <th>Round #</th>
                            <th>Circuit</th>
                            <th>Driver</th>
                            <th>Position</th>
                            <th>Points</th>
                        </tr>
                    </thead>
                    <tbody>";
            foreach ($results as $row) {
                // Grab element values and set them in variables
                $round = htmlspecialchars($row['round']);
                $raceName = htmlspecialchars($row['raceName']);
                $fullname = htmlspecialchars($row['forename']) . ' ' . htmlspecialchars($row['surname']);
                $position = htmlspecialchars($row['positionText']);
                $points = htmlspecialchars($row['points']);

                // Output race results
                $content .=
                    "<tr>
                        <td>$round</td>
                        <td>$raceName</td>
                        <td>$fullname</td>
                        <td>$position</td>
                        <td>$points</td>
                    </tr>";
            }
            $content .= "</tbody>
                    </table>
                </section>
            </div>";
        }
    } else {
        $constructor = null;
        $results = null;
        $content .=
            "<section class='no-content'>
                <h1>No results. Return to <a href='browser-page.php'>Browser Page</a> for results.</h1>
            </section>";
    }
} catch (Exception $e) {
    die($e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Constructor Page</title>
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body>
    <header>
        <div class="title">
            <img src="https://logos-world.net/wp-content/uploads/2023/12/F1-Logo-500x281.png" height="100px" width="150px" />
            <h1>F1 Dashboard Project</h1>
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