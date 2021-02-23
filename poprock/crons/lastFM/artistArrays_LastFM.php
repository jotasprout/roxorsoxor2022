<?php

function insertLastFMtrackDataArtistNames ($artistNames) {
	
	$y = ceil((count($artistNames)));
	
	for ($i=0; $i<$y; ++$i){
		
		$artistForURL = $artistNames[$i];
		$baseURL = 'data/';
	    $today = date("m-d-y");
		//$today = "12-08-19";
		$endURL = '.json';
		$artistURL = $baseURL . $artistForURL . "_" . $today . $endURL;
		echo "<p>" . $artistURL . "</p>";

		$jsonFile = $artistURL;
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
					$releaseBirthday = $album['releases'][0]['date'];
					$yearReleased = substr($releaseBirthday, 0, 4);
					$releaseName = $album['releases'][0]['name'];
					echo "<h2>" . $releaseName . "</h2>";

					$tracks = $release['tracks'];
					$tracksNum = ceil((count($tracks)));   

					for ($m=0; $m<$tracksNum; ++$m) {
						$track = $tracks[$m];
						$trackMBID = $track['mbid'];
						$trackNameYucky = $track['title'];
						$trackNameMB = mysqli_real_escape_string($connekt,$trackNameYucky);
						$trackListeners = $track['stats']['listeners'];
						$trackPlaycount = $track['stats']['playcount'];
						$trackNumber = $track['trackNumber'];

						$insertMBIDtrackInfo = "INSERT INTO tracksMB (
							albumMBID,
							trackMBID,
							trackNameMB,
							trackNumber
							) 
							VALUES(
								'$releaseMBID',
								'$trackMBID',
								'$trackNameMB',
								'$trackNumber'
							)";

						$addTrack = $connekt->query($insertMBIDtrackInfo);

						if(!$addTrack){
							echo '<p>Could not add <b>' . $trackNameMB . '</b> into tracksMB.</p>';
						} else {
							echo '<p>Added <b>' . $trackNameMB . '</b> from <i>' . $releaseName . '</i> into tracksMB.</p>';
						};

						$insertLastFMtrackStats = "INSERT INTO tracksLastFM (
							trackMBID, 
							dataDate,
							trackListeners,
							trackPlaycount 
							) 
							VALUES(
								'$trackMBID',
								'$dataDate',
								'$trackListeners',
								'$trackPlaycount'
							)";

						$pushTrack = $connekt->query($insertLastFMtrackStats);

						if(!$pushTrack){
							echo '<p>Shickety Brickety! Could not insert ' . $trackNameMB . ' stats.</p>';
						} else {
							echo '<p>' . $trackNameMB . ' from ' . $releaseName . ' had ' . $trackListeners . ' listeners and ' . $trackPlaycount . ' plays on ' . $dataDate . '.</p>';
						}; // end of IF query is not successful ELSE it is      
					} // end of FOR each track on the album
				}; // end of IF there are releases
			}; // end of FOR every album
		}; // end of IF database connection       
	}; // end of FOR each artist in array
}; // end of FUNCTION insert tracks

