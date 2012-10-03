<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  Sidebar template
//
//-->
<xsl:template match="sidebar">
<div class="weblog_sidebar">
<p><a href="{/output/page}">All articles</a></p>

All tags:
<ul>
<xsl:for-each select="tags/tag">
<li><a href="/{/output/page}/tag/{@id}"><xsl:value-of select="." /></a></li>
</xsl:for-each>
</ul>

Years:
<ul>
<xsl:for-each select="years/year">
<li><a href="/{/output/page}/period/{.}"><xsl:value-of select="." /></a></li>
</xsl:for-each>
</ul>

Periods:
<ul>
<xsl:for-each select="periods/period">
<li><a href="/{/output/page}/period/{@link}"><xsl:value-of select="." /></a></li>
</xsl:for-each>
</ul>

</div>
</xsl:template>

<!--
//
//  Weblogs template
//
//-->
<xsl:template match="weblogs">
<xsl:apply-templates select="weblog" />
</xsl:template>

<!--
//
//  Weblog template
//
//-->
<xsl:template match="weblog">
<div class="weblog">
<h2><a href="/{/output/page}/{@id}"><xsl:value-of select="title" /></a></h2>
<div class="timestamp"><xsl:value-of select="timestamp" /></div>
<div class="content"><xsl:value-of disable-output-escaping="yes" select="content" /></div>

<!-- Tags -->
<div class="tags">Tags:
<xsl:for-each select="tags/tag">
<span class="tag"><a href="/{/output/page}/tag/{@id}"><xsl:value-of select="." /></a></span>
</xsl:for-each>
</div>

<div class="author">by <xsl:value-of select="author" /></div>
<xsl:if test="comment_count">
<div class="comment_count"><a href="/{/output/page}/{@id}">Comments: <xsl:value-of select="comment_count" /></a></div>
</xsl:if>
</div>

<!-- Comments -->
<xsl:if test="comments">
<div class="comments">
<xsl:for-each select="comments/comment">
<div class="comment">
<div class="author"><xsl:value-of select="author" /></div>
<div class="timestamp"><xsl:value-of select="timestamp" /></div>
<xsl:value-of disable-output-escaping="yes" select="content" />
</div>
</xsl:for-each>

<!-- New comment form -->
<a name="new_comment" />
<form action="/{/output/page}#new_comment" method="post">
<input type="hidden" name="weblog_id" value="{@id}" />
<xsl:call-template name="show_messages" />
Name: <input type="text" name="author" value="{../comment/author}" class="text" />
<textarea name="content" class="text"><xsl:value-of select="../comment/content" /></textarea>
<input type="submit" value="Save" class="button" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/{/output/page}'" />
</form>

</div>
</xsl:if>
</xsl:template>

<!--
//
//  List template
//
//-->
<xsl:template match="list">
<h2><xsl:value-of select="@label" /></h2>
<ul class="tagged">
<xsl:for-each select="weblog">
<li><a href="/{/output/page}/{@id}"><xsl:value-of select="title" /></a> by <xsl:value-of select="author" /></li>
</xsl:for-each>
</ul>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<xsl:apply-templates select="sidebar" />
<h1>Weblog</h1>
<div class="rsslink"><a href="/weblog.xml"><img src="/images/rss.png" alt="RSS" /></a></div>
<div class="weblog_main">
<xsl:apply-templates select="weblogs" />
<xsl:apply-templates select="weblog" />
<xsl:apply-templates select="list" />
<xsl:apply-templates select="result" />
</div>
<div style="clear:both"></div>
</xsl:template>

</xsl:stylesheet>
