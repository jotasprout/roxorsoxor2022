function sortColumn (columnName, columnOrder) {
	$.ajax ({
		url: "functions/sortThisArtist.php",
		data: "sortBy=" + columnName + "&order=" + columnOrder,
		type: "POST",
		success: function (data) {
			$("#artistTable").html(data);
		}
	});
}