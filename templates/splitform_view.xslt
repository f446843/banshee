<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />
<xsl:include href="banshee/splitform.xslt" />

<!--
//
//  Layout templates
//
//-->
<xsl:template name="header">
<h1>Title</h1>
</xsl:template>
<xsl:template name="footer">
<div>Progress: step <xsl:value-of select="../../current + 1" /> of <xsl:value-of select="../../current/@max + 1" /></div>
</xsl:template>

<!--
//
//  Form template
//
//-->
<xsl:template match="splitform/template_name">
<xsl:call-template name="header" />
<table>
<tr><td>Key 1:</td><td><input type="text" name="key1" value="{key1}" class="text" /></td></tr>
<tr><td>Key 2:</td><td><input type="text" name="key2" value="{key2}" class="text" /></td></tr>
</table>
<xsl:call-template name="footer" />
</xsl:template>

<!--
//
//  Process template
//
//-->
<xsl:template match="submit">
<xsl:call-template name="header" />
<p>Your information has been processed.</p>
<input type="button" value="Continue" class="button" onClick="javascript:document.location='/'" />
</xsl:template>

</xsl:stylesheet>
