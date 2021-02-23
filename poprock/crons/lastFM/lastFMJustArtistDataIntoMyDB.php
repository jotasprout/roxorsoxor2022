<?php

require '../../rockdb.php';

$filenames = array (
    'data/justDaily_07-16-19.json',
    'data/justDaily_07-17-19.json',
    'data/justDaily_07-18-19.json',
    'data/justDaily_07-19-19.json',
    'data/justDaily_07-20-19.json',
    'data/justDaily_07-21-19.json',
    'data/justDaily_07-22-19.json',
    'data/justDaily_07-23-19.json',
    'data/justDaily_07-24-19.json'
);

$filenames = $filenames;

$x = ceil((count($filenames)));

for ($i=0; $i<$x; ++$i) {

    $jsonFile = $filenames[$i];
    $fileContents = file_get_contents($jsonFile);
    $artistData = json_decode($fileContents,true);
    echo "<p> I found " . $jsonFile . ".</p>";
    
    $dataDate = $artistData['date'];
    $artists = $artistData['myArtists'];
    $artistsNum = ceil((count($artists)));
    
    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);
    
    if(!$connekt){
        echo '<p>Fiddlesticks! Could not connect to database.</p>';
    } else {
    
        for ($j=0; $j<$artistsNum; ++$j) {
            $artist = $artists[$j];
            $artistMBID = $artist['mbid'];
            $artistNameMB = $artist['name'];
            $artistListeners = $artist['stats']['listeners'];
            $artistPlaycount = $artist['stats']['playcount'];
    
            $tryInsertArtistData = "INSERT INTO artistsMB (artistMBID, artistNameMB) VALUES ('$artistMBID', '$artistNameMB')";
    
            $rockin = $connekt->query($tryInsertArtistData);
    
            if(!$rockin){
                echo 'Could not insert ' . $artistNameMB . ' into artistsMB table.<br>';
                }
                else {
                    echo '<p>Inserted ' . $artistNameMB . ' in table.</p>';
                }; 
    
            $insertArtistStats = "INSERT INTO artistsLastFM (artistMBID, dataDate, artistListeners, artistPlaycount) VALUES('$artistMBID','$dataDate','$artistListeners', '$artistPlaycount')";
                
            $rockout = $connekt->query($insertArtistStats);
    
            if(!$rockout){
            echo 'Shickety Brickety! Could not insert stats for ' . $artistNameMB . '.<br>';
            }
            else {
                echo '<p>Inserted ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays for ' . $artistNameMB . ' on ' . $dataDate . '.</p>';
            } 
        }
    };
}

?>