<?php
$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];
$albumMBID = $_GET['albumMBID'];
$trackMBID = $_GET['trackMBID'];

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
    $trackNameMB = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['trackNameMB']));
	$albumNameMB = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['albumNameMB']));
	$artistNameMB = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistNameMB']));
	$artistMBID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistMBID']));
	$artistSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistSpotID']));
	$assocArtistSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['assocArtistSpotID']));
	$albumArtMBFilename = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['albumArtMBFilename']));
	$albumArtMB = "https://www.roxorsoxor.com/poprock/cover-art/" . $albumArtMBFilename;
	
	// save data to database
	$updateTrack = "UPDATE tracksMB SET albumNameMB='$albumNameMB', trackNameMB='$trackNameMB',artistMBID='$artistMBID', artistSpotID='$artistSpotID', assocArtistMBID='$assocArtistMBID', albumArtMBFilename='$albumArtMBFilename' WHERE albumMBID='$albumMBID'";
	
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
		header("Location: ../track_ChartsLastFM.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&trackMBID=" . $trackMBID . "&source=musicbrainz");
	}
	

}
else // if the form isn't being submitted, get the data from the db and display the form
{
	// confirm id is valid and is numeric/larger than 0)
	if (isset($_GET['trackMBID'])){
		// query db
		$trackMBID = $_GET['trackMBID'];
		
		$queryZ = "
			SELECT t.trackNameMB, t.trackMBID, z.artistNameMB, z.artistMBID, z.artistSpotID, a.albumArtMBFilename, t.assocArtistSpotID, t.assocAlbumSpotID
                FROM trackMBID t 
                JOIN albumsMB a ON m.albumMBID = t.albumMBID
				JOIN artistsMB z ON z.artistMBID = t.artistMBID
				WHERE t.albumMBID='" . $albumMBID . "';";
		
		$resultZ = mysqli_query($connekt, $queryZ) 
			or die(mysqli_error($connekt));
		
		$row = mysqli_fetch_array($resultZ);
		
		// check that the 'albumMBID' matches up with a row in the databse
		if($row){
			$trackMBID = $row['trackMBID'];
            $trackNameMB = $row['trackNameMB'];
			$albumMBID = $row['albumMBID'];
			$albumNameMB = $row['albumNameMB'];            
			$artistNameMB = $row['artistNameMB'];
			$artistMBID = $row['artistMBID'];
			$artistSpotID = $row['artistSpotID'];
			$assocArtistSpotID = $row['assocArtistSpotID'];
			//$assocArtistNameMB = $row['assocArtistNameMB'];
			$assocAlbumSpotID = $row['assocAlbumSpotID'];
			if($row["albumArtMB"] == "") {
				$albumArtMB = "nope.png";
			}
			else {
				$albumArtMB = $row["albumArtMB"];
			}				
		}
		else // if no match, display error
		{
			echo "No results!";
		}
	}
	else // if the 'albumMBID' in the URL isn't valid, or if there is no 'albumMBID' value, display an error
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
<title>Edit <?php echo $trackNameMB; ?> by <?php echo $artistNameMB; ?></title>
<?php echo $stylesAndSuch; ?>
</head>
<body>
<div class="container-fluid">	
		
	<div id="fluidCon">
	</div> <!-- end of fluidCon -->
	
	  

	
	<!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Edit <?php echo $trackNameMB; ?> by <?php echo $artistNameMB; ?></h3></div>
			<div class="panel-body">
				<!-- Panel Content -->
	
	<!-- This form displays user profile info from the database -->
	
	<form class="form-horizontal" action="" method="post">
		<input type="hidden" name="albumMBID" value="<?php echo $trackMBID; ?>"/>
		<fieldset>
            
			<div class="form-group"> <!-- Row 1 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="albumNameMB">track Name</label>				
				<!-- Column 2 -->
				<div class="col-lg-4">
					<input class="form-control" type="text" name="albumNameMB" value="<?php echo $trackNameMB; ?>" />
				</div>
			</div>
			<!-- /Row 1 -->		
            	
			<div class="form-group"> <!-- Row 2 --> 
				<!-- Column 1 -->				
				<label class="col-lg-2 control-label" for="artistNameMB">Album Name</label>
				<!-- Column 2 -->				
				<div class="col-lg-4">
					<input class="form-control" type="text" name="artistNameMB" value="<?php echo $albumNameMB; ?>" />
				</div>
			</div>
			<!-- /Row 2 -->

			<div class="form-group"> <!-- Row Art --> 
				<!-- Column 1 -->				
				<label class="col-lg-2 control-label" for="albumArtMB">Album Art</label>
				<!-- Column 2 -->				
				<div class="col-lg-4">
					<input class="form-control" type="text" name="albumArtMB" value="<?php echo $albumArtMB; ?>" />
				</div>
			</div>
			<!-- /Row Art -->
			
			<div class="form-group"> <!-- Row 3 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="artistNameMB">Artist Name</label>
				<!-- Column 2 -->
				<div class="col-lg-4">
					<input class="form-control" type="text" name="artistNameMB" value="<?php echo $artistNameMB; ?>" readonly/>
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
					<input class="form-control" type="assocArtistNameMB" name="assocArtistNameMB"  value="<?php //echo $assocArtistNameMB; ?>" readonly/>
				</div>
				-->
			</div>
			
						
			<div class="form-group"> <!-- Row 4 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="assocTrackSpotID">Associated Track SpotID</label>
				<!-- Column 2 -->
				<div class="col-lg-3">
					<input class="form-control" type="assocTrackSpotID" name="assocTrackSpotID"  value="<?php echo $assocTrackSpotID; ?>" />
				</div>
				<!-- Column 3 
				<div class="col-lg-3">
					<input class="form-control" type="assocArtistNameMB" name="assocArtistNameMB"  value="<?php //echo $assocArtistNameMB; ?>" readonly/>
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
    echo "<table class='table table-striped table-hover '><thead><tr><th>Other Albums by " . $artistNameMB . "</th></tr></thead>";
	echo "<tbody>";

	$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );
	// Connection test and feedback
	if (!$connekt) {
		die('Rats! Could not connect: ' . mysqli_error());
	}

	$query0 = "
		SELECT b.albumNameMB, b.albumMBID, z.artistNameMB, z.artistMBID, z.artistSpotID, x.albumArtMB
			FROM (SELECT t.albumNameMB, t.albumMBID, t.artistMBID
				FROM albumsMB mb 
				WHERE t.artistMBID='" . $artistMBID . "') b 
			JOIN artists z ON z.artistMBID = b.artistMBID
			LEFT JOIN albumsMB x ON b.albumMBID = x.albumMBID
			ORDER BY b.albumNameMB ASC;";
	
	$result0 = $connekt->query($query0);
	// Create a row in HTML table for each row from database
    while ($row = mysqli_fetch_array($result0)) {
		echo "<tr><td><img src='" . $row['albumArtMB'] . "' height='64' width='64'></td><td>" . $row['albumNameMB'] . "</td></tr>";
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