function insertLastFMalbumDataArtistNames ($artistNames) {
	
	$y = ceil((count($artistNames)));
	
	for ($j=0; $j<$y; ++$j){
		
		$artistForURL = $artistNames[$j];
		$baseURL = 'data/';
		$today = date("m-d-y");
		//$today = "12-08-19";
		$endURL = '.json';
		$artistURL = $baseURL . $artistForURL . "_" . $today . $endURL;
		echo "<p>" . $artistURL . "</p>";

		$jsonFile = $artistURL;
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

			for ($q=0; $q<$albumsNum; ++$q) {
				$album = $albums[$q];
				$releases = $album['releases'];
				$releasesNum = ceil((count($releases)));
				if ($releasesNum > 0){
					$releaseMBID = $album['releases'][0]['mbid'];
					$releaseNameYucky = $album['releases'][0]['name'];
					$releaseName = mysqli_real_escape_string($connekt,$releaseNameYucky);
					$releaseBirthday = $album['releases'][0]['date'];
					$yearReleased = substr($releaseBirthday, 0, 4);					
					$albumListeners = $album['releases'][0]['listeners'];
                    $albumPlaycount = $album['releases'][0]['playcount'];
                    $albumRatio = $albumPlaycount/$albumListeners;

					$insertAlbumMBinfo = "INSERT INTO albumsMB (
						albumMBID,
						albumNameMB,
						artistMBID,
						yearReleased
						) 
						VALUES(
							'$releaseMBID',
							'$releaseName',
							'$artistMBID',
							'$yearReleased'
							)";

					$rockout = $connekt->query($insertAlbumMBinfo);
		
					if(!$rockout){
						echo '<p>Shuzbutt! Could not add <b>' . $releaseName . '</b> to albumsMB.</p>';
					};

					$updateMBalbumInfo = "UPDATE albumsMB SET yearReleased = '$yearReleased' WHERE albumMBID = '$releaseMBID';";	
    
					if(!$updateMBalbumInfo){
						echo '<p>Shickety Brickety! Could not insert ' . $releaseName . ' release date.</p>';
					} else {
						echo '<p><b><i>' . $releaseName . '</i></b> (' . $releaseMBID . ') was born the year of Our Lord <b>' . $yearReleased . '</b>.</p>';
					}
					
					$insertLastFMalbumData = "INSERT INTO albumsLastFM (
						albumMBID, 
						dataDate,
						albumListeners,
						albumPlaycount,
                        albumRatio
						) 
						VALUES(
							'$releaseMBID',
							'$dataDate',
							'$albumListeners',
							'$albumPlaycount',
                            '$albumRatio'
						)";	

					$insertReleaseStats = $connekt->query($insertLastFMalbumData);

					if(!$insertReleaseStats){
						echo '<p>Shickety Brickety! Could not insert <b>' . $releaseName . '</b> stats.</p>';
					} else {
						echo '<p><b>' . $releaseName . '</b> had ' . $albumListeners . ' listeners and ' . $albumPlaycount . ' plays on ' . $dataDate . '.</p>';
					}; // end of if stats are inserted

				}; // end of if there are any releases for this album
			}; // end of FOR each album
		}; // End of IF ELSE connect to db
	}; // End of FOR each artist name
}; // end of Function


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
						$trackListeners = $track['stats']['listeners'];
						$trackPlaycount = $track['stats']['playcount'];
                        $trackNumber = $track['trackNumber'];
                        $trackRatio = $trackPlaycount/$trackListeners;

                        /**/
						$insertMBIDtrackInfo = "INSERT INTO tracksMB (
							albumMBID,
							trackMBID,
							trackNameMB,
							trackNumber
							) 
							VALUES(
								'$releaseMBID',
								'$trackMBID',
								'$trackNameMB',
								'$trackNumber'
							)";

						$addTrack = $connekt->query($insertMBIDtrackInfo);

						if(!$addTrack){
							echo '<p>Could not add <b>' . $trackNameMB . '</b> into tracksMB.</p>';
						} else {
							echo '<p>Added <b>' . $trackNameMB . '</b> from <i>' . $releaseName . '</i> into tracksMB.</p>';
                        };

						$insertLastFMtrackStats = "INSERT INTO tracksLastFM (
							trackMBID, 
							dataDate,
							trackListeners,
							trackPlaycount,
                            trackRatio 
							) 
							VALUES(
								'$trackMBID',
								'$dataDate',
								'$trackListeners',
								'$trackPlaycount',
                                '$trackRatio'
							)";

						$pushTrack = $connekt->query($insertLastFMtrackStats);

						if(!$pushTrack){
							echo '<p>Shickety Brickety! Could not insert ' . $trackNameMB . ' stats.</p>';
						} else {
							echo '<p>' . $trackNameMB . ' from ' . $releaseName . ' had ' . $trackListeners . ' listeners and ' . $trackPlaycount . ' plays on ' . $dataDate . '.</p>';
						}; // end of IF query is not successful ELSE it is    
						/*
						$updateTracksMBwithTrackNumber = "UPDATE tracksMB SET
							trackNumber = '$trackNumber' WHERE trackMBID = '$trackMBID'";

						$updateTrack = $connekt->query($updateTracksMBwithTrackNumber);

						if(!$updateTrack){
							echo '<p>Could not update <b>' . $trackNameMB . '</b> with track number.</p>';
						} else {
							echo '<p>Updated <b>' . $trackNameMB . '</b> from <i>' . $releaseName . '</i> with track #' . $trackNumber . '</p>';
                        };
                        */

					} // end of FOR each track on the album
				}; // end of IF there are releases
			}; // end of FOR every album
		}; // end of IF database connection       
	}; // end of FOR each artist in array
}; // end of FUNCTION insert tracks


