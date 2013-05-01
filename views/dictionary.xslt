<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />

<!--
//
//  Letters template
//
//-->
<xsl:template match="letters">
<div class="letters">
<xsl:for-each select="letter">
	<a href="/{/output/page}/{.}">
	<xsl:if test=".=../@selected"><xsl:attribute name="class">selected</xsl:attribute></xsl:if>
	<xsl:value-of select="." />
	</a>
</xsl:for-each>
</div>
</xsl:template>

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<xsl:apply-templates select="letters" />
<table class="dictionary">
<xsl:for-each select="words/word">
	<tr><td class="word">
	<xsl:choose>
		<xsl:when test="long_description=''">
			<xsl:value-of select="word" />
		</xsl:when>
		<xsl:otherwise>
			<a href="/{/output/page}/{@id}"><xsl:value-of select="word" /></a>
		</xsl:otherwise>
	</xsl:choose>
	</td><td class="short"><xsl:value-of select="short_description" /></td></tr>
</xsl:for-each>
</table>
</xsl:template>

<!--
//
//  keyword template
//
//-->
<xsl:template match="word">
<xsl:apply-templates select="letters" />
<h2><xsl:value-of select="word/word" /></h2>
<p><xsl:value-of disable-output-escaping="yes" select="word/long_description" /></p>

<a href="/{/output/page}/{letters/@selected}" class="button">Back</a>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Dictionary</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="word" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
