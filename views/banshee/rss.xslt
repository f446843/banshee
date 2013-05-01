<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" encoding="utf-8" />

<xsl:template match="content/rss_feed">
<rss version="2.0">
	<channel>
		<title><xsl:value-of select="title" /></title>
		<description><xsl:value-of select="description" /></description>
		<link><xsl:value-of select="url" /></link>
		<xsl:for-each select="items/item">
		<item>
			<title><xsl:value-of select="title" /></title>
			<description><xsl:value-of select="description" /></description>
			<link><xsl:value-of select="link" /></link>
			<pubDate><xsl:value-of select="timestamp" /></pubDate>
		</item>
		</xsl:for-each>
	</channel>
</rss>
</xsl:template>

<xsl:template match="/output">
<xsl:apply-templates select="content/rss_feed" />
</xsl:template>

</xsl:stylesheet>
