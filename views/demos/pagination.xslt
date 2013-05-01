<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />
<xsl:include href="../banshee/pagination.xslt" />

<xsl:template match="content">
<h1>Pagination</h1>
<table class="list">
<tr><th>List items</th></tr>
<xsl:for-each select="items/item">
<tr><td><xsl:value-of select="." /></td></tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="pagination" />

<a href="/demos" class="button">Back</a>
</xsl:template>

</xsl:stylesheet>
