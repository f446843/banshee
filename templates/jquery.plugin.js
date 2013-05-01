/* jQuery plugin template
 */
(function($) {
	var pluginName = "myPlugin";
	var element;
	var settings;
	var defaults = {
		key: 'value',
	};

	/* Constructor
	 */
	var plugin = function(el, options) {
		element = $(el);
		settings = $.extend({}, defaults, options);
	};

	var my_function = function() {
	};

	/* JQuery prototype
	 */
	$.fn[pluginName] = function(options) {
		return this.each(function(){
			(new plugin(this, options));
		}); // this.each
	};
}(jQuery));
