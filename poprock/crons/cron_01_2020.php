<?php

require_once '../secrets/auth.php';
require_once '../vendor/autoload.php';
require_once '../rockdb.php';
require_once '../data_text/artists_arrays.php';
require_once '../functions/tracks.php';

$session = new SpotifyWebAPI\Session($myClientID, $myClientSecret);
$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();
$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);

function getAlbumTracksFromMyDB($thisAlbumSpotID){
    $connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

    if ( !$connekt ) {
        echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
    } else {
        echo 'Apparently connected to DB.';
    };
    
    $thisAlbumTracks = "SELECT v.trackSpotID, v.trackNameSpot, v.trackNumber, v.albumNameSpot, v.pop, max(v.date) AS MaxDate
                        FROM (
                            SELECT z.trackSpotID, z.trackNameSpot, z.trackNumber, r.albumNameSpot, p.date, p.pop
                                FROM (
                                    SELECT t.trackSpotID, t.trackNameSpot, t.albumSpotID, t.trackNumber
                                        FROM tracksSpot t
                                        WHERE t.albumSpotID = '$thisAlbumSpotID'
                                ) z
                            INNER JOIN albumsSpot r 
                                ON r.albumSpotID = z.albumSpotID
                            JOIN popTracks p 
                                ON z.trackSpotID = p.trackSpotID					
                        ) v
                        GROUP BY v.trackSpotID";
    
}; 

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
				
		$albumSpotIds = implode(',', $albumsArrays[$i]);
	
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
 
            echo '<p><img src="' . $albumArtSpot . '" height="64" width="64"><br>' . $albumNameSpot . '<br>' . $albumReleased . '<br><strong>Popularity:</strong> ' . $albumPop . '<br><strong>Total tracks:</strong> ' . $albumTotalTracks . '</p>';
			
			$thisAlbumTracks = getAlbumTracksFromMyDB($thisAlbumSpotID);

            foreach ($thisAlbumTracks->items as $track) {
                $albumNameSpot = $row[ "albumNameSpot" ];
                $trackNameSpot = $row[ "trackNameSpot" ];
                $trackNumber = $row[ "trackNumber" ];
                $trackSpotID = $row[ "trackSpotID" ];		

                $trackPop = $row[ "pop" ];
                //echo "<p>trackPop is " . $trackPop . ".</p>";
                if ($trackPop == '') {
                    $trackPop = "n/a";				
                };	

                $popDate = $row[ "MaxDate" ];
                if ($popDate == '') {
                    $popDate = "n/a";
                };
                $AlbumsTracks [] = $trackSpotID;
                echo "<p>" . $trackNameSpot . " is track #" . $trackNumber . "</p>";
            };
            
			divideCombineTracksAndInsertPop ($AlbumsTracks);

			unset($AlbumsTracks);
            
		}
	};
  
}

function getArtistAlbumsFromMyDB($artistSpotID){
    
    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

    if (!$connekt) {
        echo '<p>Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
    };
    
    $blackScabies = "SELECT albumSpotID 
                        FROM albumsSpot sp
                        WHERE sp.artistSpotID='$artistSpotID';";
    $getit = $connekt->query($blackScabies);

    if(!$getit){
        echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
    }
    
    $allAlbumsThisArtist = getArtistAlbumsFromMyDB($artistSpotID);

	$thisMany = ceil(count($allAlbumsThisArtist));

	echo "I have gathered " . $thisMany . " albums total.";
    
    while ($row = mysqli_fetch_array($getit)) {
		$artistNameSpot = $row['artistNameSpot'];
		$albumSpotID = $row['albumSpotID'];
		$date = $row['date'];
		$source = 'spotify';
		$albumPop = $row['pop'];
		$coverArt = $row['albumArtSpot'];
		$tracksTotal = $row['tracksTotal'];
		// need to get a tracks total for MusicBrainz-only albums
		$albumNameSpot = $row['albumNameSpot'];
		$albumReleased = $row['yearReleased'];	
        ?>
					
<tr>
<td><img src='<?php echo $coverArt ?>' height='64' width='64'></td>
<!---->
<td><?php echo $albumSpotID ?></td>

<td><a href='https://www.roxorsoxor.com/poprock/album_TracksListSpot.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>&albumSpotID=<?php echo $albumSpotID ?>&source=<?php echo $source ?>'><?php echo $albumNameSpot ?></a></td>


<td class="popStyle"><?php echo $albumReleased ?></td>
<!--
<td class="popStyle"><?php //echo $tracksTotal ?></td>
-->
<td class="popStyle"><?php echo $date ?></td>
<td class="popStyle"><?php echo $albumPop ?></td>
<!---->

</tr>
<?php
    };

	divideCombineAlbums ($allAlbumsThisArtist);

};

function divideCombineArtistsForAlbums ($theseArtists) {

	$timeStart = microtime(true);	

	// Divide all artists into chunks of 50
	$artistsChunk = array ();
	$x = ceil((count($theseArtists))/50);

	$firstArtist = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastArtist = 49;
		$artistsChunk = array_slice($theseArtists, $firstArtist, $lastArtist);
		// put chunks of 50 into an array
		$artistsArraysArray [] = $artistsChunk;
		$firstArtist += 50;
	};

	for ($i=0; $i<(count($artistsArraysArray)); ++$i) {
		$artistsIds = implode(',', $artistsArraysArray[$i]);
		//echo '<br>these are the artist IDs ' . $artistsIds;
		$artistsArray = $artistsArraysArray[$i];

		// this next section experimental

		$bunchofartists = $GLOBALS['api']->getArtists($artistsArray);
			
		foreach ($bunchofartists->artists as $artist) {
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			if(!$connekt){
				echo '<p>Fiddlesticks! Could not connect to database.</p>';
			}
			$artistSpotID = $artist->id;
			$artistNameYucky = $artist->name;
			$artistNameSpot = mysqli_real_escape_string($connekt,$artistNameYucky);
			$artistArtSpot = $artist->images[0]->url;
			$artistPop = $artist->popularity;
			$artistFollowers = $artist->followers->total;
	
			$insertArtistsPop = "INSERT INTO popArtists (artistSpotID,pop,followers,date) VALUES('$artistSpotID','$artistPop','$artistFollowers',curdate())";

			$rockpop = $connekt->query($insertArtistsPop);
			if(!$rockpop){
				echo '<p>Cursed-Crap. Could not insert artists popularity & followers.</p>';
			}
	
			else {
				echo '<p><img src="' . $artistArtSpot . '"></p><p>' . $artistNameSpot . '</p><p><b>Popularity:</b> ' . $artistPop . '</p><p><b>Followers:</b> ' . $artistFollowers . '</p>';
			} 
			
		}

		// previous section experimental
			
		for ($j=0; $j<(count($artistsArray)); ++$j) {

			$artistSpotID = $artistsArray[$j];

			getArtistAlbumsFromMyDB($artistSpotID);
			
		}
	};	

	unset($artistsChunk);

$timeEnd = microtime(true);
$duration = ($timeEnd - $timeStart)/60;
echo "<p><b>This cron took:</b> " . $duration . " minutes.</p>";

}

divideCombineArtistsForAlbums ($artists01);

?>