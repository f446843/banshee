var item_id_ofs = 0;
var new_id = -1;

function add_item(menu, item_id) {
	item_id += item_id_ofs;

	var item = "<li id=\"item-" + item_id + "\">";
	item += "<input type=\"hidden\" name=\"menu[" + new_id + "][children]\" value=\"0\" />";
	item += "<input type=\"text\" name=\"menu[" + new_id + "][text]\" value=\"\" class=\"text\" />";
	item += "<input type=\"text\" name=\"menu[" + new_id + "][link]\" value=\"\" class=\"text\" />";
	item += "<div class=\"no_view\" />\n";
	item += "<input type=\"button\" value=\"delete\" class=\"operation\" onClick=\"javascript:delete_item('item-" + item_id + "')\" />";
	item += "<img src=\"/images/sort.png\" class=\"grip\" alt=\"sort\" />";
	item += "</li>\n";

	$("#"+menu).append(item);

	item_id_ofs += 1;
	new_id -= 1;
}

function delete_item(item) {
	$("#"+item).remove();
}

$(document).ready(function(){
	$("#editmenu").sortable({ 
		axis: "y",
		placeholder: "ui-selected", 
		forcePlaceholderSize: true, 
		handle: ".grip",
		revert: false,
		opacity: 0.75
	});
});
