<?php
class DatabaseHelper
{
    /* Returns a connection object to a database */
    public static function createConnection($connString)
    {
        $pdo = new PDO($connString);
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
class CircuitDB
{
    private $pdo;
    private static $baseSQL =  
                "SELECT *
                FROM circuits c
                JOIN races r ON c.circuitId = r.circuitId
                WHERE r.year = 2022";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getAll()
    {
        $sql = self::$baseSQL. " ORDER BY r.round";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
    public function getCircuit($circuitRef)
    {
        $sql = self::$baseSQL . " AND circuitRef=? ORDER BY r.round";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($circuitRef));
        return $statement->fetch();
    }
}
class ConstructorDB
{
    private $pdo;
    private static $baseSQL =  
            "SELECT c.constructorId, c.constructorRef, c.name, c.nationality, c.url
            FROM constructors c
            JOIN qualifying q ON q.constructorId = c.constructorId
            JOIN races r ON r.raceId = q.raceId
            WHERE r.year = 2022";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getAll()
    {
        $sql = self::$baseSQL;
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
    public function getConstructor($constructorRef)
    {
        $sql = self::$baseSQL . " AND c.constructorRef =?";
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
                    AND ra.year = 2022
                ORDER BY ra.round";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($constructorRef)
        );
        return $statement->fetchAll();
    }
}
class DriverDB
{
    private $pdo;
    private static $baseSQL =  "SELECT d.driverId, d.driverRef, d.number, d.code,
                                    d.forename, d.surname, d.dob, d.nationality, d.url 
                                FROM drivers d
                                JOIN qualifying q ON q.driverId = d.driverID
                                JOIN races r ON r.raceId = q.raceId";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getAll()
    {
        $sql = self::$baseSQL . " WHERE r.year = 2022";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
    }
    public function getDriver($driverRef)
    {
        $sql = self::$baseSQL . " WHERE r.year = 2022 AND driverRef =?";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($driverRef)
        );
        return $statement->fetch();
    }
    public function getDriversFromRace($raceId)
    {
        $sql = self::$baseSQL . " WHERE r.raceId =?";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($raceId)
        );
        return $statement->fetch();
    }
    public function getDriverRaceResults($driverRef)
    {
        $sql =  "SELECT (d.forename || ' '|| d.surname) AS fullname, d.dob, d.nationality, 
                    d.url, ra.round, ra.name, res.position, res.points
                FROM drivers d
                JOIN results res ON d.driverId = res.driverId
                JOIN races ra ON ra.raceId = res.raceId
                WHERE d.driverRef = ? AND ra.year = 2022
                ORDER BY ra.round";
        $statement = DatabaseHelper::runQuery(
            $this->pdo,
            $sql,
            array($driverRef)
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
        $sql = self::$baseSQL . " WHERE year = 2022 ORDER BY round";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, null);
        return $statement->fetchAll();
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
        return $statement->fetch();
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
        return $statement->fetchAll();
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
        return $statement->fetchAll();
    }
}

