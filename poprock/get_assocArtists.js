d3.json("functions/get_assocArtists.php", function (assocData) {

	console.log(assocData);
/*
	d3.select("#assocArtists")
		.append("p")
		.attr("width", 400)
		.attr("height", 100);

	
	svg.selectAll("rect")
		.data(assocData)
		.enter()
		.append("rect")
		.attr("x", function (d,i) {
			return i * 65;
		})
		.attr("width", 64)
		.attr("height", 64);


	svg.selectAll("image")
		.data(assocData)
		.enter()
		.append("svg:image")
		.attr("xlink:href", function (d){
			return d.artistArt;
		})
		.attr("x", function (d,i) {
			return i * 65;
		})
		.attr("width", 64)
		.attr("height", 64)
		.append("title")
		.text(function(d){
			return d.artistName;
		});	
*/

});