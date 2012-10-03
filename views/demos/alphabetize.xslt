<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/alphabetize.xslt" />

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

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</xsl:template>

</xsl:stylesheet>
