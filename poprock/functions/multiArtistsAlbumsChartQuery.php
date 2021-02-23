<?php

$relatives = $_GET['group'];
require_once '../rockdb.php';
require( "class.artist.php" );
require_once '../data_text/artists_groups.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

$group = array ();


switch ($relatives){
	case 'steveTaylor':
		$group = $group_steveTaylor;
		break;
	case 'joanJett':
		$group = $group_jj;
		break;
	case 'mikeKnott':
		$group = $group_knott;
		break;
	case 'tomPetty':
		$group = $group_petty;
		break;	
	case 'iggyPop':
		$group = $group_iggy;
		break;		
};

$happyScabies2 = 'SELECT a.albumNameSpot, a.artistSpotID, a.yearReleased, a.albumArtSpot, z.artistNameSpot, p1.pop, p1.date
FROM (SELECT
			y.albumSpotID AS albumSpotID,
			y.albumNameSpot AS albumNameSpot,
			y.artistSpotID AS artistSpotID,
			y.albumArtSpot AS albumArtSpot,
			y.yearReleased AS yearReleased
		FROM albumsSpot y) a
JOIN artistsSpot z ON z.artistSpotID = a.artistSpotID
JOIN (SELECT p.*
		FROM popAlbums p
		INNER JOIN (SELECT albumSpotID, pop, max(date) AS MaxDate
					FROM popAlbums  
					GROUP BY albumSpotID) groupedp
		ON p.albumSpotID = groupedp.albumSpotID
		AND p.date = groupedp.MaxDate) p1 
ON a.albumSpotID = p1.albumSpotID
WHERE a.artistSpotID IN ("' . implode('", "', $group) . '")
ORDER BY yearReleased ASC';						

$result = mysqli_query($connekt, $happyScabies2);

if (mysqli_num_rows($result) > 0) {
	$rows = array();
	while ($row = mysqli_fetch_array($result)) {
		$rows[] = $row;
	}
	echo json_encode($rows);
}

else {
	echo "Nope. Nothing to see here.";
}

mysqli_close($connekt);

?>