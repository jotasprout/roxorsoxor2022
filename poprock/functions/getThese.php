<?php

include '../page_pieces/sesh.php';
require_once '../secrets/auth.php';
require_once '../rockdb.php';

$AlbumsTracks = array();
	
$albumSpotID = '74QNc1Vo0PshHAkQu3K9sI';
			
$thisAlbumTracks = $GLOBALS['api']->getAlbumTracks($albumSpotID);

foreach ($thisAlbumTracks->items as $track) {
    // Get each trackSpotID for requesting Full Track Object with popularity
    $trackSpotID = $track->id;
    // Put trackSpotIDs in array for requesting several at a time (far fewer requests)
    $AlbumsTracks [] = $trackSpotID;
}


function divideCombineInsertTracksAndPop ($AlbumsTracks) {

	$totalTracks = count($AlbumsTracks);
	echo $totalTracks . '<br>';

	// Divide all artist's tracks into chunks of 50
	$tracksChunk = array ();
	$x = ceil((count($AlbumsTracks))/50);

	$firstTrack = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastTrack = 49;
		$tracksChunk = array_slice($AlbumsTracks, $firstTrack, $lastTrack);
		// put chunks of 50 into an array
		$albumsTracksArrays [] = $tracksChunk;
		$firstTrack += 50;
	};

	for ($i=0; $i<(count($albumsTracksArrays)); ++$i) {
				
		$tracksThisTime = count($albumsTracksArrays[$i]);
		echo $tracksThisTime . '<br>';

		$trackSpotIDs = implode(',', $albumsTracksArrays[$i]);

		// For each array of tracks (50 at a time), "get several tracks"
		$bunchoftracks = $GLOBALS['api']->getTracks($trackSpotIDs);
			
		foreach ($bunchoftracks->tracks as $track) {
			
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			
			if (!$connekt) {
				echo 'Darn. Did not connect.';
			};			

			$trackSpotID = $track->id;
			$trackalbumSpotID = $track->album->id;
			$trackAlbumNameYucky = $track->album->name;
			$trackAlbumName = mysqli_real_escape_string($connekt,$trackAlbumNameYucky);
			$trackNameYucky = $track->name;
			$trackName = mysqli_real_escape_string($connekt,$trackNameYucky);
			$trackPop = $track->popularity;
			$thisArtistName = $track->artists[0]->name;
			
			$insertTrackInfo = "INSERT INTO tracks (trackSpotID,trackName,albumSpotID) VALUES('$trackSpotID','$trackName','$trackalbumSpotID')";
	
			$rockout = $connekt->query($insertTrackInfo);
	
			if(!$rockout){
				echo 'Cursed-Crap. Could not insert "' . $trackName . '" from <i>' . $trackAlbumName . '</i>.<br>';
			}
	
			$insertTrackPop = "INSERT INTO popTracks (trackSpotID,pop) VALUES('$trackSpotID','$trackPop')";
	
			$rockpop = $connekt->query($insertTrackPop);
			
			if(!$rockpop){
				echo 'Confounded-Crap. Could not insert POPULARITY for "' . $trackName . '" from <i>' . $trackAlbumName . '</i>.<br>';
			}
	
			else {
				echo $thisArtistName . "'s album <i>" . $trackAlbumName . "</i>, track " . $trackName . " has pop " . $trackPop . "<br>";
			}
		}
	};
}

divideCombineInsertTracksAndPop ($AlbumsTracks);

?>