<?php

$artistSpotID = $_GET['artistSpotID'];
$artistMBID = $_GET['artistMBID'];
$source = $_GET['source'];
require_once 'rockdb.php';
require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo '<p>Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
};

$blackSabbath_SpotID = '5M52tdBnJaKSvOpJGz8mfZ';
$blackSabbath_MBID = '5182c1d9-c7d2-4dad-afa0-ccfeada921a8';

$blackScabies = "SELECT b.albumNameSpot, b.albumSpotID, b.yearReleased, z.artistNameSpot, p1.date, p1.pop, x.tracksTotal, x.albumArtSpot
					FROM (SELECT sp.albumNameSpot, sp.albumSpotID, sp.artistSpotID, sp.yearReleased
							FROM albumsSpot sp
							WHERE sp.artistSpotID='$artistSpotID') b
					JOIN artistsSpot z ON z.artistSpotID = b.artistSpotID
					LEFT JOIN albumsSpot x ON b.albumSpotID = x.albumSpotID	
					LEFT JOIN (SELECT p.* 
							FROM popAlbums p
							INNER JOIN (SELECT albumSpotID, pop, max(date) AS MaxDate
										FROM popAlbums  
										GROUP BY albumSpotID) groupedp
										ON p.albumSpotID = groupedp.albumSpotID
										AND p.date = groupedp.MaxDate) p1 
					ON b.albumSpotID = p1.albumSpotID
					ORDER BY p1.pop DESC;";

$getit = $connekt->query($blackScabies);

if(!$getit){
	echo '<p>Cursed-Crap. Did not run the query. Screwed up like this: ' . mysqli_error($connekt) . '</p>';
}

?>
<!doctype html>
<html>
	
<head>
	<meta charset="UTF-8">
	<title>This Artist's Albums</title>
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
<!--
<p>If, after the page loads, it is empty, or the wrong discography displays, <a href='https://www.roxorsoxor.com/poprock/index.php'>choose an artist</a> from the <a href='https://www.roxorsoxor.com/poprock/index.php'>Artists List</a> first.</p>
-->
<div class="panel panel-primary">

	<div class="panel-heading">
		<h3 id="panelTitle" class="panel-title">This Artist's Albums</h3>
	</div>

	<div class="panel-body"> 
		
		<!-- Panel Content --> 

		<?php if(!empty($getit)) { ?>
		
<table class="table table-striped table-hover" id="recordCollection">

<thead>
<tr>
<th>Cover Art</th>
<!-- -->
<th>Album Spotify ID</th>

<th onClick="sortColumn('albumNameSpot', 'ASC', '<?php echo $artistSpotID; ?>', '<?php echo $source ?>')"><div class="pointyHead">Album Name</div></th>
<th onClick="sortColumn('yearReleased', 'unsorted', '<?php echo $artistSpotID; ?>', '<?php echo $source ?>')"><div class="pointyHead popStyle">Released</div></th>
<!--
<th><div class="pointyHead popStyle">Total<br>Tracks</div></th>
-->
<th class="popStyle">Spotify<br>Data Date</th>
<th onClick="sortColumn('pop', 'unsorted', '<?php echo $artistSpotID ?>', '<?php echo $source ?>')"><div class="pointyHead popStyle">Spotify<br>Popularity</div></th>

</tr>
</thead>

<tbody>
					
<?php
	while ($row = mysqli_fetch_array($getit)) {
		$artistNameSpot = $row['artistNameSpot'];
		$albumSpotID = $row['albumSpotID'];
		$date = $row['date'];
		$source = 'spotify';
		$albumPop = $row['pop'];
		$coverArt = $row['albumArtSpot'];
		$tracksTotal = $row['tracksTotal'];
		// need to get a tracks total for MusicBrainz-only albums
		$albumNameSpot = $row['albumNameSpot'];
		$albumReleased = $row['yearReleased'];	
		// need to get release year for MusicBrainz-only albums	
/*
*/
?>
					
<tr>
<td><img src='<?php echo $coverArt ?>' height='64' width='64'></td>
<!---->
<td><?php echo $albumSpotID ?></td>

<td><a href='https://www.roxorsoxor.com/poprock/album_TracksListSpot.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>&albumSpotID=<?php echo $albumSpotID ?>&source=<?php echo $source ?>'><?php echo $albumNameSpot ?></a></td>


<td class="popStyle"><?php echo $albumReleased ?></td>
<!--
<td class="popStyle"><?php //echo $tracksTotal ?></td>
-->
<td class="popStyle"><?php echo $date ?></td>
<td class="popStyle"><?php echo $albumPop ?></td>
<!---->

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
<!-- -->
<script>
	const artistNameSpot = '<?php echo $artistNameSpot ?>';
	const panelTitleText = 'Popularity on Spotify for albums by ' + artistNameSpot + '</em>';
	const panelTitle = document.getElementById('panelTitle');
	const docTitleText = 'All ' + artistNameSpot + ' albums Spotify Stats';
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
		document.title = docTitleText;
	});
</script>
<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>

<script src="https://www.roxorsoxor.com/poprock/functions/sort_artistAlbumsSpot.js"></script>

</body>
	
</html>