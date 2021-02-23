function sortColumn (columnName, columnOrder) {
	$.ajax ({
		url: "functions/sort_Artist.php",
		data: "sortBy=" + columnName + "&order=" + columnOrder,
		type: "POST",
		success: function (data) {
			$("#artistTable").html(data);
		}
	});
}