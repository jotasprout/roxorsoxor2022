<?php
$artistMBID = $_GET['artistMBID'];
$artistSpotID = $_GET['artistSpotID'];



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
    $rowID = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['rowID']));
    $genre = mysqli_real_escape_string($connekt, htmlspecialchars($_POST['genre']));
	
	// UPDATE ARTISTS of SPOTIFY
    $updateArtistGenre = "UPDATE genresNames SET genre='$genre', WHERE rowID='$rowID'";
    
    $retval = $connekt->query($updateArtistGenre);
    
    // Feedback of whether UPDATE worked or not
	if(!$retval){
		// if update did NOT work
		die('Crap. Could not update this genre because: ' . mysqli_error($connekt));
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
	if (isset($_GET['rowID'])){

$newGenresQuery = "SELECT * FROM genresNames 
WHERE artistID = $artistMBID OR artistID = $artistSpotID ORDER BY genre ASC";
		
		$resultZ = mysqli_query($connekt, $newGenresQuery) or die(mysqli_error($connekt));
		
		$row = mysqli_fetch_array($newGenresQuery);
		

    while ( $row = mysqli_fetch_array( $getit ) ) {

        $rowID = $row["id"];
        $artistMBID = $row[ "artistMBID" ];
        $artistSpotID = $row[ "artistSpotID" ];
        $artistID = '';
        $artistName = '';
        $artistNameSpot = $row[ "artistNameSpot" ];
        $artistNameMB = $row[ "artistNameMB" ];
        $genre = $row["genre"];
        $genreSource = $row["genreSource"];
        if ($genreSource == "Spotify") {
            $artistID = $artistSpotID;
            $artistName = $artistNameSpot;
        } else {
            $artistID = $artistMBID;
            $artistName = $artistNameMB;
        };   			
		}

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
<title>Edit <?php echo $artistName; ?> Genres </title>
<?php echo $stylesAndSuch; ?>
</head>
<body>
<div class="container-fluid">	
		
	<div id="fluidCon">
	</div> <!-- end of fluidCon -->
	
	<!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Edit <?php echo $artistNameSpot; ?> Genres</h3></div>
			<div class="panel-body">
				<!-- Panel Content -->
	
	<!-- // SOME OF THESE FORM FIELDS RECEIVE VARIABLES FROM DB ... ALL OF THESE SEND INFO TO DB -->
	
	<form class="form-horizontal" action="" method="post">
		
		<fieldset>
		
            
     	<?php
    while ( $row = mysqli_fetch_array( $getit ) ) {

        $rowID = $row["id"];
        $artistMBID = $row[ "artistMBID" ];
        $artistSpotID = $row[ "artistSpotID" ];
        $artistID = '';
        $artistName = '';
        $artistNameSpot = $row[ "artistNameSpot" ];
        $artistNameMB = $row[ "artistNameMB" ];
        $genre = $row["genre"];
        $genreSource = $row["genreSource"];
        if ($genreSource == "Spotify") {
            $artistID = $artistSpotID;
            $artistName = $artistNameSpot;
        } else {
            $artistID = $artistMBID;
            $artistName = $artistNameMB;
        };   
	?>       
            
            
        <!-- Artist Art from Spotify -->
		<div class="form-group">  
			<div class="col-lg-4 align-top">
				<label class="control-label" for="artistArtSpot">rowID</label>
				<input class="form-control" type="text" name="artistArtSpot" value="<?php echo $artistArtSpot; ?>" readonly/>
			</div>
		</div> 
        <!-- /Artist Art from Spotify -->  

            
            
            
            
            
            
				
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