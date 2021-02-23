function sortColumn (columnName, currentOrder, artistSpotID, artistMBID, source) {
	$.ajax ({
		url: "functions/sort_artistAlbumsSpot.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder + "&artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=" + source,
		type: "POST",
		success: function (data) {
			$("#recordCollection").html(data);
		}
	});
	//console.log("column is " + columnName + " and current order is " + currentOrder + " and artistSpotID is " + artistSpotID)
}