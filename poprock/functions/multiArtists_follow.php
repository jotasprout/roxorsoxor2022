<?php

require_once '../rockdb.php';
require_once '../data_text/artists_groups.php';
require_once 'class.artist.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
if (!$connekt) {
	echo 'Darn. Did not connect.';
};

$multiArtistFollowers = 'SELECT a.artistSpotID, a.artistArt , a.artistNameSpot, p.followers, p.date
    FROM artistsSpot a
    JOIN popArtists p ON p.artistSpotID = a.artistSpotID
	WHERE a.artistSpotID IN ("' . implode('", "', $comedy) . '")
		AND p.followers IS NOT NULL
	ORDER BY p.date DESC';

$getit = mysqli_query($connekt, $multiArtistFollowers);

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