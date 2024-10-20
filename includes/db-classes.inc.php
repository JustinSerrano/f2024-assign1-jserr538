<?php
/* 
This file includes code guided with the help of ChatGPT (OpenAI) and W3School.
Assistance was provided in structuring the race dashboard and database interaction.
*/
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
        $sql = self::$baseSQL . " ORDER BY r.round";
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
    public function getRace($raceId)
    {
        $sql = "SELECT r.year, r.round, r.name As raceName, r.date, r.time, r.url,
                c.name AS circuitName, c.location, c.country
                FROM races r
                JOIN circuits c ON r.circuitId = c.circuitId
                WHERE r.raceId = ?
                ORDER BY r.round";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        return $statement->fetch();
    }
}
class QualifyingDB
{
    private $pdo;
    private static $baseSQL =  "SELECT q.qualifyId, q.number, q.position, q.q1, q.q2, q.q3,
                                    d.driverRef, d.code, d.forename, d.surname,
                                    r.name AS raceName, r.round, r.year, r.date,
                                    c.name AS constructorName, c.constructorRef, c.nationality
                                FROM qualifying q
                                JOIN drivers d ON d.driverID = q.driverId
                                JOIN races r ON r.raceId = q.raceId
                                JOIN constructors c ON c.constructorId = q.constructorId";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getQualifiers($raceId)
    {
        $sql = self::$baseSQL . " WHERE q.raceId =? ORDER BY q.position";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        return $statement->fetchAll();
    }
}
class ResultDB
{
    private $pdo;
    private static $baseSQL =  "SELECT res.resultId, res.number, res.grid, res.position, res.positionText,
                                    res.positionOrder, res.points, res.laps, res.time, res.milliseconds,
                                    res.fastestLap, res.rank, res.fastestLapTime, res.fastestLapSpeed,
                                    d.driverRef, d.code, d.forename, d.surname,
                                    r.name AS raceName, r.round, r.year, r.date,
                                    c.name AS constructorName, c.constructorRef, c.nationality
                                FROM results res
                                JOIN drivers d ON res.driverId = d.driverId
                                JOIN races r ON res.raceId = r.raceId
                                JOIN constructors c ON res.constructorId = c.constructorId";
    public function __construct($connection)
    {
        $this->pdo = $connection;
    }
    public function getResultsFromRace($raceId)
    {
        $sql = self::$baseSQL . " WHERE res.raceId = ? ORDER BY res.position"; // Instruction says grid, does not meet requirement
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($raceId));
        return $statement->fetchAll();
    }
    public function getResultsFromDriver($driverRef)
    {
        $sql = self::$baseSQL . " WHERE d.driverRef = ? AND r.year = 2022";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($driverRef));
        return $statement->fetchAll();
    }
    public function getResultsFromConstructor($constructorRef)
    {
        $sql = self::$baseSQL . " WHERE c.constructorRef = ? AND r.year = 2022";
        $statement = DatabaseHelper::runQuery($this->pdo, $sql, array($constructorRef));
        return $statement->fetchAll();
    }
}
