<?php

require 'rockdb.php';

function insertLastFMalbumYearsFilenames ($filenames) {
	
	$x = ceil((count($filenames)));
	
	for ($i=0; $i<$x; ++$i) {

		$jsonFile = $filenames[$i];
		$fileContents = file_get_contents($jsonFile);

		$artistData = json_decode($fileContents,true);

		$artistMBID = $artistData['mbid'];
		$artistNameMB = $artistData['name'];

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
                    $releaseMBID = $release['mbid'];
                    $releaseName = $release['name'];

                    $albumYearReleasedWhole = $release['date'];
                    $albumYearReleased = substr($albumYearReleasedWhole, 0, 4);
                    
                    $updateMBalbumData = "UPDATE albumsMB SET yearReleased = '$albumYearReleased' WHERE albumMBID = '$releaseMBID';";	

                    if(!$updateMBalbumData){
                        echo '<p>Shickety Brickety! Could not insert ' . $releaseName . ' release date.</p>';
                    } else {
                        echo '<p><b><i>' . $releaseName . '</i></b> (' . $releaseMBID . ') was born the year of Our Lord <b>' . $albumYearReleased . '</b>.</p>';
                    }

				}
			};
		};
	};	
};

$filenames = array (	
    'data/AliceCooper_Combined_07-28-19.json',
    'data/TheAmboyDukes_Group_07-28-19.json',
    'data/EvilStig_Group_07-28-19.json', 
    'data/JoanJett_Combined_07-28-19.json', 
	'data/TheRunaways_Group_07-28-19.json',
    'data/TedNugent_Person_07-28-19.json', 
    'data/DavidBowie_Person_07-28-19.json',
    'data/JanetJackson_Person_07-28-19.json',
    'data/Anvil_Group_07-29-19.json',
    'data/LindseyBuckingham_Person_07-29-19.json',
    'data/TheCure_Group_07-29-19.json',
    'data/Eminem_Person_07-29-19.json',
    'data/FleetwoodMac_Group_07-29-19.json',
    'data/StevieNicks_Person_07-29-19.json',
    'data/Radiohead_Group_07-29-19.json',
    'data/BlackSabbath_Group_08-01-19.json',
    'data/Dio_Group_08-01-19.json', 
    'data/Elf_Group_08-01-19.json', 
    'data/TheElectricElves_Group_08-01-19.json', 
    'data/Heaven&Hell_Group_08-01-19.json', 
    'data/OzzyOsbourne_Person_08-01-19.json', 
	'data/Rainbow_Group_08-01-19.json',
    'data/RonnieDioandtheProphets_Group_08-01-19.json', 
    'data/RonnieDioandtheRedCaps_Group_08-01-19.json',
    'data/TheFirm_Group_07-31-19.json',
    'data/JimmyPage_Person_07-31-19.json',
    'data/JimmyPage&RobertPlant_Group_07-31-19.json',
    'data/LedZeppelin_Group_07-31-19.json',
    'data/RobertPlant_Person_07-31-19.json',
    'data/TheYardbirds_Group_07-31-19.json',
	'data/IggyandTheStooges_Group_08-02-19.json',
    'data/IggyPop_Person_08-02-19.json',
    'data/Journey_Group_08-02-19.json', 
    'data/MeatLoaf_Person_08-02-19.json', 
    'data/Stoney&Meatloaf_Group_08-02-19.json',
    'data/TheStooges_Group_08-02-19.json',
	'data/2Pac_Person_08-02-19.json',
    'data/DefLeppard_Group_08-02-19.json',
    'data/MötleyCrüe_Group_08-02-19.json',
    'data/Queen_Group_08-02-19.json', 
    'data/QuietRiot_Group_08-02-19.json', 
    'data/ToddRundgren_Person_08-02-19.json',
	'data/Utopia_Group_08-02-19.json',
    'data/Cream_Group_08-03-19.json',
    'data/EricClapton_Person_08-03-19.json',
    'data/RoxyMusic_Group_08-03-19.json',
    'data/Saxon_Group_08-03-19.json', 
    'data/NeilYoung_Person_08-03-19.json',
	'data/TheZombies_Group_08-03-19.json'
);

insertLastFMalbumYearsFilenames ($filenames);

?>