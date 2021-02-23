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
	<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>

</html>