<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />
<xsl:include href="banshee/pagination.xslt" />

<!--
//
//  Forums template
//
//-->
<xsl:template match="forums">
<div class="forums">
<xsl:for-each select="forum">
<div>
<h4><a href="/{/output/page}/{@id}"><xsl:value-of select="title" /></a> (<xsl:value-of select="topics" />)</h4>
<xsl:value-of select="description" />
</div>
</xsl:for-each>
</div>
</xsl:template>

<!--
//
//  Forum template
//
//-->
<xsl:template match="forum">
<div class="forum">
<h3><xsl:value-of select="title" /></h3>
<xsl:for-each select="topics/topic">
	<div class="title">
		<a href="/{/output/page}/topic/{@id}"><xsl:value-of select="subject" /></a>
		<xsl:if test="unread='yes'"><span class="unread">*</span></xsl:if>
	</div>
	<div class="starter"><xsl:value-of select="starter" /></div>
	<div class="messages"><xsl:value-of select="messages" /></div>
	<div class="date"><xsl:value-of select="timestamp" /></div>
</xsl:for-each>
</div>

<xsl:apply-templates select="pagination" />

<div class="buttonbar">
<a href="/{/output/page}/{@id}/new" class="button">New topic</a>
<a href="/{/output/page}" class="button">Back</a>
</div>
</xsl:template>

<!--
//
//  Topic template
//
//-->
<xsl:template match="topic">
<div class="topic">
<h3><xsl:value-of select="subject" /></h3>
<xsl:for-each select="message">
	<a name="{@id}" />
	<div class="message">
	<div class="author {usertype}">
		<xsl:value-of select="author" />
		<xsl:if test="unread='yes'"><span class="unread">*</span></xsl:if>
	</div>
	<div class="date"><xsl:value-of select="timestamp" /></div>
	<xsl:if test="@moderate='yes'">
	<div class="moderate"><a href="/admin/forum/{@id}">edit</a></div>
	</xsl:if>
	<xsl:value-of disable-output-escaping="yes" select="content" />
	</div>
</xsl:for-each>

<xsl:call-template name="show_messages" />
<a name="response" />
<form action="/{/output/page}#response" method="post" class="new_response">
<input type="hidden" name="topic_id" value="{@id}" />
<xsl:if test="not(/output/user)">
<div class="username">Name: <input type="text" name="username" value="{response/username}" class="text" /></div>
</xsl:if>
<textarea id="content" name="content" class="text"><xsl:value-of select="response/content" /></textarea>
<xsl:call-template name="smilies" />

<input type="submit" name="submit_button" value="Post response" class="button" />
<a href="/{/output/page}/{@forum_id}" class="button">Back</a>
</form>
</div>
</xsl:template>

<!--
//
//  New topic template
//
//-->
<xsl:template match="newtopic">
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post" class="new_topic">
<input type="hidden" name="forum_id" value="{forum_id}" />
<table>
<xsl:if test="not(/output/user)">
<tr><td>Name:</td><td><input type="text" name="username" value="{username}" class="text" /></td></tr>
</xsl:if>
<tr><td>Topic subject:</td><td><input type="text" name="subject" value="{subject}" class="text" /></td></tr>
</table>
<textarea id="content" name="content" class="text"><xsl:value-of select="content" /></textarea>
<xsl:call-template name="smilies" />

<input type="submit" name="submit_button" value="Create topic" class="button" />
<a href="/{/output/page}/{forum_id}" class="button">Back</a>
</form>
</xsl:template>

<!--
//
//  Smilies template
//
//-->
<xsl:template name="smilies">
<div class="smilies">
<xsl:for-each select="../smilies/smiley">
<img src="/images/smilies/{.}" onClick="show_smiley('{@text}')" />
</xsl:for-each>
</div>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Forum</h1>
<xsl:apply-templates select="forums" />
<xsl:apply-templates select="forum" />
<xsl:apply-templates select="topic" />
<xsl:apply-templates select="newtopic" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
