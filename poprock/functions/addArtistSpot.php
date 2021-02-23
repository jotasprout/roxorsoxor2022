<?php
session_start();
require '../secrets/spotifySecrets.php';
// require '../secrets/auth.php';
require '../vendor/autoload.php';
require_once '../rockdb.php';
//require_once '../functions/artists.php';
require_once '../functions/tracks.php';
require_once '../functions/goto_ArtistChartsSpot.php';

$session = new SpotifyWebAPI\Session($myClientID, $myClientSecret);
$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();
// I don't think the cron needs this next line 
$_SESSION['accessToken'] = $accessToken;
// and I don't think the cron needs this next line either
$accessToken = $_SESSION['accessToken'];
$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);
$baseURL = "https://api.spotify.com/v1/artists/";

$artistSpotID = $_POST['artistSpotID'];

$artistNameSpot = '';

function addArtistSpot ($thisArtist) {

    $artist = $GLOBALS['api']->getArtist($thisArtist);
			
    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

    if(!$connekt){
        echo 'Fiddlesticks! Could not connect to database.<br>';
    }

    $artistSpotID = $artist->id;
    // echo $artistSpotID;
    $artistNameYucky = $artist->name;
    $artistNameSpot = mysqli_real_escape_string($connekt,$artistNameYucky);
    // echo $artistName;
    $artistArtSpot = $artist->images[0]->url;
    // echo $artistArt;
    $artistPop = $artist->popularity;
    // echo $artistPop;
    $artistFollowers = $artist->followers->total;

    // What is this next line?
    $artistInfo = '{"artistSpotID": "' . $artistSpotID . '", "artistNameSpot": "' . $artistNameSpot . '", "artistArtSpot": "' . $artistArtSpot . '", "artistPop": "' . $artistPop . '"}';

    $insertArtistsInfo = "INSERT INTO artistsSpot (artistSpotID, artistNameSpot, artistArtSpot) VALUES('$artistSpotID','$artistNameSpot', '$artistArtSpot')";

    $rockout = $connekt->query($insertArtistsInfo);

    if(!$rockout){
        echo 'Cursed-Crap. Could not insert artist ' . $artistNameSpot . '.<br>';
    } else {
        echo 'Inserted ' . $artistNameSpot . ' name and art.<br>';
        echo '<script> console.log(' . json_encode($artist) . ');</script>';
    }
    
    $insertArtistsPop = "INSERT INTO popArtists (artistSpotID,pop,followers,date) VALUES('$artistSpotID','$artistPop','$artistFollowers',curdate())";

    $rockpop = $connekt->query($insertArtistsPop);
    if(!$rockpop){
        echo '<p>Cursed-Crap. Could not insert artists popularity & followers.</p>';
    }

    else {
        echo '<p><img src="' . $artistArtSpot . '" height="128" width="auto"></p><p>' . $artistNameSpot . '</p><p><b>Popularity:</b> ' . $artistPop . '</p><p><b>Followers:</b> ' . $artistFollowers . '</p>';
    };
};

