<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/tablemanager.xslt" />

<xsl:template match="albums">
<div class="albums">
<form action="/{/output/page}" method="post">
Photo album: <select name="album" onChange="javascript:submit()">
<xsl:for-each select="album">
<option value="{@id}"><xsl:if test="@id=../@current"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if><xsl:value-of select="." /></option>
</xsl:for-each>
</select>
<input type="hidden" name="submit_button" value="album" />
</form>
</div>
</xsl:template>

<xsl:template match="show_photo">
<img src="/photo/thumbnail_{@id}.{.}" class="preview" />
</xsl:template>

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
<p><xsl:value-of select="." disable-output-escaping="yes" /></p>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<xsl:apply-templates select="tablemanager/albums" />
<xsl:apply-templates select="tablemanager/show_photo" />
<xsl:apply-templates select="tablemanager" />
</xsl:template>

</xsl:stylesheet>
