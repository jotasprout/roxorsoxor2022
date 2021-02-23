<?php

// CONTAINS
// divideCombineTracksAndInsertPop
//      trackalbumNameSpot?
//      Not to be confused with divideCombineInsertTracksAndPop (called from albums)
//      Gets popularity of tracks and inserts into popTracks

$albumsTracksArrays = array ();

function divideCombineTracksAndInsertPop ($allArtistTracks) {

	$thisMany4 = ceil(count($allArtistTracks));

	echo "I have gathered " . $thisMany4 . " tracks from this artist.";
	
	// Divide all artist's tracks into chunks of 50
	$tracksChunk = array ();
	$x = ceil((count($allArtistTracks))/50);

	$firstTrack = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastTrack = 49;
		$tracksChunk = array_slice($allArtistTracks, $firstTrack, $lastTrack);
		// put chunks of 50 into an array
		$albumsTracksArrays [] = $tracksChunk;
		$firstTrack += 50;
	};

	for ($i=0; $i<(count($albumsTracksArrays)); ++$i) {
				
		$tracksThisTime = count($albumsTracksArrays[$i]);
		// echo $tracksThisTime . '<br>';

		$trackSpotIDs = implode(',', $albumsTracksArrays[$i]);

		// For each array of tracks (50 at a time), "get several tracks"
		$bunchoftracks = $GLOBALS['api']->getTracks($trackSpotIDs);
			
		foreach ($bunchoftracks->tracks as $track) {
			
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			
			if (!$connekt) {
				echo 'Darn. Did not connect.';
			};			

			$trackSpotID = $track->id;
			$trackalbumNameSpot = $track->album->name;
			$trackNameSpot = $track->name;
			$trackNumber = $track->track_number;
			$trackPop = $track->popularity;

			echo "<p>" . $trackNameSpot . " is track #" . $trackNumber . "</p>";
	
			$insertTrackPop = "INSERT INTO popTracks (trackSpotID,pop,date) VALUES('$trackSpotID','$trackPop',curdate())";
	
			$rockpop = $connekt->query($insertTrackPop);
			
			if(!$rockpop){
				echo '<p>Confounded-Crap. Could not insert POPULARITY for "' . $trackNameSpot . '" from <i>' . $trackalbumNameSpot . '</i>.</p>';
			}
	
			else {
				echo "<p>" . $trackNameSpot . " from <i>" . $trackalbumNameSpot . "</i>" . " has pop " . $trackPop . "</p>";
			}
            /*
			$updateTrackNumber = "UPDATE tracksSpot SET trackNumber = $trackNumber WHERE trackSpotID = '$trackSpotID'";
	
			$rockNum = $connekt->query($updateTrackNumber);

			if(!$rockNum){
				echo '<p>Confounded-Crap. Could not update TRACK # for "' . $trackNameSpot . '" from <i>' . $trackalbumNameSpot . '</i>. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
			}
	
			else {
				echo "<p>" . $trackNameSpot . " is track #" . $trackNumber . " on <i>" . $trackalbumNameSpot . "</i>.</p>";
			}
            
            */
		}
	};
}

function divideCombineInsertTracksAndPop ($AlbumsTracks) {

	$totalTracks = count($AlbumsTracks);
	// echo $totalTracks . '<br>';

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
		// echo $tracksThisTime . '<br>';

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
            $trackNumber = $track->track_number;
			$trackPop = $track->popularity;
			$thisArtistName = $track->artists[0]->name;
			
			$insertTrackInfo = "INSERT INTO tracksSpot (trackSpotID,trackNumber, trackNameSpot,albumSpotID) VALUES('$trackSpotID', '$trackNumber', '$trackName','$trackalbumSpotID')";
	
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

?>