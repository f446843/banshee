<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<table class="list roles">
<tr><th class="role">Role</th><th class="users">Users with this role</th></tr>
<xsl:for-each select="roles/role">
	<tr class="click" onClick="javascript:document.location='/admin/role/{@id}'">
	<td><xsl:value-of select="." /></td>
	<td class="users"><xsl:value-of select="@users" /></td>
	</tr>
</xsl:for-each>
</table>

<input type="button" value="New role" class="button" onClick="javascript:document.location='/admin/role/new'" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/role" method="post">
<xsl:if test="role/@id">
<input type="hidden" name="id" value="{role/@id}" />
</xsl:if>
<div>Name: <input type="text" name="name" value="{role}" class="text">
<xsl:if test="role/@editable='no'">
	<xsl:attribute name="disabled">disabled</xsl:attribute>
</xsl:if>
</input></div>

<xsl:for-each select="pages/page">
	<div class="role">
		<xsl:if test="@type='checkbox'">
			<input type="checkbox" name="{.}" class="role">
				<xsl:if test="@value!='0'">
					<xsl:attribute name="checked">checked</xsl:attribute>
				</xsl:if>
				<xsl:if test="../../role/@editable='no'">
					<xsl:attribute name="disabled">disabled</xsl:attribute>
				</xsl:if>
			</input>
		</xsl:if>
		<xsl:if test="@type='select'">
			<select name="{.}" class="role">
				<xsl:if test="../../role/@editable='no'">
					<xsl:attribute name="disabled">disabled</xsl:attribute>
				</xsl:if>
				<xsl:variable name="value" select="@value" />
				<xsl:for-each select="../../readwrite/value">
					<option value="{@value}">
						<xsl:if test="@value=$value">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						<xsl:value-of select="." />
					</option>
				</xsl:for-each>
			</select>
		</xsl:if>
		<xsl:value-of select="." />
	</div>
</xsl:for-each>
<br clear="both" />

<xsl:if test="role/@editable='yes'">
<input type="submit" name="submit_button" value="Save role" class="button" />
</xsl:if>
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/admin/role'" />
<xsl:if test="role/@id and role/@editable='yes'">
<input type="submit" name="submit_button" value="Delete role" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>
</form>

<xsl:if test="role/@id">
<div class="members">
<h4>Users with this role:</h4>
<table class="list">
<tr><th>Name</th><th>E-mail address</th></tr>
<xsl:for-each select="members/member">
<tr onClick="javascript:location='/admin/user/{@id}'" class="click">
<td><xsl:value-of select="fullname" /></td>
<td><xsl:value-of select="email" /></td>
</tr>
</xsl:for-each>
</table>
<xsl:if test="not(members/member)">(none)</xsl:if>
</div>
</xsl:if>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/roles.png" class="title_icon" />Role administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
