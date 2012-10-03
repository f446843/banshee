<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<h3>Public pages</h3>
<table class="list">
<tr><th class="url">URL</th><th class="title">Title</th><th class="visible">Visible</th></tr>
<xsl:for-each select="pages/page[private=0]">
	<tr class="click" onClick="javascript:document.location='/admin/page/{@id}'">
	<td><xsl:value-of select="url" /></td>
	<td><xsl:value-of select="title" /></td>
	<td><xsl:value-of select="visible" /></td>
	</tr>
</xsl:for-each>
</table>

<h3 class="spacer">Private pages</h3>
<table class="list">
<tr><th class="url">URL</th><th class="title">Title</th><th class="visible">Visible</th></tr>
<xsl:for-each select="pages/page[private=1]">
	<tr class="click" onClick="javascript:document.location='/admin/page/{@id}'">
	<td><xsl:value-of select="url" /></td>
	<td><xsl:value-of select="title" /></td>
	<td><xsl:value-of select="visible" /></td>
	</tr>
</xsl:for-each>
</table>

<input type="button" value="New page" class="button" onClick="javascript:document.location='/admin/page/new'" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/page" method="post">
<xsl:if test="page/@id">
<input type="hidden" name="id" value="{page/@id}" />
</xsl:if>

<table class="edit left">
<tr><td>URL:</td><td><input type="text" name="url" value="{page/url}" class="text" /></td></tr>
<tr><td>Language:</td><td><select name="language" class="text language">
<xsl:for-each select="languages/language">
<option value="{@code}">
	<xsl:if test="@code=../../page/language"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
	<xsl:value-of select="." />
</option>
</xsl:for-each>
</select></td></tr>
<tr><td>Layout:</td><td><select name="layout" class="text layout">
<xsl:for-each select="layouts/layout">
<option value="{.}">
	<xsl:if test=".=../@current"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
	<xsl:value-of select="." />
</option>
</xsl:for-each>
</select></td></tr>
<tr><td>Visible:</td><td><input type="checkbox" name="visible">
<xsl:if test="page/visible='yes'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input></td></tr>
<tr><td>Back link:</td><td><input type="checkbox" name="back">
<xsl:if test="page/back='yes'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input></td></tr>
<tr><td colspan="2">
<fieldset class="roles"><legend>Private: <input type="checkbox" name="private">
<xsl:if test="page/private='yes'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input></legend>
<table>
<xsl:for-each select="roles/role">
<tr><td><xsl:value-of select="." />:</td><td><input type="checkbox" name="roles[{@id}]">
<xsl:if test="@checked='yes' or @id=$admin_role_id">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
<xsl:if test="@id=$admin_role_id">
<xsl:attribute name="disabled">disabled</xsl:attribute>
</xsl:if>
</input></td></tr>
</xsl:for-each>
</table>
</fieldset>
</td></tr>
</table>

<table class="edit right">
<tr><td>Title:</td><td><input type="text" name="title" value="{page/title}" class="text" /></td></tr>
<tr><td>Description:</td><td><input type="text" name="description" value="{page/description}" class="text" /></td></tr>
<tr><td>Keywords:</td><td><input type="text" name="keywords" value="{page/keywords}" class="text" /></td></tr>
<tr><td>Style:</td><td><textarea name="style" class="text style"><xsl:value-of select="page/style" /></textarea></td></tr>
</table>

<br clear="both" />

<textarea id="editor" name="content" class="text content"><xsl:value-of select="page/content" /></textarea>

<input type="submit" name="submit_button" value="Save page" class="button" />
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/admin/page'" />
<xsl:if test="page/@id">
<input type="submit" name="submit_button" value="Delete page" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
<input type="button" value="Start CKEditor" id="start_cke" class="button" onClick="javascript:start_ckeditor(300)" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/page.png" class="title_icon" />Page administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
