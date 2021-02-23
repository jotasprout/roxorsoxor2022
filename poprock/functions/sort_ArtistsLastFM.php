<?php

require_once '../rockdb.php';


$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
    echo '<p>Darn. Did not connect. Screwed up like: ' . mysqli_connect_error() . '.</p>';
};

$postedColumnName = $_POST[ "columnName" ];
$postedCurrentOrder = $_POST[ "currentOrder" ];

// if any POSTed variables did not come through, these defaults were basic starting sort from original sql query
$columnName = "artistNameMB";
$currentOrder = "ASC";

if ( !empty( $_POST[ "columnName" ] ) ) {
    // if the column name came through, use it
	$columnName = $_POST[ "columnName" ];
}

if ( !empty( $_POST[ "currentOrder" ] ) ) {
    // if the current order came through, use it
	$currentOrder = $_POST[ "currentOrder" ];
}

///////////////////

$artistNameNewOrder = "unsorted";

if ( $columnName == "artistNameMB" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "DESC") {
		$artistNameNewOrder = "ASC";
		$newOrder = "ASC";
	} else {
		$artistNameNewOrder = "DESC";
		$newOrder = "DESC";
	};
};

///////////////////

$datadateNewOrder = "unsorted";

if ( $columnName == "datadate" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "ASC") {
		$datadateNewOrder = "DESC";
		$newOrder = "DESC";
	} else {
		$datadateNewOrder = "ASC";
		$newOrder = "ASC";
	};
};

$listenersNewOrder = "unsorted";

if ( $columnName == "artistListeners" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "ASC") {
		$listenersNewOrder = "DESC";
		$newOrder = "DESC";
	} else {
		$listenersNewOrder = "ASC";
		$newOrder = "ASC";
	};
};

$playcountNewOrder = "unsorted";

if ( $columnName == "artistPlaycount" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "ASC") {
		$playcountNewOrder = "DESC";
		$newOrder = "DESC";
	} else {
		$playcountNewOrder = "ASC";
		$newOrder = "ASC";
	};
};

$artistRatioNewOrder = "unsorted";

if ( $columnName == "artistRatio" ) {
	if ($currentOrder == "unsorted" or $currentOrder == "ASC") {
		$artistRatioNewOrder = "DESC";
		$newOrder = "DESC";
	} else {
		$artistRatioNewOrder = "ASC";
		$newOrder = "ASC";
	};
};

$allthatAndLastFM = "SELECT a.artistMBID AS artistMBID, a.artistArtMBFilename AS artistArtMBFilename, a.artistNameMB AS artistNameMB, f1.dataDate AS dataDate, f1.artistListeners AS artistListeners, f1.artistPlaycount AS artistPlaycount, f1.artistRatio AS artistRatio
    FROM artistsMB a
    JOIN (SELECT f.*
			FROM artistsLastFM f
			INNER JOIN (SELECT artistMBID, artistListeners, artistPlaycount, artistRatio, max(dataDate) AS MaxDataDate
						FROM artistsLastFM  
						GROUP BY artistMBID) groupedf
			ON f.artistMBID = groupedf.artistMBID
			AND f.dataDate = groupedf.MaxDataDate) f1
	ON a.artistMBID = f1.artistMBID
	ORDER BY " . $columnName . " " . $newOrder . ";";	

$sortit = $connekt->query($allthatAndLastFM); 

if (!$sortit) {
    echo '<p>Darn. No query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
};

if (!empty($sortit)) { ?>

<table class="table" id="tableoartists">
<thead>
	<tr>
	<th>Pretty Face</th>	
	<th onClick="sortColumn('artistNameMB', '<?php echo $artistNameNewOrder; ?>')"><div class="pointyHead">Artist Name</div></th>
	<th>MBID</th>
	<!--
	-->
	<th onClick="sortColumn('datadate', '<?php echo $datadateNewOrder; ?>')"><div class="pointyHead popStyle">LastFM<br>Data Date</div></th>
	<th onClick="sortColumn('artistListeners', '<?php echo $listenersNewOrder; ?>')"><div class="pointyHead rightNum">LastFM<br>Listeners</div></th>
	<th onClick="sortColumn('artistPlaycount', '<?php echo $playcountNewOrder; ?>')"><div class="pointyHead rightNum">LastFM<br>Playcount</div></th>
	<th onClick="sortColumn('artistRatio', 'unsorted')"><div class="pointyHead popStyle">LastFM<br>Ratio</div></th>
	</tr>
</thead>

		<tbody>

		<?php
			while ($row = mysqli_fetch_array($sortit)) {
				$artistNameMB = $row[ "artistNameMB" ];
				$artistMBID = $row[ "artistMBID" ];
                $artistArtFilename = $row['artistArtMBFilename'];
                $artistArtMBFilepath = "https://www.roxorsoxor.com/poprock/artist-art/";
                
                
                if(empty($row["artistArtMBFilename"]) && empty($row["artistArtSpot"])) {
                    $artistArt = "nope.png";
                }
                elseif (empty($row["artistArtMBFilename"]) && !empty($row["artistArtSpot"])) {
                    $artistArt = $artistArtSpot;
                }	
                else {
                    $artistArt = $artistArtMBFilepath . $artistArtFilename;
                };
				$lastFMDate = $row[ "dataDate" ];
				$artistListenersNum = $row[ "artistListeners"];
				$artistListeners = number_format ($artistListenersNum);
				$artistPlaycountNum = $row[ "artistPlaycount"];
				$artistPlaycount = number_format ($artistPlaycountNum);
                /**/
                $ppl = $row["artistRatio"];
                
                if (!$artistPlaycount > 0) {
                    $artistPlaycount = "n/a";
                    $artistRatio = "n/a";
                    $lastFMDate = "n/a";
                } else {
                    $artistRatio = "1:" . $ppl;
                };
		?>

<tr>
<td><a href='https://www.roxorsoxor.com/poprock/artist_ChartsLastFM.php?artistMBID=<?php echo $artistMBID ?>&artistSpotID=<?php echo $artistSpotID ?>'><img src='<?php echo $artistArt ?>' class="indexArtistArt"></a></td>	
	<td><a href='https://www.roxorsoxor.com/poprock/artist_ChartsLastFM.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>&source=<?php echo $source ?>'><?php echo $artistNameMB ?></a></td>
<!---->
<td><?php echo $artistMBID ?></td>
	<td class="popStyle"><?php echo $lastFMDate ?></td>
	<td class="rightNum"><?php echo $artistListeners ?></td>
	<td class="rightNum"><?php echo $artistPlaycount ?></td>
	<td class="popStyle"><?php echo $artistRatio ?></td>
</tr>

		<?php 
			} // end of while
		?>

		</tbody>
	</table>
<?php 
	} // end of if
?>
