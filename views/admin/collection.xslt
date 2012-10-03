<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<table class="list">
<tr><th class="name">Name</th></tr>
<xsl:for-each select="collections/collection">
<tr class="click" onClick="javascript:document.location='/{/output/page}/{@id}'">
	<td><xsl:value-of select="name" /></td>
</tr>
</xsl:for-each>
</table>

<input type="button" value="New collection" class="button" onClick="javascript:document.location='/admin/collection/new'" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post">
<xsl:if test="collection/@id">
<input type="hidden" name="id" value="{collection/@id}" />
</xsl:if>
<table class="edit">
<tr><td>Name:</td><td><input type="text" name="name" value="{collection/name}" class="text" /></td></tr>
<tr><td>Albums:</td><td>
<xsl:for-each select="collection/albums/album">
<div><input type="checkbox" name="albums[]" value="{@id}">
	<xsl:if test="@checked='yes'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
</input><xsl:value-of select="." /></div>
</xsl:for-each>
</td></tr>
</table>

<input type="submit" name="submit_button" value="Save collection" class="button" />
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/admin/collection'" />

<xsl:if test="collection/@id">
<input type="submit" name="submit_button" value="Delete collection" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Collection administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
