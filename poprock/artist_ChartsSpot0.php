<?php 
    $artistSpotID = $_GET['artistSpotID'];
    $artistMBID = $_GET['artistMBID'];
    require_once 'page_pieces/stylesAndScripts.php';
    
    $artistArtMBFilepath = "https://www.roxorsoxor.com/poprock/artist-art/";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Artist Spotify Charts | PopRock</title>
	<?php echo $stylesAndSuch; ?>
	<style type="text/css">
		.line {
			fill: none;
			stroke: #00BFFF;
			stroke-width: 2;
		}

        #title {
            font-size: 24px;p
            font-weight: bold;
            fill: white;
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


<div class="container-fluid">
<div id="fluidCon"></div> <!-- end of fluidCon -->
    
  <!-- Breadcrumbs start -->
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="../index.php">Spotify Artists</a></li>
    <li class="breadcrumb-item active">Artist Name</li>
</ol>
<!-- Breadcrumbs end -->


<a role="button" class="btn btn-warning btn-sm" href="forms/edit_ArtistWithGenres.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>">Edit this Artist</a>
    
<a role="button" class="btn btn-success btn-sm" href="forms/add_artistAssocArtist.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>">Add Associated Artist</a>

<div class="row"> <!-- Start of Row 1 -->
	<div class="col-md-6"> <!-- Start of Row #1 Column 1 -->		
	<div class="panel panel-primary"> <!-- Start of Left Panel in Row #1 -->
		<div class="panel-heading">
			<h3 class="panel-title" id="topHead">Current Stats for </h3>
		</div> <!-- close panel-heading -->
        <div class="panel-body">
            <div class="col-md-6"> <!-- Start of Column 1-1 -->
                <img id="forArt">
            </div> <!-- End of Column 1-1 -->
            <!--
		   <div class="row">
-->
				<div class="col-md-6"><!-- Start of Stats Table -->
					<ul class="list-group">
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Popularity on Spotify
							<span class="badge badge-primary badge-pill" id="forCurrentPopularity"></span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Followers on Spotify
							<span class="badge badge-primary badge-pill" id="forCurrentFollowers"></span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Listeners on LastFM
							<span class="badge badge-primary badge-pill" id="forCurrentListeners">No data yet</span>
						</li>
						<li class="list-group-item d-flex justify-content-between align-items-center">
							Playcount on LastFM
							<span class="badge badge-primary badge-pill" id="forCurrentPlaycount">No data yet</span>
						</li>
					</ul>
				</div> <!-- End of Column Stats Table -->
                <!--
		   </div> 
           -->
           <!-- End of Row in Left Panel Body -->
	  </div> <!-- End of Left Panel Body -->
	</div> <!-- End of Left Panel Primary -->
</div> <!-- End of Row #1 Column 1 -->

	<div class="col-md-6"> <!-- Start of Row #1 Column 2 -->
		<div class="panel panel-primary"> <!-- Start of Right Panel Primary Row #1-->
			<div class="panel-heading">
				<h3 class="panel-title">Associated Artists</h3>
			</div> <!-- close panel-heading -->

			<div class="panel-body">
				<div id="assocArtists"></div> <!-- Associated Artists -->
			</div> <!-- End of Right Panel Body -->
		</div> <!-- End of Right Panel Primary Row #1-->
	</div> <!-- End of Row #1 Column 2 -->
	
</div>	<!-- End of Row #1 -->
	
<!-- START OF ROW #2 WITH POPULARITY LINE GRAPH AND FOLLOWERS LINE GRAPH -->	
	
<div class="row"> <!-- Start of Row 2 -->	
	
	<div class="col-md-6"> <!-- Start of Column 1 -->
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id="artistPop" class="panel-title">This artist's popularity on Spotify over time</h3>
			</div> <!-- close panel-heading -->

			<div class="panel-body">
				<div id="forArtistChart"></div> <!-- close forChart -->
			</div> <!-- panel body -->
		</div> <!-- close Panel Primary -->
	</div> <!-- End of Column 1 -->

	<div class="col-md-6"> <!-- Start of Column 2 -->
		<div class="panel panel-primary">
			<div class="panel-heading">
				<h3 id="artistPlaycounts" class="panel-title">This artist's daily LastFM playcounts</h3>
			</div> <!-- close panel-heading -->

			<div class="panel-body">
				<div id="forPlaycountChart"></div> <!-- close forChart -->
			</div> <!-- panel body -->
		</div> <!-- close Panel Primary -->
	</div> <!-- End of Column 2 -->
