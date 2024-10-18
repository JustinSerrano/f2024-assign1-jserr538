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
    public function getAll()
    {
        $sql = self::$baseSQL;
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
    public function getDriverInfo($driverRef)
    {
        $sql = self::$baseSQL . " WHERE driverRef =?";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($driverRef)
        );
        return $statement->fetch();
    }
    public function getDriverRaceResults($driverRef)
    {
        $sql =  "SELECT (d.forename || ' '|| d.surname) AS fullname, d.dob, d.nationality, d.url,
                    ra.round, ra.name, res.position, res.points
                FROM drivers d
                JOIN results res ON d.driverId = res.driverId
                JOIN races ra ON ra.raceId = res.raceId
                WHERE d.driverRef = ?
                    AND ra.year = 2023
                ORDER BY ra.round";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($driverRef)
        );
        return $statement->fetchAll();
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
    public function getAll()
    {
        $sql = self::$baseSQL;
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
    public function getConstructorInfo($constructorRef)
    {
        $sql = self::$baseSQL . " WHERE constructorRef =?";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($constructorRef)
        );
        return $statement->fetch();
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
        return $statement->fetchAll();
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
        $sql = self::$baseSQL. " WHERE year = 2023 ORDER BY round";
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
    public function getRaceDetails($raceId)
    {
        $sql = "SELECT r.name AS raceName, r.round, r.date, r.url, 
                c.name AS circuitName, c.location, c.country
                FROM races r
                JOIN circuits c ON r.circuitId = c.circuitId
                WHERE r.raceId = ?";
        $statement =
            DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        return $statement->fetch();
    }
}
