<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<h3>Future polls</h3>
<table class="list">
<tr><th class="question">Question</th><th class="date">Begin</th><th class="date">End</th></tr>
<xsl:for-each select="polls/poll[@edit='yes']">
<tr class="click" onClick="javascript:document.location='/admin/poll/{@id}'">
<td><xsl:value-of select="question" /></td>
<td><xsl:value-of select="begin" /></td>
<td><xsl:value-of select="end" /></td>
</tr>
</xsl:for-each>
</table>

<input type="button" value="New poll" class="button" onClick="javascript:document.location='/admin/poll/new'" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />

<h3 class="spacer">Active and previous polls</h3>
<table class="list">
<tr><th class="question">Question</th><th class="date">Begin</th><th class="date">End</th></tr>
<xsl:for-each select="polls/poll[@edit='no']">
<tr>
<td><xsl:value-of select="question" /></td>
<td><xsl:value-of select="begin" /></td>
<td><xsl:value-of select="end" /></td>
</tr>
</xsl:for-each>
</table>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />

<form action="/admin/poll" method="post">
<xsl:if test="poll/@id">
<input type="hidden" name="id" value="{poll/@id}" />
</xsl:if>
<table class="form">
<tr><td>Question:</td><td><input type="text" name="question" value="{poll/question}" class="long text" /></td></tr>
<tr><td>First day:</td><td><span id="begin_show" class="date"><xsl:value-of select="poll/begin_show" /></span><input type="hidden" id="begin" name="begin" value="{poll/begin}" /></td></tr>
<tr><td>Last day:</td><td><span id="end_show" class="date"><xsl:value-of select="poll/end_show" /></span><input type="hidden" id="end" name="end" value="{poll/end}" /></td></tr>
</table>
<table class="form">
<xsl:for-each select="poll/answers/answer">
	<tr><td>Answer <xsl:value-of select="@nr" />:</td><td><input type="text" name="answers[]" value="{.}" class="long text" /></td></tr>
</xsl:for-each>
</table>
<input type="submit" name="submit_button" value="Save poll" class="button" />
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/admin/poll'" />
<xsl:if test="poll/@id">
<input type="submit" name="submit_button" value="Delete poll" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/poll.png" class="title_icon" />Poll administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
