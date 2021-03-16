<?php 
    //$artistSpotID = $_GET['artistSpotID'];
    //$artistMBID = $_GET['artistMBID'];
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
	
        .tooltip {
            position: absolute;
            text-align: left;
            color: black;
            width: 220px;
            height: 42px;
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

    <div class="panel panel-primary">
		<div class="panel-heading">
			<h3 id="name" class="panel-title">Album Playcounts from Last.fm</h3>
		</div>
		<div class="panel-body">
            <div id="popchart">
            </div>
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

const formatNums = d3.format(","); 
/*
const formatNums = d3.format(",.2r");                   
*/	
d3.json("data_text/BlackSabbathalbumsLastFM_083019.json", function(dataset) {

	console.log(dataset);

    let firstYear, lastYear, minPlays, maxPlays; // For xScale, yScale
    let asof; // for Panel Heading

	const artistTitle = d3.select("#name")
		                  .text(artistName + "'s albums' LastFM playcount as of August 30, 2019");
	
	const xScale = d3.scaleLinear()
					 .domain([1970, 2015])
					 .range([margin.left, w-margin.right]);

	const yScale = d3.scaleLinear()
					 .domain([0, 15000000])
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
			return d.art;
		})
		.attr("x", function (d,i) {
			released = parseInt(d.year);
			return xScale(released);
		})
		.attr("y", function(d) {
			playcount = parseInt(d.plays);
			return yScale(playcount);
		})
		.attr("width", 64)
		.attr("height", 64)
	    .attr("transform", "translate(-32, -32)")
        .on("mouseover", function(d){

            let xPosition = parseInt(d3.select(this).attr("x")) + 72;
            let yPosition = parseInt(d3.select(this).attr("y")) + 32 + 64 + 32; // compensate for translate + image height + half image height

            let albumPlays = formatNums(d.plays);

            tooltip.transition()
                   .duration(200)
                   .style("opacity", .9);
            tooltip.html(
                        //"<a href='" + d.URL + "' target='_blank'>" + d.Name + "</a>" +
                        d.title + " (" + d.year + ")" +
                        "<br>" + albumPlays)
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

    const sabbathLogo = "art-logo/sabbath_Logo.png";

    d3.select("svg")
      .append("svg:image")
	  .attr("xlink:href", sabbathLogo)
      .attr("x", w/2 - 290)
      .attr("y", 0);

});		
</script>	


<?php echo $scriptsAndSuch; ?>


</body>

</html>