<?php
session_start();
require_once 'class.gator.php';
require_once 'stylesAndSuch.php';
require_once 'navbar.php';
$user = new USER();

if(!$user->areTheyLoggedIn()) {
	$user->redirect('https://www.roxorsoxor.com/stakeout/login_form.php');
}
else {
	$jefe = $_SESSION['jefe'];
	if ($jefe == 1) {
		echo "<script>console.log('You are an admin.')</script>";
	}
	$username = $_SESSION['username'];
	echo "<script>console.log('" . $username . " is logged in.')</script>";
}

    // PHP code in a more secure location
    include("../../secret_php/landfill.php");
    //Uses PHP code to connect to database
	$connekt = new mysqli($db_hostname, $db_username, $db_password, $db_database);
    // Connection test and feedback
	// confirm id is valid and is numeric/larger than 0)
	if (isset($_GET['id']) && is_numeric($_GET['id']) && $_GET['id'] > 0){
		// query db
		$id = $_GET['id'];
		
		// Create variable for query
		$query = "
			SELECT a.caseID, a.observeID, a.action, a.description, a.pix, a.observeAsset, a.observeTime, a.lng, a.lat, c.caseName, a.username, b.forename, b.surname, a.action 
				FROM observations a
					INNER JOIN user_creds b
						ON a.username = b.username
					INNER JOIN cases c
						ON a.caseID = c.caseID
							WHERE a.observeID = '$id'"; 				
		// Use variable with MySQL command to grab info from database
		$result = $connekt->query($query)
		or die(mysqli_error($result));
		$row = mysqli_fetch_array($result);
		// check that the 'id' matches up with a row in the databse
		if($row){
			// if there's a match, display data from db
			$caseID = $row["caseID"];
			$caseName = $row["caseName"];
			$observeAsset = "";

			
			if($row["observeAsset"] == "") {
				$observeAsset = "nope.png";
				$caseID = "nope";
			}
			else {
				$observeAsset = $row["observeAsset"];
			}
			
			$action = $row["action"];
			$forename = $row['forename'];
			$surname = $row['surname'];
			$description = $row["description"];
			$available = "";		
			
			if ($row["pix"] == 1) {
				$available = "Yes";
			}
			else {
				$available = "No";
			}	
			
			$ourTime = new DateTime($row["observeTime"] ." UTC");
			$ourTime ->setTimezone(new DateTimeZone('America/New_York'));						
			$ourTime = new DateTime($row["observeTime"] ." UTC");
			$ourTime ->setTimezone(new DateTimeZone('America/New_York'));
			
			$nowhere = false;
			
			if ($row["lat"] == 0 || $row["lng"] == 0) {
				$nowhere = true;
			} 

			$latitude = $row["lat"];
			$longitude = $row["lng"];
			$description = $row["description"];
		}
		else {
			// if no match, display error
			echo "<script>console.log('No results.')</script>";
		}
	}

	// When attempt is complete, connection closes
    mysqli_close($connekt);
	
?>
<!DOCTYPE html>
<html>
<head>
    <title>Manage Observation</title>
	<?php echo $stylesAndSuch; ?>   
	<style>
		#map {
			width: 300px;
			height: 300px;
		}
	</style>
</head>
<body onload="initMap()">
	<div class="container">
	<nav class='navbar navbar-default'>	
		<div id='header' class='container-fluid'>
			<h1 class="hide"><a href="index.php">Stakeout</a></h1>
			<?php 
				if ($jefe == 1) {
					echo $navbarJefe;
				}
				else {
					echo $navbarGator;
				}
			?>
	
		</div> <!-- /container-fluid -->   
	</nav> <!-- /navbar -->	
    <!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title">Manage Observation</h3></div>
			<div class="panel-body">
				<!-- Panel Content -->
                
<!-- SHIZZLE GOES HERE -->

<div class='row'>
	<div class='col-md-3'><strong>Case: </strong></div>
	<div class='col-md-9'><?php echo $caseName; ?> </div>
</div>

<div class='row'>
	<div class='col-md-3'><strong>Uploaded Asset:</strong></div>
	<div class='col-md-9'><a href='caseAssets/<?php echo $caseID . '/' . $observeAsset; ?>'><img class='img-thumb' src='caseAssets/<?php echo $caseID . '/' . $observeAsset; ?>' width='300px' height='auto'></a></div>
</div>

<div class='row'>
	<div class='col-md-3'><strong>Action:</strong></div>
	<div class='col-md-9'><?php echo $action; ?> </div>
</div>

<div class='row'>
	<div class='col-md-3'><strong>Investigator:</strong> </div>
	<div class='col-md-9'><?php echo $forename . " " . $surname; ?> </div>
	</div>

<div class='row'>
	<div class='col-md-3'><strong>Description:</strong></div>
	<div class='col-md-9'><?php echo $description; ?></div>
</div>

<div class='row'>
	<div class='col-md-3'><strong>Photos Available:</strong> </div>
	<div class='col-md-9'><?php echo $available; ?></div>
</div>

<div class='row'>
	<div class='col-md-3'><strong>Date:</strong></div>
	<div class='col-md-9'><?php echo $formatted_date_long=date_format($ourTime, 'F jS, Y'); ?></div>
</div>

<div class='row'>
	<div class='col-md-3'><strong>Time:</strong></div>
	<div class='col-md-9'><?php echo $formatted_date_long=date_format($ourTime, 'g:i a'); ?></div>	
</div>

<?php

	if ($nowhere) {
		echo "<div class='row'><div class='col-md-3'><strong>Location:</strong></div><div class='col-md-9'>No coordinates</div></div>";
	} else {
		echo "<div class='row'><div class='col-md-3'><strong>Location:</strong></div><div class='col-md-9'><div id='map' class='allThumbs'></div></div></div>";
	}

?>
	
<!-- /SHIZZLE WENT THERE -->

			</div>
		</div>
	</div> <!-- /container -->

<!-- google map -->
<script>
	function initMap() {
		var myLatLng = {lat: <?php echo $latitude; ?>, lng: <?php echo $longitude; ?>};  
		/*
		infoWindow = new google.maps.InfoWindow();
		*/
		
		var map = new google.maps.Map(document.getElementById('map'), {
			center: myLatLng,
			zoom: 15
		});
		
		var marker = new google.maps.Marker({
			map: map,
			position: myLatLng,
			title: 'Where I was'
		});
	}
</script>
<script src='https://maps.googleapis.com/maps/api/js?key=AIzaSyDQS5VSppsf0VpNd8le9EexkJlIleZIQ04&callback=initMap' async defer></script>
<!-- /google map -->

<?php echo $scriptsAndSuch; ?>
</body>
</html>