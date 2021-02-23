<?php

require_once '../rockdb.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo '<p>Darn. Did not connect because ' . mysqli_connect_error() . '.</p>';
};

// if any of these did not come through, the defaults are the basic starting sort from the sql query
$artistMBID = "artistMBID";
$artistSpotID = "artistSpotID";
$columnName = "trackNameMB";
$currentOrder = "ASC";
$source = $_POST[ "source" ];

// If POSTed columnNames came through, use them
if ( !empty( $_POST[ "artistSpotID" ] ) ) {
	$artistSpotID = $_POST[ "artistSpotID" ];
}

if ( !empty( $_POST[ "artistMBID" ] ) ) {
	$artistMBID = $_POST[ "artistMBID" ];
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

$trackNumberNewOrder = "DESC";

if ( $columnName == "trackNumber" and $currentOrder == "DESC" ) {
	$trackNumberNewOrder = "ASC";
}

$albumNameNewOrder = "DESC";

if ( $columnName == "albumNameMB" and $currentOrder == "DESC" ) {
	$albumNameNewOrder = "ASC";
}

$trackNameNewOrder = "DESC";

if ( $columnName == "trackNameMB" and $currentOrder == "ASC" ) {
	$trackNameNewOrder = "DESC";
}

$trackRatioNewOrder = "ASC";

if ( $columnName == "trackRatio" and $currentOrder == "ASC" ) {
	$trackRatioNewOrder = "DESC";
}

$gatherTrackInfoLastFM = "SELECT v.artistMBID, v.trackNumber, v.artistNameMB, v.trackMBID, v.trackNameMB, v.albumNameMB, v.trackListeners, v.trackPlaycount, v.trackRatio, max(v.dataDate) AS MaxDataDate
					FROM (
						SELECT z.artistMBID, z.trackMBID, z.trackNameMB, z.trackNumber, z.albumNameMB, z.artistNameMB, p.dataDate, p.trackListeners, p.trackPlaycount, p.trackRatio
							FROM (
								SELECT t.*, r.albumNameMB, a.artistNameMB, a.artistMBID
									FROM tracksMB t
									INNER JOIN albumsMB r ON r.albumMBID = t.albumMBID
									JOIN artistsMB a ON r.artistMBID = a.artistMBID
									WHERE a.artistMBID = '$artistMBID'
							) z
						JOIN tracksLastFM p 
							ON z.trackMBID = p.trackMBID					
					) v
					GROUP BY v.trackMBID
					ORDER BY " . $columnName . " " . $newOrder . ";";

$sortit = $connekt->query( $gatherTrackInfoLastFM );

if ( !$sortit ) {
	echo '<p>Cursed-Crap. Did not run the query because ' . mysqli_error($connekt) . '.</p>';
}

if(!empty($sortit)) { ?>

<table class="table" id="tableotracks">
<thead>
<tr>
<th onClick="sortColumn('trackNumber', '<?php echo $trackNumberNewOrder; ?>', '<?php echo $albumMBID ?>', '<?php echo $source ?>')"><div class="pointyHead">Track #</div></th>
	<th onClick="sortColumn('albumNameMB', '<?php echo $albumNameNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')"><div class="pointyHead">Album Title</div></th>
	<th onClick="sortColumn('trackNameMB', '<?php echo $trackNameNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')"><div class="pointyHead">Track Title</div></th>
    <!--
	<th class="popStyle">LastFM<br>Data Date</th>
    -->
	<th class="rightNum pointyHead" onClick="sortColumn('trackListeners', '<?php echo $popNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')">LastFM<br>Listeners</th>
	<th class="rightNum pointyHead" onClick="sortColumn('trackPlaycount', '<?php echo $popNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')">LastFM<br>Playcount</th>
	<th class="pointyHead popStyle" onClick="sortColumn('trackRatio', '<?php echo $trackRatioNewOrder; ?>', '<?php echo $artistMBID ?>', '<?php echo $source ?>')">LastFM<br>Ratio</th>
</thead>

	<tbody>
	<?php
		while ( $row = mysqli_fetch_array( $sortit ) ) {
			$albumNameMB = $row[ "albumNameMB" ];
			$trackNameMB = $row[ "trackNameMB" ];
			$trackMBID = $row[ "trackMBID" ];
            $trackNumber = $row[ "trackNumber" ];
			$lastFMDate = $row[ "MaxDataDate" ];
			$trackListenersNum = $row["trackListeners"];
			$trackListeners = number_format ($trackListenersNum);

			$trackPlaycountNum = $row["trackPlaycount"];
			$trackPlaycount = number_format ($trackPlaycountNum);
            /**/
            $ppl = $row["trackRatio"];

            if (!$trackPlaycount > 0) {
                $trackPlaycount = "n/a";
                $trackRatio = "n/a";
                $lastFMDate = "n/a";
            } else {
                $trackRatio = "1:" . $ppl;
            };
	?>
			<tr>
                <td><?php echo $trackNumber ?></td>
				<td><?php echo $albumNameMB ?></td>
				<td><?php echo $trackNameMB ?></td>
                <!--
				<td class="popStyle"><?php //echo $lastFMDate ?></td>
                -->
				<td class="rightNum"><?php echo $trackListeners ?></td>
				<td class="rightNum"><?php echo $trackPlaycount ?></td>
				<td class="popStyle"><?php echo $trackRatio ?></td>
			</tr>
	<?php 
		} // end of while
	?>

	</tbody>
</table>
<?php 
	} // end of if
?>