<?php
/* 
This file includes code guided with the help of ChatGPT (OpenAI) and W3School.
Assistance was provided in structuring the race dashboard and database interaction.


Image sources
F1 logo: https://logos-world.net/f1-logo/
Race car wallpaper: https://wall.alphacoders.com/big.php?i=1215632
MRU logo: https://www.mtroyal.ca/AboutMountRoyal/MarketingCommunications/OurLogo/index.htm
*/

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
        <section class="home">
            <div>
                <img src="https://www.mtroyal.ca/AboutMountRoyal/MarketingCommunications/_image/MRU_2c_logo_RGB.png"
                    width="300px" height="200px" />
            </div>
            <div>
                <p>This F1 Dashboard Project assigned by Randy Connolly for students of COMP 3532 (Fall 2024)</p>
                <p>This site was created by Justin Serrano, 3rd year student at Computer Information student at Mount Royal University.
                    <br>The GitHub repo is <a href='https://github.com/JustinSerrano/f2024-assign1'>here</a>
                </p>
            </div>
            <div>
                <p>Technologies used:</p>
                <ul>
                    <li>HTML</li>
                    <li>CSS</li>
                    <li>PHP</li>
                    <li>SQLite 3</li>
                    <li>Visual Studio Code</li>
                    <li>GitHub</li>
                </ul>
            </div>
        </section>
    </main>

    <footer>

    </footer>
</body>

</html>