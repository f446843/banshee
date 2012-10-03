function setup_calendars() {
	Calendar.setup({
		inputField : "begin",
		button     : "begin_show",
		ifFormat   : "%Y-%m-%d",
		showsTime  : false,
		firstDay   : 1,
		displayArea: "begin_show",
		daFormat   : "%e %B %Y"
	});

	Calendar.setup({
		inputField : "end",
		button     : "end_show",
		ifFormat   : "%Y-%m-%d",
		showsTime  : false,
		firstDay   : 1,
		displayArea: "end_show",
		daFormat   : "%e %B %Y"
	});
}