function insertLastFMalbumDataFilenames ($filenames) {
	
	$x = ceil((count($filenames)));
	
	for ($i=0; $i<$x; ++$i) {

		$jsonFile = $filenames[$i];
		$fileContents = file_get_contents($jsonFile);

		$artistData = json_decode($fileContents,true);

		$artistMBID = $artistData['mbid'];
		$artistNameMB = $artistData['name'];

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
					$releaseMBID = $album['releases'][0]['mbid'];
					$releaseNameYucky = $album['releases'][0]['name'];
					$releaseName = mysqli_real_escape_string($connekt,$releaseNameYucky);
					$releaseBirthday = $album['releases'][0]['date'];
					$yearReleased = substr($releaseBirthday, 0, 4);					
					$albumListeners = $album['releases'][0]['listeners'];
					$albumPlaycount = $album['releases'][0]['playcount'];

					$insertAlbumMBinfo = "INSERT INTO albumsMB (
						albumMBID,
						albumNameMB,
						artistMBID,
						yearReleased
						) 
						VALUES(
							'$releaseMBID',
							'$releaseName',
							'$artistMBID',
							'$yearReleased'
							)";

					$rockout = $connekt->query($insertAlbumMBinfo);
		
					if(!$rockout){
						echo '<p>Shuzbutt! Could not add <b>' . $releaseName . '</b> to albumsMB.</p>';
					};

					$updateMBalbumInfo = "UPDATE albumsMB SET yearReleased = '$yearReleased' WHERE albumMBID = '$releaseMBID';";	
    
					if(!$updateMBalbumInfo){
						echo '<p>Shickety Brickety! Could not insert ' . $releaseName . ' release date.</p>';
					} else {
						echo '<p><b><i>' . $releaseName . '</i></b> (' . $releaseMBID . ') was born the year of Our Lord <b>' . $yearReleased . '</b>.</p>';
					}

					$insertLastFMalbumData = "INSERT INTO albumsLastFM (
						albumMBID, 
						dataDate,
						albumListeners,
						albumPlaycount
						) 
						VALUES(
							'$releaseMBID',
							'$dataDate',
							'$albumListeners',
							'$albumPlaycount'
						)";	

					$insertReleaseStats = $connekt->query($insertLastFMalbumData);

					if(!$insertReleaseStats){
						echo '<p>Shickety Brickety! Could not insert ' . $releaseName . ' stats.</p>';
					} else {
						echo '<p>' . $releaseName . ' had ' . $albumListeners . ' listeners and ' . $albumPlaycount . ' plays on ' . $dataDate . '.</p>';
					}

				}
			};
		};
	};	
};
/**/
$aliceJJ = array (
	'data/AliceCooper_Combined_07-28-19.json',
	'data/JoanJett_Combined_07-28-19.json'
);

$filenames_01 = array (	
    'data/AliceCooper_Combined_07-21-19.json',
    'data/TheAmboyDukes_Group_07-21-19.json',
    'data/EvilStig_Group_07-21-19.json', 
    'data/JoanJett_Combined_07-21-19.json', 
	'data/TheRunaways_Group_07-21-19.json',
    'data/TedNugent_Person_07-21-19.json', 
    'data/DavidBowie_Person_07-21-19.json',
    'data/JanetJackson_Person_07-21-19.json'	
);

