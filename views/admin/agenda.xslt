<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<xsl:variable name="now" select="appointments/@now" />

<h3>Appointment in the future</h3>
<table class="list">
<tr><th class="title">Title</th><th class="date">Begin</th><th class="date">End</th></tr>
<xsl:for-each select="appointments/appointment[begin&gt;$now]">
<xsl:sort select="begin" order="ascending" />
<tr class="click" onclick="javascript:document.location='/admin/agenda/{@id}'">
<td><xsl:value-of select="title" /></td>
<td><xsl:value-of select="begin_show" /><xsl:if test="all_day='no'">, <xsl:value-of select="begin_time" /></xsl:if></td>
<td><xsl:value-of select="end_show" /><xsl:if test="all_day='no'">, <xsl:value-of select="end_time" /></xsl:if></td>
</tr>
</xsl:for-each>
</table>

<h3>Appointment in the past</h3>
<table class="list">
<tr><th class="title">Title</th><th class="date">Begin</th><th class="date">End</th></tr>
<xsl:for-each select="appointments/appointment[begin&lt;$now]">
<xsl:sort select="begin" order="descending" />
<tr class="click" onclick="javascript:document.location='/admin/agenda/{@id}'">
<td><xsl:value-of select="title" /></td>
<td><xsl:value-of select="begin_show" /><xsl:if test="all_day='no'">, <xsl:value-of select="begin_time" /></xsl:if></td>
<td><xsl:value-of select="end_show" /><xsl:if test="all_day='no'">, <xsl:value-of select="end_time" /></xsl:if></td>
</tr>
</xsl:for-each>
</table>

<a href="/admin/agenda/new" class="button">New appointment</a>
<a href="/admin" class="button">Back</a>
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/agenda" method="post">
<xsl:if test="@id">
<input type="hidden" name="id" value="{@id}" />
</xsl:if>
<table class="edit">
<tr><td>Begin:</td><td><span id="begin_show" class="date"><xsl:value-of select="begin_show" /></span><input type="hidden" id="begin_date" name="begin_date" value="{begin_date}" /> <input type="text" id="begin_time" name="begin_time" value="{begin_time}" class="text time" /></td></tr>
<tr><td>End:</td><td><span id="end_show" class="date"><xsl:value-of select="end_show" /></span><input type="hidden" id="end_date" name="end_date" value="{end_date}" /> <input type="text" id="end_time" name="end_time" value="{end_time}" class="text time" /></td></tr>
<tr><td></td><td><input type="checkbox" name="all_day" id="all_day" onClick="javascript:allday()" /> all day</td></tr>
<tr><td>Short description:</td><td><input type="text" name="title" value="{title}" class="text" /></td></tr>
<tr><td>Long description:</td><td><textarea id="editor" name="content" class="text"><xsl:value-of select="content" /></textarea></td></tr>
</table>

<div class="knoppenbalk">
<input type="submit" name="submit_button" value="Save appointment" class="button" />
<a href="/admin/agenda" class="button">Cancel</a>
<xsl:if test="@id">
<input type="submit" name="submit_button" value="Delete appointment" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
<input type="button" value="Start CKEditor" id="start_cke" class="button" onClick="javascript:start_ckeditor()" />
</div>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/agenda.png" class="title_icon" />Agenda administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
