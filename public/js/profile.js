function hash_passwords() {
	document.getElementById("password_hashed").value = "yes";

	username = hash(document.getElementById("username").value);
	current = document.getElementById("current");
	password = document.getElementById("password");
	repeat = document.getElementById("repeat");

	current.value = hash(current.value + username);
	if (password.value != "") {
		password.value = hash(password.value + username);
		repeat.value = hash(repeat.value + username);
	}
}

function password_strength(password, username) {
	username = document.getElementById(username);
	var words = ["abc", "love", "password", "qwerty", "secret", "123", "321"];

	if (password.value.length == 0) {
		password.style.backgroundColor = null;
		return;
	}

	if (password.value == username.value) {
		score = 0;
	} else {
		score = password.value.length;

		pswd = password.value.toLowerCase();
		for (var i in words) {
			if (pswd.indexOf(words[i]) > -1) {
				score -= words[i].length;
			}
		}

		characters = 0;
		numbers = 0;
		special = 0;
		for (i = 0; i < password.value.length; i++) {
			ascii = password.value.charCodeAt(i);

			if ((ascii >= 65) && (ascii <= 90)) {
				characters++;
			} else if ((ascii >= 97) && (ascii <= 122)) {
				characters++;
			} else if ((ascii >= 48) && (ascii <= 57)) {
				numbers++;
			} else {
				special++;
			}
		}

		if (characters > 0) {
			score += 3;
		}
		if (numbers > 0) {
			score += 3;
		}
		if (special > 0) {
			score += 3;
		}
	}

	if (score >= 19) {
		/* Good
		 */
		password.style.backgroundColor = "#80ff80";
	} else if (score >= 12) {
		/* Average
		 */
		password.style.backgroundColor = "#ffff80";
	} else {
		/* Bad
		 */
		password.style.backgroundColor = "#ff8080";
	}
}
