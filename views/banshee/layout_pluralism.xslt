<?xml version="1.0" ?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="layout_pluralism">
<html>

<head>
<meta http-equiv="Content-Language" content="{language}" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="author" content="Hugo Leisink" />
<meta name="publisher" content="Hugo Leisink" />
<meta name="copyright" content="Copyright (C) by Hugo Leisink. All rights reserved. Protected by the Copyright laws of the Netherlands and international treaties." />
<meta name="description" content="{description}" />
<meta name="keywords" content="{keywords}" />
<meta name="generator" content="Banshee PHP framework v{/output/banshee_version} (http://www.banshee-php.org/)" />
<link rel="apple-touch-icon" href="/images/iphone.png" />
<title><xsl:if test="title/@page!=''"><xsl:value-of select="title/@page" /> - </xsl:if><xsl:value-of select="title" /></title>
<xsl:for-each select="alternates/alternate">
<link rel="alternate" title="{.}"  type="{@type}" href="{@url}" />
</xsl:for-each>
<link rel="stylesheet" type="text/css" href="/css/banshee/layout_pluralism.css" />
<xsl:for-each select="styles/style">
<link rel="stylesheet" type="text/css" href="{.}" />
</xsl:for-each>
<xsl:if test="inline_css!=''">
<style type="text/css">
<xsl:value-of select="inline_css" />
</style>
</xsl:if>
<xsl:for-each select="javascripts/javascript">
<script type="text/javascript" src="{.}"></script><xsl:text>
</xsl:text></xsl:for-each>
</head>

<body>
<xsl:if test="javascripts/@onload">
	<xsl:attribute name="onLoad">javascript:<xsl:value-of select="javascripts/@onload" /></xsl:attribute>
</xsl:if>
<div class="wrapper">
	<div class="header">
		<div class="title"><xsl:value-of select="/output/layout_pluralism/title" /></div>
		<div class="menu">
			<ul>
				<xsl:for-each select="/output/menu/item">
				<li><a href="{link}"><xsl:value-of select="text" /></a></li>
				</xsl:for-each>
			</ul>
		</div>
	</div>
	<div class="page">
		<xsl:if test="/output/content/blocks/sidebar">
			<div class="sidebar">
			<h3><xsl:value-of select="/output/content/blocks/sidebar/title" /></h3>
			<xsl:value-of disable-output-escaping="yes" select="/output/content/blocks/sidebar/content" />
			</div>
		</xsl:if>
		<xsl:apply-templates select="/output/system_messages" />
		<xsl:apply-templates select="/output/system_warnings" />
		<xsl:apply-templates select="/output/content" />
	</div>
	<div class="footer">
		<span>Built upon the <a href="http://www.banshee-php.org/">Banshee PHP framework</a> v<xsl:value-of select="/output/banshee_version" /></span>
		<span>Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></span>
	</div>
</div>
<xsl:apply-templates select="/output/internal_errors" />
</body>

</html>
</xsl:template>

</xsl:stylesheet>