$artistNames_01 = array (
	'AliceCooper_Combined',
	'EvilStig_Group',
	'JoanJett_Combined',
	'TheRunaways_Group',
    'TheAmboyDukes_Group',
	'TedNugent_Person', 
    'JanetJackson_Person',
    '12thTribe_Group',
    'ArgylePark_Group',
    'BarrenCross_Group',
	'Believer_Group',
	'GeorgeClinton_Person',
	'Funkadelic_Group',
	'Parliament-Funkadelic_Group',
	'Parliament_Group',
    'GraceJones_Person',
    'JudasPriest_Group',
    'DepecheMode_Group',
    'Motörhead_Group',
    'T.Rex_Group',
    'Kraftwerk_Group',
    'Rufus_Group',
    'MC5_Group',
    'DaveMatthewsBand_Group',
    'PatBenatar_Person',
    'DoobieBrothers_Group',
    'Soundgarden_Group',
    'WhitneyHouston_Person',
    'ThinLizzy_Group',
    'NineInchNails_Group',
    'NotoriousB.I.G._Person',
    'DeepPurple_Group'
);

$filenames_02 = array (	
    'data/Anvil_Group_07-22-19.json',
    'data/LindseyBuckingham_Person_07-22-19.json',
    'data/TheCure_Group_07-22-19.json',
    'data/Eminem_Person_07-22-19.json',
    'data/FleetwoodMac_Group_07-22-19.json',
    'data/StevieNicks_Person_07-22-19.json',
    'data/Radiohead_Group_07-22-19.json'
);

$artistNames_02 = array (
    'Anvil_Group',
    'LindseyBuckingham_Person',
    'BuckinghamNicks_Group',
	'TheCure_Group',
    'Eminem_Person',
    'FleetwoodMac_Group',
    'StevieNicks_Person',
    'Radiohead_Group',
    'Bloodgood_Group',
    'BobDylan_Person',
    'Bride_Group',
    'CannibalCorpse_Group',
    'ChagallGuevara_Group',
    'CircleofDust_Group',
    'Crashdog_Group',
    'Deitiphobia_Group',
    'Deliverance_Group'
);

$filenames_03 = array (
    'data/BlackSabbath_Group_07-23-19.json',
    'data/Dio_Group_07-23-19.json', 
    'data/Elf_Group_07-23-19.json', 
    'data/TheElectricElves_Group_07-23-19.json', 
    'data/Heaven&Hell_Group_07-23-19.json', 
    'data/OzzyOsbourne_Person_07-23-19.json', 
	'data/Rainbow_Group_07-23-19.json',
    'data/RonnieDioandtheProphets_Group_07-23-19.json', 
    'data/RonnieDioandtheRedCaps_Group_07-23-19.json',
    'data/BlackSabbath_Group_07-16-19.json',
    'data/Dio_Group_07-16-19.json', 
    'data/Elf_Group_07-16-19.json', 
    'data/TheElectricElves_Group_07-16-19.json', 
    'data/Heaven&Hell_Group_07-16-19.json', 
    'data/OzzyOsbourne_Person_07-16-19.json', 
	'data/Rainbow_Group_07-16-19.json',
    'data/RonnieDioandtheProphets_Group_07-16-19.json', 
    'data/RonnieDioandtheRedCaps_Group_07-16-19.json'	
);

$artistNames_03 = array (
    'Dio_Group', 
    'RonnieDioandtheProphets_Group', 
    'RonnieDioandtheRedCaps_Group',
    'TheElectricElves_Group',
    'Elf_Group',
    'Heaven&Hell_Group',
    'Rainbow_Group',
	'RonnieJamesDio_Person',
	'RogerGlover_Person',
	'KerryLivgren_Person',
    'OzzyOsbourne_Person',
    'BlackSabbath_Group',
    'DisciplesofChrist_Group',
    'E-Roc_Person',
    'BillyIdol_Person'
);

$filenames_04 = array (
    'data/TheFirm_Group_07-17-19.json',
    'data/JimmyPage_Person_07-17-19.json',
    'data/JimmyPage&RobertPlant_Group_07-17-19.json',
    'data/LedZeppelin_Group_07-17-19.json',
    'data/RobertPlant_Person_07-17-19.json',
    'data/TheYardbirds_Group_07-17-19.json',
    'data/TheFirm_Group_07-24-19.json',
    'data/JimmyPage_Person_07-24-19.json',
    'data/JimmyPage&RobertPlant_Group_07-24-19.json',
    'data/LedZeppelin_Group_07-24-19.json',
    'data/RobertPlant_Person_07-24-19.json',
	'data/TheYardbirds_Group_07-24-19.json'
);

