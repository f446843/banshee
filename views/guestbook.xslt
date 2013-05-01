<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />
<xsl:include href="banshee/pagination.xslt" />

<!--
//
//  Guestbook template
//
//-->
<xsl:template match="guestbook">
<xsl:for-each select="item">
<div class="guestbook">
<div class="author"><xsl:value-of select="author" /></div>
<div class="timestamp"><xsl:value-of select="timestamp" /></div>
<div class="message"><xsl:value-of disable-output-escaping="yes" select="message" /></div>
</div>
</xsl:for-each>
<xsl:apply-templates select="pagination" />

<xsl:if test="@skip_sign_link='no'">
<a name="sign" />
</xsl:if>
<form action="/{/output/page}#sign" method="post">
<xsl:call-template name="show_messages" />
Name: <input type="input" name="author" value="{../author}" class="text" />
<textarea name="message" class="text"><xsl:value-of select="../message" /></textarea>
<input type="submit" name="submit_button" value="Sign guestbook" class="button" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Guestbook</h1>
<xsl:apply-templates select="guestbook" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
