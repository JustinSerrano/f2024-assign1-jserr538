<?php
class DatabaseHelper
{
    /* Returns a connection object to a database */
    public static function createConnection($values = array())
    {
        $connString = $values[0];
        $user = $values[1];
        $password = $values[2];
        $pdo = new PDO($connString, $user, $password);
        $pdo->setAttribute(
            PDO::ATTR_ERRMODE,
            PDO::ERRMODE_EXCEPTION
        );
        $pdo->setAttribute(
            PDO::ATTR_DEFAULT_FETCH_MODE,
            PDO::FETCH_ASSOC
        );
        return $pdo;
    }
    /*
Runs the specified SQL query using the passed connection and
the passed array of parameters (null if none)
*/
    public static function runQuery($connection, $sql, $parameters)
    {
        $statement = null;
        // if there are parameters then do a prepared statement
        if (isset($parameters)) {
            // Ensure parameters are in an array
            if (!is_array($parameters)) {
                $parameters = array($parameters);
            }
            // Use a prepared statement if parameters
            $statement = $connection->prepare($sql);
            $executedOk = $statement->execute($parameters);
            if (! $executedOk) throw new PDOException;
        } else {
            // Execute a normal query
            $statement = $connection->query($sql);
            if (!$statement) throw new PDOException;
        }
        return $statement;
    }
}
class DriverDB
{
    private $pdo;
    private static $baseSQL =  "SELECT (forename || ' '|| surname) AS fullname, 
                                driverRef, dob, nationality, url FROM drivers";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getDriverInfo($driverRef)
    {
        $sql = self::$baseSQL . " WHERE driverRef =?";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($driverRef)
        );
        $row = $statement->fetch();
        $markup = "";

        // Grab element values and set them in variables
        $fullname = htmlspecialchars($row['fullname']);
        $nationality = htmlspecialchars($row['nationality']);
        $url = htmlspecialchars($row['url']);
        // Format DOB
        $dateObject = new DateTime($row['dob']);
        $formattedDate = date_format($dateObject, "F j, Y");
        $dob = htmlspecialchars($formattedDate);

        // Output the driver information
        return $markup .
            "<h2>Driver Information</h2>
            <p><strong>Name: </strong>$fullname</p>
            <p><strong>Date of Birth: </strong>$dob</p>
            <p><strong>Nationality: </strong>$nationality</p>
            <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>";
    }
    public function getDriverRaceResults($driverRef)
    {
        $sql =  "SELECT (d.forename || ' '|| d.surname) AS fullname, d.dob, d.nationality, 
                    d.url, ra.round, ra.name, res.position, res.points
                FROM drivers d
                JOIN results res ON d.driverId = res.driverId
                JOIN races ra ON ra.raceId = res.raceId
                WHERE d.driverRef = ? AND ra.year = 2023
                ORDER BY ra.round";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($driverRef)
        );
        $data = $statement->fetchAll();
        $markup = "";

        $markup .= "<h2>Race Results</h2>
                    <table border='1'>
                        <tr>
                            <th>Rnd</th>
                            <th>Circuit</th>
                            <th>Pos</th>
                            <th>Points</th>
                        </tr>";
        foreach ($data as $row) {
            // Grab element values and set them in variables
            $round = htmlspecialchars($row['round']);
            $name = htmlspecialchars($row['name']);
            $position = htmlspecialchars($row['position']);
            $points = htmlspecialchars($row['points']);

            // Output race results
            $markup .= "<tr>
                            <td>$round</td>
                            <td>$name</td>
                            <td>$position</td>
                            <td>$points</td>
                        </tr>";
        }
        return $markup . "</table>";
    }
}
class ConstructorDB
{
    private $pdo;
    private static $baseSQL =  "SELECT * FROM constructors";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getConstructorInfo($constructorRef)
    {
        $sql = self::$baseSQL . " WHERE constructorRef =?";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($constructorRef)
        );
        $row = $statement->fetch();
        $markup = "";

        // Grab element values and set them in variables
        $name = htmlspecialchars($row['name']);
        $nationality = htmlspecialchars($row['nationality']);
        $url = htmlspecialchars($row['url']);