</div> <!-- End of row 2 -->	
	
	<!-- START OF ROW #3 WITH ALBUMS COLUMNS -->
	
    <div class="panel panel-primary">
		<div class="panel-heading">
			<h3 id="albumPop" class="panel-title">This Artist's Albums Current Popularity</h3>
		</div>

		<div class="panel-body">
			<div id="recordCollection"></div>
		</div> <!-- panel body -->
	</div> <!-- close Panel Primary -->

</div> <!-- close container -->

<script>
	
d3.json("functions/get_assocArtists.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>", function (assocData) {

	console.log(assocData);
	
	const assocFaces = d3.select("#assocArtists");
	
	assocFaces.selectAll("img")
			  .data(assocData)
			  .enter()
			  .append("img")
			  .attr("src", function (d){
				if (d.artistArtSpot == "" || d.artistArtSpot == null || d.artistArtSpot == undefined) {
                    let artistArtMBFilepath = '<?php echo $artistArtMBFilepath ?>';
                    let artistArtMBFilename = d.artistArtMB;
                    const prettyFace =  artistArtMBFilepath + artistArtMBFilename;
					console.log("artistArt is " + prettyFace);
					return prettyFace;
				} else {
					console.log("artistArt is " + d.artistArtSpot);
					return d.artistArtSpot;
				};
			  })
			  .attr("x", function (d,i) {
				return i * 65;
			  })
              .attr("height", 166)
              .attr("class", "assocArtistArt")
			  .attr("title", (d) => d.assocArtistName);
});

</script>	

	
	
<script>

var w = 740;
var h = 400;
var padding = 40;

var dataset, xScale, yScale, xAxis, yAxis, line;

