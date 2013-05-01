<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />
<xsl:include href="../banshee/pagination.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<table class="list">
<tr><th class="content">Title</th>
<xsl:if test="/output/user/@admin='yes'">
<th class="author">Author</th>
</xsl:if>
<th class="timestamp">Timestamp</th></tr>
<xsl:for-each select="weblogs/weblog">
<tr class="click" onClick="javascript:document.location='/admin/weblog/{@id}'">
<td><xsl:value-of select="title" /></td>
<xsl:if test="/output/user/@admin='yes'">
<td><xsl:value-of select="author" /></td>
</xsl:if>
<td><xsl:value-of select="timestamp" /></td>
</tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="pagination" />

<a href="/admin/weblog/new" class="button">New weblog</a>
<a href="/admin" class="button">Back</a>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/weblog" method="post">
<xsl:if test="weblog/@id">
<input type="hidden" name="id" value="{weblog/@id}" />
</xsl:if>
Title: <input type="text" name="title" value="{weblog/title}" class="text" />
<textarea id="editor" name="content" class="text"><xsl:value-of select="weblog/content" /></textarea>

<!-- Tags -->
<table>
<tr><td>Tags:</td><td><div class="tags">
<xsl:for-each select="tags/tag">
<span>
<input type="checkbox" name="tag[]" value="{@id}">
<xsl:if test="@selected='yes'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
</input>
<xsl:value-of select="." />
</span>
</xsl:for-each></div></td></tr>
<tr><td>New tags:</td><td><input type="text" name="new_tags" value="{weblog/new_tags}" class="text" /></td></tr>
<tr><td>Visible:</td><td><input type="checkbox" name="visible">
<xsl:if test="weblog/visible='yes'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input></td></tr>
</table>

<!-- Buttons -->
<input type="submit" name="submit_button" value="Save weblog" class="button" />
<a href="/admin/weblog" class="button">Cancel</a>
<xsl:if test="weblog/@id">
<input type="submit" name="submit_button" value="Delete weblog" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
<input type="button" value="Start CKEditor" id="start_cke" class="button" onClick="javascript:start_ckeditor()" />

<!-- Comments -->
<h3>Comments</h3>
<p>Selected comments will be deleted.</p>
<table class="list">
<tr><th class="delete"></th><th class="author">Author</th><th>Content</th></tr>
<xsl:for-each select="comments/comment">
<tr><td><input type="checkbox" name="comment[]" value="{@id}" /></td><td><xsl:value-of select="author" /></td><td><xsl:value-of select="content" /></td></tr>
</xsl:for-each>
</table>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/weblog.png" class="title_icon" />Weblog administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
