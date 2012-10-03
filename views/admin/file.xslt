<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Files template
//
//-->
<xsl:template match="files">
<table class="list">
<tr><th class="filename">Filename</th><th class="link">Link</th><th class="size">Filesize</th><th class="delete"></th></tr>

<xsl:if test="back">
<tr><td><a href="{back}">&lt;&lt;&lt; one directory up </a></td><td></td><td></td></tr>
</xsl:if>

<xsl:for-each select="dir">
<tr>
<td><a href="{link}">[ <xsl:value-of select="name" /> ]</a></td>
<td></td>
<td></td>
<td><xsl:if test="delete='yes'"><form action="/admin/file{../@dir}" method="post">
<input type="hidden" name="filename" value="{name}" />
<input type="submit" name="submit_button" value="delete" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</form></xsl:if></td>
</tr>
</xsl:for-each>

<xsl:for-each select="file">
<tr>
<td><xsl:value-of select="name" /></td>
<td><a href="{link}" target="_blank"><xsl:value-of select="link" /></a></td>
<td><xsl:value-of select="size" /></td>
<td><xsl:if test="delete='yes'"><form action="/admin/file{../@dir}" method="post">
<input type="hidden" name="filename" value="{name}" />
<input type="submit" name="submit_button" value="delete" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</form></xsl:if></td>
</tr>
</xsl:for-each>

</table>

<p><xsl:call-template name="show_messages" /></p>

<fieldset class="upload">
<legend>Upload new file</legend>
<form action="/admin/file{@dir}" method="post" enctype="multipart/form-data">
<input type="file" name="file" class="text" />
<input type="submit" name="submit_button" value="Upload file" class="button" />
</form>
</fieldset>

<fieldset class="create">
<legend>Create directory</legend>
<form action="/admin/file{@dir}" method="post">
<input type="text" name="create" value="{../create}" class="text" />
<input type="submit" name="submit_button" value="Create directory" class="button" />
</form>
</fieldset>

<br clear="both" />

<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/files.png" class="title_icon" />File administration</h1>
<xsl:apply-templates select="files" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
