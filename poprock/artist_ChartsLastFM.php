<?php 
    $artistMBID = $_GET['artistMBID'];
    $artistSpotID = $_GET['artistSpotID'];
    require_once 'page_pieces/stylesAndScripts.php';
    
    $artistArtMBFilepath = "https://www.roxorsoxor.com/poprock/artist-art/";
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Artist LastFM Charts | PopRock</title>
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
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/indexLastFM.php'>Artists<br>LastFM</a>
						</li>

						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_AlbumsListSpot.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=spotify'>Albums<br>Spotify</a>
						</li>  
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_AlbumsListLastFM.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=musicbrainz'>Albums<br>LastFM</a>
						</li>                                                
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_TracksListSpot.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=spotify'>Tracks<br>Spotify</a>
						</li>  
						<li class="nav-item">
							<a class="nav-link" href='https://roxorsoxor.com/poprock/artist_TracksListLastFM.php?artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=musicbrainz'>Tracks<br>LastFM</a>
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
<!-- 
    <p>If, after the page loads, it is empty, or the wrong discography displays, <a href='https://www.roxorsoxor.com/poprock/index.php'>choose an artist</a> from the <a href='https://www.roxorsoxor.com/poprock/index.php'>Artists List</a> first.</p>
 -->
    
<a role="button" class="btn btn-warning btn-sm" href="forms/edit_Artist.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>">Edit this Artist</a>
    
<a role="button" class="btn btn-success btn-sm" href="forms/add_artistAssocArtist.php?artistSpotID=<?php echo $artistSpotID ?>&artistMBID=<?php echo $artistMBID ?>">Add Associated Artist</a>

 <div class="row"> <!-- Start of Row 1 -->
    <div class="col-md-6"> <!-- Start of Row #1 Column 1 -->
        <div class="panel panel-primary"> <!-- Start of Left Panel Primary -->
            <div class="panel-heading">
                <h3 class="panel-title" id="topHead">Current Stats for </h3>
            </div> <!-- close panel-heading -->
            <div class="panel-body"> <!-- Start of Left Panel Body -->
                <div class="col-md-6"> <!-- Start of Art -->
                    <img id="forArt">
                </div> <!-- End of Art -->
                <div id="row"> 

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
                    </div> <!-- End of Stats Table -->
                </div> <!-- End of Row in Left Panel Body -->
            </div> <!-- End of Left Panel Body -->
        </div> <!-- End of Left Panel Primary -->
    </div> <!-- End of Row #1 Column 1 -->

    <div class="col-md-6"> <!-- Start of Row #1 Associated Artists -->
        <div class="panel panel-primary"> <!-- Start of Right Panel Primary Row #1-->
            <div class="panel-heading">
                <h3 class="panel-title">Associated Artists</h3>
            </div> <!-- close panel-heading -->

            <div class="panel-body">
                <div id="assocArtists"></div> <!-- Associated Artists -->
            </div> <!-- End of Right Panel Body -->
        </div> <!-- End of Right Panel PrimaryRow #1-->
    </div> <!-- End of Row #1 Column 2 -->

</div>	<!-- End of Row #1 -->
	
<!-- START OF ROW #2 WITH POPULARITY LINE GRAPH AND FOLLOWERS LINE GRAPH -->

<div class="row"> <!-- Start of Row 2 -->	

    <div class="col-md-6"> <!-- Start of Column 2 -->
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 id="" class="panel-title">This artist's sick things</h3>
            </div> <!-- close panel-heading -->

            <div class="panel-body">
                <div id=""></div> <!-- close  -->
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
</div class="row"> <!-- End of Row 2 -->	

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

const artistArtMBFilepath = '<?php echo $artistArtMBFilepath ?>';
console.log(artistArtMBFilepath);
    
var w = 740;
var h = 400;
var padding = 40;

var dataset, xScale, yScale, xAxis, yAxis, line;
    
d3.json("functions/get_artist_LastFM.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID ?>", function(dataset) {

    console.log(dataset);
    const data = dataset;
    console.log(data);

    const artistNameMB = dataset[0].artistNameMB;
    console.log(artistNameMB);

    const artistTitle = d3.select("#albumPop")
        .text(artistNameMB + "'s albums' current popularity on Spotify");

    const nameInTitle = d3.select("title")
            .text(artistNameMB + "'s current stats on Spotify and LastFM")    

    const topHeading = d3.select("#topHead")
            .text(artistNameMB + "'s current stats on Spotify and LastFM");   

    const artistArtMBFilename = dataset[0].artistArtMBFilename;
        console.log(artistArtMBFilename);
    const artistArtMB = artistArtMBFilepath + artistArtMBFilename;
        console.log(artistArtMB);

    d3.select("#forArt")
            .data(dataset)
            .attr("src", artistArtMB)
            .attr("height", 166);
            //.attr("width", auto)
    
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
    
<script>

var w = 740;
var h = 400;
var padding = 40;

var dataset, xScale, yScale, xAxis, yAxis, line;

d3.json("functions/createArtistSpotD3.php?artistSpotID=<?php echo $artistSpotID; ?>&artistMBID=<?php echo $artistMBID ?>", function(data) {
    
    console.log(data);
    
    var dataset = data;

    const artistNameSpot = dataset[0].artistNameSpot;

    const currentPopArtist = dataset[0].pop;

    const currentPop = d3.select("#forCurrentPopularity")
            .text(currentPopArtist);               

    const dataFollowers = dataset[0].followers;
    let followers = String(dataFollowers).replace(/(.)(?=(\d{3})+$)/g,'$1,');

    const artistFollowers = d3.select("#forCurrentFollowers")
            .text(followers);  

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
                    let artistArtMBFilename = d.artistArtMBFilename;
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
	
	
<script type="text/javascript">
    d3.json("functions/createAlbumsD3.php?artistSpotID=<?php echo $artistSpotID ?>", function(dataset) {
        console.log(dataset);
        // Width and height
        var w = 2400;
        var h = 265;
        var barPadding = 1;
        
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
                return d.albumName;
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



<?php echo $scriptsAndSuch; ?>
    
<script>
const artistSpotID = '<?php echo $artistSpotID; ?>';
const artistMBID = '<?php echo $artistMBID ?>';
</script>


</body>

</html>