<?php

include 'page_pieces/sesh.php';
require_once 'rockdb.php';
require_once 'page_pieces/navbar_rock.php';
require_once 'page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect.';
};

$artistInfoRecentWithArt = "SELECT a.artistSpotID AS artistSpotID, a.artistArt AS artistArt, a.artistName AS artistName, p1.pop AS pop, p1.date AS date
    FROM artists a
    JOIN (SELECT p.*
			FROM popArtists p
			INNER JOIN (SELECT artistSpotID, pop, max(date) AS MaxDate
						FROM popArtists  
						GROUP BY artistSpotID) groupedp
			ON p.artistSpotID = groupedp.artistSpotID
			AND p.date = groupedp.MaxDate) p1
	ON a.artistSpotID = p1.artistSpotID
	ORDER BY p1.pop DESC";

$getit = $connekt->query( $artistInfoRecentWithArt );

if(!$getit){ echo 'Cursed-Crap. Did not run the query.'; }	

?>

<!DOCTYPE html>

<html>

<head>
	<meta charset="UTF-8">
	<title>Artists Popularity</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

	<div class="container">
		<?php echo $navbar ?>

		<!-- main -->

		<div class="panel panel-primary">

			<div class="panel-heading">
				<h3 class="panel-title">Current Popularity from Spotify</h3>
			</div>

			<div class="panel-body">

				<!-- Panel Content -->
				<!-- D3 chart goes here -->
				<?php if (!empty($getit)) { ?>

				<table class="table" id="tableoartists">
					<thead>
						<tr>
						<th>Pretty Face</th>	
						<th onClick="sortColumn('artistName', 'ASC')"><div class="pointyHead">Artist Name</div></th>
						<!--
						<th>artistSpotID</th>
						<th>Date</th>
				-->
						<th onClick="sortColumn('pop', 'DESC')"><div class="pointyHead popScore">Popularity</div></th>
						
						
						</tr>
					</thead>

					<tbody>

					<?php
						while ( $row = mysqli_fetch_array( $getit ) ) {
							$artistName = $row[ "artistName" ];
							$artistSpotID = $row[ "artistSpotID" ];
							$artistPop = $row[ "pop" ];
							$artistArt = $row[ "artistArt" ];
							$popDate = $row[ "date" ];
					?>

					<tr>
						<td><img src='<?php echo $artistArt ?>' class="indexArtistArt"></td>	
						<td><a href='https://www.roxorsoxor.com/poprock/artist_Chart.php?artistSpotID=<?php echo $artistSpotID ?>'><?php echo $artistName ?></a></td>
						<!--
						<td><?php //echo $artistSpotID ?></td>
						<td><?php // echo $popDate ?></td>
						-->
						<td class="popScore"><?php echo $artistPop ?></td>
						
						
					</tr>

					<?php 
						} // end of while
					?>

					</tbody>
				</table>
				<?php 
					} // end of if
				?>

			</div>
			<!-- panel body -->

		</div>
		<!-- panel panel-primary -->

	</div>
	<!-- close container -->

	<?php echo $scriptsAndSuch; ?>
	<script src="https://www.roxorsoxor.com/poprock/functions/sortTheseArtists.js"></script>

	<script>
	
	</script>

</body>

</html>