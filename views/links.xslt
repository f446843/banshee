<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Links template
//
//-->
<xsl:template match="links">
<ul>
<xsl:for-each select="link">
<li><xsl:value-of select="." /> - <a href="{@url}" target="_blank"><xsl:value-of select="@url" /></a></li>
</xsl:for-each>
</ul>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Links</h1>
<xsl:apply-templates select="links" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
