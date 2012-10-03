function hash_passwords() {
	document.getElementById("password_hashed").value = "yes";

	username = hash(document.getElementById("username").value);
	password = document.getElementById("password");
	repeat = document.getElementById("repeat");

	password.value = hash(password.value + username);
	repeat.value = hash(repeat.value + username);
}
