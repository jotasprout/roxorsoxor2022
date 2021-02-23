<?php 
	require_once 'page_pieces/stylesAndScripts.php';
    require_once 'page_pieces/navbar_rock.php'; 
    require_once 'data_text/artists_groups.php';
    require_once 'functions/class.artist.php';
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Artists Compare Line Chart</title>
    
    <?php echo $stylesAndSuch; ?>
    
	<style type="text/css">
		.line {
			fill: none;
			stroke: #00BFFF;
			stroke-width: 2;
		}

        #title {
            font-size: 24px;
            font-weight: bold;
            fill: white;
        }

        .legend {
            font-size: 12px;
        }

        .artistName {
            font-size: 14px;
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
<div id="fluidCon">
</div> <!-- end of fluidCon -->
	<div class="panel panel-primary">
		<div class="panel-heading">
			<h3 class="panel-title">These Artists Followers Over Time</h3>
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
    left: 100
};

var dataset, xScale, yScale, xAxis, yAxis, line;

const $comedians = "Stand-up Comedians";	
const $thrashEtc = "Thrash and Black Metal";
const $longTerm = "Elder Artists with History";
const $allRappers = "All Rappers";
const $xianPunk = "Christian Punk & Alternative";
const $detroitRockers = "Detroit Rock Citizens";
const $latinos = "Alternativo y Rock en Espanol";
const $2019Noms = "2019 Rock and Roll Hall of Fame Nominees";
const $2019Inductees = "2019 Rock and Roll Hall of Fame Inductees";
const $crue = "Motley Crue and Friends";

d3.json("functions/multiArtists_follow2.php", function(data) {

        console.log(data);
    
        var dataset = data;

        var parseTime = d3.timeParse("%y-%m-%d");

        const title = $comedians;

        dataset.forEach(function(d) {
            // date = parseTime(d.date);
            d.date = new Date(d.date);
            d.followers = +d.followers;
        });

        xScale = d3.scaleTime()
                    .domain([
                        d3.min(dataset, function(d) { return d.date; }),
                        d3.max(dataset, function(d) { return d.date; })
                    ])
                    .range([margin.left, w + margin.left]);


        yScale = d3.scaleLinear()
                .domain([
                        d3.min(dataset, function(d) { return d.followers; }),
                        d3.max(dataset, function(d) { return d.followers; })
                    ])
                .range([h + margin.top, margin.top]);
                
        const xAxis = d3.axisBottom()
                        .scale(xScale);

        const yAxis = d3.axisLeft()
                        .scale(yScale);

        var line = d3.line()
                     .x(function(d) { return xScale(d.date); })
                     .y(function(d) { return yScale(d.followers); });

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
<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>

</html>