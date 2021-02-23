function sortColumn (columnName, currentOrder, artistMBID, source) {
	$.ajax ({
		url: "functions/sort_artistAlbumsLastFM.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder + "&artistSpotID=" + artistSpotID + "&artistMBID=" + "&source=" + source,
		type: "POST",
		success: function (data) {
			$("#recordCollection").html(data);
		}
	});
	//console.log("column is " + columnName + " and current order is " + currentOrder + " and artistMBID is " + artistMBID)
}