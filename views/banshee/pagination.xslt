<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="pagination">
<ul class="pagination">
<xsl:choose>
	<xsl:when test="@page=0">
		<li class="nolink">&lt;&lt;</li>
		<li class="nolink">&lt;</li>
	</xsl:when>
	<xsl:otherwise>
		<li class="link"><a href="{/output/@url}?offset=0">&lt;&lt;</a></li>
		<li class="link"><a href="{/output/@url}?offset={@page - @step}">&lt;</a></li>
	</xsl:otherwise>
</xsl:choose>

<xsl:for-each select="page">
<xsl:choose>
	<xsl:when test=".=../@page">
		<li class="nolink current"><xsl:value-of select=". + 1" /></li>
	</xsl:when>
	<xsl:otherwise>
		<li class="link"><a href="{/output/@url}?offset={.}"><xsl:value-of select=". + 1" /></a></li>
	</xsl:otherwise>
</xsl:choose>
</xsl:for-each>

<xsl:choose>
	<xsl:when test="@page=@max">
		<li class="nolink">&gt;</li>
		<li class="nolink">&gt;&gt;</li>
	</xsl:when>
	<xsl:otherwise>
		<li class="link"><a href="{/output/@url}?offset={@page + @step}">&gt;</a></li>
		<li class="link"><a href="{/output/@url}?offset={@max}">&gt;&gt;</a></li>
	</xsl:otherwise>
</xsl:choose>
</ul>
<div style="clear:both" />
</xsl:template>

</xsl:stylesheet>
