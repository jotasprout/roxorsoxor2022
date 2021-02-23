<?php

session_start();
require '../secrets/auth.php';
require_once '../rockdb.php';
//require_once '../functions/albums.php';
require '../functions/artists.php';
require '../data_text/artists_arrays.php';

$session = new SpotifyWebAPI\Session($myClientID, $myClientSecret);

$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();

$_SESSION['accessToken'] = $accessToken;
$accessToken = $_SESSION['accessToken'];

$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);

$artistAlbums = array ();

function divideCombineAlbums ($artistAlbums) {
	
	$artistAlbumsChunk = array ();
	$x = ceil((count($artistAlbums))/20);

	$firstAlbum = 0;
	
    for ($i=0; $i<$x; ++$i) {
	  $lastAlbum = 19;
	  $artistAlbumsChunk = array_slice($artistAlbums, $firstAlbum, $lastAlbum);
      $albumsArrays [] = $artistAlbumsChunk;
      $firstAlbum += 20;
	};

	for ($i=0; $i<(count($albumsArrays)); ++$i) {
				
		$albumSpotIDs = implode(',', $albumsArrays[$i]);
	
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumSpotIDs);
			
		foreach ($bunchofalbums->albums as $album) {

			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
	
            $albumSpotID = $album->id;	
            $albumName = $album->name;
			$albumTotalTracks = $album->total_tracks;

			$insertAlbumTotalTracks = "UPDATE albums SET albumTotalTracks='$albumTotalTracks' WHERE albumSpotID='$albumSpotID' AND albumTotalTracks='0'";
			
			if (!$connekt) {
				echo 'Darn. Did not connect.<br>';
			};
			
			$rockout = $connekt->query($insertAlbumTotalTracks);

			if(!$rockout){
				echo 'Crap de General Tsao! Could not insert ' . $albumName . ' total tracks.<br>';
			}
		
            echo '<p>' . $albumName . ' has ' . $albumTotalTracks . ' total tracks.</p>';

		}
	};
}

function divideCombineArtistsForAlbums ($theseArtists) {

	$artistsChunk = array ();
	$x = ceil((count($theseArtists))/50);

	$firstArtist = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastArtist = 49;
		$artistsChunk = array_slice($theseArtists, $firstArtist, $lastArtist);
		$artistsArraysArray [] = $artistsChunk;
		$firstArtist += 50;
	};

	for ($i=0; $i<(count($artistsArraysArray)); ++$i) {
		$artistsIds = implode(',', $artistsArraysArray[$i]);
		$artistsArray = $artistsArraysArray[$i];
			
		for ($j=0; $j<(count($artistsArray)); ++$j) {

			$artistSpotID = $artistsArray[$j];

			$discography = $GLOBALS['api']->getArtistAlbums($artistSpotID, [
                'limit' => '50'
			]);
			
			foreach ($discography->items as $album) {
				$albumSpotID = $album->id;
				$artistAlbums [] = $albumSpotID;
			}
			
			divideCombineAlbums ($artistAlbums);

			unset($artistAlbums);
			
		}
	};	
}

divideCombineArtistsForAlbums ($allArtists)

?>