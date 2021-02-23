<?php

require_once '../../rockdb.php';

$filenames = array (
    'data/2Pac_Person_05-02-19.json',
    'data/AliceCooper_Combined_05-02-19.json',
    'data/Anvil_Group_05-02-19.json',
    'data/BlackSabbath_Group_05-02-19.json',
    'data/DavidBowie_Person_05-02-19.json',
    'data/LindseyBuckingham_Person_05-02-19.json',
    'data/Eminem_Person_05-02-19.json',
    'data/Cream_Group_05-02-19.json',
    'data/DefLeppard_Group_05-02-19.json',
    'data/Dio_Group_05-02-19.json', 
    'data/Elf_Group_05-02-19.json', 
    'data/EricClapton_Person_05-02-19.json',
    'data/EvilStig_Group_05-02-19.json', 
    'data/FleetwoodMac_Group_05-02-19.json',
    'data/Heaven&Hell_Group_05-02-19.json', 
    'data/IggyandTheStooges_Group_05-02-19.json',
    'data/JanetJackson_Person_05-02-19.json', 
    'data/JimmyPage_Person_05-02-19.json',
    'data/JimmyPage&RobertPlant_Group_05-02-19.json',
    'data/JoanJett_Combined_05-02-19.json', 
    'data/Journey_Group_05-02-19.json', 
    'data/LedZeppelin_Group_05-02-19.json',
    'data/MeatLoaf_Person_05-02-19.json', 
    'data/MötleyCrüe_Group_05-02-19.json', 
    'data/NeilYoung_Person_05-02-19.json',
    'data/StevieNicks_Person_05-02-19.json',
    'data/OzzyOsbourne_Person_05-02-19.json', 
    'data/RobertPlant_Person_05-02-19.json',
    'data/IggyPop_Person_05-02-19.json',
    'data/Queen_Group_05-02-19.json', 
    'data/QuietRiot_Group_05-02-19.json', 
    'data/Radiohead_Group_05-02-19.json',
    'data/Rainbow_Group_05-02-19.json', 
    'data/RonnieDioandtheProphets_Group_05-02-19.json', 
    'data/RonnieDioandtheRedCaps_Group_05-02-19.json', 
    'data/RoxyMusic_Group_05-02-19.json',
    'data/Saxon_Group_05-02-19.json', 
    'data/Stoney&Meatloaf_Group_05-02-19.json',
    'data/TedNugent_Person_05-02-19.json', 
    'data/TheAmboyDukes_Group_05-02-19.json',
    'data/TheCure_Group_05-02-19.json',
    'data/TheElectricElves_Group_05-02-19.json', 
    'data/TheFirm_Group_05-02-19.json',
    'data/TheRunaways_Group_05-02-19.json',
    'data/TheStooges_Group_05-02-19.json',
    'data/TheYardbirds_Group_05-02-19.json',
    'data/TheZombies_Group_05-02-19.json',
    'data/ToddRundgren_Person_05-02-19.json',
    'data/Utopia_Group_05-02-19.json'
);

$filenames = $filenames;

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
                $release = $releases[0];
                $releaseMBID = $album['releases'][0]['mbid'];
                $releaseName = $album['releases'][0]['name'];

                $tracks = $release['tracks'];
                $tracksNum = ceil((count($tracks)));   

                for ($m=0; $m<$tracksNum; ++$m) {
                    $track = $tracks[$m];
                    $trackMBID = $track['mbid'];
                    $trackNameYucky = $track['title'];
                    $trackName = mysqli_real_escape_string($connekt,$trackNameYucky);
                    $trackListeners = $track['stats']['listeners'];
                    $trackPlaycount = $track['stats']['playcount'];

                    $insertMBIDtrack = "INSERT INTO tracksLastFM (
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

                    $pushTrack = $connekt->query($insertMBIDtrack);

                    if(!$pushTrack){
                        echo '<p>Shickety Brickety! Could not insert ' . $trackName . ' stats.</p>';
                    } else {
                        echo '<p>' . $trackName . ' from ' . $releaseName . ' had ' . $trackListeners . ' listeners and ' . $trackPlaycount . ' plays on ' . $dataDate . '.</p>';
                    }       
                };
            }  
        };
    };
};       

?>