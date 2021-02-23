function sortColumn (columnName, currentOrder) {
	$.ajax ({
		url: "sort_genres.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder,
		type: "POST",
		success: function (data) {
			$("#tableoartists").html(data);
		}
	});
}