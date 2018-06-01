function showSeries(series_id) {
	var s_rows = document.getElementsByName(series_id);
	var all_rows = cerf_tbody.getElementsByTagName('tr');

	Array.from(all_rows).forEach ( function (row) {
		row.hidden = true;
	}
	);

	s_rows.forEach( function(row) {
		row.hidden = false;
	}
	);
}