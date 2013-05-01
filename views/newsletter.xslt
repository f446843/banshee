<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="subscribe">
<xsl:call-template name="show_messages" />
<p>Subscribe here to our newsletter.</p>
<form action="/{/output/page}" method="post" class="newsletter">
<p>E-mail address: <input type="text" name="email" id="email" class="text" /></p>
<input type="submit" name="submit_button" value="Subscribe" class="button" />
<input type="submit" name="submit_button" value="Unsubscribe" class="button" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Newsletter</h1>
<xsl:apply-templates select="subscribe" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
