<?php

$artistSpotID = $_GET['artistSpotID'];
$artistMBID = $_GET['artistMBID'];
require_once '../rockdb.php';
require( "class.artist.php" );

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};

$getPlaycounts = "SELECT m.artistNameMB, p.dataDate, p.artistPlaycount
	FROM artistsMB m
	INNER JOIN artistsLastFM p ON m.artistMBID = p.artistMBID
	WHERE m.artistMBID = '$artistMBID' AND p.dataDate >= '2019-05-01'
	ORDER BY p.dataDate ASC";

$getit = mysqli_query($connekt, $getPlaycounts);

if(!$getit){
	echo 'Cursed-Crap. Did not run the query.';
}	

if (mysqli_num_rows($getit) > 0) {
    $rows = array();
	while ($row = mysqli_fetch_array($getit)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
}

else {
	echo "Nope. Nothing to see here.";
}

mysqli_close($connekt);

?>