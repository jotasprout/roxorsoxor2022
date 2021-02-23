<?php

require '../secrets/auth.php';
require_once '../rockdb.php';
require_once '../functions/albums.php';
require '../functions/artists.php';
require '../data_text/artists_arrays.php';
/*
$session = new SpotifyWebAPI\Session($myClientID, $myClientSecret);

$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();

$accessToken = $_SESSION['accessToken'];
// Does the cron needs this next line?
$_SESSION['accessToken'] = $accessToken;


$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);
*/
$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

$coverArtArchiveBaseURL = 'https://coverartarchive.org/release/';

if (!$connekt) {
    echo '<p>Darn. Did not connect.</p>';
} else {

    $getAlbumMBID = "SELECT albumMBID, albumName FROM albumsMB WHERE artistMBID='e491fae8-3a5a-438e-8368-925753fb41a1'";

    $getit = $connekt->query($getAlbumMBID);

    if(!$getit){
        echo '<p>Poop. Could not do the query thing.</p>';
    } else {

        while ($row = mysqli_fetch_array($getit)) {

            $albumName = $row['albumName'];
            $albumMBID = $row['albumMBID'];
            $coverArtURL = $coverArtArchiveBaseURL . $albumMBID;
            $fileContents = file_get_contents($coverArtURL);
            $imageData = json_decode($fileContents, true);
            $imageURL = $imageData['images'][0]['image'];

            $insertCoverArt = "INSERT INTO albumsMB (albumArtMB) VALUES ('$imageURL')";
            $insertit = $connekt->query($insertCoverArt);
            if(!$insertit){
                echo '<p>Shoot. Could not do the inserty thing with <i>' . $albumName . '</i>.</p>';
                echo '<p>' . $coverArtURL . '</p>';
                echo '<p>' . $imageURL . '</p>';
                echo '<p>Cover art for <i>' . $albumName . '</i>.</p><p><img src="' . $imageURL . '"></p>'; 
            } else {
                echo '<p>Cover art for <i>' . $albumName . '</i>.</p><p><img src="' . $imageURL . '"></p>';
                echo '<p>' . $coverArtURL . '</p>';
                echo '<p>' . $imageURL . '</p>';
            }

        };
    
    }
}


die();

?>