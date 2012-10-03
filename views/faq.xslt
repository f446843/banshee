<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<xsl:for-each select="sections/section">
	<xsl:variable name="section_id" select="@id" />
	<h2><xsl:value-of select="." /></h2>
	<xsl:for-each select="../../faqs/faq[section_id=$section_id]">
		<div class="question" onClick="javascript:$('.faq{@id}').slideToggle('normal')"><xsl:value-of select="question" /></div>
		<div class="answer faq{@id}"><xsl:value-of disable-output-escaping="yes" select="answer" /></div>
	</xsl:for-each>
</xsl:for-each>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Frequently Asked Questions</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
