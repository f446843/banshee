function allday() {
	input = document.getElementById("all_day");

	if (input.checked) {
		document.getElementById("begin_time").style.display = "none";
		document.getElementById("end_time").style.display = "none";
	} else {
		document.getElementById("begin_time").style.display = "inline";
		document.getElementById("end_time").style.display = "inline";
	}
}

function setup_calendars(all_day) {
	Calendar.setup({
		inputField : "begin_date",
		button     : "begin_show",
		ifFormat   : "%Y-%m-%d",
		showsTime  : false,
		firstDay   : 1,
		displayArea: "begin_show",
		daFormat   : "%e %B %Y"
	});

	Calendar.setup({
		inputField : "end_date",
		button     : "end_show",
		ifFormat   : "%Y-%m-%d",
		showsTime  : false,
		firstDay   : 1,
		displayArea: "end_show",
		daFormat   : "%e %B %Y"
	});

	if (all_day == "yes") {
		document.getElementById("all_day").checked = true;
		allday();
		document.getElementById("begin_time").value = "12:00";
		document.getElementById("end_time").value = "15:00";
	}
}
