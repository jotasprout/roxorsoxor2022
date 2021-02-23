<?php
	require_once '../page_pieces/stylesAndScripts.php';
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="user-scalable=no, width=device-width" />
<meta charset="UTF-8">
<title>Add New MusicBrainz Artist</title>
<?php echo $stylesAndSuch; ?>
</head>
<body>
<div class="container-fluid">	
		
	<div id="fluidCon"></div> <!-- end of fluidCon -->
	
	<!-- main -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Add New MusicBrainz Artist</h3>
		</div>
		<div class="panel-body">
		<!-- Panel Content -->

			<form class="form-horizontal" action="../functions/addArtistMB.php" method="post">
				
				<fieldset> 				       
					<div class="form-group"> <!-- Artist MBID --> 			
						<label class="col-lg-2 control-label" for="artistMBID">MBID</label>			
						<div class="col-lg-3">
							<input class="form-control" type="text" name="artistMBID" value="" />
						</div>
					</div> <!-- / Artist MBID -->
				
					<!-- Last Row -->
					<div class="form-group"> <!-- Last Row -->	
						<div class="col-lg-4 col-lg-offset-2">
							<button class="btn btn-primary" type="submit" name="submit">Insert</button>
						</div>
					</div>
					<!-- /Last Row -->
				</fieldset>
			</form>
		</div> <!-- /panel-body -->
	</div> <!-- /panel IS THIS PRIMARY? -->

</div> <!-- /container-fluid --> 

<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbarIndex.js"></script>
</body>
</html>