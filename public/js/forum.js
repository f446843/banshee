function show_smiley(smiley) {
	textarea = document.getElementById("content");
	pos = textarea.selectionStart + smiley.length + 1;

	text  = textarea.value.substring(0, textarea.selectionStart);
	text += " " + smiley;
	text += textarea.value.substring(textarea.selectionEnd);
	textarea.value = text;

	textarea.setSelectionRange(pos, pos);
	textarea.focus();
}

$(document).ready(function() {
	if ($.browser.msie) {
		$("textarea.resizable:not(.processed)").TextAreaResizer();
	}
});
