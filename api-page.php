<?php

// Required to connect to the f1 database
require_once "config.inc.php";
require_once "db-classes.inc.php";

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
        <h1>F1 Dashboard Project</h1>
        <nav>
            <a href="index.php">Home</a>
            <a href="browse-page.php">Browse</a>
            <a href="api-page.php">APIs</a>
        </nav>
    </header>
    <main>
        <!-- Race details and race results layout -->
        <div class="content">
            <section class="sidebar">
                <ul>
                    <li><a href="/api/circuits.php">/api/circuits.php</a></li>
                    <li><a href="/api/circuits.php?ref=monaco">/api/circuits.php?ref=monaco</a></li>
                    <li><a href="/api/constructors.php">/api/constructors.php</a></li>
                    <li><a href="/api/constructors.php?ref=mclaren">/api/constructors.php?ref=mclaren</a></li>
                </ul>
            </section>
        </div>
    </main>
</body>

</html>