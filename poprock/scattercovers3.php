<?php 
	require_once 'page_pieces/stylesAndScripts.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Dio Albums Spotify Popularity Scatterplot | PopRock</title>
	
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
	
        .tooltip {
            position: absolute;
            text-align: left;
            color: black;
            width: 310px;
            height: 56px;
            padding: 8px;
            font: 12px sans-serif;
            background: lightsteelblue;
            border: 0px;
            border-radius: 8px;
        }

        #logo {
            position: absolute;
        }

	</style>
</head>

<body>

<div class="container-fluid">
	
<div id="fluidCon"></div> <!-- end of fluidCon -->

    <div class="panel panel-primary">
		<div class="panel-heading">
			<h3 id="name" class="panel-title">Ronnie James Dio Albums' Popularity on Spotify</h3>
		</div>
		<div class="panel-body">
            <div id="popchart">
            </div>
		</div> <!-- panel body -->
	</div> <!-- close Panel Primary -->

</div> <!-- close container-fluid -->

<script type="text/javascript">
	
	
const w = 1600;
const h = 900;
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
		spotPop: d.spotPop
	};
};
	
// const artistName = "Black Sabbath";

const formatNums = d3.format(","); 
/*
const formatNums = d3.format(",.2r");                   
*/	
d3.json("data_text/dio-related-data/scatter_Dio_AlbumsSpotPop.json", function(dataset) {

	console.log(dataset);

    let firstYear, lastYear, minspotPop, maxspotPop; // For xScale, yScale
    let asof; // for Panel Heading

	const artistTitle = d3.select("#name")
		                  .text("Ronnie James Dio albums Spotify popularity as of November 29, 2019");
	
	const xScale = d3.scaleLinear()
					 .domain([1970, 2015])
					 .range([margin.left, w-margin.right]);

	const yScale = d3.scaleLinear()
					 .domain([0, 100])
					 .range([h-margin.bottom, margin.top]);

	var svg = d3.select("#popchart")
		.append("svg")
		.attr("width", w)
		.attr("height", h);

    const tooltip = d3.select("#popchart")
                      .append("div")
                      .attr("class", "tooltip")
                      .style("opacity", 0);

	// Images
	svg.selectAll("image")
		.data(dataset)
		.enter()
		.append("svg:image")
		.attr("xlink:href", function (d){
			return d.albumArt;
		})
		.attr("x", function (d,i) {
			released = parseInt(d.year);
			return xScale(released);
		})
		.attr("y", function(d) {
			pop = parseInt(d.spotPop);
			return yScale(pop);
		})
		.attr("width", 64)
		.attr("height", 64)
	    .attr("transform", "translate(-32, -32)")
        .on("mouseover", function(d){

            let xPosition = parseInt(d3.select(this).attr("x")) + 72;
            let yPosition = parseInt(d3.select(this).attr("y")) + 32 + 64 + 32; // compensate for translate + image height + half image height

            let albumspotPop = formatNums(d.spotPop);

            tooltip.transition()
                   .duration(200)
                   .style("opacity", .9);
            tooltip.html(
                        //"<a href='" + d.URL + "' target='_blank'>" + d.Name + "</a>" +
                        d.artistName + 
                        "<br>" + d.title + " (" + d.year + ")" +
                        "<br>Pop: " + albumspotPop)
                  .style("left", xPosition + "px")
                  .style("top", yPosition + "px");
        })
        .on("mouseout", function(d) {
            tooltip.transition()
                   .duration(200)
                   .style("opacity", 0);
        });

	const formatYear = d3.format("d");
	
	const xAxis = d3.axisBottom()
					.scale(xScale)
					.tickFormat(formatYear);
	
	svg.append("g")
		.attr("class", "axis")
	   .attr("transform", "translate(0," + (h-margin.bottom) + ")")
	   .call(xAxis);
/*
    const sabbathLogo = "art-logo/sabbath_Logo.png";

    d3.select("svg")
      .append("svg:image")
	  .attr("xlink:href", sabbathLogo)
      .attr("x", w/2 - 290)
      .attr("y", 0);
*/
});		
</script>	


<?php echo $scriptsAndSuch; ?>

<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbarIndex.js"></script>
</body>

</html>