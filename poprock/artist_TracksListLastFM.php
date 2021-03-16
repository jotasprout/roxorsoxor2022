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

$blackSabbath_MBID = '5182c1d9-c7d2-4dad-afa0-ccfeada921a8';

$gatherTrackInfo = "SELECT v.artistNameMB, v.trackNameMB, v.trackNumber, v.albumNameMB, v.trackListeners, v.trackPlaycount, v.trackRatio, max(v.dataDate) AS MaxDataDate
					FROM (
						SELECT z.trackMBID, z.trackNameMB, z.trackNumber, z.albumNameMB, z.artistNameMB, p.dataDate, p.trackListeners, p.trackPlaycount, p.trackRatio
							FROM (
								SELECT t.*, r.albumNameMB, a.artistNameMB
									FROM tracksMB t
									INNER JOIN albumsMB r ON r.albumMBID = t.albumMBID
									JOIN artistsMB a ON r.artistMBID = a.artistMBID
									WHERE a.artistMBID = '$artistMBID'
							) z
						JOIN tracksLastFM p 
							ON z.trackMBID = p.trackMBID					
					) v
					GROUP BY v.trackMBID
					ORDER BY v.trackPlaycount DESC";


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
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_TracksListSpot.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=spotify'>Tracks<br>Spotify</a>
						</li>  
						<li class="nav-item active">
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
		<p>If this page is empty, or the wrong discography displays, <a href='https://www.roxorsoxor.com/poprock/indexLastFM.php'>choose an artist</a> first.</p>

		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id="panelTitle" class="panel-title">Latest Tracks Stats from My Database</h3>
			</div>
			<div class="panel-body">

				<?php if(!empty($getit)) { ?>
				
<table class="table table-striped table-hover" id="tableotracks">
<thead>
<tr>
<!---->
<th class="popStyle" onClick="sortColumn('trackNumber', 'ASC', '<?php echo $albumMBID ?>', 'musicbrainz')"><div class="pointyHead">Track #</div></th>
<th onClick="sortColumn('albumNameMB', 'DESC', '<?php echo $artistMBID ?>', '<?php echo $source ?>')"><div class="pointyHead">Album Title</div></th>

<th class="pointyHead" onClick="sortColumn('trackNameMB', 'ASC', '<?php echo $artistMBID ?>', '<?php echo $source ?>')">Track Title</th>
<th class="popStyle">LastFM<br>Data Date</th>
<!---->
<th class="rightNum pointyHead" onClick="sortColumn('trackListeners', 'DESC', '<?php echo $albumMBID ?>', 'musicbrainz')">LastFM<br>Listeners</th>
<th class="rightNum pointyHead" onClick="sortColumn('trackPlaycount', 'DESC', '<?php echo $albumMBID ?>', 'musicbrainz')">LastFM<br>Playcount</th>
<th class="pointyHead popStyle" onClick="sortColumn('trackRatio', 'unsorted')">LastFM<br>Ratio</th>

</tr>
</thead>
					
<tbody>
<?php
    while ( $row = mysqli_fetch_array( $getit ) ) {
        $albumNameMB = $row[ "albumNameMB" ];
        $trackNameMB = $row[ "trackNameMB" ];
        $trackNumber = $row[ "trackNumber" ];
        $artistNameMB = $row[ "artistNameMB" ];
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
                            <td class="popStyle"><?php echo $trackNumber ?></td>
								<td><?php echo $albumNameMB ?></td>
								<td><?php echo $trackNameMB ?></td>
                                <!---->
								<td class="popStyle"><?php echo $lastFMDate ?></td>
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
			</div> <!-- panel body -->
		</div> <!-- panel panel-primary -->
	</div> <!-- closing container -->
	
<?php echo $scriptsAndSuch; ?>

<script>
	const artistNameMB = '<?php echo $artistNameMB; ?>';
	const panelTitleText = 'Last.fm stats for all tracks by ' + artistNameMB;
	const panelTitle = document.getElementById('panelTitle');
	const docTitleText = 'All ' + artistNameMB + ' tracks Last.fm Stats';
	$(document).ready(function(){
		panelTitle.innerHTML = panelTitleText;
		document.title = docTitleText;
	});
</script>

<script>
	const artistSpotID = '<?php echo $artistSpotID ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>

<script src="https://www.roxorsoxor.com/poprock/functions/sort_artistTracksLastFM.js"></script>

</body>
	
</html>