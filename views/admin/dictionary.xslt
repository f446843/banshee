<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/pagination.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<table class="list">
<tr><th class="word">Word</th><th>Short description</th></tr>
<xsl:for-each select="words/word">
	<tr class="click" onClick="javascript:document.location='/admin/dictionary/{@id}'">
	<td><xsl:value-of select="word" /></td>
	<td><xsl:value-of select="short_description" /></td>
	</tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="pagination" />

<input type="button" value="New word" onClick="javascript:document.location='/admin/dictionary/new'" class="button" />
<input type="button" value="Cancel" onClick="javascript:document.location='/admin'" class="button" />
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/dictionary" method="post">
<xsl:if test="@id">
<input type="hidden" name="id" value="{@id}" />
</xsl:if>
<table class="edit">
<tr><td>Word:</td><td><input type="text" name="word" value="{word}" class="text" /></td></tr>
<tr><td>Short description:</td><td><input type="text" name="short_description" value="{short_description}" class="text" /></td></tr>
<tr><td>Long description:</td><td><textarea id="editor" name="long_description" class="text"><xsl:value-of select="long_description" /></textarea></td></tr>
</table>

<input type="submit" name="submit_button" value="Save word" class="button" />
<input type="button" value="Cancel" onClick="javascript:document.location='/admin/dictionary'" class="button" />
<xsl:if test="@id">
<input type="submit" name="submit_button" value="Delete word" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
<input type="button" value="Start CKEditor" id="start_cke" class="button" onClick="javascript:start_ckeditor()" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/dictionary.png" class="title_icon" />Dictionary administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
