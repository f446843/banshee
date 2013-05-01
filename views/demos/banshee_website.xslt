<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Banshee_website library demo</h1>
<p>Fetching full name for user 'admin' from website demo.banshee-php.org:</p>
<ul>
<xsl:for-each select="message">
<li><xsl:value-of select="." /></li>
</xsl:for-each>
</ul>

<a href="/demos" class="button">Back</a>
</xsl:template>

</xsl:stylesheet>
