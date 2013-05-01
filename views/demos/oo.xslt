<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Object-Oriented page demo</h1>
<p><xsl:value-of select="message" /></p>

<a href="/demos" class="button">Back</a>
</xsl:template>

</xsl:stylesheet>
