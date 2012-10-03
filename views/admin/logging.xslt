<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Graph template
//
//-->
<xsl:template match="graph">
<div class="graph" onMouseOut="javascript:clear_info('{@id}')">
<h2><xsl:value-of select="@title" /></h2>
<div class="info">
<span class="max">max: <xsl:value-of select="@max" /></span>
<span id="count_{@id}" class="count"></span>
<span id="day_{@id}" class="day"></span>
</div>
<xsl:for-each select="item">
<a href="/{/output/page}/{date}"><div class="column" style="width:{../../width}px; height:{../../height}px" onMouseOver="javascript:set_info('{../@id}', '{count}', '{day}')">
<div class="bar" style="width:{../../width}px; height:{height}px"></div>
</div></a>
</xsl:for-each>
<div style="clear:both"></div>
</div>
</xsl:template>

<!--
//
//  Deselect template
//
//-->
<xsl:template match="deselect">
<h2>Selected day: <xsl:value-of select="." /></h2>
<p class="deselect"><a href="/{/output/page}">Remove day selection</a></p>
</xsl:template>

<!--
//
//  Pages template
//
//-->
<xsl:template match="pages">
<div class="pages">
<h2>Top pages</h2>
<xsl:for-each select="page">
<div class="toppage"><xsl:value-of select="page" /> (<xsl:value-of select="count" />)</div>
</xsl:for-each>
</div>
</xsl:template>

<!--
//
//  Search template
//
//-->
<xsl:template match="search">
<div class="search">
<h2>Search queries</h2>
<xsl:for-each select="query">
<div class="query"><xsl:value-of select="query" /> (<xsl:value-of select="count" />)</div>
</xsl:for-each>
</div>
</xsl:template>

<!--
//
//  Referers template
//
//-->
<xsl:template match="referers">
<div class="referers">
<h2>Referers</h2>
<xsl:for-each select="host">
<xsl:variable name="id" select="position()" />
<div class="host" onClick="javascript:$('.ref{$id}').slideToggle('normal')">
	<xsl:value-of select="@hostname" /> (<xsl:value-of select="@total" /> / <xsl:value-of select="@count" />)
</div>
<div class="referer ref{$id}"><ul>
	<xsl:for-each select="referer">
	<li><a href="{url}" target="_blank"><xsl:value-of select="url" /></a> (<xsl:value-of select="count" />)
	</li>
	</xsl:for-each>
	<form action="/{/output/page}/{../../deselect/@date}" method="post" onSubmit="javascript:return confirm('DELETE: Are you sure?')">
		Block:<input type="checkbox" name="block" />
		<input type="hidden" name="hostname" value="{@hostname}" />
		<input type="submit" name="submit_button" value="delete" />
	</form>
</ul></div>
</xsl:for-each>
</div>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/logging.png" class="title_icon" />Logging</h1>
<xsl:apply-templates select="graph" />
<xsl:apply-templates select="deselect" />
<xsl:apply-templates select="pages" />
<xsl:apply-templates select="search" />
<div style="clear:both"></div>
<xsl:apply-templates select="referers" />
<xsl:apply-templates select="result" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</xsl:template>

</xsl:stylesheet>
