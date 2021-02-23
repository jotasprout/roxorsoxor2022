<?php

$artistSpotID = $_GET['artistSpotID'];
$artistMBID = $_GET['artistMBID'];

require_once '../rockdb.php';
require( "class.artist.php" );

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};


$getAssocArtists = "SELECT r.assocArtistName, r.assocArtistSpotID, r.assocArtistMBID, a.artistArtSpot, m.artistArtMBFilename
					FROM artistAssocArtists r
					LEFT JOIN artistsSpot a ON r.assocArtistSpotID = a.artistSpotID
					LEFT JOIN artistsMB m ON m.artistMBID = '$artistMBID'
					WHERE r.primaryArtistSpotID = '$artistSpotID';";
	
$result = mysqli_query($connekt, $getAssocArtists);
/**/
if(!$result){
	echo 'Cursed-Crap. Did not run the query.';
}

if (mysqli_num_rows($result) > 0) {
	/**/
	$rows = array();
	while ($row = mysqli_fetch_array($result)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
	
}

else {
	echo "Nope. Nothing to see here. Screwed up like this: " . mysqli_error($result) . "</p>";
}

mysqli_close($connekt);

?>