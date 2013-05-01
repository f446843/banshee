<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />

<!--
//
//  Mailbox template
//
//-->
<xsl:template match="mailbox">
<table class="list">
<tr><th class="subject">Subject</th><th class="from"><xsl:value-of select="@column" /></th><th class="date">Date</th></tr>
<xsl:for-each select="mail">
<tr class="click {read}" onClick="javascript:document.location='/{/output/page}/{@id}'">
<td><xsl:value-of select="subject" /></td>
<td><xsl:value-of select="user" /></td>
<td><xsl:value-of select="timestamp" /></td>
</tr>
</xsl:for-each>
</table>
<a href="/{/output/page}/new" class="button">New mail</a>
<a href="/{/output/page}{../link/@url}" class="button"><xsl:value-of select="../link" /></a>
</xsl:template>

<!--
//
//  Mail template
//
//-->
<xsl:template match="mail">
<form action="/{/output/page}" method="post">
<input type="hidden" name="id" value="{@id}" />
<div class="from">From: <xsl:value-of select="from_user" /></div>
<div class="message"><xsl:value-of disable-output-escaping="yes" select="message" /></div>
<xsl:if test="@actions='yes'">
<a href="/{/output/page}/reply/{@id}" class="button">Reply</a>
</xsl:if>
<input type="submit" name="submit_button" value="Delete mail" class="button" onClick="return confirm('DELETE: Are you sure?')" />
<a href="/{/output/page}{@back}" class="button">Back</a>
</form>
</xsl:template>

<!--
//
//  Write template
//
//-->
<xsl:template match="write">
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post">
<table class="edit">
<tr><td>To:</td><td><select name="to_user_id" class="text">
<xsl:for-each select="recipients/recipient">
<option value="{@id}">
<xsl:if test="@id=../../mail/to_user_id">
<xsl:attribute name="selected">selected</xsl:attribute>
</xsl:if>
<xsl:value-of select="." /></option>
</xsl:for-each>
</select></td></tr>
<tr><td>Subject:</td><td><input type="text" name="subject" value="{mail/subject}" class="text" /></td></tr>
</table>
<textarea name="message" class="text"><xsl:value-of select="mail/message" /></textarea>
<input type="submit" name="submit_button" value="Send mail" class="button" />
<a href="/{/output/page}" class="button">Cancel</a>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><xsl:value-of select="title" /></h1>
<xsl:apply-templates select="mailbox" />
<xsl:apply-templates select="mail" />
<xsl:apply-templates select="write" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
