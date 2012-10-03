<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="sessions">
<table class="list">
<tr><th>IP address</th><th>Expire date</th><th>Name</th></tr>
<xsl:for-each select="session">
<tr class="click {owner}" onClick="javascript:document.location='/session/{@id}'">
<td><xsl:value-of select="ip_address" /></td>
<td><xsl:value-of select="expire" /></td>
<td><xsl:value-of select="name" /></td>
</tr>
</xsl:for-each>
</table>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/session" method="post">
<input type="hidden" name="id" value="{session/@id}" />

<table class="edit">
<tr><td>Name:</td><td><input type="text" name="name" value="{session/name}" class="text" /></td></tr>
<tr><td>IP address:</td><td><xsl:value-of select="session/ip_address" /></td></tr>
<tr><td>Expire date:</td><td><xsl:value-of select="session/expire" /></td></tr>
</table>

<input type="submit" name="submit_button" value="Update session" class="button" />
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/session'" />
<input type="submit" name="submit_button" value="Delete session" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</form>
</xsl:template>

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
<p><xsl:value-of select="." /></p>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Session Manager</h1>
<xsl:apply-templates select="sessions" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
