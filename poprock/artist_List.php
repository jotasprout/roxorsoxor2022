<?php 

$artistSpotID = $_GET['artistSpotID'];

require_once 'rockdb.php';
require_once 'page_pieces/stylesAndScripts.php';
require_once 'page_pieces/navbar_rock.php';
require_once 'functions/artists.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect.';
};

$artistInfoAll = "SELECT a.artistSpotID, a.artistName, b.pop, b.followers, b.date, f1.dataDate,  
	FROM artists a
		INNER JOIN popArtists b ON a.artistSpotID = b.artistSpotID
			WHERE a.artistSpotID = '$artistSpotID'
				ORDER BY b.date DESC";

$getit = $connekt->query($artistInfoAll);

if(!$getit){
	echo 'Cursed-Crap. Did not run the query.';
}	

?>

<!DOCTYPE html>

<html>

<head>
    <meta charset="UTF-8">
    <title>This Artist</title>
    <?php echo $stylesAndSuch; ?>
    <script src='https://d3js.org/d3.v4.min.js'></script>
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

        <!-- D3 chart goes here -->
		<?php if(!empty($getit)) { ?>
		
        <table class="table" id="artistTable">
			<thead>
				<tr>
					<th>Artist Name</th>
					<th onClick="sortColumn('date', 'DESC')"><div class="pointyHead">Spotify<br>Date</div></th>
					<th>Popularity</th>
					<th class="rightNum">Spotify<br>Followers</th>
					
					<th>LastFM<br>Data Date</th>
					<th class="rightNum">LastFM<br>Listeners</th>
					<th class="rightNum">LastFM<br>Playcount</th>
					<!--
					-->
					
				</tr>	
			</thead>
			<tbody>

			<?php

			while ($row = mysqli_fetch_array($getit)) {
				// $artistSpotID = $row["artistSpotID"];
				$artistName = $row["artistName"];
				$artistPop = $row["pop"];
				$lastFMDate = $row[ "dataDate" ];
				$artistFollowers = $row["followers"];
				$popDate = $row["date"];
				$popDateShort = substr($popDate, 0, 10);
			?>
							
			<tr>
				<td><?php echo $artistName ?></td>
				<td><?php echo $popDate ?></td>
				<td><?php echo $artistPop ?></td>
				<td><?php echo $artistFollowers ?></td>
			<!-- -->
				
				<td class="popStyle"><?php echo $lastFMDate ?></td>
				<td class="rightNum"><?php echo $artistListeners ?></td>
				<td class="rightNum"><?php echo $artistPlaycount ?></td>
			</tr>

			<?php 
				} // end of while
			?>

			</tbody>
        </table>

		<?php 
		} // end of if
		?>

    </div> <!-- close container -->
    
    <?php echo $scriptsAndSuch; ?>
	<script src="https://www.roxorsoxor.com/poprock/functions/sortThisArtist.js"></script>

</body>

</html>