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

    // Connect and retrieve data from ConstructorDB
    $constructorGateway = new ConstructorDB($conn);
    $content = "";

    // Retrieve constructor details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $constructor = $constructorGateway->getConstructorInfo($_GET['ref']);
        $raceResults = $constructorGateway->getConstructorRaceResults($_GET['ref']);
        $content .=
            "<!-- Constructor details and race results layout -->
        <div class='content-container'>
            <!-- Constructor details container -->
            <section class='sidebar-details'>$constructor</section>

            <!-- Race results container -->
            <section class='race-results'>$raceResults</section>
        </div>";
    } else {
        $constructor = null;
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
    <title>Constructor Page</title>
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