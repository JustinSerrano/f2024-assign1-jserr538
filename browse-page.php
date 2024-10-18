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

    // Retrieve all races
    $raceGateway = new RaceDB($conn);
    $allRaces = $raceGateway->getAll();

    // Retrieve race details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $race = $raceGateway->getRaceDetails($_GET['ref']);
    } else {
        $race = null;
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
    <main>
        <!-- Search form container -->
        <section class="search-container">
            <form method="get" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <label for="ref">Select Race: </label>
                <select name="ref" id="ref" required>
                    <option value="" disabled selected>-- Select a Race --</option>
                    <?php
                    if ($allRaces) {
                        foreach ($allRaces as $raceOption) {
                            echo "<option value='" . htmlspecialchars($raceOption['raceId']) . "'>" .
                                htmlspecialchars($raceOption['name']) . "</option>";
                        }
                    } else {
                        echo "<option disabled>No races available</option>";
                    }
                    ?>
                </select>
                <button type="submit">Search</button>
            </form>
        </section>
        <!-- Race details and race results layout -->
        <div class="content-container">
            <!-- Race details container -->
            <section class="sidebar-details">
                <?php
                if ($race) {
                    // Grab element values and set them in variables
                    $raceName = htmlspecialchars($race['raceName']);
                    $round = htmlspecialchars($race['round']);
                    $date = htmlspecialchars(date_format(new DateTime($race['date']), "F j, Y"));
                    $url = htmlspecialchars($race['url']);
                    $circuitName = htmlspecialchars($race['circuitName']);
                    $location = htmlspecialchars($race['location']);
                    $country = htmlspecialchars($race['country']);

                    // Output the race information
                    echo "<h2>$raceName</h2>
                            <p><strong>Round #: </strong>$round</p>
                            <p><strong>Circuit: </strong>$circuitName</p>
                            <p><strong>Location: </strong>$location</p>
                            <p><strong>Country: </strong>$country</p>
                            <p><strong>Date: </strong>$date</p>
                            <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>";
                } else {
                    echo "<h3>Please click on the race hyperlink for more details.</h3>";
                }
                ?>
            </section>

            <!-- Race results container -->
            <section class="race-results">
                <?php

                ?>
            </section>
    </main>
</body>

</html>