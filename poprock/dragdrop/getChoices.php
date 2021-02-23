<?php

require_once '../rockdb.php';
require_once '../data_text/artists_groups.php';
//require_once 'class.artist.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!connekt) {
    echo 'Nope. No connection.';
}

$multiArtistPop = 'SELECT a.artistSpotID, a.artistArt , a.artistName, p.pop, p.date
    FROM artists a
    JOIN popArtists p ON p.artistSpotID = a.artistSpotID
	WHERE a.artistSpotID IN ("' . implode('", "', $group_sabbathRainbow) . '")
    ORDER BY a.artistSpotID ASC';
    
$getem = mysqli_query($connekt, $multiArtistPop);

if(!$getem){
    echo 'Did not run the query.';
}	

if (mysqli_num_rows($getem) > 0) {
    $rows = array();
    while ($row = mysqli_fetch_array($getem)) {
        $rows[] = $row;
        echo "<p>" . $row['artistName'] . "</p>";
    }
    echo json_encode($rows);
}
else {
    echo "Nope. No results.";
}

mysqli_close($connekt);

?>