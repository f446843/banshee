<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="main.xslt" />

<xsl:template match="content">
<h1>Error</h1>
<img src="/images/error.png" alt="error" class="error" />
<p><xsl:apply-templates select="website_error" /></p>
<p>If you believe this is due to a bug in this website, please notify the <a href="mailto:{webmaster_email}">webmaster</a>. Click <a href="/">here</a> to return to the homepage.</p>
</xsl:template>

</xsl:stylesheet>
