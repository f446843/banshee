<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="layout_compromise">
<html>

<head>
<meta http-equiv="Content-Language" content="{language}" />
<meta name="author" content="Hugo Leisink" />
<meta name="publisher" content="Hugo Leisink" />
<meta name="copyright" content="Copyright (C) by Hugo Leisink. All rights reserved. Protected by the Copyright laws of the Netherlands and international treaties." />
<meta name="description" content="{description}" />
<meta name="keywords" content="{keywords}" />
<meta name="generator" content="Banshee PHP framework v{/output/banshee_version} (http://www.banshee-php.org/)" />
<title><xsl:if test="title/@page!=''"><xsl:value-of select="title/@page" /> - </xsl:if><xsl:value-of select="title" /></title>
<xsl:for-each select="alternates/alternate">
<link rel="alternate" title="{.}"  type="{@type}" href="{@url}" />
</xsl:for-each>
<link rel="stylesheet" type="text/css" href="/css/includes/layout_compromise.css" />
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
		<div class="title">Banshee Content Management System</div>
	</div>
	<div class="menu">
		<xsl:if test="/output/user and /output/page!='logout' and /output/page!='profile'">
		<ul>
			<xsl:for-each select="/output/menu/item">
			<li><a href="{link}"><xsl:value-of select="text" /></a></li>
			</xsl:for-each>
		</ul>
		</xsl:if>
	</div>
	<div class="page">
		<xsl:apply-templates select="/output/system_messages" />
		<xsl:apply-templates select="/output/system_warnings" />
		<xsl:apply-templates select="/output/content" />
	</div>
	<div class="footer">
		<xsl:if test="/output/user"><span>Logged in as <a href="/profile"><xsl:value-of select="/output/user" /></a></span></xsl:if>
		<span>Built upon the <a href="http://www.banshee-php.org/">Banshee PHP framework</a> v<xsl:value-of select="/output/banshee_version" /></span>
		<span>Design by <a href="http://www.freecsstemplates.org/">Free CSS Templates</a></span>
	</div>
</div>
<xsl:apply-templates select="/output/internal_errors" />
</body>

</html>
</xsl:template>

</xsl:stylesheet>