        // Output the constructor information
        return $markup .
            "<h2>Constructor Information</h2>
                <p><strong>Name: </strong>$name</p>
                <p><strong>Nationality: </strong>$nationality</p>
                <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>";
    }
    public function getConstructorRaceResults($constructorRef)
    {
        $sql =  "SELECT c.name AS constructorName, c.nationality, c.url,
                    (d.forename || ' ' || d.surname) AS fullname,
                    ra.round, ra.name AS raceName, res.position, res.points
                FROM constructors c
                JOIN constructorResults cr ON c.constructorId = cr.constructorId
                JOIN results res ON cr.raceId = res.raceId AND cr.constructorId = res.constructorId
                JOIN drivers d ON res.driverId = d.driverId
                JOIN races ra ON ra.raceId = cr.raceId
                WHERE c.constructorRef = ?
                    AND ra.year = 2023
                ORDER BY ra.round";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($constructorRef)
        );
        $data = $statement->fetchAll();
        $markup = "";

        $markup .= "<h2>Race Results</h2>
                    <table border='1'>
                        <tr>
                            <th>Rnd</th>
                            <th>Circuit</th>
                            <th>Driver</th>
                            <th>Pos</th>
                            <th>Points</th>
                        </tr>";
        foreach ($data as $row) {
            // Grab element values and set them in variables
            $round = htmlspecialchars($row['round']);
            $raceName = htmlspecialchars($row['raceName']);
            $fullname = htmlspecialchars($row['fullname']);
            $position = htmlspecialchars($row['position']);
            $points = htmlspecialchars($row['points']);

            // Output race results
            $markup .= "<tr>
                            <td>$round</td>
                            <td>$raceName</td>
                            <td>$fullname</td>
                            <td>$position</td>
                            <td>$points</td>
                        </tr>";
        }
        return $markup . "</table>";
    }
}
class RaceDB
{
    private $pdo;
    private static $baseSQL =  "SELECT * FROM races";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getAll()
    {
        $sql = self::$baseSQL . " WHERE year = 2023 ORDER BY round";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        $data = $statement->fetchAll();
        $markup = "";

        if ($data) {
            foreach ($data as $row) {
                $markup .= "<option value='" . htmlspecialchars($row['raceId']) . "'>" .
                    htmlspecialchars($row['name']) . "</option>";
            }
        } else {
            $markup .= "<option disabled>No races available</option>";
        }
        return $markup;
    }
    public function getRaceDetails($raceId)
    {
        $sql = "SELECT r.name AS raceName, r.round, r.date, r.url, 
                c.name AS circuitName, c.location, c.country
                FROM races r
                JOIN circuits c ON r.circuitId = c.circuitId
                WHERE r.raceId = ?
                ORDER BY r.round";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        $row = $statement->fetch();
        $markup = "";

        // Grab element values and set them in variables
        $raceName = htmlspecialchars($row['raceName']);
        $round = htmlspecialchars($row['round']);
        $url = htmlspecialchars($row['url']);
        $circuitName = htmlspecialchars($row['circuitName']);
        $location = htmlspecialchars($row['location']);
        $country = htmlspecialchars($row['country']);
        // Format Date
        $dateObject = new DateTime($row['date']);
        $formattedDate = date_format($dateObject, "F j, Y");
        $date = htmlspecialchars($formattedDate);


        // Output the race information
        return $markup .= "<h2>$raceName</h2>
                    <p><strong>Round #: </strong>$round</p>
                    <p><strong>Circuit: </strong>$circuitName</p>
                    <p><strong>Location: </strong>$location</p>
                    <p><strong>Country: </strong>$country</p>
                    <p><strong>Date: </strong>$date</p>
                    <p><strong>URL: </strong><a href='$url'>Wikipedia</a></p>";
    }
    public function getQualifiedDrivers($raceId)
    {
        $sql = "SELECT q.position, (d.forename || ' ' || d.surname) AS fullname, d.driverRef,
                    c.constructorRef, c.name AS constructorName, q.q1, q.q2, q.q3
                FROM qualifying q
                JOIN races r ON q.raceId = r.raceId
                JOIN drivers d ON q.driverId = d.driverId
                JOIN constructors c ON q.constructorId = c.constructorID
                WHERE q.raceId = ?
                ORDER BY q.position";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        $data = $statement->fetchAll();
        $markup = "";

        $markup .= "<h2>Qualifying</h2>
                    <table border='1'>
                        <tr>
                            <th>Pos</th>
                            <th>Driver</th>
                            <th>Constructor</th>
                            <th>Q1</th>
                            <th>Q2</th>
                            <th>Q3</th>
                        </tr>";
        foreach ($data as $row) {
            // Grab element values and set them in variables
            $position = htmlspecialchars($row['position']);
            $driverRef = htmlspecialchars($row['driverRef']);
            $driver = htmlspecialchars($row['fullname']);
            $constructorRef = htmlspecialchars($row['constructorRef']);
            $constructor = htmlspecialchars($row['constructorName']);
            $q1 = htmlspecialchars($row['q1']);
            $q2 = htmlspecialchars($row['q2']);
            $q3 = htmlspecialchars($row['q3']);

            // Output qualifying drivers
            $markup .= "<tr>
                            <td>$position</td>
                            <td><a href='driver-page.php?ref=$driverRef'>$driver</a></td>
                            <td><a href='constructor-page.php?ref=$constructorRef'>$constructor</a></td>
                            <td>$q1</td>
                            <td>$q2</td>
                            <td>$q3</td>
                        </tr>";
        }
        return $markup . "</table>";
    }
    public function getRaceResults($raceId)
    {
        $sql = "SELECT res.position, (d.forename || ' ' || d.surname) AS fullname,
                    res.laps, res.points
                FROM results res
                JOIN races r ON res.raceId = r.raceId
                JOIN drivers d ON res.driverId = d.driverId
                WHERE res.raceId = ?
                ORDER BY res.position";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        $data = $statement->fetchAll();
        $markup = "";

        $markup .= "<h2>Results</h2>
                    <table border='1'>
                        <tr>
                            <th>Pos</th>
                            <th>Driver</th>
                            <th>Laps</th>
                            <th>Pts</th>
                        </tr>";
        foreach ($data as $row) {
            // Grab element values and set them in variables
            $rowClass = "";
            if (!empty($row['position'])) {
                $position = htmlspecialchars($row['position']);
                if ($position == '1') {
                    $rowClass = "first-place";
                } elseif ($position == "2") {
                    $rowClass = "second-place";
                } elseif ($position == "3") {
                    $rowClass = "third-place";
                }
            } else {
                $position = "DNF";
                $rowClass = "dnf";
            }
            $driver = htmlspecialchars($row['fullname']);
            $laps = htmlspecialchars($row['laps']);
            $points = htmlspecialchars($row['points']);

            // Output race results
            $markup .= "<tr class='$rowClass'>
                            <td>$position</td>
                            <td>$driver</td>
                            <td>$laps</td>
                            <td>$points</td>
                        </tr>";
        }
        return $markup . "</table>";
    }
}
