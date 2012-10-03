function hash_password() {
	username = hash(document.getElementById("username").value);
	password = document.getElementById("password").value;
	challenge = document.getElementById("challenge").value;

	document.getElementById("password").value = hash(challenge + hash(password + username));
	document.getElementById("use_cr_method").value = "yes";
}

function set_focus() {
	document.getElementById("username").focus();
}
