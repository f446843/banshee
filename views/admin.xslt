<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<xsl:template match="content">
<h1>Website administration</h1>
<xsl:for-each select="menu/section">
	<xsl:if test="count(entry[@access='yes'])>0">
		<div class="section {@class}">
		<h2><xsl:value-of select="@text" /></h2>
		<ul class="admin">
		<xsl:for-each select="entry[@access='yes']">
			<li><a href="/{.}"><img src="/images/icons/{@icon}" class="icon" /><xsl:value-of select="@text" /></a></li>
		</xsl:for-each>
		</ul>
		<br class="break" />
		</div>
	</xsl:if>
</xsl:for-each>
<br class="break" />
</xsl:template>

</xsl:stylesheet>
