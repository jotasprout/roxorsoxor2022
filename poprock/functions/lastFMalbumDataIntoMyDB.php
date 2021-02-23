<?php

//$filenames = $_GET['filenames'];

require_once '../rockdb.php';

$filenames = array (
    '../data_text/jsonLastFM/AliceCooper_Combined_04-22-19.json',
    '../data_text/jsonLastFM/Anvil_Group_04-22-19.json',
    '../data_text/jsonLastFM/BlackSabbath_Group_04-22-19.json',
    '../data_text/jsonLastFM/LindseyBuckingham_Person_04-22-19.json',
    '../data_text/jsonLastFM/DefLeppard_Group_04-22-19.json',
    '../data_text/jsonLastFM/Dio_Group_04-22-19.json', 
    '../data_text/jsonLastFM/Elf_Group_04-22-19.json', 
    '../data_text/jsonLastFM/EvilStig_Group_04-22-19.json', 
    '../data_text/jsonLastFM/FleetwoodMac_Group_04-22-19.json',
    '../data_text/jsonLastFM/Heaven&Hell_Group_04-22-19.json', 
    '../data_text/jsonLastFM/JanetJackson_Person_04-22-19.json', 
    '../data_text/jsonLastFM/JoanJett_Combined_04-22-19.json', 
    '../data_text/jsonLastFM/Journey_Group_04-22-19.json', 
    '../data_text/jsonLastFM/MeatLoaf_Person_04-22-19.json', 
    '../data_text/jsonLastFM/MötleyCrüe_Group_04-22-19.json', 
    '../data_text/jsonLastFM/StevieNicks_Person_04-22-19.json',
    '../data_text/jsonLastFM/OzzyOsbourne_Person_04-22-19.json', 
    '../data_text/jsonLastFM/Queen_Group_04-22-19.json', 
    '../data_text/jsonLastFM/QuietRiot_Group_04-22-19.json', 
    '../data_text/jsonLastFM/Radiohead_Group_04-22-19.json',
    '../data_text/jsonLastFM/Rainbow_Group_04-22-19.json', 
    '../data_text/jsonLastFM/RonnieDioandtheProphets_Group_04-22-19.json', 
    '../data_text/jsonLastFM/RonnieDioandtheRedCaps_Group_04-22-19.json', 
    '../data_text/jsonLastFM/RoxyMusic_Group_04-22-19.json',
    '../data_text/jsonLastFM/Saxon_Group_04-22-19.json', 
    '../data_text/jsonLastFM/Stoney&Meatloaf_Group_04-22-19.json',
    '../data_text/jsonLastFM/TedNugent_Person_04-22-19.json', 
    '../data_text/jsonLastFM/TheAmboyDukes_Group_04-22-19.json',
    '../data_text/jsonLastFM/TheCure_Group_04-22-19.json',
    '../data_text/jsonLastFM/TheElectricElves_Group_04-22-19.json', 
    '../data_text/jsonLastFM/TheRunaways_Group_04-22-19.json',
    '../data_text/jsonLastFM/TheZombies_Group_04-22-19.json'
);

$x = ceil((count($filenames)));

for ($i=0; $i<$x; ++$i) {

    $jsonFile = $filenames[$i];
    $fileContents = file_get_contents($jsonFile);
	
    $artistData = json_decode($fileContents,true);

    $artistMBID = $artistData['mbid'];
    $artistName = $artistData['name'];

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
                $albumListeners = $album['releases'][0]['listeners'];
                $albumPlaycount = $album['releases'][0]['playcount'];
				
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

?>