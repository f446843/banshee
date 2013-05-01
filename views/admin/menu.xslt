<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<!--
//
//  Template template
//
//-->
<xsl:template match="branch">
<ul>
<xsl:for-each select="item">
<li><input type="text" value="{text}" class="text" /><input type="text" value="{link}" class="text" />
<xsl:apply-templates select="branch" /></li>
</xsl:for-each>
</ul>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />

<form action="/admin/menu" method="post">
<xsl:apply-templates select="branch" />
<input type="submit" name="submit_button" value="Update" class="button" />
<a href="/admin" class="button">Back</a>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/menu.png" class="title_icon" />Menu administration</h1>
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
