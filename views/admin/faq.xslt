<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<xsl:for-each select="sections/section">
	<xsl:variable name="section_id" select="@id" />
	<h3><xsl:value-of select="." /></h3>
	<table class="list">
	<tr><th>Question</th></tr>
	<xsl:for-each select="../../faqs/faq[section_id=$section_id]">
		<tr class="click" onClick="javascript:document.location='/admin/faq/{@id}'"><td><xsl:value-of select="question" /></td></tr>
	</xsl:for-each>
	</table>
</xsl:for-each>

<a href="/admin/faq/new" class="button">New FAQ</a>
<a href="/admin" class="button">Back</a>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/faq" method="post">
<xsl:if test="faq/@id">
<input type="hidden" name="id" value="{faq/@id}" />
</xsl:if>
<table class="edit">
<tr><td>Question:</td><td><input type="text" name="question" value="{faq/question}" class="text" /></td></tr>
<tr><td>Answer:</td><td><textarea id="editor" name="answer" class="text"><xsl:value-of select="faq/answer" /></textarea></td></tr>
<tr><td>Section:</td><td>

<xsl:if test="sections/section">
<div><input type="radio" id="select_old" name="select" value="old">
<xsl:if test="faq/select='old'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
</input><select name="section_id" onFocus="javascript:document.getElementById('select_old').checked = true">
<xsl:for-each select="sections/section">
<option value="{@id}"><xsl:if test="@id=../../faq/section_id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if><xsl:value-of select="." /></option>
</xsl:for-each>
</select></div>
</xsl:if>

<div><input type="radio" id="select_new" name="select" value="new">
<xsl:if test="faq/select='new'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
</input><input type="text" name="label" value="{faq/label}" class="input" onFocus="javascript:document.getElementById('select_new').checked = true" /></div>

</td></tr>
</table>

<input type="submit" name="submit_button" value="Save FAQ" class="button" />
<a href="/admin/faq" class="button">Back</a>
<xsl:if test="faq/@id">
<input type="submit" name="submit_button" value="Delete FAQ" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
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
<h1><img src="/images/icons/faq.png" class="title_icon" />F.A.Q. Administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
</xsl:template>

</xsl:stylesheet>
