<?php
require_once 'page_pieces/stylesAndScripts.php';
?>

<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Compare Followers Bar Chart</title>
	<?php echo $stylesAndSuch; ?>
</head>

<body>

 <div class="container">
 <div id="fluidCon">
</div> <!-- end of fluidCon -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">These Artists' Current Followers On Spotify</h3>
		</div>

		<div class="panel-body">
            <div id="forChart"></div> <!-- /for chart -->
        </div> <!-- panel body -->

	</div> <!-- close Panel Primary -->

    
</div> <!-- /container -->		 

<script type="text/javascript">
    d3.json("functions/multiArtistsFollowersColumnsD3.php", function(dataset) {
        console.log(dataset);
        // Width and height
        var w = 2400;
        var h = 1200;
        var barPadding = 1;
        const widen = dataset.length;
        console.log(widen);
        
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
                return h - 64 - (d.followers / 5000)
            })
            .attr("width", 64)
            .attr("height", function(d) {
                return (d.followers / 5000);
            });
          
        // Images
        svg.selectAll("image")
            .data(dataset)
            .enter()
            .append("svg:image")
            .attr("xlink:href", function (d){
                return d.artistArtSpot;
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
                return d.artistNameSpot;
            });			   
        
        // followers Labels atop columns
        svg.selectAll("text")
            .data(dataset)
            .enter()
            .append("text")
            .text(function(d){
                //return d.followers;
                let followTip = '';
                f = d.followers;
                if (f < 1000000) {
                    followNum = f/1000;
                    followNum = followNum.toFixed(0);
                    followTip = followNum + " k";
                    console.log(followTip);
                    return followTip;
                } else {
                    followNum = f/1000000;
                    followNum = followNum.toFixed(1);
                    followTip = followNum + " M";
                    console.log(followTip);
                    return followTip;                    
                };
            })
            .attr("text-anchor", "middle")
            .attr("x", function (d, i){
                return i * 65 + 65 / 2;
            })
            .attr("y", function(d){
                return h - 64 - (d.followers / 5000) - 5;
            })
            .attr("font-family", "sans-serif")
            .attr("font-size", "12px")
            .attr("fill", "white");
    });		
</script>				


		<?php echo $scriptsAndSuch; ?>	
        <script src="https://www.roxorsoxor.com/poprock/page_pieces/navbarIndex.js"></script>
</body>

</html>