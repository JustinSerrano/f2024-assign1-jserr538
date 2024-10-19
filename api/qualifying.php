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
    $qualifyingGateway = new QualifyingDB($conn);

    if (isset($_GET['ref']) && !empty($_GET['ref'])) {
        $qualifying = $qualifyingGateway->getQualifiers($_GET['ref']);
    }
    echo json_encode($qualifying, JSON_NUMERIC_CHECK + JSON_PRETTY_PRINT);
} catch (Exception $e) {
    die($e->getMessage());
}
