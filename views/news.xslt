<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />
<xsl:include href="banshee/pagination.xslt" />

<!--
//
//  News item template
//
//-->
<xsl:template match="news">
<h2><xsl:value-of select="title" /></h2>
<div class="timestamp"><xsl:value-of select="timestamp" /></div>
<xsl:value-of disable-output-escaping="yes" select="content" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>News</h1>
<div class="rsslink"><a href="/news.xml"><img src="/images/rss.png" /></a></div>
<xsl:apply-templates select="news" />
<xsl:apply-templates select="pagination" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
