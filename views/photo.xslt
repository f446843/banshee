<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />
<xsl:include href="includes/pagination.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<div class="albums">
<ul>
<xsl:for-each select="albums/album">
<li>
<xsl:if test="thumbnail">
<div class="thumbnail"><a href="/{/output/page}/{@id}"><img src="/{/output/page}/thumbnail_{thumbnail}.{extension}" alt="thumbnail {thumbnail}" class="thumbnail" /></a></div>
</xsl:if>
<div class="name"><a href="/{/output/page}/{@id}"><xsl:value-of select="name" /></a></div>
<div class="description"><xsl:value-of select="description" /></div>
<br clear="both" />
</li>
</xsl:for-each>
</ul>
</div>
<br clear="both" />

<xsl:apply-templates select="pagination" />
</xsl:template>

<!--
//
//  Photos template
//
//-->
<xsl:template match="photos">
<p><xsl:value-of select="@info" /></p>
<div class="photos" id="gallery">
<xsl:for-each select="photo">
<div class="photo"><a href="/{/output/page}/image_{@id}.{extension}" title="{title}"><div class="box"><img src="/{/output/page}/thumbnail_{@id}.{extension}" alt="{title}" /></div></a></div>
</xsl:for-each>
</div>
<br clear="both" />

<xsl:apply-templates select="pagination" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/{/output/page}'" />
</xsl:template>

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
<p><xsl:value-of select="." /></p>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><xsl:value-of select="title" /></h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="photos" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
