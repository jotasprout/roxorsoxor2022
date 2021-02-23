<?php
    require_once '../page_pieces/stylesAndScripts.php';
    require_once '../page_pieces/navbar_rock.php';
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<?php echo $stylesAndSuch; ?>
		<script src='https://d3js.org/d3.v4.min.js'></script>
	</head>

	<body>

		<DIV class="container">
	    
		    <?php echo $navbar ?> <!-- /navbar -->

			<div id="forChart"> <!-- main -->
				<script type="text/javascript">

					//Width and height
					var w = 500;
					var h = 100;
					var barPadding = 1;
					
					var dataset = [ 10, 9, 12, 14, 21, 18, 16, 19, 23, 23, 20 ];
					
					//Create SVG element
					var svg = d3.select("#forChart")
								.append("svg")
								.attr("width", w)
								.attr("height", h);

					//Rectangles
					svg.selectAll("rect")
					   .data(dataset)
					   .enter()
					   .append("rect")
					   .attr("x", function(d, i) {
					   		return i * (w / dataset.length);
					   })
					   .attr("y", function(d) {
					   		return h - (d * 4);
					   })
					   .attr("width", w / dataset.length - barPadding)
					   .attr("height", function(d) {
					   		return d * 4;
					   })
					   .attr("fill", function(d){
					   		return "rgb(0, 0, " + (d * 10) + ")";
					   	})
					   .on("mouseover", function() {
					   		d3.select(this)
					   			.attr("fill", "red");
					   })
					   .on("mouseout", function(d) {
						   d3.select(this)
						   		.transition()
						   		.duration(250)
								.attr("fill", "rgb(0, 0, " + (d * 10) + ")");
					   });
					
					//Labels
					svg.selectAll("text")
						.data(dataset)
						.enter()
						.append("text")
						.text(function(d){
							return d;
						})
						.attr("text-anchor", "middle")
						.attr("x", function (d, i){
							return i * (w / dataset.length) + (w / dataset.length - barPadding) / 2;
						})
						.attr("y", function(d){
							return h - (d * 4) + 14;
						})
						.attr("font-family", "sans-serif")
						.attr("font-size", "11px")
						.attr("fill", "white");
						
				</script>

				<p><strong>Source:</strong> <a href="http://www.fridaythe13thfranchise.com/2011/07/body-count.html">Fridaythe13thFranchise.com</a></p>
				<p>Other sites and articles with less credibility and no apparent research have significantly different numbers. The DataViz-inclined may enjoy <a href="http://i2.wp.com/bitcast-a-sm.bitgravity.com/slashfilm/wp/wp-content/images/Jason-Voorhees-Body-Count.jpg">this infographic from Slashfilm</a>.
			

			</div> <!-- main -->

			<footer class="footer">

				

			</footer>
		</div> 	<!-- /container -->	

		<?php echo $scriptsAndSuch; ?>	
	</body>
</html>