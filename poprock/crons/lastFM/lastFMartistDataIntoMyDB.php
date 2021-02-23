<?php

/*
$artistsMatchSpotifyMBID_Lookup = 'artistsMatchSpotifyMBID';
$artistListenersPlaycount = 'artistListenersPlaycount';
$albumListenersPlaycount = 'albumListenersPlaycount';
$trackListenersPlaycount = 'trackListenersPlaycount';
$relatedAlbums = 'relatedAlbums';
$relatedArtists = 'relatedArtists';
*/

require_once '../../rockdb.php';

function assembleURL ($artistForURL) {
    $baseURL = 'data/done/04-30-19/';
    $today = date("m-d-y");
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
$filenames2 = array (
    'data/done/04-30-19/2Pac_Person_04-30-19.json',
    'data/done/04-30-19/AliceCooper_Combined_04-30-19.json',
    'data/done/04-30-19/Anvil_Group_04-30-19.json',
    'data/done/04-30-19/BlackSabbath_Group_04-30-19.json',
    'data/done/04-30-19/DavidBowie_Person_04-30-19.json',
    'data/done/04-30-19/LindseyBuckingham_Person_04-30-19.json',
    'data/done/04-30-19/Eminem_Person_04-30-19.json',
    'data/done/04-30-19/Cream_Group_04-30-19.json',
    'data/done/04-30-19/DefLeppard_Group_04-30-19.json',
    'data/done/04-30-19/Dio_Group_04-30-19.json', 
    'data/done/04-30-19/Elf_Group_04-30-19.json', 
    'data/done/04-30-19/EricClapton_Person_04-30-19.json',
    'data/done/04-30-19/EvilStig_Group_04-30-19.json', 
    'data/done/04-30-19/FleetwoodMac_Group_04-30-19.json',
    'data/done/04-30-19/Heaven&Hell_Group_04-30-19.json', 
    'data/done/04-30-19/IggyandTheStooges_Group_04-30-19.json',
    'data/done/04-30-19/JanetJackson_Person_04-30-19.json', 
    'data/done/04-30-19/JimmyPage_Person_04-30-19.json',
    'data/done/04-30-19/JimmyPage&RobertPlant_Group_04-30-19.json',
    'data/done/04-30-19/JoanJett_Combined_04-30-19.json', 
    'data/done/04-30-19/Journey_Group_04-30-19.json', 
    'data/done/04-30-19/LedZeppelin_Group_04-30-19.json',
    'data/done/04-30-19/MeatLoaf_Person_04-30-19.json', 
    'data/done/04-30-19/MötleyCrüe_Group_04-30-19.json', 
    'data/done/04-30-19/NeilYoung_Person_04-30-19.json',
    'data/done/04-30-19/StevieNicks_Person_04-30-19.json',
    'data/done/04-30-19/OzzyOsbourne_Person_04-30-19.json', 
    'data/done/04-30-19/RobertPlant_Person_04-30-19.json',
    'data/done/04-30-19/IggyPop_Person_04-30-19.json',
    'data/done/04-30-19/Queen_Group_04-30-19.json', 
    'data/done/04-30-19/QuietRiot_Group_04-30-19.json', 
    'data/done/04-30-19/Radiohead_Group_04-30-19.json',
    'data/done/04-30-19/Rainbow_Group_04-30-19.json', 
    'data/done/04-30-19/RonnieDioandtheProphets_Group_04-30-19.json', 
    'data/done/04-30-19/RonnieDioandtheRedCaps_Group_04-30-19.json', 
    'data/done/04-30-19/RoxyMusic_Group_04-30-19.json',
    'data/done/04-30-19/Saxon_Group_04-30-19.json', 
    'data/done/04-30-19/Stoney&Meatloaf_Group_04-30-19.json',
    'data/done/04-30-19/TedNugent_Person_04-30-19.json', 
    'data/done/04-30-19/TheAmboyDukes_Group_04-30-19.json',
    'data/done/04-30-19/TheCure_Group_04-30-19.json',
    'data/done/04-30-19/TheElectricElves_Group_04-30-19.json', 
    'data/done/04-30-19/TheFirm_Group_04-30-19.json',
    'data/done/04-30-19/TheRunaways_Group_04-30-19.json',
    'data/done/04-30-19/TheStooges_Group_04-30-19.json',
    'data/done/04-30-19/TheYardbirds_Group_04-30-19.json',
    'data/done/04-30-19/TheZombies_Group_04-30-19.json',
    'data/done/04-30-19/ToddRundgren_Person_04-30-19.json',
    'data/done/04-30-19/Utopia_Group_04-30-19.json'
);

$filenames = array (
    'data/done/05-02-19/2Pac_Person_05-02-19.json',
    'data/done/05-02-19/AliceCooper_Combined_05-02-19.json',
    'data/done/05-02-19/Anvil_Group_05-02-19.json',
    'data/done/05-02-19/BlackSabbath_Group_05-02-19.json',
    'data/done/05-02-19/DavidBowie_Person_05-02-19.json',
    'data/done/05-02-19/LindseyBuckingham_Person_05-02-19.json',
    'data/done/05-02-19/Eminem_Person_05-02-19.json',
    'data/done/05-02-19/Cream_Group_05-02-19.json',
    'data/done/05-02-19/DefLeppard_Group_05-02-19.json',
    'data/done/05-02-19/Dio_Group_05-02-19.json', 
    'data/done/05-02-19/Elf_Group_05-02-19.json', 
    'data/done/05-02-19/EricClapton_Person_05-02-19.json',
    'data/done/05-02-19/EvilStig_Group_05-02-19.json', 
    'data/done/05-02-19/FleetwoodMac_Group_05-02-19.json',
    'data/done/05-02-19/Heaven&Hell_Group_05-02-19.json', 
    'data/done/05-02-19/IggyandTheStooges_Group_05-02-19.json',
    'data/done/05-02-19/JanetJackson_Person_05-02-19.json', 
    'data/done/05-02-19/JimmyPage_Person_05-02-19.json',
    'data/done/05-02-19/JimmyPage&RobertPlant_Group_05-02-19.json',
    'data/done/05-02-19/JoanJett_Combined_05-02-19.json', 
    'data/done/05-02-19/Journey_Group_05-02-19.json', 
    'data/done/05-02-19/LedZeppelin_Group_05-02-19.json',
    'data/done/05-02-19/MeatLoaf_Person_05-02-19.json', 
    'data/done/05-02-19/MötleyCrüe_Group_05-02-19.json', 
    'data/done/05-02-19/NeilYoung_Person_05-02-19.json',
    'data/done/05-02-19/StevieNicks_Person_05-02-19.json',
    'data/done/05-02-19/OzzyOsbourne_Person_05-02-19.json', 
    'data/done/05-02-19/RobertPlant_Person_05-02-19.json',
    'data/done/05-02-19/IggyPop_Person_05-02-19.json',
    'data/done/05-02-19/Queen_Group_05-02-19.json', 
    'data/done/05-02-19/QuietRiot_Group_05-02-19.json', 
    'data/done/05-02-19/Radiohead_Group_05-02-19.json',
    'data/done/05-02-19/Rainbow_Group_05-02-19.json', 
    'data/done/05-02-19/RonnieDioandtheProphets_Group_05-02-19.json', 
    'data/done/05-02-19/RonnieDioandtheRedCaps_Group_05-02-19.json', 
    'data/done/05-02-19/RoxyMusic_Group_05-02-19.json',
    'data/done/05-02-19/Saxon_Group_05-02-19.json', 
    'data/done/05-02-19/Stoney&Meatloaf_Group_05-02-19.json',
    'data/done/05-02-19/TedNugent_Person_05-02-19.json', 
    'data/done/05-02-19/TheAmboyDukes_Group_05-02-19.json',
    'data/done/05-02-19/TheCure_Group_05-02-19.json',
    'data/done/05-02-19/TheElectricElves_Group_05-02-19.json', 
    'data/done/05-02-19/TheFirm_Group_05-02-19.json',
    'data/done/05-02-19/TheRunaways_Group_05-02-19.json',
    'data/done/05-02-19/TheStooges_Group_05-02-19.json',
    'data/done/05-02-19/TheYardbirds_Group_05-02-19.json',
    'data/done/05-02-19/TheZombies_Group_05-02-19.json',
    'data/done/05-02-19/ToddRundgren_Person_05-02-19.json',
    'data/done/05-02-19/Utopia_Group_05-02-19.json'
);

$catchUp = array (
    'data/done/04-30-19/2Pac_Person_05-12-19.json',
    'data/done/04-30-19/AliceCooper_Combined_05-12-19.json',
    'data/done/04-30-19/DavidBowie_Person_05-12-19.json',
    'data/done/04-30-19/Eminem_Person_05-12-19.json',
    'data/done/04-30-19/Cream_Group_05-12-19.json',
    'data/done/04-30-19/EricClapton_Person_05-12-19.json',
    'data/done/04-30-19/IggyandTheStooges_Group_05-12-19.json',
    'data/done/04-30-19/JanetJackson_Person_05-12-19.json', 
    'data/done/04-30-19/JimmyPage_Person_05-12-19.json',
    'data/done/04-30-19/JimmyPage&RobertPlant_Group_05-12-19.json',
    'data/done/04-30-19/JoanJett_Combined_05-12-19.json', 
    'data/done/04-30-19/Journey_Group_05-12-19.json', 
    'data/done/04-30-19/LedZeppelin_Group_05-12-19.json',
    'data/done/04-30-19/RobertPlant_Person_05-12-19.json',
    'data/done/04-30-19/IggyPop_Person_05-12-19.json',
    'data/done/04-30-19/Stoney&Meatloaf_Group_05-12-19.json',
    'data/done/04-30-19/TheElectricElves_Group_05-12-19.json', 
    'data/done/04-30-19/TheFirm_Group_05-12-19.json',
    'data/done/04-30-19/TheStooges_Group_05-12-19.json',
    'data/done/04-30-19/TheYardbirds_Group_05-12-19.json',
    'data/done/04-30-19/ToddRundgren_Person_05-12-19.json',
    'data/done/04-30-19/Utopia_Group_05-12-19.json'
);

$filenames = $filenames2;

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
    $artistName = $artistData['name'];
    
    $dataDate = $artistData['date'];
    
    $artistListeners = $artistData['stats']['listeners'];
    $artistPlaycount = $artistData['stats']['playcount'];

    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
    
    if(!$connekt){
        echo '<p>Fiddlesticks! Could not connect to database.</p>';
    }; // Could and should this if statement go outside this for loop?

    $tryInsertArtistData = "INSERT INTO artistsMB (artistMBID, artistName) VALUES ('$artistMBID', '$artistName')";

    $rockin = $connekt->query($tryInsertArtistData);

    if(!$rockin){
        echo 'Could not insert info for ' . $artistName . '.<br>';
        }
        else {
            echo '<p>Inserted ' . $artistName . ' in table.</p>';
        } 

    $insertArtistStats = "INSERT INTO artistsLastFM (artistMBID, dataDate, artistListeners, artistPlaycount) VALUES('$artistMBID','$dataDate','$artistListeners', '$artistPlaycount')";
        
    $rockout = $connekt->query($insertArtistStats);
    
    if(!$rockout){
    echo 'Shickety Brickety! Could not insert stats for ' . $artistName . '.<br>';
    }
    else {
        echo '<p>Inserted ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays for ' . $artistName . ' on ' . $dataDate . '.</p>';
    } 
    
};

?>