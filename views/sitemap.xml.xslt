<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" encoding="utf-8" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
	<url>
		<loc><xsl:value-of select="protocol" />://<xsl:value-of select="hostname" />/</loc>
		<changefreq>weekly</changefreq>
		<priority>0.5</priority>
	</url>
<xsl:for-each select="urls/url">
	<url>
		<loc><xsl:value-of select="../../protocol" />://<xsl:value-of select="../../hostname" />/<xsl:value-of select="loc" /></loc>
	</url>
</xsl:for-each>
</urlset>
</xsl:template>

<!--
//
//  Output template
//
//-->
<xsl:template match="/output">
<xsl:apply-templates select="content" />
</xsl:template>

</xsl:stylesheet>
