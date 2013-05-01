<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />
<xsl:include href="../banshee/splitform.xslt" />

<!--
//
//  Layout templates
//
//-->
<xsl:template name="header">
<h1>Splitform library demo</h1>
</xsl:template>
<xsl:template name="footer">
<div>Progress: step <xsl:value-of select="../../current + 1" /> of <xsl:value-of select="../../current/@max + 1" /></div>
</xsl:template>

<!--
//
//  Form 1 template
//
//-->
<xsl:template match="splitform/form_1">
<xsl:call-template name="header" />
<table>
<tr><td>Name:</td><td><input type="text" name="name" value="{name}" class="text" /></td></tr>
<tr><td>Number:</td><td><input type="text" name="number" value="{number}" class="text" /></td></tr>
</table>
<xsl:call-template name="footer" />
</xsl:template>

<!--
//
//  Form 2 template
//
//-->
<xsl:template match="splitform/form_2">
<xsl:call-template name="header" />
<table>
<tr><td>Title:</td><td><input type="text" name="title" value="{title}" class="text" /></td></tr>
<tr><td>Content:</td><td><textarea name="content" class="text"><xsl:value-of select="content" /></textarea></td></tr>
</table>
<xsl:call-template name="footer" />
</xsl:template>

<!--
//
//  Form 3 template
//
//-->
<xsl:template match="splitform/form_3">
<xsl:call-template name="header" />
Remark: <input type="text" name="remark" value="{remark}" class="text" />
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

<h3>Values</h3>
<table class="values">
<xsl:for-each select="value">
<tr><td><xsl:value-of select="@key" />:</td><td><xsl:value-of select="." /></td></tr>
</xsl:for-each>
</table>

<a href="/demos" class="button">Continue</a>
</xsl:template>

</xsl:stylesheet>
