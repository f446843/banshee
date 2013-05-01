<?xml version="1.0" ?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="layout_XXX">
<html>

<head>
<meta http-equiv="Content-Language" content="{language}" />
<meta name="author" content="AUTHOR" />
<meta name="publisher" content="PUBLISHER" />
<meta name="copyright" content="COPYRIGHT" />
<meta name="description" content="{description}" />
<meta name="keywords" content="{keywords}" />
<meta name="generator" content="Banshee PHP framework v{/output/banshee_version} (http://www.banshee-php.org/)" />
<link rel="apple-touch-icon" href="/images/iphone.png" />
<title><xsl:if test="title/@page!=''"><xsl:value-of select="title/@page" /> - </xsl:if><xsl:value-of select="title" /></title>
<xsl:for-each select="alternates/alternate">
<link rel="alternate" title="{.}"  type="{@type}" href="{@url}" />
</xsl:for-each>
<link rel="stylesheet" type="text/css" href="/css/banshee/layout_XXX.css" />
<xsl:for-each select="styles/style">
<link rel="stylesheet" type="text/css" href="{.}" />
</xsl:for-each>
<xsl:if test="inline_css!=''">
<style type="text/css">
<xsl:value-of select="inline_css" />
</style>
</xsl:if>
<xsl:for-each select="javascripts/javascript">
<script type="text/javascript" src="{.}"></script>
</xsl:for-each>
</head>

<body>
<xsl:if test="javascripts/@onload">
	<xsl:attribute name="onLoad">javascript:<xsl:value-of select="javascripts/@onload" /></xsl:attribute>
</xsl:if>
<div class="menu">
	<ul>
	<xsl:for-each select="/output/menu/item">
	<li><a href="{link}"><xsl:value-of select="text" /></a></li>
	</xsl:for-each>
	</ul>
</div>
<div class="page">
	<xsl:apply-templates select="/output/system_messages" />
	<xsl:apply-templates select="/output/system_warnings" />
	<xsl:apply-templates select="/output/content" />
</div>
<xsl:apply-templates select="/output/internal_errors" />
</body>

</html>
</xsl:template>

</xsl:stylesheet>
