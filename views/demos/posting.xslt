<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<xsl:template match="content">
<h1>Posting library</h1>
<xsl:call-template name="show_messages" />

<form action="/demos/posting" method="post" accept-charset="utf-8">
<textarea name="input" class="text"><xsl:value-of select="input" /></textarea><br />
<input type="submit" value="Submit text" class="button" />
<input type="button" value="Back" class="button" onClick="document.location='/demos'" />
</form>
<div class="output"><xsl:value-of select="output" /></div>
<div class="output"><xsl:value-of disable-output-escaping="yes" select="output" /></div>
</xsl:template>

</xsl:stylesheet>
