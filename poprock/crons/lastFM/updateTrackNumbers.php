<?php

require 'rockdb.php';

function insertLastFMtrackDataFilenames ($filenames) {
	
	$y = ceil((count($filenames)));
	
	for ($i=0; $i<$y; ++$i){
		
		$jsonFile = $filenames[$i];
		$fileContents = file_get_contents($jsonFile);
		$artistData = json_decode($fileContents,true);

		$artistMBID = $artistData['mbid'];
		$artistNameMB = $artistData['name'];
		echo "<h1>" . $artistNameMB . "</h1>";

		$dataDate = $artistData['date'];

		$albums = $artistData['albums'];

		$albumsNum = ceil((count($albums)));

		$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

		if(!$connekt){
			echo 'Fiddlesticks! Could not connect to database.<br>';
		} else {

			for ($j=0; $j<$albumsNum; ++$j) {
				$album = $albums[$j];
				$releases = $album['releases'];
				$releasesNum = ceil((count($releases)));
				if ($releasesNum > 0){
					$release = $releases[0];
					$releaseMBID = $album['releases'][0]['mbid'];
					$releaseName = $album['releases'][0]['name'];
					echo "<h2>" . $releaseName . "</h2>";

					$tracks = $release['tracks'];
					$tracksNum = ceil((count($tracks)));   

					for ($m=0; $m<$tracksNum; ++$m) {
						$track = $tracks[$m];
						$trackMBID = $track['mbid'];
						$trackNameYucky = $track['title'];
						$trackNameMB = mysqli_real_escape_string($connekt,$trackNameYucky);
						$trackNumber = $track['trackNumber'];

                        $updateTracksMBwithTrackNumber = "UPDATE tracksMB SET trackNumber = '$trackNumber' WHERE trackMBID = '$trackMBID'";

						$updateTrack = $connekt->query($updateTracksMBwithTrackNumber);

						if(!$updateTrack){
							echo '<p>Could not update <b>' . $trackNameMB . '</b> with track number.</p>';
						} else {
							echo '<p>Updated <b>' . $trackNameMB . '</b> from <i>' . $releaseName . '</i> with track #' . $trackNumber . '</p>';
                        };

					} // end of FOR each track on the album
				}; // end of IF there are releases
			}; // end of FOR every album
		}; // end of IF database connection       
	}; // end of FOR each artist in array
}; // end of FUNCTION insert tracks

$filenames = array ('data/Rainbow_Group_07-27-19.json');

insertLastFMtrackDataFilenames ($filenames)

?>