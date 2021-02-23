<?php

// Find out what next line does and needs + build up that file
// Eventually, replace artists.php with class file?
require ("class.artist.php");

function updateMyArtistFollowers($artistSpotID, $artistFollowers){

	$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

	$artistSpotID = $artistSpotID;
	$artistFollowers = $artistFollowers;

	$updatePopArtistsTableFollowers = "UPDATE popArtists SET followers $artistFollowers WHERE artistSpotID = $artistSpotID";

	$followArtist = $connekt->query($updatePopArtistsTableFollowers);
	
	if(!$followArtist){
		echo '<p>Cursed-Crap. Could not insert followers.</p>';
	}

	else {
		echo '<p>' . $artistName . ' has ' . $artistFollowers . ' followers</p>';
	} 
};


function getArtistFollowersFromSpotify ($theseArtists) {
	
	$totalArtists = count($theseArtists);

	// Divide all artists into chunks of 50
	$artistsChunk = array ();
	$x = ceil((count($theseArtists))/50);

	$firstArtist = 0;

	for ($i=0; $i<$x; ++$i) {
		$lastArtist = 49;
		$artistsChunk = array_slice($theseArtists, $firstArtist, $lastArtist);
		// put chunks of 50 into an array
		$artistsArrays [] = $artistsChunk;
		$firstArtist += 50;
	};

	for ($i=0; $i<(count($artistsArrays)); ++$i) {
				
		$artistsThisTime = count($artistsArrays[$i]);

		$artistSpotIDs = implode(',', $artistsArrays[$i]);

		// For each array of artists (50 at a time), "get several artists"
		$bunchofartists = $GLOBALS['api']->getArtists($artistSpotIDs);
			
		foreach ($bunchofartists->artists as $artist) {
			$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
			if(!$connekt){
				echo 'Fiddlesticks! Could not connect to database.<br>';
			}
			$artistSpotID = $artist->id;
			$artistNameYucky = $artist->name;
			$artistName = mysqli_real_escape_string($connekt,$artistNameYucky);
			$artistArt = $artist->images[0]->url;
			$artistPop = $artist->popularity;
			$artistFollowers = $artist->followers->total;
			$jsonArtistGenres = $artist->genres;

			$insertArtistsInfo = "INSERT INTO artists (artistSpotID, artistName, artistArt) VALUES('$artistSpotID','$artistName', '$artistArt')";

			$rockout = $connekt->query($insertArtistsInfo);

			if(!$rockout){
			echo 'Cursed-Crap. Could not insert artist ' . $artistName . '.<br>';
			}
	
			$insertArtistsPop = "INSERT INTO popArtists (artistSpotID,pop,followers,date) VALUES('$artistSpotID','$artistPop','$artistFollowers',curdate())";

			$rockpop = $connekt->query($insertArtistsPop);
			if(!$rockpop){
				echo '<p>Cursed-Crap. Could not insert artists popularity & followers.</p>';
			}
	
			else {
				echo '<p><img src="' . $artistArt . '"><br>' . $artistName . '<br><b>Population:</b> ' . $artistPop . '<br><b>Followers:</b> ' . $artistFollowers . '</p>';
			} 
			
		}
	};	
}

?>