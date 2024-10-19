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

    // Connect and retrieve data from RaaceDB
    $raceGateway = new RaceDB($conn);
    $allRaces = $raceGateway->getAll();
    $content = "";

    // Retrieve race details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $race = $raceGateway->getRaceDetails($_GET['ref']);
        $qualifiedDrivers = $raceGateway->getQualifiedDrivers($_GET['ref']);
        $raceResults = $raceGateway->getRaceResults($_GET['ref']);
        $content .=
            "<!-- Content layout -->
            <div class='content'>
                <!-- Race details container -->
                <section class='sidebar'>$race</section>
                <!-- Qualifying drivers container -->
                <section class='results'>$qualifiedDrivers</section>
                <!-- Race results container -->
                <section class='results placement'>$raceResults</section>
            </div>";
    } else {
        $race = null;
        $qualifiedDrivers = null;
        $raceResults = null;
        $content .=
            "<div class='no-content'>
                <h1>Please select a race to view contents</h1>
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
        <section class="search">
            <form method="get" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <label for="ref">Select Race: </label>
                <select name="ref" id="ref" required>
                    <option value="" disabled selected>-- Select a Race --</option>
                    <?php echo $allRaces ?>
                </select>
                <button type="submit">Search</button>
            </form>
        </section>
        <!-- Displays content if race selected -->
        <?php echo $content ?>
    </main>
</body>

</html>