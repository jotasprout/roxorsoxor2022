function sortColumn (columnName, currentOrder, artistMBID, source) {
	$.ajax ({
		url: "functions/sort_artistTracksLastFM.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder + "&artistMBID=" + artistMBID + "&source=" + source,
		type: "POST",
		success: function (data) {
			$("#tableotracks").html(data);
		}
	});
}