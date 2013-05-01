<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Search</h1>
<form action="/{/output/page}" method="post">
<input type="text" name="query" value="{query}" id="query" class="text query" /><input type="submit" name="submit_button" value="Search" class="button" />
<div class="sections">Sections:
<xsl:for-each select="sections/section">
<span><input type="checkbox" name="{.}"><xsl:if test="@checked='yes'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if></input> <xsl:value-of select="@label" /></span>
</xsl:for-each>
</div>
</form>

<xsl:if test="result">
<div class="error"><xsl:value-of select="result" /></div>
</xsl:if>

<xsl:for-each select="section">
	<h2><xsl:value-of select="@label" /></h2>
	<ul class="pagination">
	<xsl:for-each select="hit">
		<li>
		<div class="link"><a href="{url}"><xsl:value-of select="text" /></a></div>
		<div class="preview"><xsl:value-of select="content" /></div>
		</li>
	</xsl:for-each>
	</ul>
</xsl:for-each>
</xsl:template>

</xsl:stylesheet>
