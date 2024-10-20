<?php

// Required to connect to the f1 database
require_once "includes/config.inc.php";
require_once "includes/db-classes.inc.php";

?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>APIs Page</title>
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
        <!-- Race details and race results layout -->
        <section class="api">
            <ul>
                <li><a href="api\circuits.php">api\circuits.php</a></li>
                <li><a href="api\circuits.php?ref=monaco">api\circuits.php?ref=monaco</a></li>
                <li><a href="api\constructors.php">api\constructors.php</a></li>
                <li><a href="api\constructors.php?ref=mclaren">api\constructors.php?ref=mclaren</a></li>
                <li><a href="api\drivers.php">api\drivers.php</a></li>
                <li><a href="api\drivers.php?ref=hamilton">api\drivers.php?ref=hamilton</a></li>
                <li><a href="api\drivers.php?race=1106">api\drivers.php?race=1106</a></li>
                <li><a href="api\races.php">api\races.php</a></li>
                <li><a href="api\races.php?ref=1106">api\races.php?ref=1106</a></li>
                <li><a href="api\qualifying.php?ref=1106">api\qualifying.php?ref=1106</a></li>
                <li><a href="api\results.php?ref=1106">api\results.php?ref=1106</a></li>
                <li><a href="api\results.php?driver=max_verstappen">api\results.php?driver=max_verstappen</a></li>
            </ul>
        </section>
    </main>
</body>

</html>