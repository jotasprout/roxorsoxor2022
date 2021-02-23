<?php

require_once '../../rockdb.php';

$filenames_05 = array (
    'data/IggyandTheStooges_Group_05-23-19.json',
    'data/IggyPop_Person_05-23-19.json',
    'data/Journey_Group_05-23-19.json', 
    'data/MeatLoaf_Person_05-23-19.json', 
    'data/Stoney&Meatloaf_Group_05-23-19.json',
    'data/TheStooges_Group_05-23-19.json'
);


$filenames_06 = array (
    'data/2Pac_Person_05-24-19.json',
    'data/DefLeppard_Group_05-24-19.json',
    'data/MötleyCrüe_Group_05-24-19.json',
    'data/Queen_Group_05-24-19.json', 
    'data/QuietRiot_Group_05-24-19.json', 
    'data/ToddRundgren_Person_05-24-19.json',
    'data/Utopia_Group_05-24-19.json'
);

$filenames = $filenames_06;

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
    
    $artistListeners = $artistData['stats']['listeners'];
    $artistPlaycount = $artistData['stats']['playcount'];

    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
    
    if(!$connekt){
        echo '<p>Fiddlesticks! Could not connect to database.</p>';
    }; // Could and should this if statement go outside this for loop?

    $tryInsertArtistData = "INSERT INTO artistsMB (artistMBID, artistNameMB) VALUES ('$artistMBID', '$artistNameMB')";

    $rockin = $connekt->query($tryInsertArtistData);

    if(!$rockin){
        echo 'Could not insert info for ' . $artistNameMB . '.<br>';
        }
        else {
            echo '<p>Inserted ' . $artistNameMB . ' in table.</p>';
        } 

    $insertArtistStats = "INSERT INTO artistsLastFM (artistMBID, dataDate, artistListeners, artistPlaycount) VALUES('$artistMBID','$dataDate','$artistListeners', '$artistPlaycount')";
        
    $rockout = $connekt->query($insertArtistStats);
    
    if(!$rockout){
    echo 'Shickety Brickety! Could not insert stats for ' . $artistNameMB . '.<br>';
    }
    else {
        echo '<p>Inserted ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays for ' . $artistNameMB . ' on ' . $dataDate . '.</p>';
    } 
    
};

?>