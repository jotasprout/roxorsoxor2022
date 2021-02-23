<?php 
    require "functions/class.artist.php";
	require_once 'page_pieces/stylesAndScripts.php';
    require_once 'page_pieces/navbar_rock.php'; 
    require_once 'data_text/artists_groups.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Selected Artists Popularity Over Time</title>
    
    <?php echo $stylesAndSuch; ?>
    <link rel='stylesheet' href='css/dragDrop.css'>
	<link rel='stylesheet' href='css/lineGraphStyles.css'>
</head>

<body>

<div class="container">
	<?php echo $navbar ?>
    <p>Drag images onto the chart to compare artists.</p>

	<div class="panel panel-primary">

		<div class="panel-body">
		    <div id="dragFrom">

            </div> <!-- close dragFrom -->
		</div> <!-- panel body -->

	</div> <!-- close Panel Primary -->



	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">Selected Artists Popularity Over Time</h3>
		</div>

		<div class="panel-body">
		    <div id="forChart"></div> <!-- close forChart -->
		</div> <!-- panel body -->

	</div> <!-- close Panel Primary -->

</div> <!-- close container -->

<script>

var w = 1000;
var h = 400;

margin = {
    top: 50,
    right: 30,
    bottom: 350,
    left: 50
};

var dataset, xScale, yScale, xAxis, yAxis, line;

d3.json("functions/dragDropCompare.php", function(data) {

        console.log(data);
    
        var dataset = data;

        var parseTime = d3.timeParse("%y-%m-%d");

        const title = "Thrash and Black Metal";

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
                    .range([margin.left, w + margin.left]);


        yScale = d3.scaleLinear()
                .domain([0, 100])
                .range([h + margin.top, margin.top]);
                
        const xAxis = d3.axisBottom()
                        .scale(xScale);

        const yAxis = d3.axisLeft()
                        .scale(yScale);

        var line = d3.line()
                    .x(function(d) { return xScale(d.date); })
                    .y(function(d) { return yScale(d.pop); });

        var svg = d3.select("#forChart")
                        .append("svg")
                        .style("background-color", "black")
                        .attr("width", w + margin.left + margin.right)
                        .attr("height", h + margin.top + margin.bottom);

        const dataNest = d3.nest()
                        .key(function(d) { return d.artistSpotID;})
                        .entries(data);

        const color = d3.scaleOrdinal(d3.schemeCategory20);

        dataNest.forEach(function(d) {
            svg.append("path")
               .attr("class", "line")
               .style("stroke", function(){
                   return d.color = color(d.key);
               })
               .attr("id", 'tag'+d.key.replace(/\s+/g, ''))
               .attr("d", line(d.values));
        })

        svg.append("g")
        .call(xAxis)
        .attr("transform", "translate(0," + (h + margin.top) + ")")
        .attr("class", "axis");

        svg.append("g")
        .call(yAxis)
        .attr("transform", "translate(" + margin.left + ",0)")
        .attr("class", "axis");

        svg.append("text")
        .style("text-anchor", "middle")
        .attr("id", "title")
        .attr("x", (w + margin.left + margin.right)/2)
        .attr("y", 50)
        .text(`${title}`);

        const legendtop = h + margin.top + 40;

        const legend = svg.selectAll(".legend").data(dataNest).enter().append("g")
                          .attr("class", "legend")
                          .attr("transform", function (d,i){
                              xOff = ((i%8)+1) * 110
                              yOff = Math.floor(i/8) * 105 + legendtop
                              return "translate(" + xOff + "," + yOff + ")"
                          });
                          
        legend.append("svg:image")
            .attr("xlink:href", function(d){
                return d.values[0].artistArt;
            })
            .attr("width", 64)
            .attr("height", 64);            

        legend.append("rect")
            .attr("width", 64)
            .attr("height", 64)
            .style("stroke", function(d){
                return d.color = color(d.key);
            })
            .style("stroke-width", 4)
            .style("fill-opacity", 0);

        legend.append("text")
            .style("text-anchor", "middle")
            .attr("class", "artistName")
            .attr("dx", +32)
            .attr("dy", +84)
            .text(function(d) {
                return d.values[0].artistName
            })
            .attr("fill", function(d){
                return d.color = color(d.key);
            });

        legend.on("click", function(d){
            let active = d.active ? false : true,
            newOpacity = active ? 0 : 1;
            d3.select("#tag" + d.key.replace(/\s+/g, ''))
              .transition().duration(100)
              .style("opacity", newOpacity);
            d.active = active;
        });

    })
</script>

<?php echo $scriptsAndSuch; ?>

</body>

</html>