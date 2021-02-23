<?php
$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];
$albumMBID = $_GET['albumMBID'];

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
	$albumMBID = $_POST['albumMBID'];
	$albumNameMB = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['albumNameMB']));
	$artistNameMB = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistNameMB']));
	$artistMBID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistMBID']));
	$artistSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistSpotID']));
	$assocArtistSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['assocArtistSpotID']));
	$assocAlbumSpotID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['assocAlbumSpotID']));
	$albumArtMBFilename = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['albumArtMBFilename']));
	
	
	// save data to database
	$updateAlbum = "UPDATE albumsMB SET albumNameMB='$albumNameMB', artistMBID='$artistMBID', artistSpotID='$artistSpotID', assocArtistSpotID='$assocArtistSpotID', assocAlbumSpotID='$assocAlbumSpotID', albumArtMBFilename='$albumArtMBFilename' WHERE albumMBID='$albumMBID'";
	
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
		header("Location: ../artist_AlbumsListLastFM.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID . "&albumMBID=" . $albumMBID . "&source=musicbrainz");
	}
	

}
else // if the form isn't being submitted, get the data from the db and display the form
{
	// confirm id is valid and is numeric/larger than 0)
	if (isset($_GET['albumMBID'])){
		// query db
		$albumMBID = $_GET['albumMBID'];
		
		$queryZ = "
			SELECT mb.albumNameMB, mb.albumMBID, z.artistNameMB, z.artistMBID, mb.albumArtMBFilename, mb.assocArtistSpotID, mb.assocAlbumSpotID
				FROM albumsMB mb 
				JOIN artistsMB z ON z.artistMBID = mb.artistMBID
				WHERE mb.albumMBID='" . $albumMBID . "';";
		
		$resultZ = mysqli_query($connekt, $queryZ) 
			or die(mysqli_error($connekt));
		
		$row = mysqli_fetch_array($resultZ);
		
		// check that the 'albumMBID' matches up with a row in the databse
		if($row){
			$albumMBID = $row['albumMBID'];
			$albumNameMB = $row['albumNameMB'];
			$artistNameMB = $row['artistNameMB'];
			$artistMBID = $row['artistMBID'];
			//$artistSpotID = $row['artistSpotID'];
			$assocArtistSpotID = $row['assocArtistSpotID'];
			//$assocArtistNameMB = $row['assocArtistNameMB'];
            $assocAlbumSpotID = $row['assocAlbumSpotID'];
            $albumArtMBFilename = $row['albumArtMBFilename'];
            $albumArtMBFilepath = "https://www.roxorsoxor.com/poprock/cover-art/";
            $coverArt = $albumArtMBFilepath . $albumArtMBFilename;
			if($albumArtMBFilename == "") {
				$albumArtMBFilename = "nope.png";
			}
			else {
				$albumArtMBFilename = $row['albumArtMBFilename'];
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
<title>Edit <?php echo $albumNameMB; ?> by <?php echo $artistNameMB; ?></title>
<?php echo $stylesAndSuch; ?>
</head>
<body>
<div class="container-fluid">	
		
	<div id="fluidCon">
	</div> <!-- end of fluidCon -->
	
	<!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Edit <i><?php echo $albumNameMB; ?></i> by <?php echo $artistNameMB; ?></h3></div>
			<div class="panel-body">
				<!-- Panel Content -->
	
	<!-- This form displays user profile info from the database -->
	
	<form class="form-horizontal" action="" method="post">
		<input type="hidden" name="albumMBID" value="<?php echo $albumMBID; ?>"/>
		<fieldset>
            
			<div class="form-group"> <!-- Row 1 --> 
				<!-- Column 1 -->
				<label class="col-lg-2 control-label" for="albumNameMB">Album Name</label>				
				<!-- Column 2 -->
				<div class="col-lg-4">
					<input class="form-control" type="text" name="albumNameMB" value="<?php echo $albumNameMB; ?>" />
				</div>
			</div>
			<!-- /Row 1 -->		
            	
			<div class="form-group"> <!-- Row 2 --> 
				<!-- Column 1 -->				
				<label class="col-lg-2 control-label" for="artistNameMB">Artist Name</label>
				<!-- Column 2 -->				
				<div class="col-lg-4">
					<input class="form-control" type="text" name="artistNameMB" value="<?php echo $artistNameMB; ?>" />
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
					<input class="form-control" type="assocArtistNameMB" name="assocArtistNameMB"  value="<?php //echo $assocArtistNameMB; ?>" readonly/>
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