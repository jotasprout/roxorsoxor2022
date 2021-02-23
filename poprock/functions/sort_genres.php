<?php

require_once '../rockdb.php';

//$artistArtMBFilepath = "https://www.roxorsoxor.com/poprock/artist-art/";

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
    echo '<p>Darn. Did not connect. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};

$postedColumnName = $_POST[ "columnName" ];
$postedCurrentOrder = $_POST[ "currentOrder" ];
//$postedSource = $_POST[ "source" ];

// if any POSTed variables did not come through, these defaults were basic starting sort from original sql query
$columnName = "artistName";
$currentOrder = "ASC";
//$source="spotify";

if ( !empty( $_POST[ "columnName" ] ) ) {
    // if the column name came through, use it
	$columnName = $_POST[ "columnName" ];
}

if ( !empty( $_POST[ "currentOrder" ] ) ) {
    // if the current order came through, use it
	$currentOrder = $_POST[ "currentOrder" ];
}

$artistNameNewOrder = "unsorted";

if ( $columnName == "artistName" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "DESC") {
		$artistNameNewOrder = "ASC";
		$newOrder = "ASC";
	} else {
		$artistNameNewOrder = "DESC";
		$newOrder = "DESC";
	};
};

$genreNewOrder = "unsorted";

if ( $columnName == "genre" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "ASC") {
		$genreNewOrder = "DESC";
		$newOrder = "DESC";
	} else {
		$genreNewOrder = "ASC";
		$newOrder = "ASC";
	};
};

$newGenresQuery = "SELECT g.*, s.artistNameSpot, m.artistNameMB
					FROM genres g
					LEFT JOIN artistsSpot s ON s.artistSpotID = g.artistID
					LEFT JOIN artistsMB m ON m.artistMBID = g.artistID
                    ORDER BY " . $columnName . " " . $newOrder . ";";

$sortit = $connekt->query($newGenresQuery); 


if(!$sortit){ 
	echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};

if (!empty($sortit)) { ?>

<table class="table" id="tableoartists">
<thead>
<tr>
    <!-- 
    <th>Pretty Face</th>	
    -->
    <th>Table ID</th>	
    <th onClick="sortColumn('artistName', '<?php echo $artistNameNewOrder; ?>')"><div class="pointyHead">Artist Name</div></th>
    <th onClick="sortColumn('genre', '<?php echo $genreNewOrder; ?>')"><div class="pointyHead">Genre</div></th>
    <th><div class="popStyle">Source</div></th>
</tr>
</thead>

<tbody>

    <?php
        while ( $row = mysqli_fetch_array( $getit ) ) {
            $rowID = $row["id"];
            $artistID = '';
            //$artistArtSpot = $row[ "artistArtSpot" ];
            //$artistArtMBFilename = $row[ "artistArtMBFilename" ];
            $artistName = '';
            $artistNameSpot = $row[ "artistNameSpot" ];
            $artistNameMB = $row[ "artistNameMB" ];
            $genre = $row["genre"];
            $genreSource = $row["genreSource"];
            if ($genreSource = "spotify") {
                //$artistArt = $artistArtSpot;
                $artistName = $artistNameSpot;
            } else {
                //$artistArt = $artistArtMBFilepath . $artistArtMBFilename;
                $artistName = $artistNameMB;
            };          
    ?>

		<tr>
		<!--  -->
        <td><?php echo $rowID ?></td>
		    <td><img src='<?php echo $artistArt ?>' height='64' width='64'></td>	
		
			<td><?php echo $artistName ?></td>
			<td><a href='https://www.roxorsoxor.com/poprock/genreArtists_popCurrentBars.php?artistGenre=<?php echo $genre ?>'><?php echo $genre ?></a></td>
            <td class="popStyle"><?php echo $genreSource ?></td>
			<!--  -->
		</tr>

		<?php 
			} // end of while
		?>

		</tbody>
	</table>
<?php 
	} // end of if
?>
