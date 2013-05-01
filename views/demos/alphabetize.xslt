<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />
<xsl:include href="../banshee/alphabetize.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Alphabetize demo</h1>
<table class="list">
<tr><th>Words</th></tr>
<xsl:for-each select="words/word">
<tr><td><xsl:value-of select="." /></td></tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="alphabetize" />

<a href="/demos" class="button">Back</a>
</xsl:template>

</xsl:stylesheet>
