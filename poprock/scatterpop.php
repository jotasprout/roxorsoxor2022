<?php 
	require_once 'page_pieces/stylesAndScripts.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Albums Scatterplot | PopRock</title>
	
	<?php echo $stylesAndSuch; ?>
	<style>
		.axis path,
		.axis line {
			fill: none;
			stroke: white;
			shape-rendering: crispEdges;
		}
		
		.axis text {
			font-family: sans-serif;
			fill: white;
		}
	
	</style>
</head>

<body>

<div class="container-fluid">
	
<div id="fluidCon"></div> <!-- end of fluidCon -->

    <div class="panel panel-primary">
		<div class="panel-heading">
			<h3 id="name" class="panel-title">ScatterPop</h3>
		</div>
		<div class="panel-body">
			<div id="popchart"></div>
		</div> <!-- panel body -->
	</div> <!-- close Panel Primary -->

</div> <!-- close container-fluid -->

<script type="text/javascript">
	
	
const w = 1800;
const h = 800;
const margin = {
	top: 25,
	right: 25,
	bottom: 25,
	left: 25
};
	
//const year = timeFormat('%Y');

const rowConverter = function(d){
	return {
		title: d.title,
		year: parseTime(d.year),
		plays: d.plays
	};
};
	
const artistName = "Black Sabbath";
	
d3.json("data_text/BlackSabbathalbumsLastFM.json", function(dataset) {
	console.log(dataset);

	const artistTitle = d3.select("#name")
		.text(artistName + "'s albums' current popularity on Spotify");


	
	const xScale = d3.scaleLinear()
					 .domain([1970, 2020])
					 .range([margin.left, w-margin.right]);

	const yScale = d3.scaleLinear()
					 .domain([0, 15000000])
					 .range([h-margin.bottom, margin.top]);

	var svg = d3.select("#popchart")
		.append("svg")
		.attr("width", w)
		.attr("height", h);

	svg.selectAll("circle")
		.data(dataset)
		.enter()
		.append("circle")
		.attr("cx", function (d) {
			released = parseInt(d.year);
			return xScale(released);
		})
		.attr("cy", function(d) {
			playcount = parseInt(d.plays);
			return yScale(playcount);			
		})
		.attr("r", 5)
		.style("fill", "white")
		.append("title")
		.text(function(d){
			return d.title;
		});
	
	const formatYear = d3.format("d");
	
	const xAxis = d3.axisBottom()
					.scale(xScale)
					.tickFormat(formatYear);
	
	svg.append("g")
		.attr("class", "axis")
	   .attr("transform", "translate(0," + (h-margin.bottom) + ")")
	   .call(xAxis);

});		
</script>	


<?php echo $scriptsAndSuch; ?>

<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbarIndex.js"></script>
</body>

</html>