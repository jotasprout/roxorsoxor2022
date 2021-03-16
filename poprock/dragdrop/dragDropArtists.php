<?php 
    require_once '../rockdb.php';
    require_once '../page_pieces/stylesAndScripts.php';
?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Drag-n-Drop</title>
    <?php echo $stylesAndSuch; ?>  
    <link rel='stylesheet' href='dragDrop.css'>
</head>

<body>

<div class="container">

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
						<li class="nav-item active">
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
			<h3 class="panel-title">Drag and Drop Artists</h3>
		</div>
		<div class="panel-body">
            <div id="forD3"></div> <!-- /for chart -->
        </div> <!-- panel body -->
	</div> <!-- close Panel Primary -->
</div> <!-- /container -->

<script>
    
const w = 850;
const h = 800;
	
const margin = {
	top: 20,
	right: 20,
	bottom: 20,
	left: 20
};
	
const spacepadding = 10;
    
const innerTo = {
    top: h/2 - margin.bottom + spacepadding,
    right: w - margin.right + spacepadding,
    left: margin.left + spacepadding,
    bottom: margin.bottom + spacepadding
};
    
const svg = d3.select("#forD3")
			  .append("svg")
			  .attr("width", w)
              .attr("height", h);
              
let chosenBoxReady = false;
console.log("chosenBoxReady is " + chosenBoxReady);
let choicesBoxReady = false;
console.log("choicesBoxReady is " + choicesBoxReady);
let choiceItemReady = false;
let chosenItemReady = false;

const choicesBox = svg.append("rect")
					.attr("id", "choicesBox")
					.style("fill", "red")
					.attr("x", margin.left)
					.attr("y", margin.top)
					.attr("class", "choicesBox")
					.attr("width", w - (margin.left + margin.right))
					.attr("height", 240)
				    .on("mouseover", function(){
                      if (chosenItemReady == true){
                          choicesBoxReady = true;
                          console.log("choicesBoxReady is " + choicesBoxReady);
                      };
				    })
				    .on("mouseout", function(){
                        choicesBoxReady = false;
                        console.log("choicesBoxReady is " + choicesBoxReady);
				    });
    
const chosenBox = svg.append("rect")
				  .attr("id", "chosenBox")
				  .attr("fill", "blue")
				  .attr("x", margin.left)
				  .attr("y", h/2 - (margin.top + margin.bottom))
				  .attr("class", "chosenBox")
				  .attr("width", w - (margin.left + margin.right))
				  .attr("height", h/2 + margin.top)
				  .on("mouseover", function(){
                      if (choiceItemReady == true){
                          chosenBoxReady = true;
                          console.log("chosenBoxReady is " + chosenBoxReady);
                      };
				  })
				  .on("mouseout", function(){
					chosenBoxReady = false;
					console.log("chosenBoxReady is " + chosenBoxReady);
				  });
    
