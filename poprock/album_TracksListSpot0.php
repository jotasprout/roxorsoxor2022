<?php

$artistSpotID = $_GET['artistSpotID'];
$albumSpotID = $_GET['albumSpotID'];
$artistMBID = $_GET['artistMBID'];

require_once 'rockdb.php';

require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
} else {
    echo 'Apparently connected to DB.';
};

$liveEvil_albumSpotID = '1Uq7JKrKGGYCkg6l79gkoa';
$crossPurposes_albumMBID = '5d2e8936-8c36-3ccd-8e8f-916e3b771d49';
$thirteen_SpotID = '46fDgOnY2RavytWwL88x5M';
$thirteen_MBID = '7dbf4b1f-d3e9-47bc-9194-d15b31017bd6';

$getAlbumTracks = "SELECT v.trackSpotID, v.trackNameSpot, v.trackNumber, v.albumNameSpot, v.pop, max(v.date) AS MaxDate
	FROM (
		SELECT z.trackSpotID, z.trackNameSpot, z.trackNumber, r.albumNameSpot, p.date, p.pop
			FROM (
				SELECT t.trackSpotID, t.trackNameSpot, t.albumSpotID, t.trackNumber
					FROM tracksSpot t
					WHERE t.albumSpotID = '$albumSpotID'
			) z
		INNER JOIN albumsSpot r 
			ON r.albumSpotID = z.albumSpotID
		JOIN popTracks p 
			ON z.trackSpotID = p.trackSpotID					
	) v
	GROUP BY v.trackSpotID";
/*
if ($source = 'spotify') {
	$getAlbumTracks = $SpotAndLastFM;
};

if ($source = 'musicbrainz') {
	$getAlbumTracks = $LastFMAndSpot;
};
*/
$getit = $connekt->query( $getAlbumTracks );

if ( !$getit ) {
	echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>This Album's Tracks Popularity On Spotify</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

	<div class="container-fluid">
	<div id="fluidCon"></div> <!-- end of fluidCon -->

  <!-- Breadcrumbs start -->
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../index.php">Spotify Artists</a></li>
    <li class="breadcrumb-item"><a href="../artist_ChartsSpot.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID; ?>"><?php echo $artistNameSpot; ?></a></li>
    <li class="breadcrumb-item active"> Edit <?php echo $albumNameSpot; ?></li>
</ol>
<!-- Breadcrumbs end -->


		<!-- main -->
    <a role="button" class="btn btn-warning btn-sm" href="forms/edit_AlbumSpot.php?albumSpotID=<?php echo $albumSpotID ?>">Edit this Album</a>
    
    <a role="button" class="btn btn-success btn-sm" href="forms/add_albumAssocArtist.php?albumSpotID=<?php echo $albumSpotID ?>">Add Associated Artist</a>	        
		<!-- -->
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id="panelTitle" class="panel-title">This Album's Tracks Popularity On Spotify</h3>
			</div>
			<div class="panel-body">

				<?php if(!empty($getit)) { ?>
				
<table class="table table-striped table-hover" id="tableotracks">
<thead>
<tr>
<!---->
<th onClick="sortColumn('trackNumber', 'ASC')"><div class="pointyHead">Track #</div></th>
<th>Track<br>Spotify ID</th>

<th onClick="sortColumn('trackNameSpot', 'DESC', '<?php echo $albumSpotID ?>', 'spotify')"><div class="pointyHead">Track Title</div></th>
<th class="popStyle">Spotify<br>Data Date</th>
<th class="popStyle" onClick="sortColumn('pop', 'ASC', '<?php echo $albumSpotID ?>', 'spotify')"><div class="pointyHead">Track<br>Popularity</div></th>
<!--
<th class="popStyle">LastFM<br>Data Date</th>
<th class="rightNum pointyHead">LastFM<br>Listeners</th>
<th class="rightNum pointyHead">LastFM<br>Playcount</th>
-->
</tr>
</thead>
	
	<tbody>
	<?php
		while ( $row = mysqli_fetch_array( $getit ) ) {
			$albumNameSpot = $row[ "albumNameSpot" ];
			$trackNameSpot = $row[ "trackNameSpot" ];
			$trackNumber = $row[ "trackNumber" ];
			$trackSpotID = $row[ "trackSpotID" ];		

			$trackPop = $row[ "pop" ];
			//echo "<p>trackPop is " . $trackPop . ".</p>";
			if ($trackPop == '') {
				$trackPop = "n/a";				
			};	

			$popDate = $row[ "MaxDate" ];
			if ($popDate == '') {
				$popDate = "n/a";				
			};
	?>
<tr>

<!---->
<td><?php echo $trackNumber ?></td>
<td><?php echo $trackSpotID ?></td>
<td><?php echo $trackNameSpot ?></td>
<td class="popStyle"><?php echo $popDate ?></td>
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
			</div> <!-- panel body -->
		</div> <!-- panel panel-primary -->
	</div> <!-- closing container -->
	
<?php echo $scriptsAndSuch; ?>

<script>
/**/
	const albumNameSpot = '<?php echo $albumNameSpot ?>';
	const panelTitleText = 'Popularity on Spotify for tracks from <em>' + albumNameSpot + '</em>';
	const panelTitle = document.getElementById('panelTitle');
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
	});

</script>

<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>

<script src="https://www.roxorsoxor.com/poprock/functions/sort_albumTracksSpot.js"></script>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>
	
</html>