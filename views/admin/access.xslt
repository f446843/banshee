<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Overview template
//
//-->
<xsl:template match="overview">
<div class="access">
<table class="list">
<tr><th class="user">user</th>
<xsl:for-each select="roles/role">
	<th class="access"><xsl:value-of select="." /></th>
</xsl:for-each>
<xsl:for-each select="users/user">
	<tr><td><xsl:value-of select="@name" /></td>
	<xsl:for-each select="role">
		<td class="access">
		<xsl:choose>
			<xsl:when test=".=0">
				<img src="/images/cross.gif" />
			</xsl:when>
			<xsl:otherwise>
				<img src="/images/check.gif" />
			</xsl:otherwise>
		</xsl:choose>
		</td>
	</xsl:for-each>
	</tr>
</xsl:for-each>
</tr>
</table>
</div>

<div class="access">
<table class="list">
<tr><th class="module">module</th>
<xsl:for-each select="roles/role">
	<th class="access"><xsl:value-of select="." /></th>
</xsl:for-each>
<xsl:for-each select="modules/module">
	<tr><td><xsl:value-of select="@url" /></td>
	<xsl:for-each select="access">
		<td class="access">
		<xsl:choose>
			<xsl:when test=".=0">
				<img src="/images/cross.gif" />
			</xsl:when>
			<xsl:otherwise>
				<img src="/images/check.gif" />
			</xsl:otherwise>
		</xsl:choose>
		</td>
	</xsl:for-each>
	</tr>
</xsl:for-each>
</tr>
</table>
</div>

<xsl:if test="pages/page">
	<div class="access">
	<table class="list">
	<tr><th class="module">url</th>
	<xsl:for-each select="roles/role">
		<th class="access"><xsl:value-of select="." /></th>
	</xsl:for-each>
	<xsl:for-each select="pages/page">
		<tr><td><xsl:value-of select="@url" /></td>
		<xsl:for-each select="access">
			<td class="access">
			<xsl:choose>
				<xsl:when test=".=0">
					<img src="/images/cross.gif" />
				</xsl:when>
				<xsl:otherwise>
					<img src="/images/check.gif" />
				</xsl:otherwise>
			</xsl:choose>
			</td>
		</xsl:for-each>
		</tr>
	</xsl:for-each>
	</tr>
	</table>
	</div>
</xsl:if>

<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/access.png" class="title_icon" />Access overview</h1>
<xsl:apply-templates select="overview" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
