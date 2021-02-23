<?php

require_once '../rockdb.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
    echo 'Darn. Did not connect.';
};

$artistInfo = "SELECT * FROM artists ORDER BY artistName ASC";

$getThem = $connekt->query($artistInfo); 

if (!$getThem) {
    echo 'Darn. No query.';
} 

$returnables = array();

while ( $row = mysqli_fetch_array( $getThem ) ) {
    $rowstuff['artistSpotID'] = $row[ "artistSpotID" ];
    $rowstuff['artistName'] = $row[ "artistName" ];
    array_push($returnables, $rowstuff);
};

echo json_encode($returnables);

?>