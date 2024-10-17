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
    private static $baseSQL =  "SELECT * FROM drivers";
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
        $sql = self::$baseSQL. " WHERE driverRef =?";
        $statement = DatabaseHelper::runQuery(
                        $this->pdo,
                        $sql,
                        array($driverRef)
                    );
        return $statement->fetch();
    }
}
// class PaintingDB
// {
//     private $pdo;
//     private static $baseSQL = "SELECT PaintingID, Paintings.ArtistID,
//         FirstName, LastName, Paintings.GalleryID, GalleryName,
//         ImageFileName, Title, Excerpt, YearOfWork 
//     FROM Galleries INNER JOIN (Artists INNER JOIN Paintings 
//     ON Artists.ArtistID = Paintings.ArtistID) 
//     ON Galleries.GalleryID = Paintings.GalleryID ";

//     public function __construct($connection)
//     {
//         $this->pdo = $connection;
//     }

//     public function getAll()
//     {
//         $sql = self::$baseSQL;
//         $statement =
//             DatabaseHelper::runQuery($this->pdo, $sql, null);
//         return $statement->fetchAll();
//     }

//     public function getAllForArtist($artistID)
//     {
//         $sql = self::$baseSQL . " WHERE Paintings.ArtistID=?";
//         $statement = DatabaseHelper::runQuery(
//             $this->pdo,
//             $sql,
//             array($artistID)
//         );
//         return $statement->fetchAll();
//     }
// }
