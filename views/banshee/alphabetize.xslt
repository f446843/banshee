<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="alphabetize">
<ul class="alphabetize">
<xsl:for-each select="char">
<xsl:choose>
    <xsl:when test="@link=../@char">
		<li class="nolink current"><xsl:value-of select="." /></li>
	</xsl:when>
	<xsl:otherwise>
		<li class="link"><a href="{/output/@url}?char={@link}"><xsl:value-of select="." /></a></li>
	</xsl:otherwise>
</xsl:choose>
</xsl:for-each>
</ul>
<div style="clear:both" />
</xsl:template>

</xsl:stylesheet>
