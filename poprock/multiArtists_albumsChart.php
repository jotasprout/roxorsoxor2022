<?php
    require "functions/class.artist.php";
    require_once 'page_pieces/stylesAndScripts.php';
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Albums Popularity from Spotify</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>


 <div class="container">
	
<div id="fluidCon">
			<nav class="navbar navbar-expand-lg navbar-dark bg-primary">

				<a class="navbar-brand" href="#">PopRock</a>

				<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
				</button>

				<div class="collapse navbar-collapse" id="navbarColor01">

					<ul class="navbar-nav mr-auto">
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/index.php'>Artists<br>Spotify
							<span class="sr-only">(current)</span>
							</a>
						</li>
						<li class="nav-item active">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/indexLastFM.php'>Artists<br>LastFM</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_albumsChart.php'>Related<br>Artists</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_popTimeLines.php'>Over Time<br>Popularity</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_popCurrentColumns.php'>Current<br>Popularity</a>
						</li>				
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/multiArtists_followersCurrentColumns.php'>Current<br>Followers</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/genres/artistsGenres.php'>Genres</a>
						</li>		
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/dragdrop/dragDropArtists.php'>Drag Drop</a>
						</li>			
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/rels.php'>Network</a>
						</li>
													
						<li class="nav-item dropdown">
							<a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Scatter</a>
							<div class="dropdown-menu">
								<a class="dropdown-item" href='https://roxorsoxor.com/poprock/scattercovers.php'>Black Sabbath</a>
								<a class="dropdown-item" href='https://roxorsoxor.com/poprock/scattercovers2.php'>Ronnie James Dio</a>
								<a class="dropdown-item" href='https://roxorsoxor.com/poprock/scattercovers3.php'>Dio</a>
								<div class="dropdown-divider"></div>
								<a class="dropdown-item" href="#">Separated link</a>
							</div>
						</li>
					</ul>

				</div> <!-- end of collapse -->
			</nav> <!-- end of navbar -->		
		</div> <!-- end of fluidCon -->
<form id="selectGroup" action="" method="post">

    <fieldset class="form-group">
        <legend>Groups of Related Artists</legend>
		
		<div class="form-check">
		    <label class="form-check-label">
			<input type="radio" name="options" id="steveTaylor" value="steveTaylor" autocomplete="off" checked=""> Steve Taylor
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
			<input type="radio" name="options" id="joanJett" value="joanJett" autocomplete="off"> Joan Jett
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
			<input type="radio" name="options" id="mikeKnott" value="mikeKnott" autocomplete="off"> Mike Knott
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
			<input type="radio" name="options" id="tomPetty" value="tomPetty" autocomplete="off"> Tom Petty
            </label>
        </div>
        <div class="form-check">
            <label class="form-check-label">
			<input type="radio" name="options" id="iggyPop" value="iggyPop" autocomplete="off"> Iggy Pop
		    </label>
		</div>
    </fieldset>

</form>
	 
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Related Artists' Albums Popularity</h3>
		</div>

		<div class="panel-body">
            <div id="forChart"></div> <!-- /for chart -->
        </div> <!-- panel body -->

	</div> <!-- close Panel Primary -->

</div> <!-- /container -->		 

<script type="text/javascript">



function artistsAssemble (relatives) {
	
	let groupURL = 'functions/multiArtistsAlbumsChartQuery.php?group=';
	
	console.log(relatives + " is inside the artistsAssemble function");
	
	groupURL += relatives;
	
	console.log("the URL is " + groupURL)
	
    d3.json(groupURL, function(dataset) {
        
        // Width and height
        var w = 2400;
        var h = 265;
        var barPadding = 1;
        
        // Maybe this next line will clear old graph before creating new graph
        d3.select('#forChart').html('');

        // Create SVG element
        var svg = d3.select("#forChart")
            .append("svg")
            .attr("width", w)
            .attr("height", h);
        
        // Rectangles
        svg.selectAll("rect")
            .data(dataset)
            .enter()
            .append("rect")
            .attr("x", function (d,i) {
                return i * 65;
            })
            .attr("y", function(d) {
                return h - 64 - (d.pop * 2)
            })
            .attr("width", 64)
            .attr("height", function(d) {
                return (d.pop * 2);
            });
        // Images
        svg.selectAll("image")
            .data(dataset)
            .enter()
            .append("svg:image")
            .attr("xlink:href", function (d){
                return d.albumArtSpot;
                console.log(d.albumArtSpot);
            })
            .attr("x", function (d,i) {
                return i * 65;
            })
            .attr("y", function(d) {
                return h - 64
            })
            .attr("width", 64)
            .attr("height", 64)
            .append("title")
            .text(function(d){
                return d.albumNameSpot;
            });			   
        
        // Labels
        svg.selectAll("text")
            .data(dataset)
            .enter()
            .append("text")
            .text(function(d){
                return d.pop;
            })
            .attr("text-anchor", "middle")
            .attr("x", function (d, i){
                return i * 65 + 65 / 2;
            })
            .attr("y", function(d){
                return h - 64 - (d.pop * 2) - 5;
            })
            .attr("font-family", "sans-serif")
            .attr("font-size", "11px")
            .attr("fill", "white");
    });		
}

let relatives = 'steveTaylor';
	
artistsAssemble (relatives);

$(document).ready(function(){
	
    $('input[type=radio]').click(function() {
        let buttonvalue = this.value;
        switch (buttonvalue) {
            case 'steveTaylor':
				artistsAssemble('steveTaylor');
				break;
            case 'joanJett':
				artistsAssemble('joanJett');
				break;
            case 'mikeKnott':
				artistsAssemble('mikeKnott');
				break;
            case 'tomPetty':
				artistsAssemble('tomPetty');
				break;	
            case 'iggyPop':
				artistsAssemble('iggyPop');
				break;				
        }
    });
});
/**/

</script>				

<?php echo $scriptsAndSuch; ?>	

</body>

</html>