<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/pagination.xslt" />
<!--
//
//  Overview template
//
//-->
<xsl:template match="guestbook">
<table class="list">
<tr><th class="author">Author</th><th class="message">Message</th><th class="timestamp">Timestamp</th><th class="ip_address">IP address</th><th class="delete"></th></tr>
<xsl:for-each select="item">
<tr>
<td><xsl:value-of select="author" /></td>
<td><xsl:value-of select="message" /></td>
<td><xsl:value-of select="timestamp" /></td>
<td><xsl:value-of select="ip_address" /></td>
<td><form action="/admin/guestbook" method="post">
<input type="hidden" name="id" value="{@id}" />
<input type="submit" name="submit_button" value="delete" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</form></td>
</tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="pagination" />

<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/guestbook.png" class="title_icon" />Guestbook administration</h1>
<xsl:apply-templates select="guestbook" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
