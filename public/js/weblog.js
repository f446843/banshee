$(function() {
	$('div.weblog').each(function() {
		$(this).find('s.slimbox').slimbox({
			counterText: "Image {x} of {y}"
		});
	});
});

