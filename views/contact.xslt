<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Contact template
//
//-->
<xsl:template match="contact">
<xsl:call-template name="show_messages" />

<form action="/{/output/page}" method="post">
<table class="contact">
<tr><td>Name:</td><td><input type="text" name="name" value="{name}" class="text" /></td></tr>
<tr><td>E-mail address:</td><td><input type="text" name="email" value="{email}" class="text" /></td></tr>
<tr><td>Telephone nr:</td><td><input type="text" name="telephone" value="{telephone}" class="text" /></td></tr>
<tr><td>Comment:</td><td><textarea name="comment" class="text"><xsl:value-of select="comment" /></textarea></td></tr>
</table>
<input type="submit" name="submit_button" value="Submit" class="button" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Contact</h1>
<xsl:apply-templates select="contact" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
