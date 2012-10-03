<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/pagination.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<table class="list">
<tr><th class="username">Username</th><th class="name">Name</th><th class="email">E-mail address</th><th class="status">Account status</th></tr>
<xsl:for-each select="users/user">
<tr class="disabled">
<xsl:if test="/output/user/@admin='yes' or @admin='no'">
<xsl:attribute name="class">click</xsl:attribute>
<xsl:attribute name="onClick">javascript:document.location='/admin/user/<xsl:value-of select="@id" />'</xsl:attribute>
</xsl:if>
<td><xsl:value-of select="username" /></td>
<td><xsl:value-of select="fullname" /></td>
<td><xsl:value-of select="email" /></td>
<td><xsl:value-of select="status" /></td>
</tr>
</xsl:for-each>
</table>
<xsl:apply-templates select="pagination" />

<input type="button" value="New user" class="button" onClick="javascript:document.location='/admin/user/new'" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<form action="/admin/user" method="post" onSubmit="javascript:hash_passwords(); return true;">
<xsl:if test="user/@id">
<input type="hidden" name="id" value="{user/@id}" />
</xsl:if>
<table>
<tr><td>Username:</td><td><input type="text" name="username" id="username" value="{user/username}" class="text" /></td></tr>
<tr><td>Password:</td><td><input type="password" id="password" name="password" class="text">
<xsl:if test="user/generate='on'"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
</input></td></tr>
<tr><td></td><td><input type="checkbox" name="generate" id="generate" onClick="javascript:generate_checkbox()">
<xsl:if test="user/generate='on'"><xsl:attribute name="checked">checked</xsl:attribute></xsl:if>
</input>Auto-generate password and send it to the user via e-mail.</td></tr>
<tr><td>E-mail address:</td><td><input type="text" name="email" value="{user/email}" class="text" /></td></tr>
<tr><td>Account status:</td><td><select name="status" class="text">
<xsl:if test="user/@id=/output/user/@id">
<xsl:attribute name="disabled">disabled</xsl:attribute>
</xsl:if>
<xsl:for-each select="status/status">
<option value="{@id}">
<xsl:if test="@id=../../user/status">
<xsl:attribute name="selected">selected</xsl:attribute>
</xsl:if>
<xsl:value-of select="." />
</option>
</xsl:for-each>
</select></td></tr>
<tr><td>Full name:</td><td><input type="text" name="fullname" value="{user/fullname}" class="text" /></td></tr>
<xsl:if test="organisations">
<tr><td>Organisation:</td><td><select name="organisation_id" class="text">
<xsl:for-each select="organisations/organisation">
<option value="{@id}">
<xsl:if test="@id=../../user/organisation_id">
<xsl:attribute name="selected">selected</xsl:attribute>
</xsl:if>
<xsl:value-of select="." />
</option>
</xsl:for-each>
</select></td></tr>
</xsl:if>
<tr><td valign="top">Roles:</td><td>
<xsl:for-each select="roles/role">
<div><input type="checkbox" name="roles[{@id}]" value="{@id}" class="role">
<xsl:if test="@enabled='no'">
<xsl:attribute name="disabled">disabled</xsl:attribute>
</xsl:if>
<xsl:if test="@checked='yes'">
<xsl:attribute name="checked">checked</xsl:attribute>
</xsl:if>
</input><xsl:value-of select="." /></div>
</xsl:for-each>
</td></tr>
</table>

<input type="submit" name="submit_button" value="Save user" class="button" />
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/admin/user'" />
<xsl:if test="user/@id and not(user/@id=/output/user/@id)">
<input type="submit" name="submit_button" value="Delete user" class="button" onClick="javascript:return confirm('DELETE: Are you sure?')" />
</xsl:if>

<input type="hidden" id="password_hashed" name="password_hashed" value="no" />
</form>
</xsl:template>

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
<p><xsl:value-of select="." /></p>
<xsl:call-template name="redirect" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/users.png" class="title_icon" />User administration</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
