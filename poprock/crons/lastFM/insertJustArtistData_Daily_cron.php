<?php
require 'rockdb.php';

$baseURL = '/home/roxorsox/public_html/poprock/crons/lastFM/data/justDaily_';
$today = date("m-d-y");
$endURL = '.json';
$filenameForDisplay = $baseURL . $today . $endURL;
echo "<p>" . $filenameForDisplay . "</p>"; 
$filename = $baseURL . $today . $endURL;
echo $filename;  

$jsonFile = $filename;
$jsonFileForDisplay = $jsonFile;
echo "<p> I found " . $jsonFileForDisplay . ".</p>";

$fileContents = file_get_contents($jsonFile);
$artistData = json_decode($fileContents,true);

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
        $artistRatio = $artistPlaycount/$artistListeners;
        /**/
        $tryInsertArtistData = "INSERT INTO artistsMB (artistMBID, artistNameMB) VALUES ('$artistMBID', '$artistNameMB')";

        $rockin = $connekt->query($tryInsertArtistData);

        if(!$rockin){
            echo 'Could not insert ' . $artistNameMB . ' into artistsMB table.<br>';
        } else {
                echo '<p>Inserted ' . $artistNameMB . ' in table.</p>';
        }; 
        
        $insertArtistStats = "INSERT INTO artistsLastFM (artistMBID, dataDate, artistListeners, artistPlaycount, artistRatio) VALUES('$artistMBID','$dataDate','$artistListeners', '$artistPlaycount', '$artistRatio')";
            
        $rockout = $connekt->query($insertArtistStats);

        if(!$rockout){
        echo 'Shickety Brickety! Could not insert stats for ' . $artistNameMB . '.<br>';
        } else {
            echo '<p>Inserted ' . $artistListeners . ' listeners and ' . $artistPlaycount . ' plays for ' . $artistNameMB . ' on ' . $dataDate . '.</p>';
        } 
    }
};

?>