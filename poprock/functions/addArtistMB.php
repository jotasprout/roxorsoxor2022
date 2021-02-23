<?php
session_start();
require '../secrets/spotifySecrets.php';
// require '../secrets/auth.php';
require '../vendor/autoload.php';
require_once '../rockdb.php';
//require_once '../functions/artists.php';
require_once 'py_musicBrainz.php';

$gimmeJSON = file_get_contents($getReleaseGroups_totalURL);

$lemmeSeeJSON = json_decode($gimmeJSON, true);

var_dump($lemmeSeeJSON);

echo "<script>console.log(" . $gimmeJSON . ");</script>";

echo "<script>console.log(" . $lemmeSeeJSON . ");</script>";

/*
function addArtistMB ($thisArtist) {
			
    $connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

    if(!$connekt){
        echo 'Fiddlesticks! Could not connect to database.<br>';
    }

    $artistMBID = $artist->id;
    // echo $artistMBID;
    $artistNameYucky = $artist->name;
    $artistNameMB = mysqli_real_escape_string($connekt,$artistNameYucky);
    // echo $artistName;
    $artistArtMB = $artist->images[0]->url;
    // echo $artistArt;
    $artistPop = $artist->popularity;
    // echo $artistPop;
    $artistInfo = '{"artistMBID": "' . $artistMBID . '", "artistNameMB": "' . $artistNameMB . '", "artistArtMB": "' . $artistArtMB . '", "artistPop": "' . $artistPop . '"}';

    $insertArtistsInfo = "INSERT INTO artistsMB (artistMBID, artistNameMB, artistArtMB) VALUES('$artistMBID', '$artistNameMB', '$artistArtMB')";

    $rockout = $connekt->query($insertArtistsInfo);

    if(!$rockout){
        echo 'Cursed-Crap. Could not insert artist ' . $artistNameMB . '.<br>';
    }
    
    $insertArtistsPop = "INSERT INTO popArtists (artistMBID,pop) VALUES ('$artistMBID','$artistPop')";
    
    $rockpop = $connekt->query($insertArtistsPop);
    
    if(!$rockpop){
        echo 'Cursed-Crap. Could not insert artists popularity.';
    } else {
        echo json_encode($artist);
    };
};

addArtistMB ($artistMBID);
*/
die();

?>