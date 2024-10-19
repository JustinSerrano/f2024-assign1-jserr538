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

    // Connect and retrieve data from DriverDB
    $driverGateway = new DriverDB($conn);
    $content = "";

    // Retrieve driver details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $driver = $driverGateway->getDriverInfo($_GET['ref']);
        $raceResults = $driverGateway->getDriverRaceResults($_GET['ref']);
        $content .=
            "<!-- Driver details and race results layout -->
            <div class='content-container'>
                <!-- Driver details container -->
                <section class='sidebar-details'>$driver</section>

                <!-- Race results container -->
                <section class='race-results'>$raceResults</section>
            </div>";
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