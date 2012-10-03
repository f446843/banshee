<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<script type="text/javascript" src="/js/calendar.js" />
<script type="text/javascript" src="/js/calendar-en.js" />
<script type="text/javascript" src="/js/calendar-setup.js" />

<input type="text" id="date" name="date" value="{date}" class="input" />
<input type="button" id="opencal" value=".." />

<script type="text/javascript">
&lt;!--
	Calendar.setup({
		inputField: "date",
		button    : "opencal",
		ifFormat  : "%Y-%m-%d %H:%M:%S",
		showsTime : true,
		timeFormat: "24",
		firstDay  : 1
	});
//-->
</script>
</xsl:template>

</xsl:stylesheet>
