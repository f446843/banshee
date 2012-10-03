function login_other_website(result) {
	if ((login = result.getValue("login")) == null) {
		return;
	}

	img = new Image();
	img.src = login;
}

function sso(max) {
	ajax = new ajax();

	for (i = 0; i < max; i++) {
		ajax.get("system/sso/website/" + i, "", login_other_website);
	}
}
