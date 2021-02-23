<?php

require_once '../../rockdb.php';

function assembleURL ($artistForURL) {
    $baseURL = 'data/';
    $today = date("m/d/y");
    $endURL = '.json';
	$artistURL = $baseURL . $artistForURL . "_" . $today . $endURL;
	echo "<p>" . $artistURL . "</p>";
};

$artistNames = array (
    '2Pac_Person',
    'AliceCooper_Combined',
    'Anvil_Group',
    'BlackSabbath_Group',
    'DavidBowie_Person',
    'LindseyBuckingham_Person',
    'Eminem_Person',
    'Cream_Group',
    'DefLeppard_Group',
    'Dio_Group', 
    'Elf_Group', 
    'EricClapton_Person',
    'EvilStig_Group', 
    'FleetwoodMac_Group',
    'Heaven&Hell_Group', 
    'IggyandTheStooges_Group',
    'JanetJackson_Person', 
    'JimmyPage_Person',
    'JimmyPage&RobertPlant_Group',
    'JoanJett_Combined', 
    'Journey_Group', 
    'LedZeppelin_Group',
    'MeatLoaf_Person', 
    'MötleyCrüe_Group', 
    'NeilYoung_Person',
    'StevieNicks_Person',
    'OzzyOsbourne_Person', 
    'RobertPlant_Person',
    'IggyPop_Person',
    'Queen_Group', 
    'QuietRiot_Group', 
    'Radiohead_Group',
    'Rainbow_Group', 
    'RonnieDioandtheProphets_Group', 
    'RonnieDioandtheRedCaps_Group', 
    'RoxyMusic_Group',
    'Saxon_Group', 
    'Stoney&Meatloaf_Group',
    'TedNugent_Person', 
    'TheAmboyDukes_Group',
    'TheCure_Group',
    'TheElectricElves_Group', 
    'TheFirm_Group',
    'TheRunaways_Group',
    'TheStooges_Group',
    'TheYardbirds_Group',
    'TheZombies_Group',
    'ToddRundgren_Person',
    'Utopia_Group'
);

$filenames = array (
    'data/Anvil_Group_12-09-19.json',
    'data/LindseyBuckingham_Person_12-09-19.json',
    'data/TheCure_Group_12-09-19.json',
    'data/Eminem_Person_12-09-19.json',
    'data/FleetwoodMac_Group_12-09-19.json',
    'data/StevieNicks_Person_12-09-19.json',
    'data/Radiohead_Group_12-09-19.json',
    'data/BuckinghamNicks_Group_12-09-19.json',
    'data/Bloodgood_Group_12-09-19.json',
    'data/BobDylan_Person_12-09-19.json',
    'data/Bride_Group_12-09-19.json',
    'data/CannibalCorpse_Group_12-09-19.json',
    'data/ChagallGuevara_Group_12-09-19.json',
    'data/CircleofDust_Group_12-09-19.json',
    'data/Crashdog_Group_12-09-19.json',
    'data/Deitiphobia_Group_12-09-19.json',
    'data/Deliverance_Group_12-09-19.json'
);

$filenames = $filenames;

$x = ceil((count($filenames)));
/*
$y = ceil((count($artistNames)));

for ($j=0; $j<$y; ++$j){
	assembleURL ($artistNames[$j]);
};
*/
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
                $release = $releases[0];
                $releaseMBID = $album['releases'][0]['mbid'];
                $releaseName = $album['releases'][0]['name'];

                $tracks = $release['tracks'];
                $tracksNum = ceil((count($tracks)));   

                for ($m=0; $m<$tracksNum; ++$m) {
                    $track = $tracks[$m];
                    $trackMBID = $track['mbid'];
                    $trackNameYucky = $track['title'];
                    $trackNameMB = mysqli_real_escape_string($connekt,$trackNameYucky);
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
                        echo '<p>Shickety Brickety! Could not insert ' . $trackNameMB . ' stats.</p>';
                    } else {
                        echo '<p>' . $trackNameMB . ' from ' . $releaseName . ' had ' . $trackListeners . ' listeners and ' . $trackPlaycount . ' plays on ' . $dataDate . '.</p>';
                    }       
                };
            }  
        };
    };
};       

?>