<?php
// Used for connecting to database
require_once 'config.inc.php';
require_once 'db-classes.inc.php';

// Tell the browser to expect JSON rather than HTML
header('Content-type: application/json');

// indicate whether other domains can use this API
header("Access-Control-Allow-Origin: *");
try {
    $conn = DatabaseHelper::createConnection(array(
        DBCONNSTRING,
        DBUSER,
        DBPASS
    ));
    $circuitGateway = new CircuitDB($conn);

    if (isCorrectQueryStringInfo()) {
        $circuits = $circuitGateway->getCircuit($_GET['ref']);
    } else {
        $circuits = $circuitGateway->getAll();
    }
    // echo json_encode($circuits, JSON_NUMERIC_CHECK);
} catch (Exception $e) {
    die($e->getMessage());
}
function isCorrectQueryStringInfo()
{
    return isset($_GET['ref']) && !empty($_GET['ref']);
}
