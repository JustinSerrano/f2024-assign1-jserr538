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
    $constructorGateway = new ConstructorDB($conn);
    $allConstructors = $constructorGateway->getAll();

    // Retrieve driver details
    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $constructor = $constructorGateway->getConstructorInfo($_GET['ref']);
        $raceResults = $constructorGateway->getConstructorRaceResults($_GET['ref']);
    } else {
        $constructor = null;
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
        <!-- Search form container -->
        <section class="search-container">
            <form method="get" action="<?= $_SERVER['REQUEST_URI'] ?>">
                <label for="ref">Select Constructor: </label>
                <select name="ref" id="ref" required>
                    <option value="" disabled selected>-- Select a Constructor --</option>
                    <?php
                    if ($allConstructors) {
                        foreach ($allConstructors as $constructorOption) {
                            echo "<option value='" . htmlspecialchars($constructorOption['constructorRef']) . "'>" .
                                htmlspecialchars($constructorOption['name']) . "</option>";
                        }
                    } else {
                        echo "<option disabled>No constructors available</option>";
                    }
                    ?>
                </select>
                <button type="submit">Search</button>
            </form>
        </section>

        <!-- Constructor details and race results layout -->
        <div class="content-container">
            <!-- Constructor details container -->
            <section class="sidebar-details">
                <?php
                if ($constructor) {
                    // Grab element values and set them in variables
                    $name = htmlspecialchars($constructor['name']);
                    $nationality = htmlspecialchars($constructor['nationality']);
                    $url = htmlspecialchars($constructor['url']);

                    // Output the constructor information
                    echo "<h2>Constructor Information</h2>
                            <p><strong>Name: </strong>$name</p>
                            <p><strong>Nationality: </strong>$nationality</p>
                            <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>";
                } else {
                    echo "<h3>No constructor information available. Please search using the constructor reference.</h3>";
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
                            <th>Driver</th>
                            <th>Pos</th>
                            <th>Points</th>
                        </tr>";
                    foreach ($raceResults as $result) {
                        echo "<tr>
                            <td>" . htmlspecialchars($result['round']) . "</td>
                            <td>" . htmlspecialchars($result['raceName']) . "</td>
                            <td>" . htmlspecialchars($result['fullname']) . "</td>
                            <td>" . htmlspecialchars($result['position']) . "</td>
                            <td>" . htmlspecialchars($result['points']) . "</td>
                        </tr>";
                    }
                    echo "</table>";
                } else {
                    echo "<h3>No race results found for the constructor.</h3>";
                }
                ?>
            </section>
        </div>
    </main>
</body>

</html>