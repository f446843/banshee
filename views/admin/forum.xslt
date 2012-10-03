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
<br clear="right" />
<xsl:for-each select="messages/message">
<div class="post">
<div class="subject"><xsl:value-of select="subject" /></div>
<div class="timestamp"><xsl:value-of select="timestamp" /></div>
<div class="ip_address"><xsl:value-of select="ip_address" /></div>
<div class="action">
	<input type="button" value="view" onClick="javascript:document.location='/forum/topic/{topic_id}#{@id}'" class="action" />
	<input type="button" value="edit" onClick="javascript:document.location='/admin/forum/{@id}'" class="action" />
	<form action="/admin/forum" method="post">
	<input type="hidden" name="message_id" value="{@id}" />
	<input type="submit" name="submit_button" value="delete" class="action" onClick="javascript:return confirm('DELETE: Are you sure?')" />
	</form>
</div>
<div class="message">
<span class="author"><xsl:value-of select="author" />:</span>
<xsl:value-of select="content" />
</div>
</div>
</xsl:for-each>
<xsl:apply-templates select="pagination" />

<input type="button" value="Back" onClick="javascript:document.location='/admin'" class="button" />
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/forum" method="post">
<input type="hidden" name="id" value="{message/@id}" />
<p><textarea name="content" class="text"><xsl:value-of select="message/content" /></textarea></p>
<input type="submit" name="submit_button" value="Save message" class="button" />
<input type="button" value="Cancel" onClick="javascript:document.location='/admin/forum'" class="button" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/forum.png" class="title_icon" />Forum administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
