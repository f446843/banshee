<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<xsl:template match="content">
<h1>Error messages</h1>
<p>This page demonstrates how internal errors are shown.</p>

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</xsl:template>

</xsl:stylesheet>
