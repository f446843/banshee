/* js/banshee/ajax.js
 *
 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
 * This file is part of the Banshee PHP framework
 * http://www.banshee-php.org/
 */

function ajax() {
	var xmlhttp = null;
	var result_handler = null;
	var obj = this;
	var no_support = "Your browser does not support XMLHTTP.";

	this.state_change = state_change;
	this.get = get;
	this.post = post;
	this.hasValue = hasValue;
	this.getValue = getValue;
	this.getRecords = getRecords;

	function url_encode(plaintext) {
		var hex = "0123456789ABCDEF";

		var encoded = "";
		for (var i = 0; i < plaintext.length; i++ ) {
			var c = plaintext.charCodeAt(i);

			if (c == 32) {
				encoded += "+";
			} else if (((c >= 48) && (c <= 57)) || ((c >= 65) && (c <= 90)) || ((c >= 97) && (c <= 122))) {
				encoded += plaintext.charAt(i);
			} else {
				encoded += "%";
				encoded += hex.charAt((c >> 4) & 15);
				encoded += hex.charAt(c & 15);
			}
		}

		return encoded;
	}

	function state_change() {
		if (xmlhttp.readyState == 4) {
			if (xmlhttp.status == 200) {
				if (result_handler != null) {
					result_handler(obj);
				}
			} else {
				alert("Problem retrieving XML data:" + xmlhttp.statusText)
			}
		}
	}

	function get(page, data, handler) {
		if (xmlhttp != null) {
			if (data == null) {
				data = "";
			} else if (data != "") {
				data = "?" + data;
			}

			result_handler = handler;
			xmlhttp.open("GET", "/" + page + data, true);
			xmlhttp.onreadystatechange = (result_handler != null) ? state_change : null;
			xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			xmlhttp.send(null);
		} else {
			alert(no_support);
		}
	}

	function post(page, form_id, handler) {
		if (xmlhttp != null) {
			var form_obj = document.getElementById(form_id);
			var post_data = new Array();
			var p = 0;
			
			for (i = 0; i < form_obj.elements.length; i++) {
				if (form_obj.elements[i].name != "") {
					name = url_encode(form_obj.elements[i].name);
					value = url_encode(form_obj.elements[i].value);

					switch (form_obj.elements[i].type) {
						case "submit":
							if (form_obj.elements[i].clicked) {
								form_obj.elements[i].clicked = false;
								post_data[p++] = name + "=" + value;
							}
							break;
						case "checkbox":
							post_data[p++] = name + "=" + (form_obj.elements[i].checked ? "on" : "");
							break;
						case "radio":
							if (form_obj.elements[i].checked) {
								post_data[p++] = name + "=" + value;
							}
							break;
						default:
							post_data[p++] = name + "=" + value;
					}
				}
			}

			result_handler = handler;
			xmlhttp.open("POST", "/" + page, true);
			xmlhttp.onreadystatechange = (result_handler != null) ? state_change : null;
			xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
			xmlhttp.setRequestHeader("X-Requested-With", "XMLHttpRequest");
			xmlhttp.send(post_data.join("&"));
		} else {
			alert(no_support);
		}
	}

	function hasValue(key) {
		elem = xmlhttp.responseXML.getElementsByTagName(key);
		if (elem.length == 0) {	
			return false;
		}

		return typeof(elem[0]) != "undefined";
	}

	function getValue(key) {
		elem = xmlhttp.responseXML.getElementsByTagName(key);

		if (elem.length == 0) {
			return null;
		} else if (elem[0].firstChild == null) {
			return null;
		}

		return elem[0].firstChild.nodeValue;
	}

	function tag_to_array(tag) {
		var result = new Array();

		for (var i = 0; i < tag.childNodes.length; i++) {
			var elem = tag.childNodes[i];
			var key = elem.nodeName;
			if (key.charAt(0) == "#") {
				continue;
			}

			if (result[key] == null) {
				result[key] = new Array();
			}
			var idx = result[key].length;

			if (elem.childNodes.length == 0) {
				result[key][idx] = elem.nodeValue;
			} else if (elem.childNodes.length > 1) {
				result[key][idx] = tag_to_array(elem);
			} else if (elem.childNodes[0].nodeValue == null) {
				result[key][idx] = tag_to_array(elem);
			} else {
				result[key][idx] = elem.childNodes[0].nodeValue;
			}

			for (var a = 0; a < elem.attributes.length; a++) {
				result[key][idx]["@"+elem.attributes.item(a).nodeName] = elem.attributes.item(a).nodeValue;
			}
		}

		return result;
	}

	function getRecords() {
		if (xmlhttp != null) {
			var result = tag_to_array(xmlhttp.responseXML);
			return result["output"][0];
		} else {
			return null;
		}
	}

	function mouse_click(e) {
		if (!e) {
			if (window.event.srcElement.type == "submit") {
				window.event.srcElement.clicked = true;
			}
		} else if (e.target.type == "submit") {
			e.target.clicked = true;
		}
	}

	if (window.XMLHttpRequest) {
		xmlhttp = new XMLHttpRequest();
	} else if (window.ActiveXObject) {
		xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
	} else {
		xmlhttp = null;
	}

	if (xmlhttp == null) {
		alert(no_support);
	}

	document.onmousedown = mouse_click;
}

function ajax_clear(name) {
	var elem;

	if ((elem = document.getElementById(name)) != null) {
		elem.innerHTML = "";
	}
}

function ajax_print(name, data) {
	var elem;

	if ((elem = document.getElementById(name)) != null) {
		elem.innerHTML += data;
	}
}

function ajax_getvalue(name) {
	var elem;

	if ((elem = document.getElementById(name)) == null) {
		return null;
	}

	return elem.value;
}

function ajax_setvalue(name, data) {
	var elem;

	if ((elem = document.getElementById(name)) != null) {
		elem.value = data;
	}
}

function ajax_focus(name) {
	var elem;

	if ((elem = document.getElementById(name)) != null) {
		elem.focus();
	}
}

function ajax_hide(name) {
	var elem;

	if ((elem = document.getElementById(name)) != null) {
		elem.style.display = "none";
	}
}

function ajax_show(name) {
	var elem;

	if ((elem = document.getElementById(name)) != null) {
		elem.style.display = "inline";
	}
}

function ajax_formvalue(formname, name, data) {
	var elem;

	if ((elem = document.getElementById(formname)) == null) {
		return;
	}

	for (var i = 0; i < elem.elements.length; i++) {
		if (elem.elements[i].name == name) {
			elem.elements[i].value = data;
		}
	}
}

function ajax_formget(formname, name) {
	var elem;

	if ((elem = document.getElementById(formname)) == null) {
		return null;
	}

	for (var i = 0; i < elem.elements.length; i++) {
		if (elem.elements[i].name == name) {
			return elem.elements[i].value;
		}
	}

	return null;
}
