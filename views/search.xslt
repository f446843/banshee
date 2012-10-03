<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<xsl:template match="content">
<h1>Search</h1>
<script type="text/javascript">
	function log_search_query(sc, searcher, query) {
		ajax.get("search", "query=" + query);
	}

	function OnLoad() {
		var searchControl = new google.search.SearchControl();

		// callback
		searchControl.setSearchStartingCallback(this, log_search_query);

		// web search, open
		options = new google.search.SearcherOptions();
		options.setExpandMode(google.search.SearchControl.EXPAND_MODE_OPEN);

		// Add in a full set of searchers
		var searcher = new google.search.WebSearch();
		searcher.setSiteRestriction("<xsl:value-of select="hostname" />");
		searchControl.addSearcher(searcher, options);

		// tell the searcher to draw itself and tell it where to attach
		searchControl.draw(document.getElementById("searchcontrol"));
	}

	ajax = new ajax();
	google.load("search", "1.0", {"language" : "en"});
	google.setOnLoadCallback(OnLoad, true);
</script>

<div id="searchcontrol">Loading Google search controller...</div>
</xsl:template>

</xsl:stylesheet>
