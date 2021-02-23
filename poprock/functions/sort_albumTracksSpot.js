function sortColumn (columnName, currentOrder, albumSpotID, source) {
	$.ajax ({
		url: "functions/sort_albumTracksSpot.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder + "&albumSpotID=" + albumSpotID + "&artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=" + source,
		type: "POST",
		success: function (data) {
			$("#tableotracks").html(data);
		}
	});
}