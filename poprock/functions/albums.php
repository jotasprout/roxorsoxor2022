<?php

// CONTAINS
// divideCombineAlbumsForTracks
//      Gets lists of tracks from Spotify -- inefficient
//      CALLS divideCombineInsertTracksAndPop
// divideCombineAlbums
//      Gets list of albums from Spotify
//      Gets popularity for each album
//      Inserts albums into my DB
//      Inserts album pop into my DB

$artistAlbums = array ();

require_once '../rockdb.php';

function divideCombineAlbumsForTracks ($artistAlbums) {

	$albumsArrays = array ();
	
	// Divide all artist's albums into chunks of 20
	$artistAlbumsChunk = array ();
	$x = ceil((count($artistAlbums))/20);

	$firstAlbum = 0;
	
    for ($i=0; $i<$x; ++$i) {
	  $lastAlbum = 19;
      $artistAlbumsChunk = array_slice($artistAlbums, $firstAlbum, $lastAlbum);
	  // put chunks of 20 into an array
      $albumsArrays [] = $artistAlbumsChunk;
      $firstAlbum += 20;
	};

	for ($i=0; $i<(count($albumsArrays)); ++$i) {
				
		$albumSpotIDs = implode(',', $albumsArrays[$i]);
	
		// For each array of albums (20 at a time), "get several albums"
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumSpotIDs);
			
		foreach ($bunchofalbums->albums as $album) {

			$AlbumsTracks = array();
	
            $albumSpotID = $album->id;
            // Does the above get get the id from Spotify? If so, do I need it anymore? At least not here?
			
			$thisAlbumTracks = $GLOBALS['api']->getAlbumTracks($albumSpotID);

			// should be method in albums class
			foreach ($thisAlbumTracks->items as $track) {
				
				// Get each trackSpotID for requesting Full Track Object with popularity
				$trackSpotID = $track->id;
				
				// Put trackSpotIDs in array for requesting several at a time (far fewer requests)
				$AlbumsTracks [] = $trackSpotID;
				
			}

			divideCombineInsertTracksAndPop ($AlbumsTracks);

			unset($AlbumsTracks);
			
		}
	};
}

function divideCombineAlbums ($artistAlbums) {
	
	// Divide all artist's albums into chunks of 20
	$artistAlbumsChunk = array ();
	$x = ceil((count($artistAlbums))/20);

	$firstAlbum = 0;
	
    for ($i=0; $i<$x; ++$i) {
	  $lastAlbum = 19;
	  $artistAlbumsChunk = array_slice($artistAlbums, $firstAlbum, $lastAlbum);
	  // put chunks of 20 into an array
      $albumsArrays [] = $artistAlbumsChunk;
      $firstAlbum += 20;
	};

	for ($i=0; $i<(count($albumsArrays)); ++$i) {
				
		$albumSpotIDs = implode(',', $albumsArrays[$i]);
	
		// For each array of albums (20 at a time), "get several albums"
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumSpotIDs);
			
		foreach ($bunchofalbums->albums as $album) {

			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
	
			$albumSpotID = $album->id;	
			$albumNameYucky = $album->name;
			$albumNameSpot = mysqli_real_escape_string($connekt,$albumNameYucky);
			$albumReleasedWhole = $album->release_date;
			$albumReleased = substr($albumReleasedWhole, 0, 4);
			$albumTotalTracks = $album->total_tracks;
			$thisartistSpotID = $album->artists[0]->id;
			$thisArtistName = $album->artists[0]->name;
			$albumPop = $album->popularity;
			$albumArtSpot = $album->images[0]->url;

			$insertAlbums = "INSERT INTO albumsSpot (albumSpotID,albumNameSpot,artistSpotID,yearReleased,albumArtSpot) VALUES('$albumSpotID','$albumNameSpot','$thisartistSpotID','$albumReleased','$albumTotalTracks','$albumArtSpot')";
			
			if (!$connekt) {
				echo 'Darn. Did not connect.<br>';
			};
			
			$rockout = $connekt->query($insertAlbums);

			if(!$rockout){
				echo '<p>Crap de General Tsao! Could not insert ' . $albumNameSpot . '.</p>';
			}

			$insertAlbumsPop = "INSERT INTO popAlbums (albumSpotID,pop,date) VALUES('$albumSpotID','$albumPop',curdate())";

			$rockin = $connekt->query($insertAlbumsPop);
			
			if(!$rockin){
				echo 'Sweet & Sour Crap! Could not insert albums popularity.';
			}
		
            echo '<p><img src="' . $albumArtSpot . '" height="64" width="64"><br>' . $albumNameSpot . '<br>' . $albumReleased . '<br>Pop is ' . $albumPop . '<br>Total tracks: ' . $albumTotalTracks . '</p>';

		}
	};
  
}

?>