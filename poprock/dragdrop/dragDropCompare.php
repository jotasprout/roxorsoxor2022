<?php
require_once '../rockdb.php';
$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );
if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};
$dragdrop = array ('0oSGxfWSnnOXhD2fKuz2Gy', '2BTZIqw0ntH9MvilQ3ewNY', '3RYdggbT5C9r4BsljokJ1Q', '3qm84nBOXUEQ2vnTfUTTFC','33EUXrFKGjpUSGacqEHhU4', '6mdiAmATAx73kdxrNrnlao', '4xtWjIlVuZwTCeqVAsgEXy', '1Fmb52lZ6Jv7FMWXXTPO3K', '3lPQ2Fk5JOwGWAF3ORFCqH', '4VCZkmckTZMDFU0WsaepBe', '0klkYTAeGHgItyB4R9YYjU', '36QJpDe2go2KgaRleHCDTp', '7dnB1wSxbYa8CejeVg98hz', '0VOvF0kfqCTRe37XzWQdvH', '1P72cdCRCvytPnFLkGSeVm', '5a2EaR3hamoenG9rDuVn8j', '2y8Jo9CKhJvtfeKOsYzRdT', '3PXQl96QHBJbzAGENdJWc1', '0t1uzfQspxLvAifZLdmFe2', '4salDzkGmfycRqNUbyBphh', '2x9SpqnPi8rlE9pjHBwmSC', '3lHPBMb024SqetFwwVwuwH', '3lgxzeCbj6oMQMmaUhH2H6', '3h66yQiOXZpT6AV2Np5yIq');
$thisarray = implode("', '", $dragdrop);
$artistPopCurrentWithArt = "SELECT a.artistSpotID AS artistSpotID, a.artistArtSpot AS artistArtSpot, a.artistNameSpot AS artistNameSpot, p1.pop AS pop, p1.date AS date
    FROM artistsSpot a
    JOIN (SELECT p.*
			FROM popArtists p
			INNER JOIN (SELECT artistSpotID, pop, max(date) AS MaxDate
						FROM popArtists  
						GROUP BY artistSpotID) groupedp
			ON p.artistSpotID = groupedp.artistSpotID
			AND p.date = groupedp.MaxDate) p1
	ON a.artistSpotID = p1.artistSpotID
	WHERE a.artistSpotID IN ('" . $thisarray . "')    
    ORDER BY a.artistNameSpot ASC";
$artistInfoWithArt = "SELECT a.artistSpotID AS artistSpotID, a.artistArtSpot AS artistArtSpot, a.artistNameSpot AS artistNameSpot
    FROM artistsSpot a
	WHERE a.artistSpotID IN ('" . $thisarray . "')    
    ORDER BY a.artistNameSpot ASC";
$result = mysqli_query($connekt, $artistPopCurrentWithArt);
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