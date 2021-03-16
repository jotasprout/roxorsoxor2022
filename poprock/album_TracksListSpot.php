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

	<div id="fluidCon">
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

				<a class="navbar-brand" href="#">PopRock</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarColor01">

					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/index.php'>Artists<br>Spotify
							<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/indexLastFM.php'>Artists<br>LastFM</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_AlbumsListSpot.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=spotify'>Albums<br>Spotify</a>
						</li>  
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_AlbumsListLastFM.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=musicbrainz'>Albums<br>LastFM</a>
						</li>                                                
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_TracksListSpot.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=spotify'>Tracks<br>Spotify</a>
						</li>  
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_TracksListLastFM.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=musicbrainz'>Tracks<br>LastFM</a>
						</li>   

						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_albumsChart.php'>Related<br>Artists</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_popTimeLines.php'>Over Time<br>Popularity</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_popCurrentColumns.php'>Current<br>Popularity</a>
						</li>				
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_followersCurrentColumns.php'>Current<br>Followers</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/genres/artistsGenres.php'>Genres</a>
						</li>		
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/dragdrop/dragDropArtists.php'>Drag Drop</a>
						</li>			
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/rels.php'>Network</a>
						</li>
													
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Scatter</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href='https://roxorsoxor.com/poprock/scattercovers.php'>Black Sabbath</a>
								<a class="dropdown-item" href='https://roxorsoxor.com/poprock/scattercovers2.php'>Ronnie James Dio</a>
								<a class="dropdown-item" href='https://roxorsoxor.com/poprock/scattercovers3.php'>Dio</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</li>
					</ul>

				</div> <!-- end of collapse -->
			</nav> <!-- end of navbar -->		
		</div> <!-- end of fluidCon -->

  <!-- Breadcrumbs start -->
  <ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../index.php">Spotify Artists</a></li>
    <li class="breadcrumb-item"><a href="../artist_ChartsSpot.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID; ?>"><?php echo $artistNameSpot; ?></a></li>
    <li class="breadcrumb-item active"> Edit <?php echo $albumNameSpot; ?></li>
</ol>
<!-- Breadcrumbs end -->


		<!-- main -->
        
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

</body>
	
</html>