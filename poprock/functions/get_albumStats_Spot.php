<?php

$albumSpotID = $_GET['albumSpotID'];
require_once '../rockdb.php';
require( "class.album.php" );

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect.';
};

$getSpotStats = "SELECT z.artistNameSpot, a.albumNameSpot, f1.pop, f1.date
    FROM (SELECT f.*
			FROM popAlbums f
			INNER JOIN (SELECT albumSpotID, pop, max(date) AS Maxdate
						FROM popAlbums  
						GROUP BY albumSpotID) groupedf
			ON f.albumSpotID = groupedf.albumSpotID
			AND f.date = groupedf.Maxdate) f1
	JOIN albumsSpot a ON a.albumSpotID = f1.albumSpotID
    JOIN artistsSpot z ON a.artistSpotID = z.artistSpotID
    WHERE a.albumSpotID = '$albumSpotID'
	ORDER BY a.albumNameSpot ASC";

$getit = mysqli_query($connekt, $getSpotStats);

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