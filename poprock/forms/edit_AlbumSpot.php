<?php
$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];
$albumSpotID = $_GET['albumSpotID'];

require_once '../rockdb.php';
require_once '../page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
};

// check if the form has been submitted.
if (isset($_POST['submit'])){
	// If form is being submitted, process the form
	// get form data, making sure it is valid
	$albumSpotID = $_POST['albumSpotID'];
	$albumNameSpot = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['albumNameSpot']));
	$artistNameSpot = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistNameSpot']));
	$artistMBID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistMBID']));
	$artistSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistSpotID']));
	$assocArtistSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['assocArtistSpotID']));
	$assocAlbumSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['assocAlbumSpotID']));
	$albumArtMBFilename = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['albumArtMBFilename']));
	$albumArtMBFilename = "https://www.roxorsoxor.com/poprock/cover-art/" . $albumArtMBFilename;
	
	// save data to database
	$updateAlbum = "UPDATE albumsMB SET albumNameSpot='$albumNameSpot', artistMBID='$artistMBID', artistSpotID='$artistSpotID', assocArtistSpotID='$assocArtistSpotID', assocAlbumSpotID='$assocAlbumSpotID', albumArtMBFilename='$albumArtMBFilename' WHERE albumSpotID='$albumSpotID'";
	
	$retval = $connekt->query($updateAlbum);
	
	// Feedback of whether UPDATE worked or not
	if(!$retval){
		// if insert did NOT work
		die('Crap. Could not update this album because: ' . mysqli_error($connekt));
	}
	else
	{
		// if update worked, go to list of albums
		// do I want to change that to something AJAXy?
		header("Location: ../artist_AlbumsListLastFM.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&source=musicbrainz");
	}
	

}
else // if the form isn't being submitted, get the data from the db and display the form
{
	// confirm id is valid and is numeric/larger than 0)
	if (isset($_GET['albumSpotID'])){
		// query db
		$albumSpotID = $_GET['albumSpotID'];
		
		$queryZ = "
			SELECT mb.albumNameSpot, mb.albumSpotID, z.artistNameSpot, z.artistMBID, z.artistSpotID, mb.albumArtMBFilename, mb.assocArtistSpotID, mb.assocAlbumSpotID
				FROM albumsMB mb 
				JOIN artists z ON z.artistMBID = mb.artistMBID
				WHERE mb.albumSpotID='" . $albumSpotID . "';";
		
		$resultZ = mysqli_query($connekt, $queryZ) 
			or die(mysqli_error($connekt));
		
		$row = mysqli_fetch_array($resultZ);
		
		// check that the 'albumSpotID' matches up with a row in the databse
		if($row){
			$albumSpotID = $row['albumSpotID'];
			$albumNameSpot = $row['albumNameSpot'];
			$artistNameSpot = $row['artistNameSpot'];
			$artistMBID = $row['artistMBID'];
			$artistSpotID = $row['artistSpotID'];
			$assocArtistSpotID = $row['assocArtistSpotID'];
			//$assocArtistNameSpot = $row['assocArtistNameSpot'];
			$assocAlbumSpotID = $row['assocAlbumSpotID'];
			if($row["albumArtMBFilename"] == "") {
				$albumArtMBFilename = "nope.png";
			}
			else {
				$albumArtMBFilename = $row["albumArtMBFilename"];
			}				
		}
		else // if no match, display error
		{
			echo "No results!";
		}
	}
	else // if the 'albumSpotID' in the URL isn't valid, or if there is no 'albumSpotID' value, display an error
	{
		echo $error;
	}
} // end of what to do if form isn't being submitted
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="user-scalable=no, width=device-width" />
<meta charset="UTF-8">
<title>Edit <?php echo $albumNameSpot; ?> by <?php echo $artistNameSpot; ?></title>
<?php echo $stylesAndSuch; ?>
</head>
<body>
<div class="container-fluid">	
		
	<div id="fluidCon">
	</div> <!-- end of fluidCon -->
	
	  

	
	<!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Edit <?php echo $albumNameSpot; ?> by <?php echo $artistNameSpot; ?></h3></div>
			<div class="panel-body">
				<!-- Panel Content -->
	
	<!-- This form displays user profile info from the database -->
	
	<form class="form-horizontal" action="" method="post">
		<input type="hidden" name="albumSpotID" value="<?php echo $albumSpotID; ?>"/>
		<fieldset>
            
			<div class="form-group"> <!-- Row 1 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="albumNameSpot">Album Name</label>				
				<!-- Column 2 -->
				<div class="col-lg-4">
					<input class="form-control" type="text" name="albumNameSpot" value="<?php echo $albumNameSpot; ?>" />
				</div>
			</div>
			<!-- /Row 1 -->		
            	
			<div class="form-group"> <!-- Row 2 --> 
				<!-- Column 1 -->				
				<label class="col-lg-2 control-label" for="artistNameSpot">Artist Name</label>
				<!-- Column 2 -->				
				<div class="col-lg-4">
					<input class="form-control" type="text" name="artistNameSpot" value="<?php echo $artistNameSpot; ?>" />
				</div>
			</div>
			<!-- /Row 2 -->

			<div class="form-group"> <!-- Row Art --> 
				<!-- Column 1 -->				
				<label class="col-lg-2 control-label" for="albumArtMBFilename">Album Art</label>
				<!-- Column 2 -->				
				<div class="col-lg-4">
					<input class="form-control" type="text" name="albumArtMBFilename" value="<?php echo $albumArtMBFilename; ?>" />
				</div>
			</div>
			<!-- /Row Art -->
			
			<div class="form-group"> <!-- Row 3 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="artistMBID">artistMBID</label>
				<!-- Column 2 -->
				<div class="col-lg-4">
					<input class="form-control" type="text" name="artistMBID" value="<?php echo $artistMBID; ?>" readonly/>
				</div>
			</div>
			<!-- /Row 3 -->
			
			<div class="form-group"> <!-- Row 4 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="artistSpotID">artistSpotID</label>
				<!-- Column 2 -->
				<div class="col-lg-4">
					<input class="form-control" type="artistSpotID" name="artistSpotID"  value="<?php echo $artistSpotID; ?>" readonly/>
				</div>
			</div>
			<!-- /Row 4 --> 					
			
			<div class="form-group"> <!-- Row 4 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="assocArtistSpotID">Associated Artist SpotID</label>
				<!-- Column 2 -->
				<div class="col-lg-3">
					<input class="form-control" type="assocArtistSpotID" name="assocArtistSpotID"  value="<?php echo $assocArtistSpotID; ?>" />
				</div>
				<!-- Column 3 
				<div class="col-lg-3">
					<input class="form-control" type="assocArtistNameSpot" name="assocArtistNameSpot"  value="<?php //echo $assocArtistNameSpot; ?>" readonly/>
				</div>
				-->
			</div>
			
						
			<div class="form-group"> <!-- Row 4 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="assocAlbumSpotID">Associated Album SpotID</label>
				<!-- Column 2 -->
				<div class="col-lg-3">
					<input class="form-control" type="assocAlbumSpotID" name="assocAlbumSpotID"  value="<?php echo $assocAlbumSpotID; ?>" />
				</div>
				<!-- Column 3 
				<div class="col-lg-3">
					<input class="form-control" type="assocArtistNameSpot" name="assocArtistNameSpot"  value="<?php //echo $assocArtistNameSpot; ?>" readonly/>
				</div>
				-->
			</div>
					
			<!-- Last Row -->
			<div class="form-group"> <!-- Last Row -->	
				<div class="col-lg-4 col-lg-offset-2">
					<button class="btn btn-primary" type="submit" name="submit">Update</button>
				</div>
			</div>
			<!-- /Last Row -->
		</fieldset>
	</form>
    