function divideCombineAlbums ($artistAlbums) {
    
    echo "<p>I am in <b>divideCombineAlbums</b></p>";
	
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
				
		$albumSpotIds = implode(',', $albumsArrays[$i]);
	
		// For each array of albums (20 at a time), "get several albums"
		$bunchofalbums = $GLOBALS['api']->getAlbums($albumSpotIds);
			
		foreach ($bunchofalbums->albums as $album) {

			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
	
			$albumSpotID = $album->id;	
			$albumNameSpot = $album->name;
			$albumReleasedWhole = $album->release_date;
			$albumReleased = substr($albumReleasedWhole, 0, 4);
			$albumTotalTracks = intval($album->total_tracks);
			$thisartistSpotID = $album->artists[0]->id;
			$thisArtistName = $album->artists[0]->name;
			$albumPop = $album->popularity;
			$albumArtSpot = $album->images[0]->url;

			$insertAlbum = "INSERT INTO albumsSpot (albumSpotID, albumNameSpot ,artistSpotID, yearReleased, tracksTotal, albumArtSpot) VALUES('$albumSpotID','$albumNameSpot','$thisartistSpotID','$albumReleased','$albumTotalTracks','$albumArtSpot')";
			
			if (!$connekt) {
				echo '<p>Darn. Did not connect.</p>';
			};
			
			$rockout = $connekt->query($insertAlbum);
		
			if(!$rockout){
				echo '<p>Crap de General Tsao! Could not insert ' . $albumNameSpot . '.</p>';
			} else {
                echo '<p><img src="' . $albumArtSpot . '" height="64" width="64"><br>' . $albumNameSpot . '<br>' . $albumReleased . '<br><strong>Total tracks:</strong> ' . $albumTotalTracks . '</p>';                
            };
		
			$insertAlbumsPop = "INSERT INTO popAlbums (albumSpotID,pop,date) VALUES('$albumSpotID','$albumPop',curdate())";
		
			$rockin = $connekt->query($insertAlbumsPop);
			
			if(!$rockin){
				echo '<p>Sweet & Sour Crap! Could not insert albums popularity.</p>';
			} else {
            echo '<p>' . $albumNameSpot . ' has <strong>Popularity:</strong> ' . $albumPop . '.</p>';                
            };
            
			$AlbumsTracks = array ();
			
			$trackListOffset = 0;
			
			$d = ceil($albumTotalTracks/50);
			
			//echo "<p>This album's tracks divided by 50 = " . $d . "</p>";
			
			for ($q=0; $q<$d; $q++) {
	
				$tracksChunk = array ();

				//echo "<p>Tracklist Offset = " . $trackListOffset . ".</p>";

				//echo "<p>Here is chunk #" . $q . ".</p>";
				
				$thisAlbumTracks = $GLOBALS['api']->getAlbumTracks($albumSpotID, [
					'limit' => '50',
					'offset' => $trackListOffset,
					'market' => 'US'
				]);

				foreach ($thisAlbumTracks->items as $track) {
					$trackSpotID = $track->id;
					$trackNameSpot = $track->name;
					$AlbumsTracks [] = $trackSpotID;
				};

				$trackListOffset += 50;

				unset($tracksChunk);
			};
			
			divideCombineInsertTracksAndPop ($AlbumsTracks);

			unset($AlbumsTracks);

		}
	};
  
}

function gatherArtistAlbums ($artistSpotID) {
    
    echo "<p>I am in <b>gatherArtistAlbums</b></p>";

	$discogOffset = 0;

	$discography = $GLOBALS['api']->getArtistAlbums($artistSpotID, [
		'limit' => '50',
		'offset' => $discogOffset
	]);

	$artistAlbumsTotal = intval($discography->total);

	echo "<p>" . $artistNameSpot . " has " . $artistAlbumsTotal . " total albums.</p>";

	$a = ceil($artistAlbumsTotal/50);

	// echo "<p>Total albums divided by 50 = " . $a;

	$allAlbumsThisArtist = array ();

	for ($p=0; $p<$a; $p++) {
		
		$discogChunk = array ();
		
		// echo "<p>Discog Offset = " . $discogOffset . ".</p>";
		
		// echo "<p>Here is chunk #" . $p . ".</p>";

		$discography = $GLOBALS['api']->getArtistAlbums($artistSpotID, [
			'limit' => '50',
			'offset' => $discogOffset,
			'album_type' => ['album', 'compilation']//,
			//'market' => 'US'
		]);

		foreach ($discography->items as $album) {
			$albumSpotID = $album->id;
			$discogChunk [] = $albumSpotID;
		};

		$allAlbumsThisArtist = array_merge($allAlbumsThisArtist, $discogChunk);
		
		$discogOffset += 50;

		unset($discogChunk);
	};

	$thisMany = ceil(count($allAlbumsThisArtist));

	echo "I have gathered " . $thisMany . " albums total.";

    divideCombineAlbums ($allAlbumsThisArtist);
    


	unset($allAlbumsThisArtist);

}

addArtistSpot ($artistSpotID); // inserts artist in artistsSpot, inserts current pop in popArtist
gatherArtistAlbums ($artistSpotID); // gets all artist's albums from Spotify, sends them to ...

// divideCombineAlbums
// gets album info, inserts into albumsSpot, gets stats, inserts into popAlbums, gets tracks from all this artist's albums, sends them to ...

// divideCombineInsertTracksAndPop
// Gets tracks info, inserts into tracksSpot
// gets stats, inserts into popTracks

goto_ArtistChartsSpot($artistSpotID);

die();

?>