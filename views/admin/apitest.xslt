<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
<pre class="result"><xsl:value-of select="." /></pre>
<h2>Form</h2>
</xsl:template>

<!--
//
//  Form template
//
//-->
<xsl:template match="form">
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post">
<table class="edit">
<tr><td>Method:</td><td><select name="method" class="text"><xsl:for-each select="methods/method">
<option><xsl:if test=".=../../method"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if><xsl:value-of select="." /></option>
</xsl:for-each></select></td></tr>
<tr><td>Type:</td><td><select name="type" class="text"><xsl:for-each select="types/type">
<option><xsl:if test=".=../../type"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if><xsl:value-of select="." /></option>
</xsl:for-each></select></td></tr>
<tr><td>URL:</td><td><input type="text" name="url" value="{url}" class="text" /></td></tr>
<tr><td>POST data:</td><td><textarea name="postdata" class="text"><xsl:value-of select="postdata" /></textarea></td></tr>
<tr><td>Username:</td><td><input type="text" name="username" value="{username}" class="text" /></td></tr>
<tr><td>Password:</td><td><input type="password" name="password" value="{password}" class="text" /></td></tr>
</table>

<input type="submit" name="submit_button" value="Submit" class="button" />
<a href="/admin" class="button">Back</a>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>API test</h1>
<xsl:apply-templates select="result" />
<xsl:apply-templates select="form" />
</xsl:template>

</xsl:stylesheet>
