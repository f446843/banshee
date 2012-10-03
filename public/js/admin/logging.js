function set_info(id, count, day) {
	if ((elem = document.getElementById("count_" + id)) != null) {
		elem.innerHTML = count;
	}

	if ((elem = document.getElementById("day_" + id)) != null) {
		elem.innerHTML = day;
	}
}

function clear_info(id) {
	set_info(id, "", "");
}
