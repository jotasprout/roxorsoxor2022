<?php

$albumSpotID = $_GET['albumSpotID'];

require_once '../rockdb.php';
require( "class.album.php" );

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect.';
};

$albumInfoAll = "SELECT a.artistSpotID, a.artistNameSpot, a.artistArtSpot, r.albumSpotID, r.albumNameSpot, b.pop, b.date
	FROM albumsSpot r
    JOIN artistsSpot a ON a.artistSpotID = r.artistSpotID
		INNER JOIN popAlbums b ON a.albumSpotID = b.albumSpotID
			WHERE a.albumSpotID = '$albumSpotID'
				ORDER BY b.date DESC";

$getit = mysqli_query($connekt, $albumInfoAll);

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