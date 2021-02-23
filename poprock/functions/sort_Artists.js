function sortColumn (columnName, currentOrder) {
	$.ajax ({
		url: "functions/sort_Artists.php",
		data: "columnName=" + columnName + "&currentOrder=" + currentOrder,
		type: "POST",
		success: function (data) {
			$("#tableoartists").html(data);
		}
	});
}