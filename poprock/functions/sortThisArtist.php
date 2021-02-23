<?php 

include '../page_pieces/sesh.php';
$artistSpotID = $_SESSION['artist'];
$_SESSION['artist'] = $artistSpotID;
require_once '../rockdb.php';
require_once 'artists.php';

$connekt = new mysqli($GLOBALS['host'], $GLOBALS['un'], $GLOBALS['magicword'], $GLOBALS['db']);

if (!$connekt) {
	echo 'Darn. Did not connect.';
};	

$sortBy = "date";
$order = "ASC";

if ( !empty( $_POST[ "sortBy" ] ) ) {
	// echo $_POST[ "sortBy" ] . "<br>";
	$sortBy = $_POST[ "sortBy" ];
}

if ( !empty( $_POST[ "order" ] ) ) {
	// echo $_POST[ "order" ] . "<br>";
	$order = $_POST[ "order" ];
}

$dateNextOrder = "ASC";

if ( $order == "ASC" ) {
	$dateNextOrder = "DESC";
}

$artistInfoAll = "SELECT a.artistSpotID, a.artistName, b.pop, b.date 
	FROM artists a
		INNER JOIN popArtists b ON a.artistSpotID = b.artistSpotID
			WHERE a.artistSpotID = '$artistSpotID'
			ORDER BY " . $sortBy . " " . $order . ";";

$sortit = $connekt->query($artistInfoAll);

if(!$sortit){
	echo 'Cursed-Crap. Did not run the query.';
}

if(!empty($sortit))	 { ?>

	<table class="table-content" id="artistTable">
		<thead>
			<tr>
				<th>Artist Name</th>
				<th>Popularity</th>
				<th onClick="sortColumn('date', '<?php echo $dateNextOrder; ?>')"><div class="pointyHead">Date</div></th>
			</tr>
		</thead>
		<tbody>

<?php
		while ($row = mysqli_fetch_array($sortit)) {
	// $artistSpotID = $row["artistSpotID"];
	$artistName = $row["artistName"];
	$artistPop = $row["pop"];
	$popDate = $row["date"];
	
?>

	<tr>
		<td><?php echo $artistName; ?></td>
		<td><?php echo $artistPop; ?></td>
		<td><?php echo $popDate; ?></td>
	</tr>

<?php
} // end of while
?>
</tbody>
</table>
<?php
} // end of if
?>