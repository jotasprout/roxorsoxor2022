<?php

$artistSpotID = $_GET['artistSpotID'];
$artistMBID = $_GET['artistMBID'];
//$artistSpotID = '3EhbVgyfGd7HkpsagwL9GS';
// $artistMBID = 'ee58c59f-8e7f-4430-b8ca-236c4d3745ae';
require_once '../rockdb.php';
require( "class.artist.php" );

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect.';
};

$getLastFM = "SELECT a.artistMBID, a.artistNameMB, a.artistArtMBFilename, f1.dataDate, f1.artistListeners, f1.artistPlaycount, f1.artistRatio
	FROM artistsMB a
    JOIN (SELECT f.*
            FROM artistsLastFM f
            INNER JOIN (SELECT artistMBID, artistListeners, artistPlaycount, artistRatio, max(dataDate) AS MaxDataDate
                        FROM artistsLastFM  
                        GROUP BY artistMBID) groupedf
	       ON f.artistMBID = groupedf.artistMBID
	       AND f.dataDate = groupedf.MaxDataDate) f1
	WHERE a.artistMBID = '$artistMBID'";

$getit = mysqli_query($connekt, $getLastFM);

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