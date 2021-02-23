<?php
    require "functions/class.artist.php";
    require_once 'page_pieces/stylesAndScripts.php';
    require_once 'page_pieces/navbar_rock.php';
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
                return d.albumName;
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
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>

</html>