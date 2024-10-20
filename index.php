<?php
/* Comments and source
F1 logo: https://logos-world.net/f1-logo/
Race car wallpaper: https://wall.alphacoders.com/big.php?i=1215632
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
                <img src="https://scontent.fyyc3-1.fna.fbcdn.net/v/t1.6435-9/65016264_2572895552731198_7037461612810207232_n.jpg?_nc_cat=109&ccb=1-7&_nc_sid=1d70fc&_nc_ohc=Xb6DJg-g0IAQ7kNvgHNWtiP&_nc_zt=23&_nc_ht=scontent.fyyc3-1.fna&_nc_gid=AAytV3_HTqJI4v1N2M45aLr&oh=00_AYCjpUwFBAO3gn6b_8hMuJm4LABJnOeKmSLwr89piNwHeg&oe=673BFD56"
                    width="250px" height="250px" />
            </div>
            <div>
                <p>F1 Dashboard Project is an assignment project assigned to students of COMP 3532 (Fall 2024)</p>
                <p>This site was created by Justin Serrano
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