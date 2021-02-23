<?php
$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];

$artistArtMBFilePath = "https://www.roxorsoxor.com/poprock/artist-art/";

require_once '../rockdb.php';
require_once '../page_pieces/stylesAndScripts.php';

$connekt = new mysqli( $GLOBALS[ 'host' ], $GLOBALS[ 'un' ], $GLOBALS[ 'magicword' ], $GLOBALS[ 'db' ] );

if ( !$connekt ) {
	echo 'Darn. Did not connect. Screwed up like this: ' . mysqli_connect_error() . '</p>';
};

// check if form is being submitted.
if (isset($_POST['submit'])){
	// If form is being submitted, process the form
	
    // THESE VARIABLES TAKE INFO FROM THE FORM FIELDS AND PUT IN DB TABLES
    $artistArtSpot = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistArtSpot']));
    $artistArtMBFilename = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistArtMBFilename']));
    $artistNameSpot = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistNameSpot']));
    $artistNameMB = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistNameMB']));
	$artistSpotID = $_POST['artistSpotID'];
    $artistMBID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['artistMBID']));
	
	// UPDATE ARTISTS of SPOTIFY
    $updateArtistSpot = "UPDATE artistsSpot SET artistNameSpot='$artistNameSpot', artistMBID='$artistMBID' WHERE artistSpotID='$artistSpotID'";
    
    $retval = $connekt->query($updateArtistSpot);
    
    // Feedback of whether UPDATE worked or not
	if(!$retval){
		// if update did NOT work
		die('Crap. Could not update this artist because: ' . mysqli_error($connekt));
	}
	else
	{
		// if update worked, go back to artist page
		header("Location: https://www.roxorsoxor.com/poprock/artist_ChartsSpot.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID);
    }
    
    // UPDATE ARTISTS of MusicBrainz
    $updateArtistMB = "UPDATE artistsMB SET artistNameMB='$artistNameMB', artistMBID='$artistMBID', artistArtMB='$artistArtMBFilename' WHERE artistMBID='$artistMBID'";

	$retval2 = $connekt->query($updateArtistMB);
    
    	// Feedback of whether UPDATE worked or not
	if(!$retval2){
		// if update did NOT work
		die('Crap. Could not update this artist because: ' . mysqli_error($connekt));
	}
	else
	{
		// if update worked, go back to artist page
		header("Location: https://www.roxorsoxor.com/poprock/artist_ChartsSpot.php?artistSpotID=" . $artistSpotID . "&artistMBID=" . $artistMBID);
    }
}
else // if the form isn't being submitted, get the data from the db and display the form
{
	// THESE VARIABLES POPULATE THE FORM FIELDS WITH EXISTING INFO
    
    // confirm id is valid and is numeric/larger than 0)
	if (isset($_GET['artistSpotID'])){
		// query db
		//$artistSpotID = $_GET['artistSpotID'];
		//$artistMBID = $_GET['artistMBID'];

		$queryZ = "
			SELECT z.artistNameSpot, z.artistSpotID, z.artistArtSpot, z.artistMBID, mb.artistArtMBFilename, mb.artistNameMB
                FROM artistsSpot z 
                LEFT JOIN artistsMB mb ON z.artistMBID = mb.artistMBID
				WHERE z.artistSpotID='" . $artistSpotID . "';";
		
		$resultZ = mysqli_query($connekt, $queryZ) or die(mysqli_error($connekt));
		
		$row = mysqli_fetch_array($resultZ);
		
		// check that the 'artistMBID' matches up with a row in the database
		if($row){
            $artistArtSpot = $row['artistArtSpot'];
			$artistArtMBFilename = $row['artistArtMBFilename'];
            $artistNameMB = $row['artistNameMB'];
            $artistNameSpot = $row['artistNameSpot'];
            $artistMBID = $row['artistMBID'];
			$artistSpotID = $row['artistSpotID'];

			if($artistArtMBFilename == "" || $artistArtMBFilename == null) {
                $prettyFace = $artistArtMBFilePath . "nope.png";
            } else {
                $prettyFace = $artistArtMBFilePath . $artistArtMBFilename;
            };				
		}
		else // if no match, display error
		{
			echo "No results!";
        }
        
        $queryG = "SELECT id, genre FROM genresNames g 
        WHERE g.artistID='" . $artistSpotID . "' OR g.artistID='" . $artistMBID . "';";
    
        $resultG = mysqli_query($connekt, $queryG) or die(mysqli_error($connekt));

	}
	else // if the 'artistMBID' in the URL isn't valid, or if there is no 'artistMBID' value, display an error
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
<title>Edit <?php echo $artistNameSpot; ?></title>
<?php echo $stylesAndSuch; ?>
</head>
<body>
<div class="container-fluid">	
		
	<div id="fluidCon">
	</div> <!-- end of fluidCon -->
  <!-- Breadcrumbs start -->
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="../index.php">Spotify Artists</a></li>
        <li class="breadcrumb-item"><a href="../artist_ChartsSpot.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID; ?>"><?php echo $artistNameSpot; ?></a></li>
        <li class="breadcrumb-item active"> Edit <?php echo $artistNameSpot; ?></li>
    </ol>
    <!-- Breadcrumbs end -->

	<!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Edit <?php echo $artistNameSpot; ?></h3></div>
			<div class="panel-body">
				<!-- Panel Content -->
	
	<!-- // SOME OF THESE FORM FIELDS RECEIVE VARIABLES FROM DB ... ALL OF THESE SEND INFO TO DB -->
	
	<form class="form-horizontal" action="" method="post">
		
		<fieldset>
		
        <!-- Artist Art from Spotify -->
		<div class="form-group">  
			<div class="col-lg-2 rightNum">
				<img src='<?php echo $artistArtSpot ?>' class="indexArtistArt" id="artistArtSpot">
			</div>
			<div class="col-lg-4 align-top">
				<label class="control-label" for="artistArtSpot">Artist Art Spotify</label>
				<input class="form-control" type="text" name="artistArtSpot" value="<?php echo $artistArtSpot; ?>" readonly/>
			</div>
		</div> 
        <!-- /Artist Art from Spotify -->  

		<div class="form-group"> <!-- Row ArtMB --> 	
			<div class="col-lg-2 rightNum">
				<img src='<?php echo $prettyFace ?>' class="indexArtistArt" id="artistArtMB">
			</div>		
			<div class="col-lg-4 align-top">
				<label class="control-label" for="artistArtMBFilename">Artist Art MB</label>
				<input class="form-control" type="text" name="artistArtMBFilename" value="<?php echo $artistArtMBFilename; ?>" />
			</div>
		</div> <!-- /Row ArtMB -->				   

		<div class="form-group"> <!-- Artist Spotify Name --> 			
			<label class="col-lg-2 control-label" for="artistNameSpot">Spotify</label>			
			<div class="col-lg-3">
				<input class="form-control" type="text" name="artistNameSpot" value="<?php echo $artistNameSpot; ?>" />
			</div>
            			<!-- Column 3 -->
			<div class="col-lg-3">
				<input class="form-control" type="artistSpotID" name="artistSpotID"  value="<?php echo $artistSpotID; ?>" readonly/>
			</div>
		</div> <!-- /Artist Spotify Name -->
            
		<div class="form-group"> <!-- Artist MB Name --> 			
			<label class="col-lg-2 control-label" for="artistNameMB">MusicBrainz</label>			
			<div class="col-lg-3">
				<input class="form-control" type="text" name="artistNameMB" value="<?php echo $artistNameMB; ?>" />
			</div>
            			<!-- Column 3 -->
			<div class="col-lg-3">
				<input class="form-control" type="text" name="artistMBID" value="<?php echo $artistMBID; ?>" />
			</div>
		</div> <!-- /Primary Artist MB Name -->	


        <?php
            $i = 0;

            while ($row = mysqli_fetch_array($resultG)) {
                $genreID = $row['id'];
                $genre = $row['genre'];
                /*
                $i++;
                if ($i < 10){
                    $genreNum = "genre_0" . $i;
                } else {
                    $genreNum = "genre_" . $i;
                };
                */
        ?>	

        <div class="form-group"> <!-- Artist Genree --> 			
			<label class="col-lg-2 control-label" for="<?php echo $genreID; ?>">Genre</label>			
			<div class="col-lg-3">
				<input class="form-control" type="text" name="<?php echo $genreID; ?>" value="<?php echo $genre; ?>" />
			</div>
		</div> <!-- /Artist Genre -->

        <?php 
               
            } // end of while
        ?>		
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
</div> <!-- /panel IS THIS PRIMARY? -->
	

	</div> <!-- /container-fluid --> 
<script>
	const artistSpotID = '<?php echo $artistSpotID; ?>';
	const artistMBID = '<?php echo $artistMBID ?>';
</script>
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>
</html>