function setup_calendar() {
	Calendar.setup({
		inputField: "date",
		button    : "opencal",
		ifFormat  : "%Y-%m-%d %H:%M:%S",
		showsTime : true,
		timeFormat: "24",
		firstDay  : 1
	});
}
