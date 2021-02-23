<?php
require_once '../rockdb.php';

$jsonFile = '../data_text/genres.json';

$fileContents = file_get_contents($jsonFile);
echo $fileContents;
$artistData = json_decode($fileContents,true);
//echo $artistData;
$artistsNum = ceil((count($artistData)));

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if(!$connekt){
    echo '<p>Fiddlesticks! Could not connect to database.</p>';
} else {

    for ($j=0; $j<$artistsNum; ++$j) {
        $source = 'LastFM';
        $artist = $artistData[$j];
        $artistMBID = $artist['mbid'];
        $artistName = $artist['name'];
        $artistGenres = $artist['genres'];
        $genresNum = ceil((count($artistGenres)));
        echo '<p>' . $artistName . ' has ' . $genresNum . ' genres.</p>';

        for ($i=0; $i<$genresNum; ++$i) {
            $genre = $artistGenres[$i]; 
            $insertGenre = "INSERT INTO genresLastFM (artistMBID, genre, source) VALUES('$artistMBID','$genre', '$source')";
            $genreIn = $connekt->query($insertGenre);
    
            if(!$genreIn){
            echo '<p>Could not insert genre for ' . $artistName . '.</p>';
            }
            else {
                echo '<p>Inserted ' . $genre . ' for ' . $artistName . '.</p>';
            };             
        }; 

    };
};

?>