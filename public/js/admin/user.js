function hash_passwords() {
	document.getElementById("password_hashed").value = "yes";

	username = hash(document.getElementById("username").value);
	password = document.getElementById("password");

	if (password.value != "") {
		password.value = hash(password.value + username);
	}
}

function generate_checkbox() {
	checkbox = document.getElementById("generate");
	password = document.getElementById("password");

	if (checkbox.checked) {
		password.value = "";
		password.disabled = true;
	} else {
		password.disabled = false;
	}
}
