<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>System message demo</h1>
<input type="button" value="Back" class="button" onClick="document.location='/demos'" />
</xsl:template>

</xsl:stylesheet>
