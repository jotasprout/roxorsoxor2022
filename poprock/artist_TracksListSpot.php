<?php

$artistSpotID = $_GET['artistSpotID'];
$artistMBID = $_GET['artistMBID'];
$source = $_GET['source'];

require_once 'rockdb.php';
require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

$blackSabbath_SpotID = '5M52tdBnJaKSvOpJGz8mfZ';

$gatherTrackInfo = "SELECT v.artistNameSpot, v.trackSpotID, v.trackNumber, v.trackNameSpot, v.albumNameSpot, v.pop, max(v.date) AS MaxDate
					FROM (
						SELECT z.artistNameSpot, z.trackNumber, z.trackSpotID, z.trackNameSpot, z.albumNameSpot, p.date, p.pop
							FROM (
								SELECT t.*, r.albumNameSpot, a.artistNameSpot
									FROM tracksSpot t
									INNER JOIN albumsSpot r ON r.albumSpotID = t.albumSpotID
									JOIN artistsSpot a ON r.artistSpotID = a.artistSpotID
									WHERE a.artistSpotID = '$artistSpotID'
							) z
						JOIN popTracks p 
							ON z.trackSpotID = p.trackSpotID					
					) v
					GROUP BY v.trackSpotID
					ORDER BY v.pop DESC";

$getit = $connekt->query( $gatherTrackInfo );

if ( !$getit ) {
	echo 'Cursed-Crap. Did not run the query.';
}

?>

<!DOCTYPE html>
<html>

<head>
	<meta charset="UTF-8">
	<title>Stats for All Tracks By This Artist</title>
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
						<li class="nav-item active">
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

		<!-- main -->

		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id='panelTitle' class="panel-title">Latest Tracks Stats from My Database</h3>
			</div>
			<div class="panel-body">

				<?php if(!empty($getit)) { ?>
				
<table class="table table-striped table-hover" id="tableotracks">
	<thead>
	<tr>
        <th onClick="sortColumn('trackNumber', 'ASC', '<?php echo $artistSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead">Track #</div></th>
		<th onClick="sortColumn('trackNameSpot', 'ASC', '<?php echo $artistSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead">Track Title</div></th>
        <th>Spotify<br>trackSpotID</th>
        <th onClick="sortColumn('albumNameSpot', 'DESC', '<?php echo $artistSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead">Album Title</div></th>
		
		<th class="popStyle" onClick="sortColumn('pop', 'ASC', '<?php echo $artistSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead">Spotify<br>Popularity</div></th>
		<th>Spotify<br>Data Date</th>
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
							$artistNameSpot = $row[ "artistNameSpot" ];
							$trackNameSpot = $row[ "trackNameSpot" ];
							$trackSpotID = $row[ "trackSpotID" ];
							$trackNumber = $row[ "trackNumber" ];
							$trackPop = $row[ "pop" ];
							$popDate = $row[ "MaxDate" ];
					?>
							<tr>
							    <td><?php echo $trackNumber ?></td>
								<td><?php echo $trackNameSpot ?></td>
                                <td><?php echo $trackSpotID ?></td>
                                <td><?php echo $albumNameSpot ?></td>
                                <!--
								
                                -->
								<td class="popStyle"><?php echo $trackPop ?></td>
								<td><?php echo $popDate ?></td>
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
	const artistNameSpot = '<?php echo $artistNameSpot ?>';
	const panelTitleText = 'Spotify stats for all tracks by ' + artistNameSpot;
	const panelTitle = document.getElementById('panelTitle');
	const docTitleText = 'All ' + artistNameSpot + ' tracks Spotify Stats';
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
		document.title = docTitleText;
	});
</script>
<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>
<script src="https://www.roxorsoxor.com/poprock/functions/sort_artistTracksSpot.js"></script>

</body>
	
</html>