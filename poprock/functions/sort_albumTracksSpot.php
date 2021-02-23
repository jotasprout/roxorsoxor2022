<?php

require_once '../rockdb.php';
//require_once '../page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo '<p>Darn. Did not connect because ' . mysqli_connect_error() . '.</p>';
} else {
    echo '<p>Apparently connected.</p>';
};

// if any of these did not come through, the defaults are the basic starting sort from the sql query
$artistMBID = "artistMBID";
$artistSpotID = "artistSpotID";
$albumSpotID = "albumSpotID";
$columnName = "trackNameSpot";
$currentOrder = "ASC";
$source = $_POST[ "source" ];

// If POSTed columnNames came through, use them
if ( !empty( $_POST[ "artistSpotID" ] ) ) {
	$artistSpotID = $_POST[ "artistSpotID" ];
}

if ( !empty( $_POST[ "artistMBID" ] ) ) {
	$artistMBID = $_POST[ "artistMBID" ];
}

if ( !empty( $_POST[ "albumSpotID" ] ) ) {
	$albumSpotID = $_POST[ "albumSpotID" ];
}

if ( !empty( $_POST[ "columnName" ] ) ) {
	$columnName = $_POST[ "columnName" ];
}

if ( !empty( $_POST[ "currentOrder" ] ) ) {
	$currentOrder = $_POST[ "currentOrder" ];
}

// Toggle sorting order

if ( $currentOrder == "DESC" ) {
	$newOrder = "ASC";
}

if ( $currentOrder == "ASC" ) {
	$newOrder = "DESC";
}

$albumNameNewOrder = "DESC";

if ( $columnName == "albumNameSpot" and $currentOrder == "DESC" ) {
	$albumNameNewOrder = "ASC";
}

$trackNameSpotNewOrder = "DESC";

if ( $columnName == "trackNameSpot" and $currentOrder == "ASC" ) {
	$trackNameSpotNewOrder = "DESC";
}

$popNewOrder = "ASC";

if ( $columnName == "pop" and $currentOrder == "ASC" ) {
	$popNewOrder = "DESC";
}

$trackNumberNewOrder = "ASC";

if ( $columnName == "trackNumber" and $currentOrder == "ASC" ) {
	$trackNumberNewOrder = "DESC";
}

$gatherTrackInfoSpot = "SELECT v.trackSpotID, v.trackNameSpot, v.trackNumber, v.albumNameSpot, v.pop, max(v.date) AS MaxDate
					FROM (
						SELECT z.trackSpotID, z.trackNameSpot, z.trackNumber, z.albumNameSpot, p.date, p.pop
							FROM (
								SELECT t.*, r.albumNameSpot
									FROM tracksSpot t
									INNER JOIN albumsSpot r ON r.albumSpotID = t.albumSpotID
									WHERE t.albumSpotID = '$albumSpotID'
							) z
						JOIN popTracks p 
							ON z.trackSpotID = p.trackSpotID					
					) v
					GROUP BY v.trackSpotID
					ORDER BY " . $columnName . " " . $newOrder . ";";

$sortit = $connekt->query( $gatherTrackInfoSpot );

if ( !$sortit ) {
	echo '<p>Cursed-Crap. Did not run the query because ' . mysqli_error($connekt) . '.</p>';
}

if(!empty($sortit)) { ?>

<table class="table" id="tableotracks">
<thead>
<tr>
<th>Date</th>
<th onClick="sortColumn('trackNameSpot', '<?php echo $trackNameSpotNewOrder; ?>', '<?php echo $albumSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead">Track Title</div></th>

<th class="popStyle" onClick="sortColumn('pop', '<?php echo $popNewOrder; ?>', '<?php echo $albumSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead">Spotify<br>Popularity</div></th>

</tr>
</thead>

	<tbody>
	<?php
		while ( $row = mysqli_fetch_array( $sortit ) ) {
			$albumNameSpot = $row[ "albumNameSpot" ];
			$trackNameSpot = $row[ "trackNameSpot" ];
			$trackSpotID = $row[ "trackSpotID" ];
			$trackPop = $row[ "pop" ];
			$popDate = $row[ "MaxDate" ];
	?>
			<tr>
			<td><?php echo $popDate ?></td>
				<td><?php echo $trackNameSpot ?></td>
				<td class="popStyle"><?php echo $trackPop ?></td>
			
			</tr>
	<?php 
		} // end of while
	?>

	</tbody>
</table>
<?php 
	} // end of if
?>