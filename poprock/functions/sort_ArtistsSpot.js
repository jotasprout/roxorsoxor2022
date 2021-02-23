function sortColumn (columnName, currentOrder, artistSpotID, artistMBID, source) {
	$.ajax ({
		url: "functions/sort_ArtistsSpot.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder + "&artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=" + source,
		type: "POST",
		success: function (data) {
			$("#tableoartists").html(data);
		}
	});
}