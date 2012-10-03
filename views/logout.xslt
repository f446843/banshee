<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Logout template
//
//-->
<xsl:template match="logout">
<p>You are now logged out.</p>
<xsl:call-template name="redirect">
<xsl:with-param name="url"></xsl:with-param>
</xsl:call-template>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Logout</h1>
<xsl:apply-templates select="logout" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