d3.json("functions/createArtistSpotD3.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID ?>", function(data) {
    
    console.log(data);
    
    var dataset = data;

    const artistNameSpot = dataset[0].artistNameSpot;

    const nameInTitle = d3.select("title")
            .text(artistNameSpot + "'s current stats on Spotify and LastFM")

    const topHeading = d3.select("#topHead")
            .text(artistNameSpot + "'s current stats on Spotify and LastFM"); 

    const artistTitle = d3.select("#artistPop")
            .text(artistNameSpot + "'s popularity on Spotify over time");   

    const currentPopArtist = dataset[0].pop;

    const currentPop = d3.select("#forCurrentPopularity")
            .text(currentPopArtist);               

    const dataFollowers = dataset[0].followers;
    let followers = String(dataFollowers).replace(/(.)(?=(\d{3})+$)/g,'$1,');

    const artistFollowers = d3.select("#forCurrentFollowers")
            .text(followers);  

    const artistArtSpot = dataset[0].artistArtSpot;

    d3.select("#forArt")
            .data(dataset)
            .attr("src", artistArtSpot)
            .attr("height", 166);
            //.attr("width", auto)

    dataset.forEach(function(d) {
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
               //.domain(d3.extent(data, function(d) { return d.pop; }))
               // Above is lowest to highest
               // Below is 0-100 
               .domain([0, 100])
               .range([h - padding, padding]);

    const xAxis = d3.axisBottom()
                    .scale(xScale)
                    .tickFormat(d3.timeFormat("%b"));

    const yAxis = d3.axisLeft()
                    .scale(yScale);

    var line = d3.line()
                .x(function(d) { return xScale(d.date); })
                .y(function(d) { return yScale(d.pop); });

    var svg = d3.select("#forArtistChart")
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
<!--  -->
<!--  -->
<script>

var w = 800;
var h = 500;
var padding = 50;

var dataset, xScale, yScale, xAxis, yAxis, line;

d3.json("functions/get_artist_Playcounts.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID ?>", function(data) {
    console.log("Get Artist Playcounts");
    console.log(data);
    
    var dataset = data;

    const artistNameMB = dataset[0].artistNameMB;

    const artistTitle = d3.select("#artistPlaycounts")
                          .text(artistNameMB + "'s Daily LastFM Playcounts");   
	
    dataset.forEach(function(d,i) {
		if (i>0){
			d.date = new Date(d.dataDate);
			let n = i-1;
			let todaysTotal = parseInt(dataset[i].artistPlaycount, 10);
			let yesterPlays = parseInt(dataset[n].artistPlaycount, 10);
			d.todaysPlays = parseInt(todaysTotal - yesterPlays);
			//todaysPlays = +d.todaysPlays;
			//console.log(todaysPlays);
		} else {
			d.date = new Date(d.dataDate);
			d.todaysPlays = +d.artistPlaycount;
		};
    });
	
	console.log(dataset);
	
	dataset.splice(0,2);
	
	console.log(dataset);

    xScale = d3.scaleTime()
                .domain([
                    d3.min(dataset, function(d) { return d.date; }),
                    d3.max(dataset, function(d) { return d.date; })
                ])
                .range([padding, w]);

    yScale = d3.scaleLinear()
               //.domain(d3.extent(data, function(d) { return (d.playcount); }))
			   .domain([
					d3.min(dataset, function(d) { return d.todaysPlays; }),
                    d3.max(dataset, function(d) { return d.todaysPlays; })
				])
               .range([h - padding, padding]);

    const xAxis = d3.axisBottom()
                    .scale(xScale)
                    .tickFormat(d3.timeFormat("%-m/%-d"));

    formatMillions = d3.format(".3s");
/*
    const p = d3.precisionRound(0.01, 1.01),
          f = d3.format("." + p + "r");
*/
    const yAxis = d3.axisLeft()
                    .scale(yScale)
                    .tickFormat(function(d) { return formatMillions(d)});

	var line = d3.line()
                 //.defined(function(d) { return d.todaysPlays > 0})
                 .x(function(d) { return xScale(d.date); })
                 .y(function(d) { return yScale((d.todaysPlays)); });

    var svg = d3.select("#forPlaycountChart")
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
	
	
<script type="text/javascript">
    d3.json("functions/createAlbumsD3.php?artistSpotID=<?php echo $artistSpotID ?>", function(dataset) {
        console.log(dataset);
        // Width and height
        var w = 4800;
        var h = 265;
        var barPadding = 1;

        const artistNameSpot = dataset[0].artistNameSpot;

        const artistTitle = d3.select("#albumPop")
            .text(artistNameSpot + "'s albums' current popularity on Spotify");
        
        // Create SVG element
        var svg = d3.select("#recordCollection")
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
                return h - 64 - (d[4] * 2)
            })
            .attr("width", 64)
            .attr("height", function(d) {
                return (d[4] * 2);
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
                return d[4];
            })
            .attr("text-anchor", "middle")
            .attr("x", function (d, i){
                return i * 65 + 65 / 2;
            })
            .attr("y", function(d){
                return h - 64 - (d[4] * 2) - 5;
            })
            .attr("font-family", "sans-serif")
            .attr("font-size", "11px")
            .attr("fill", "white");
    });		
</script>	

<script>

	var w = 740;
	var h = 500;
	var padding = 50;

	var dataset, xScale, yScale, xAxis, yAxis, line;
	
    d3.json("functions/get_artist_LastFM.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID ?>", function(dataset) {
        
        console.log(dataset);
        
        var data = dataset;

        const dataListeners = data[0].artistListeners;
        let listeners = String(dataListeners).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        const artistListeners = d3.select("#forCurrentListeners")
            .text(listeners);   

        const dataPlaycount = data[0].artistPlaycount;
        let playcount = String(dataPlaycount).replace(/(.)(?=(\d{3})+$)/g,'$1,');
        const artistPlaycount = d3.select("#forCurrentPlaycount")
            .text(playcount); 

    });   

</script>

<?php echo $scriptsAndSuch; ?>

<script>
const artistSpotID = '<?php echo $artistSpotID; ?>';
const artistMBID = '<?php echo $artistMBID ?>';
</script>

<script src="https://www.roxorsoxor.com/poprock/page_pieces/navbar.js"></script>
</body>

</html>