<div class="well">	
	
	<?php
	// Start creating an HTML table for Assigned Cases and create header row
    echo "<table class='table table-striped table-hover '><thead><tr><th>Other Albums by " . $artistNameSpot . "</th></tr></thead>";
	echo "<tbody>";

	$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );
	// Connection test and feedback
	if (!$connekt) {
		die('Rats! Could not connect: ' . mysqli_error());
	}

	$query0 = "
		SELECT b.albumNameSpot, b.albumSpotID, z.artistNameSpot, z.artistMBID, z.artistSpotID, x.albumArtMBFilename
			FROM (SELECT mb.albumNameSpot, mb.albumSpotID, mb.artistMBID
				FROM albumsMB mb 
				WHERE mb.artistMBID='" . $artistMBID . "') b 
			JOIN artists z ON z.artistMBID = b.artistMBID
			LEFT JOIN albumsMB x ON b.albumSpotID = x.albumSpotID
			ORDER BY b.albumNameSpot ASC;";
	
	$result0 = $connekt->query($query0);
	// Create a row in HTML table for each row from database
    while ($row = mysqli_fetch_array($result0)) {
		echo "<tr><td><img src='" . $row['albumArtMBFilename'] . "' height='64' width='64'></td><td>" . $row['albumNameSpot'] . "</td></tr>";
    }

    echo "</tbody></table>";
	// When attempt is complete, connection closes
    mysqli_close($connekt);
?>

</div> <!-- /well -->

	</div> <!-- /panel-body -->
</div> <!-- /panel -->
	

	</div> <!-- /container-fluid --> 
<script>
	const artistSpotID = '<?php echo $artistSpotID; ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>
</html>