var FB_timerID = null;
var DT_timerID = null;

function show_answer(result) {
	answer = result.getValue("result");
	ajax_clear("feedback");
	ajax_print("feedback", "Your answer is " + answer + ".<br>\n");

	ajax_setvalue("answer", "");
	ajax_focus("answer");

	if (FB_timerID != null) {
		clearTimeout(FB_timerID);
	}
	FB_timerID = setTimeout("document.getElementById('feedback').innerHTML = ''; FB_timerID = null;", 1500);
}

function show_records(result) {
	records = result.getRecords();
	prefix = "";
	ajax_clear("data");

	if (records["vars"] != null) {
		for (i = 0; i < records["vars"].length; i++) {
			for (j = 0; j < records["vars"][i]["var"].length; j++) {
				ajax_print("data", records["vars"][i]["var"][j] + "<br>\n");
			}
			ajax_print("data", "<br>\n");
		}
	}

	ajax_setvalue("records", "");
	ajax_focus("records");

	if (DT_timerID != null) {
		clearTimeout(DT_timerID);
	}
	DT_timerID = setTimeout("document.getElementById('data').innerHTML = ''; DT_timerID = null;", 2500);
}

function show_text(result) {
	text = result.getValue("text");

	alert(text)
}

function set_focus() {
	ajax_focus("answer");
}

ajax = new ajax();
