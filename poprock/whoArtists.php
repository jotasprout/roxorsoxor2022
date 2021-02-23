<?php

require 'secrets/spotifySecrets.php';
require 'vendor/autoload.php';

$session = new SpotifyWebAPI\Session($myClientID, $myClientSecret);

$session->requestCredentialsToken();
$accessToken = $session->getAccessToken();

// Store access token 
$_SESSION['accessToken'] = $accessToken;

$GLOBALS['api'] = new SpotifyWebAPI\SpotifyWebAPI();
$GLOBALS['api']->setAccessToken($accessToken);

$mysteryArtists = array ("06nsZ3qSOYZ2hPVIMcr1IN", "0AKXyxpM8OTfV5xlJkWhhh", "0FI0kxP0BWurTz8cB8BBug", "0FRIWJYklnmsll5M6h4gUL", "0yV8woUT2cpAkxkBNfXoin", "1coQ4GcxuazfjZ0MP9JnBF", "1dfUmPiIel95MFXvQMUAED", "1eKucUW0u8DXDBkNCewl0m", "1I5Cu7bqjkRg85idwYsD91", "1nJvji2KIlWSseXRSlNYsC", "1RpIWJHxLsDE8YfFcRaBKw", "1WOIkzm2dYpAqf5FZf5ziQ", "2bmixwMZXlkl2sbIbOfviq", "2Ex4vjQ6mSh5woTlDWto6d", "2ScuQMRWThcifBRIvNDFDC", "375zxMmh2cSgUzFFnva0O7", "3fo31cpxTYmcMT3m4A1RNC", "3rxIQc9kWT6Ueg4BhnOwRK", "3Y8HQ2Val8XhZg7RxyCfqH", "3YowTUlFJJA6E5Yd67GZNv", "43YDeIUqhR3Y5rRPSH6AOt", "4fteWGGNzyj9p8TnMQwcOA", "4kGt8nIQNBkCRusp4sJ3RX", "4kHtgiRnpmFIV5Tm4BIs8l", "55bGuHb50r5c0PeqqMeNBV", "5RNFFojXkPRmlJZIwXeKQC", "69VgCcXFV59QuQWEXSTxfK", "6gABJRqeRV4XW6T8vP9QEn", "6IRouO5mvvfcyxtPDKMYFN", "73ndLgs6jSrpZzjyzU9TJV", "7Hf9AwMO37bSdxHb0FBGmO");

function whoAreTheseArtists ($mysteryArtists) {

    $totalArtists = count($mysteryArtists);

    // Divide all artists into chunks of 50
    $artistsChunk = array ();
    $x = ceil((count($mysteryArtists))/50);

    $firstArtist = 0;

    for ($i=0; $i<$x; ++$i) {
        $lastArtist = 49;
        $artistsChunk = array_slice($mysteryArtists, $firstArtist, $lastArtist);
        // put chunks of 50 into an array
        $artistsArrays [] = $artistsChunk;
        $firstArtist += 50;
    };

    for ($i=0; $i<(count($artistsArrays)); ++$i) {
                
        $artistsThisTime = count($artistsArrays[$i]);

        $artistSpotIDs = implode(',', $artistsArrays[$i]);

        // For each array of artists (50 at a time), "get several artists"
        $bunchofartists = $GLOBALS['api']->getArtists($artistSpotIDs);
            
        foreach ($bunchofartists->artists as $artist) {

            $artistSpotID = $artist->id;
            $artistNameYucky = $artist->name;
            $artistName = mysqli_real_escape_string($connekt,$artistNameYucky);
            $artistArt = $artist->images[0]->url;
            $artistPop = $artist->popularity;
            $artistFollowers = $artist->followers->total;
            $jsonArtistGenres = $artist->genres;

                echo '<p><img src="' . $artistArt . '"><br>' . $artistSpotID . '<br>' . $artistName . '<br>' . $artistNameYucky . '<br><b>Popularity:</b> ' . $artistPop . '<br><b>Followers:</b> ' . $artistFollowers . '</p>';
            } 

    };	
}

whoAreTheseArtists($mysteryArtists);

die();

?>