d3.json("dragDropCompare.php", function (dataset) {
   let thechoices = dataset;
   
   let thechosen = thechoices.splice(0,5);


   const key = function (d) {
        return d.artistSpotID;
    }
    
   console.log("Choices now contains");
   console.log(thechoices);
   console.log("Chosen now contains");
   console.log(thechosen);

   let firstChoices = svg.selectAll(".choice")
                          .data(thechoices);
    
   let firstChosen = svg.selectAll(".chosen")
                          .data(thechosen, key); 
    
    function fillChoicesBox(whichChoices){
        whichChoices.enter()
                    .append("svg:image")
                    .attr("xlink:href", function(d){
                        return d.artistArtSpot;
                    })
                    .attr("x", function (d,i){
                        x = (i%10) * 75 + margin.left + spacepadding;
                        return x;
                    })
                    .attr("y", function (d,i){
                        y = Math.floor(i/10) * 75 + margin.top + spacepadding;
                        return y;
                    })                    
                    .attr("data-artistName", (d) => d.artistNameSpot)
                    .attr("data-artistPop", (d) => d.pop)
                    .attr("data-artistSpotID", (d) => d.artistSpotID)
                    .attr("data-popDate", (d) => d.date)
                    .attr("class", "choice")
                    .append("title")
                    .text((d) => d.artistNameSpot);
                    //.attr("initial-x", (d) => d.x)
                    //.attr("initial-y", (d) => d.y);       
    };      
    
   function fillChosenBox(whichChosen){
        whichChosen.enter()
                   .append("svg:image")
                   .attr("xlink:href", function (d){
                        return d.artistArtSpot;
                    })
                    .attr("x", function (d,i) {
                        return innerTo.left + (i * 65);
                    })
                    .attr("y", function(d) {
                        return h - innerTo.bottom - 64;
                    })
                    .attr("width", 64)
                    .attr("height", 64)
                    .attr("data-artistName", (d) => d.artistNameSpot)
                    .attr("data-artistPop", (d) => d.pop)
                    .attr("data-artistSpotID", (d) => d.artistSpotID)
                    .attr("data-popDate", (d) => d.date)
                    .attr("class", "chosen")
                    .append("title")
                    .text((d) => d.artistNameSpot);
                   //.attr("initial-x", (d) => d.x)
                   //.attr("initial-y", (d) => d.y);        
    };
    
    function makeColumns(){
        svg.selectAll(".column")
           .data(thechosen, key)
           .enter()
           .append("rect")
           .attr("x", function (d,i) {
                return innerTo.left + (i * 65);
            })
           .attr("y", function(d) {
                return h - innerTo.bottom - 64 - (d.pop * 2)
           })
           .attr("width", 64)
           .attr("height", function(d) {
                return (d.pop * 2);
           })
           .attr("class", "column");
    };
    
    // Popularity text Labels atop columns
    function makeColumnLabels(){
      svg.selectAll("text")
	   .data(thechosen, key)
	   .enter()
	   .append("text")
	   .text(function(d){
			return d.pop;
	   })
	   .attr("text-anchor", "middle")
	   .attr("x", function (d, i){
			return innerTo.left + (i * 65 + 65 / 2);
	   })
	   .attr("y", function(d){
			return h - innerTo.bottom - 64 - (d.pop * 2) - 5;
	   })
	   .attr("font-family", "sans-serif")
	   .attr("font-size", "11px")
	   .attr("fill", "white");  
    }
    
	function choicetochosen(aChoice){
        // Add this artist to theChosen
        thechosen.push(aChoice);
        console.log ("Added " + aChoice.artistNameSpot + " to Chosen.");
        console.log(thechosen);
        // Remove this artist from theChoices
        let oldindex = thechoices.indexOf(aChoice);
        thechoices.splice(oldindex, 1);
        console.log ("Removed " + aChoice.artistNameSpot + " from Choices.");
        console.log(thechoices);
    };
    
    function chosentochoice(aChosen){
        // Add this artist to theChoices
        thechoices.push(aChosen);
        console.log ("Added " + aChosen.artistNameSpot + " to Choices.");
        console.log(thechoices);
        // Remove this artist from theChosen
        let oldindex = thechosen.indexOf(aChosen);
        thechosen.splice(oldindex, 1);
        console.log ("Removed " + aChosen.artistNameSpot + " from Chosen.");
        console.log(thechosen);
    };
    
    // CHOICE HANDLER
    const choiceHandler = d3.drag()
        .on("start", function (d){
            console.log ("Picked up " + d.artistNameSpot + " from Choices");
            choiceItemReady = true;
            console.log("choiceItemReady is " + choiceItemReady);
        })
        .on("drag", function (d) {
            const mouse = d3.mouse(this);
            const picWidth = 64;
            const picHeight = 64;
            console.log ("Dragging " + d.artistNameSpot);
            d3.select(this)
              // the event x and y under Start was here
              .attr("x", (mouse[0])-picWidth/2)
              .attr("y", (mouse[1])-picHeight/2)
              .attr("pointer-events", "none");
        })
        // CHOICE TO CHOSEN END
        .on("end", function (d) {
            
            choiceItemReady = false;
            // I think above needs to go last. APPARENTLY NOT!
            console.log("choiceItemReady is " + choiceItemReady);
            
            if (chosenBoxReady == true){
                
                choicetochosen(d);
                
                // Update images in Choices, remove from Choices and move others over

                d3.select(this)
                  .attr("pointer-events", "auto");

                let aa = svg.selectAll(".choice")
                            .data(thechoices, key);
                
                aa.enter()
                 .append("svg:image")
                 .merge(aa)              
                 .attr("xlink:href", function(d){
                    return d.artistArtSpot;
                 })
                 .attr("x", function (d,i){
                    x = (i%10) * 75 + margin.left + spacepadding;
                    return x;
                })
                .attr("y", function (d,i){
                    y = Math.floor(i/10) * 75 + margin.top + spacepadding;
                    return y;
                })
                 .attr("data-artistName", (d) => d.artistNameSpot)
                 .attr("data-artistPop", (d) => d.pop)
                 .attr("data-artistSpotID", (d) => d.artistSpotID)
                 .attr("data-popDate", (d) => d.date)
                 .attr("class", "choice")
                 .append("title")
                 .text((d) => d.artistNameSpot);
                 
                 aa.exit()
                  .remove();

               // Update images in theChosen, Add image to theChosen
                
               let ab = svg.selectAll(".chosen")
                           .data(thechosen, key);

                ab.enter()
                 .append("svg:image")
                 .merge(ab)                         
                 .attr("xlink:href", function (d){
                    return d.artistArtSpot;
                 })
                 .attr("x", function (d,i) {
                    return innerTo.left + (i * 65);
                 })
                 .attr("y", function(d) {
                    return h - innerTo.bottom - 64;
                 })
                 .attr("width", 64)
                 .attr("height", 64)
                 .attr("data-artistName", (d) => d.artistNameSpot)
                 .attr("data-artistPop", (d) => d.pop)
                 .attr("data-artistSpotID", (d) => d.artistSpotID)
                 .attr("data-popDate", (d) => d.date)
                 .attr("class", "chosen")
                 .append("title")
                 .text((d) => d.artistNameSpot)
                 .attr("pointer-events", "auto");
                
                ab.exit().remove();  

                // Update text labels, Add label to theChosen
                
                let ad = svg.selectAll("text")
                            .data(thechosen, key);

                ad.enter()
                  .append("text")
                  .attr("opacity", 0)
                  .attr("x", function (d, i){
                    return innerTo.left + (i * 65 + 65 / 2);
                  })
                  .attr("y", function(d){
                    return h - innerTo.bottom - 64 - (d.pop * 2) - 5;
                  })
                  .merge(ad)
                  .transition()
                  .delay(1000)
                  .text(function(d){
                    return d.pop;
                  })
                  .attr("text-anchor", "middle")
                  .attr("x", function (d, i){
                    return innerTo.left + (i * 65 + 65 / 2);
                  })
                  .attr("y", function(d){
                    return h - innerTo.bottom - 64 - (d.pop * 2) - 5;
                  })
                  .attr("font-family", "sans-serif")
                  .attr("font-size", "11px")
                  .attr("fill", "white")
                .attr("opacity", 100);
                
                ad.exit()
                  .remove();                  

               // Update Columns, Add Column to theChosen
                
               let ac = svg.selectAll(".column")
                            .data(thechosen, key);   
                
                ac.enter()
                  .append("rect")
                  
                  .attr("x", function (d,i) {
                    return innerTo.left + (i * 65);
                  })
                  .attr("y", function(d) {
                    return h - innerTo.bottom - 64;
                  })
                  .attr("height", function(d) {
                    return 0;
                  })    
                  .merge(ac)
                  .attr("width", 64)
                  .transition()
                  .delay(500)
                  .duration(500)
                  .attr("y", function(d) {
                    return h - innerTo.bottom - 64 - (d.pop * 2);
                  })
                  .attr("height", function(d) {
                    return (d.pop * 2);
                  })      
                  .attr("class", "column");

                ac.exit()
                  .transition()
                  .duration(500)
                  .remove();

                choiceHandler(svg.selectAll(".choice"));
                chosenHandler(svg.selectAll(".chosen"));
                
            } else {
                d3.select(this)
                    .attr("x", function (d){
                        let j = thechoices.indexOf(d);
                        x = (j%10) * 75 + margin.left + spacepadding;
                        return x;
                    })
                    .attr("y", function (d){
                        let h = thechoices.indexOf(d);
                        y = Math.floor(h/10) * 75 + margin.top + spacepadding;
                        return y;
                    })
                    .attr("pointer-events", "auto");
            };
	   });
 
    
    // CHOSEN HANDLER
    
    const chosenHandler = d3.drag()
        .on("start", function (d){
            console.log ("Picked up " + d.artistNameSpot + " from theChosen");
            chosenItemReady = true; 
            console.log("chosenItemReady is " + chosenItemReady);
        })
        .on("drag", function (d) {
            const mouse = d3.mouse(this);
            const picWidth = 64;
            const picHeight = 64;
            console.log ("Dragging " + d.artistNameSpot);
            d3.select(this)
              // the event x and y under Start was here
              .attr("x", (mouse[0])-picWidth/2)
              .attr("y", (mouse[1])-picHeight/2)
              .attr("pointer-events", "none");
        })
    
        // CHOSEN TO CHOICE END
    
        .on("end", function (d) {
            
            chosenItemReady = false;

            d3.select(this)
              .attr("pointer-events", "auto");
            console.log("chosenItemReady is " + chosenItemReady);
            
            if (choicesBoxReady == true){
                
                chosentochoice(d);
                
                d3.select(this)
                  .attr("class", "choice");

                // Update images in Choices, Add image to end of theChoices
                
                let ba = svg.selectAll(".choice")
                           .data(thechoices);
                
                ba.enter()
                 .append("svg:image")
                 .merge(ba)
                 .attr("xlink:href", function(d){
                    return d.artistArtSpot;
                 })
                 .attr("x", function (d,i){
                    x = (i%10) * 75 + margin.left + spacepadding;
                    return x;
                })
                .attr("y", function (d,i){
                    y = Math.floor(i/10) * 75 + margin.top + spacepadding;
                    return y;
                })
                 .attr("data-artistName", (d) => d.artistNameSpot)
                 .attr("data-artistPop", (d) => d.pop)
                 .attr("data-artistSpotID", (d) => d.artistSpotID)
                 .attr("data-popDate", (d) => d.date)
                 .attr("class", "choice")
                 .append("title")
                 .text((d) => d.artistNameSpot)
                 .attr("pointer-events", "auto");
                 
                 ba.exit()
                  .remove();

                // Update Columns, Remove Column from theChosen and move others over
                
                let bc = svg.selectAll(".column")
                           .data(thechosen, key);    
                
                bc.enter()
                 .append("rect")
                 .merge(bc)
                 .transition()
                 .duration(500)
                 .attr("x", function (d,i) {
                    return innerTo.left + (i * 65);
                 })
                 .attr("y", function(d) {
                    return h - innerTo.bottom - 64 - (d.pop * 2)
                 })
                 .attr("width", 64)
                 .attr("height", function(d) {
                    return (d.pop * 2);
                 })
                 .attr("class", "column");
                
                bc.exit()
                  //.transition()
                  //.duration(500)
                  .remove();
                
                // Update text labels, Remove label from theChosen and move others over
                
                let bd = svg.selectAll("text")
                           .data(thechosen, key);
                
                bd.enter()
                 .append("text")
                 .merge(bd)
                 .transition()
                 .duration(500)
                 .text(function(d){
                    return d.pop;
                 })
                 .attr("text-anchor", "middle")
                 .attr("x", function (d, i){
                    return innerTo.left + (i * 65 + 65 / 2);
                 })
                 .attr("y", function(d){
                    return h - innerTo.bottom - 64 - (d.pop * 2) - 5;
                 })
                 .attr("font-family", "sans-serif")
                 .attr("font-size", "11px")
                 .attr("fill", "white");
                
                bd.exit().remove();
                
                // Update images in theChosen, Remove image from theChosen and move others over
                let bb = svg.selectAll(".chosen")
                           .data(thechosen, key);
                bb.enter()
                 .append("svg:image")
                 .merge(bb)  
                 .transition()
                 .duration(500)                             
                 .attr("xlink:href", function (d){
                    return d.artistArtSpot;
                 })
                 .attr("x", function (d,i) {
                    return innerTo.left + (i * 65);
                 })
                 .attr("y", function(d) {
                    return h - innerTo.bottom - 64;
                 })
                 .attr("width", 64)
                 .attr("height", 64)
                 .attr("data-artistName", (d) => d.artistNameSpot)
                 .attr("data-artistPop", (d) => d.pop)
                 .attr("data-artistSpotID", (d) => d.artistSpotID)
                 .attr("data-popDate", (d) => d.date)
                 .attr("class", "chosen")
                 .append("title")
                 .text((d) => d.artistNameSpot)
                 .attr("pointer-events", "auto");
                
                bb.exit().remove();
                /*
                 */
                choiceHandler(svg.selectAll(".choice"));
                chosenHandler(svg.selectAll(".chosen"));
            } else {
                d3.select(this)
                  .attr("x", function (d) {
                        let m = thechosen.indexOf(d);
                        return innerTo.left + (m * 65);
                    })
                    .attr("y", function(d) {
                        return h - innerTo.bottom - 64;
                    })
                  .attr("pointer-events", "auto");
            };
	   });
    
    fillChoicesBox(firstChoices);
    fillChosenBox(firstChosen);
    
    makeColumns();
    makeColumnLabels();
    
	choiceHandler(svg.selectAll(".choice"));
    chosenHandler(svg.selectAll(".chosen"));
    
});
        
</script>

<?php echo $scriptsAndSuch; ?>	

<script src="https://www.roxorsoxor.com/poprock/dragdrop/dragdrop.js"></script>
</body>
</html>