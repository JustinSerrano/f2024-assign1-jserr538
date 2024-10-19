<?php
// Used for connecting to database
require_once '../includes/config.inc.php';
require_once '../includes/db-classes.inc.php';

// Tell the browser to expect JSON rather than HTML
header('Content-type: application/json');

// indicate whether other domains can use this API
header("Access-Control-Allow-Origin: *");

try {
    $conn = DatabaseHelper::createConnection(DBCONNSTRING);
    $driverGateway = new DriverDB($conn);

    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $drivers = $driverGateway->getDriver($_GET['ref']);
    } elseif (isset($_GET['race']) && !empty($_GET['race'])){
        $drivers = $driverGateway->getDriversFromRace($_GET['race']);
    }
    else {
        $drivers = $driverGateway->getAll();
    }
    echo json_encode($drivers, JSON_NUMERIC_CHECK + JSON_PRETTY_PRINT);
} catch (Exception $e) {
    die($e->getMessage());
}
