<?php

require_once '../rockdb.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo '<p>Darn. Did not connect. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};

$newGenresQuery = "SELECT g.*, s.artistNameSpot, m.artistNameMB, m.artistMBID, s.artistSpotID
					FROM genres g
					LEFT JOIN artistsSpot s ON s.artistSpotID = g.artistID
					LEFT JOIN artistsMB m ON m.artistMBID = g.artistID
					ORDER BY g.artistID ASC;";

$getit = $connekt->query( $newGenresQuery );

if(!$getit){ 
	echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($getit) . '</p>';
}	

if (!empty($getit)) { 

    $rows = array ();

    while ( $row = mysqli_fetch_array( $getit ) ) {
        $rows[] = $row;

        //$rowID = $row["id"];
        $artistMBID = $row[ "artistMBID" ];
        $artistSpotID = $row[ "artistSpotID" ];
        $artistID = '';
        $artistName = '';
        $artistNameSpot = $row[ "artistNameSpot" ];
        $artistNameMB = $row[ "artistNameMB" ];
        $genre = $row["genre"];
        $genreSource = $row["genreSource"];
        if ($genreSource == "Spotify") {
            $artistID = $artistSpotID;
            $artistName = $artistNameSpot;
        } else {
            $artistID = $artistMBID;
            $artistName = $artistNameMB;
        };    
        
        
        $gatherNameAndGenre = "INSERT INTO genresNames (
            artistID,
            artistName,
            genre,
            genreSource
        ) 
        VALUES(
            '$artistID',
            '$artistName',
            '$genre',
            '$genreSource'
        )";

        $insertNameAndGenre = $connekt->query($gatherNameAndGenre);

        if(!$insertNameAndGenre){
            echo '<p>Could not insert genre for <b>' . $artistName . '</b> into namesGenres.</p>';
        } else {
            echo '<p>Inserted <b>' . $genre . '</b> genre for <i>' . $artistName . '</i> into namesGenres.</p>';
        };
        

    } // end of while
    echo json_encode($rows);

} // end of if
else {
	echo "Nope. Nothing to see here. Screwed up like this: " . mysqli_error($insertNameAndGenre) . "</p>";
}

mysqli_close($connekt);

?>