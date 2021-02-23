<?php

$albumMBID = $_GET['albumMBID'];
require_once '../rockdb.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
};
// b7d17108-0217-36e6-9110-b7f24ab6da8f
$getLastFM = "SELECT z.artistNameMB, a.albumNameMB, a.albumMBID, a.albumArtMBFilename, f1.albumListeners, f1.albumPlaycount, f1.dataDate
				FROM (SELECT f.*
						FROM albumsLastFM f
						INNER JOIN (SELECT albumMBID, albumListeners, albumPlaycount, max(dataDate) AS MaxDataDate
									FROM albumsLastFM  
									GROUP BY albumMBID) groupedf
						ON f.albumMBID = groupedf.albumMBID
						AND f.dataDate = groupedf.MaxDataDate) f1
				JOIN albumsMB a ON a.albumMBID = f1.albumMBID
				JOIN artistsMB z ON a.artistMBID = z.artistMBID
				WHERE a.albumMBID = '$albumMBID';";

$getit = mysqli_query($connekt, $getLastFM);

if(!$getit){
	echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
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