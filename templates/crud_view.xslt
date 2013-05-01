<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />
<xsl:include href="banshee/pagination.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<table class="list">
<tr>
<th>xxx</th>
</tr>
<xsl:for-each select="XXXs/XXX">
<tr class="click" onClick="javascript:document.location='/{/output/page}/{@id}'">
<td><xsl:value-of select="xxx" /></td>
</tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="pagination" />

<a href="/{/output/page}/new" class="button">New XXX</a>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post">
<xsl:if test="XXX/@id">
<input type="hidden" name="id" value="{XXX/@id}" />
</xsl:if>

<table class="edit">
<tr><td>xxx:</td><td><input type="text" name="xxx" value="{XXX/xxx}" class="text" /></td></tr>
</table>

<input type="submit" name="submit_button" value="Save XXX" class="button" />
<a href="/{/output/page}" class="button">Cancel</a>
<xsl:if test="XXX/@id">
<input type="submit" name="submit_button" value="Delete XXX" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Page title</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
