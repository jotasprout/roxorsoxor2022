<?php

//$filenames = $_GET['filenames'];

require_once '../rockdb.php';

/*
$artistsMatchSpotifyMBID_Lookup = 'artistsMatchSpotifyMBID';
$artistListenersPlaycount = 'artistListenersPlaycount';
$albumListenersPlaycount = 'albumListenersPlaycount';
$trackListenersPlaycount = 'trackListenersPlaycount';
$relatedAlbums = 'relatedAlbums';
$relatedArtists = 'relatedArtists';
*/

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

$filenames = $filenames;

$x = ceil((count($filenames)));

for ($i=0; $i<$x; ++$i) {
    $jsonFile = $filenames[$i];
    $fileContents = file_get_contents($jsonFile);
    $artistData = json_decode($fileContents,true);
    
    $artistMBID = $artistData['mbid'];
    $artistName = $artistData['name'];
    
    $dataDate = $artistData['date'];
    
    $artistListeners = $artistData['stats']['listeners'];
    $artistPlaycount = $artistData['stats']['playcount'];
    
    $insertArtistStats = "INSERT INTO artistsLastFM (artistMBID, dataDate, artistListeners, artistPlaycount) VALUES('$artistMBID','$dataDate','$artistListeners', '$artistPlaycount')";
    
    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
    
    if(!$connekt){
        echo '<p>Fiddlesticks! Could not connect to database.</p>';
    }
    
    $rockout = $connekt->query($insertArtistStats);
    
    if(!$rockout){
    echo 'Shickety Brickety! Could not insert stats for ' . $artistName . '.<br>';
    }
    else {
        echo '<p>Inserted ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays for ' . $artistName . ' on ' . $dataDate . '.</p>';
    } 
    
};

?>