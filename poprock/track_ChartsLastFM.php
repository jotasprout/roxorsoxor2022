<?php 
    $trackSpotID = $_GET['trackSpotID'];
	require_once 'page_pieces/stylesAndScripts.php';
	require_once 'page_pieces/navbar_rock.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>This Track's Last FM stats</title>
	<?php echo $stylesAndSuch; ?>
	<style type="text/css">
		.line {
			fill: none;
			stroke: #00BFFF;
			stroke-width: 2;
		}

        .axis {
            font-size: 14px;
        }

        .axis line {
            stroke: yellow;
        }

        .axis path {
            stroke: yellow;
        }

        .axis text {
            fill: yellow;
        }
	</style>
</head>

<body>

<div class="container">
	<?php echo $navbar ?>

    <!--
        Get trackSpotID from URL
        fetch trackName, artistName, artistArt, albumName, albumArtSpot and trackPop using that artistSpotID
        Display trackName, artistName, artistArt, albumName, albumArtSpot and trackPop
    -->

	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">This Track's Last FM stats</h3>
		</div>

		<div class="panel-body">
		<div id="forChart"></div> <!-- close forChart -->
		</div> <!-- panel body -->

	</div> <!-- close Panel Primary -->

</div> <!-- close container -->

<script>

var w = 1100;
var h = 400;
var padding = 40;

var dataset, xScale, yScale, xAxis, yAxis, line;

d3.json("functions/createTrackD3.php?trackSpotID=<?php echo $trackSpotID ?>", function(data) {
    
    console.log(data);
    
    var dataset = data;

    var parseTime = d3.timeParse("%y-%m-%d");

    dataset.forEach(function(d) {
        // date = parseTime(d.date);
        d.date = new Date(d.date);
        d.pop = +d.pop;
    });

    xScale = d3.scaleTime()
                .domain([
                    d3.min(dataset, function(d) { return d.date; }),
                    d3.max(dataset, function(d) { return d.date; })
                ])
                .range([padding, w - padding]);


    yScale = d3.scaleLinear()
               .domain(d3.extent(data, function(d) { return d.pop; }))
               .range([h - padding, padding]);
               
    const xAxis = d3.axisBottom()
                    .scale(xScale);

    const yAxis = d3.axisLeft()
                    .scale(yScale);

    var line = d3.line()
                .x(function(d) { return xScale(d.date); })
                .y(function(d) { return yScale(d.pop); });

    var svg = d3.select("#forChart")
                    .append("svg")
                    .attr("width", w)
                    .attr("height", h);

    svg.append("path")
        .datum(dataset)
        .attr("class", "line")
        .attr("d", line);

    svg.append("g")
       .call(xAxis)
       .attr("transform", "translate(0," + (h - padding) + ")")
       .attr("class", "axis");

    svg.append("g")
       .call(yAxis)
       .attr("transform", "translate(" + padding + ",0)")
       .attr("class", "axis");

});

</script>

<?php echo $scriptsAndSuch; ?>

</body>

</html>