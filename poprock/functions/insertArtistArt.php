<?php
require_once '../rockdb.php';

$jsonFile = '../data_text/artistArtGallery.json';
$fileContents = file_get_contents($jsonFile);
$artistData = json_decode($fileContents,true);
$artistsNum = ceil((count($artistData)));

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if(!$connekt){
    echo '<p>Fiddlesticks! Could not connect to database.</p>';
} else {

    for ($j=0; $j<$artistsNum; ++$j) {
        $artist = $artistData[$j];
        $artistMBID = $artist['mbid'];
        $artistName = $artist['name'];
        $artistPrettyFace = $artist['prettyFace'];

        $insertFace = "UPDATE artistsMB SET artistArtMB = '$artistPrettyFace' WHERE artistMBID = '$artistMBID';";

        $faceIn = $connekt->query($insertFace);

        if(!$faceIn){
        echo '<p>Could not insert face for ' . $artistName . '.</p>';
        }
        else {
            echo '<p>Inserted this face for ' . $artistName . ':<br><img src="' . $artistPrettyFace . '"></p>';
        };             

    };
};

?>