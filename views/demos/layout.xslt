<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Layouts template
//
//-->
<xsl:template match="layouts">
<form action="/{/output/page}" method="post">
Available layout: <select name="layout" class="text">
<xsl:for-each select="layout">
<option>
<xsl:if test="@current='yes'"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
<xsl:value-of select="." />
</option>
</xsl:for-each>
</select>
<input type="submit" value="Set layout" class="button" />
</form>
<p>You can make a layout active by specifying it in settings/website.conf.</p>
<p>For more themes, visit the <a href="http://www.banshee-php.org/download">Banshee website</a>.</p>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
edit
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Layouts</h1>
<xsl:apply-templates select="layouts" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
