function sortColumn (albumMBID, columnName, currentOrder, artistSpotID, artistMBID, source) {
	$.ajax ({
		url: "functions/sort_albumTracksLastFM.php",
		data: "&albumMBID=" + albumMBID + "columnName=" + columnName + "&currentOrder=" + currentOrder + "&artistSpotID=" + artistSpotID + "&artistMBID=" + artistMBID + "&source=" + source,
		type: "POST",
		success: function (data) {
			$("#tableotracks").html(data);
		}
	});
}