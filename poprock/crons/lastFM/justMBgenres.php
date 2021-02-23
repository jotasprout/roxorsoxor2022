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
        $MBgenres = $artist['MBgenres'];
        $MBgenresNum = ceil((count($MBgenres)));
        if ($MBgenresNum > 0) {
            for ($g=0; $g<$MBgenresNum; ++$g) {
                $genre = $MBgenres[$g]['name'];
                $insertMBgenres = "INSERT INTO genresMB (
                    artistMBID,
                    genre,
                    source
                )
                VALUES (
                    '$artistMBID',
                    '$genre',
                    'musicbrainz'
                )";

                $rockover = $connekt->query($insertMBgenres);

                if(!$rockover){
                echo 'Shickety Brickety! Could not insert MB genres for ' . $artistNameMB . '.<br>';
                } else {
                    echo '<p>Inserted ' . $genre . ' into genresMB for ' . $artistNameMB . '.</p>';
                } 
            }
        }

    }
};

?>