$artistNames_04 = array (
	'TheFirm_Group',
	'JimmyPage_Person',
    'JimmyPage&RobertPlant_Group',
	'LedZeppelin_Group',
	'RobertPlant_Person',
    'TheYardbirds_Group',
    'EltonJohn_Person', 
    'AceFrehley_Person',  
    #'Frehleys Comet',    
    'Jerusalem_Group', 
    'GlennKaiser_Person', 
    'ResurrectionBand_Group',       
    #'KingsX_Group',   
    'LarryNorman_Person',  
    #'LifeSavers_Group', 
    'LustControl_Group'   
);

$filenames_05 = array (
	'data/IggyandTheStooges_Group_07-18-19.json',
    'data/IggyPop_Person_07-18-19.json',
    'data/Journey_Group_07-18-19.json', 
    'data/MeatLoaf_Person_07-18-19.json', 
    'data/Stoney&Meatloaf_Group_07-18-19.json',
    'data/TheStooges_Group_07-18-19.json'
);

$artistNames_05 = array (
	'IggyandTheStooges_Group',
	'IggyPop_Person',
	'Journey_Group',
	'MeatLoaf_Person',
    'Stoney&Meatloaf_Group',
    'DavidBowie_Person',
    'TheStooges_Group',
    'MadattheWorld_Group',  
    'Mortal_Group',  
    'Mortification_Group',  
    #'MxPx_Group',      
    'NinetyPoundWuss_Group',  
    'OneBadPig_Group',      
    'P.I.D._Group',  
    'P.O.D._Group',    
    'SackclothFashion_Group',  
    'Saint_Group',  
    'SayWhat?_Group'  
);

$filenames_06 = array (
	'data/2Pac_Person_07-19-19.json',
    'data/DefLeppard_Group_07-19-19.json',
    'data/MötleyCrüe_Group_07-19-19.json',
    'data/Queen_Group_07-19-19.json', 
    'data/QuietRiot_Group_07-19-19.json', 
    'data/ToddRundgren_Person_07-19-19.json',
	'data/Utopia_Group_07-19-19.json'
);

$artistNames_06 = array (
	'2Pac_Person',
	'DefLeppard_Group',
	'MötleyCrüe_Group',
	'Queen_Group', 
    'QuietRiot_Group',
	'ToddRundgren_Person',
    'Utopia_Group',
    'SteveTaylor_Person',  
    'TheCrucified_Group',  
    #'The Dynamic Twins_Group',      
    'TheVioletBurning_Group',  
    'TomPetty_Person',  
    'TomPettyandtheHeartbreakers_Group'   
    #'SFC_Group', # 
    #'Sintax the Terrific_Person' # 
);

$filenames_07 = array (
    'data/Cream_Group_07-20-19.json',
    'data/EricClapton_Person_07-20-19.json',
    'data/RoxyMusic_Group_07-20-19.json',
    'data/Saxon_Group_07-20-19.json', 
    'data/NeilYoung_Person_07-20-19.json',
    'data/TheZombies_Group_07-20-19.json',
    'data/Crosby,Stills,Nash&Young_Group_07-20-19.json',
    'data/BuffaloSpringfield_Group_07-20-19.json',
    'data/TheZombies_Group_07-20-19.json',
    'data/ScaterdFew_Group_07-20-19.json',
    'data/SeventhAngel_Group_07-20-19.json',
    'data/Prince_Person__07-20-19.json'
);

$artistNames_07 = array (
	'Cream_Group',
	'EricClapton_Person',
	'RoxyMusic_Group',
    'Saxon_Group',
	'NeilYoung_Person',
	'Crosby,Stills,Nash&Young_Group',
	'BuffaloSpringfield_Group',
	'TheZombies_Group',
    #'TRYTAN_Group', 
    #'VeilofAshes_Group', 
    #'VengeanceRising_Group', 
    #'VeniDomine_Group', 
    #'Whitecross_Group', 
    #'XLandDeathBeforeDishonor_Group',     
    'ScaterdFew_Group',  
    'SeventhAngel_Group',
    'Prince_Person'  
);

?>