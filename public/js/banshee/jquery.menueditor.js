/* js/banshee/jquery.menueditor.js
 *
 * Copyright (C) by Hugo Leisink <hugo@leisink.net>
 * This file is part of the Banshee PHP framework
 * http://www.banshee-php.org/
*/

(function($) {
	var pluginName = "menuEditor";
	var element;
	var settings;
	var defaults = {
		max_depth: 3
	};

	var l_text =        '<span class="text label">Text:</span>';
	var l_link =        '<span class="link label">Link:</span>';
	var new_node =      '<li>' + l_text + '<input type="text" class="text">' + l_link + '<input type="text" class="text"></li>';
	var h_insert_node = '<input type="button" value="insert" class="insert button">';
	var h_add_node =    '<input type="button" value="+" class="add_node button">';
	var h_delete_node = '<input type="button" value="-" class="delete_node button">';
	var h_add_branch =  '<input type="button" value="&gt;" class="add_branch button">';

	/* Constructor
	 */
	var plugin = function(el, options) {
		element = $(el);
		settings = $.extend({}, defaults, options);

		if (element.prop("tagName") != "UL") {
			return null;
		}

		element.find("li").each(function(){
			if ($(this).find("ul").size() == 0) {
				$(this).find("input:nth-child(2)").first().after(all_buttons());
			} else {
				$(this).find("input:nth-child(2)").first().after(node_buttons());
			}
		});

		element.find("li > input:nth-child(2)").before(l_link);
		element.find("li > input:first-child").before(l_text);

		var b_insert_node = $(h_insert_node);
		b_insert_node.bind("click", function(e) { insert_node(); });
		element.before(b_insert_node);

		element.addClass("menu-editor");

		element.addClass("sortable");
		element.find("ul").addClass("sortable");
		make_editor_sortable();

		element.parent("form").bind("submit", function(e) { menu_submit() });

		return this;
	};

	/* Calculate depth
	 */
	var node_depth = function(item) {
		var depth = 0;

		var node = $(item).parent().parent().parent();
		while (node.prop("tagName") == "UL") {
			node = node.parent().parent();
			depth++;
		}

		return depth;
	}

	/* Return node buttons
	 */
	var node_buttons = function() {
		var buttons = $('<span class="buttons">' + h_add_node + h_delete_node + "</span>");
		buttons.find("input.add_node").bind("click", function(e) { add_node(this); });
		buttons.find("input.delete_node").bind("click", function(e) { delete_node(this); });

		return buttons;
	};

	/* Return all three buttons
	 */
	var all_buttons = function() {
		var buttons = node_buttons();
		buttons.find("input").last().after(h_add_branch);
		buttons.find("input.add_branch").bind("click", function(e) { add_branch(this); });

		return buttons;
	};

	/* Insert node at top
	 */
	var insert_node = function() {
		var node = $(new_node);
		node.append(all_buttons());
		element.prepend(node);
	};

	/* Add node
	 */
	var add_node = function(item) {
		var node = $(new_node);
		node.append(all_buttons());
		$(item).parent().parent().after(node);
	};

	/* Delete node
	 */
	var delete_node = function(item) {
		li = $(item).parent().parent();
		console.log(li.prop("tagName"));
		ul = li.parent();

		if (li.find("ul").size() > 0) {
			if (confirm("Delete branch?") == false) {
				return;
			}
		}
		li.remove();

		if (ul.parent().prop("tagName") == "LI") {
			if (ul.find("li").size() == 0) {
				var b_add_branch = $(h_add_branch);
				b_add_branch.bind("click", function(e) { add_branch(this); });
				ul.parent().find("span.buttons").append(b_add_branch);

				ul.remove();
			}
		}
	};

	/* Add branch
	 */
	var add_branch = function(item) {
		var depth = node_depth(item);
		var li = $(item).parent().parent();

		li.find("input.add_branch").first().remove();

		var branch = $('<ul class="sortable">' + new_node + "</ul>");
		if (depth >= settings.max_depth) {
			branch.find("li").append(node_buttons());
		} else {
			branch.find("li").append(all_buttons());
		}
		li.append(branch);

		make_editor_sortable();
	};

	/* Check for empty branches after sorting
	 */
	var sorting_done = function(event, ui) {
		element.find("ul").each(function(){
			if ($(this).find("li").size() == 0) {
				var b_add_branch = $(h_add_branch);
				b_add_branch.bind("click", function(e) { add_branch(this); });
				$(this).parent().find("span.buttons").append(b_add_branch);

				$(this).remove();
			}
		});
	}

	/* Make menu editor sortable
	 */
	var make_editor_sortable = function() {
		element.sortable({ connectWith:"ul.sortable", axis:"y", update:sorting_done });
		element.find("ul.sortable").sortable({ connectWith:"ul.sortable", axis:"y", update:sorting_done });
	};

	/* Give name to elements
	 */
	var give_name = function(elems, current) {
		var i = 0;
		elems.children("li").each(function(){
			var pos = "[" + i + "]";
			$(this).find("input:nth-child(2)").prop("name", "menu" + current + pos + "[text]");
			$(this).find("input:nth-child(4)").prop("name", "menu" + current + pos + "[link]");
			$(this).children("ul").each(function(){
				console.log("submenu");
				give_name($(this), current + pos + "[submenu]");
			});
			i++;
		});
	}

	/* Menu submit handler
	 */
	var menu_submit = function() {
		give_name(element, "");
	};

	/* JQuery prototype
	 */
	$.fn[pluginName] = function(options) {
		return this.each( function () {
			(new plugin(this, options));
		}); // this.each
	};

}(jQuery));
