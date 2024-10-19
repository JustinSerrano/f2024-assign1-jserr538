<?php

// Required to connect to the f1 database
require_once "includes/config.inc.php";
require_once "includes/db-classes.inc.php";

?>
<!DOCTYPE html>
<html lang="en-us">

<head>
    <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Page</title>
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
            <!-- List of races container -->
            <section class="sidebar">
                <p>F1 Dashboard Project is an assignment project assigned to students of COMP 3532 (Fall 2024)</p>
                <p>This site was created by Justin Serrano</p>
                <p>Technologies used:</p>
                <ul>
                    <li>HTML</li>
                    <li>CSS</li>
                    <li>PHP</li>
                    <li>SQLite 3</li>
                    <li>Visual Studio Code</li>
                    <li>GitHub</li>
                </ul>
                <p>The GitHub repo is <a href='https://github.com/JustinSerrano/f2024-assign1'>here</a></p>
            </section>

            <!-- Race results container -->
            <section class="results">
            </section>
    </main>
</body>

</html>