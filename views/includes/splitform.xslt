<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!--
//
//  Splitforms template
//
//-->
<xsl:template match="splitforms">
<form action="/{/output/page}" method="post">
<xsl:apply-templates select="splitform/*" />
<div class="messages">
<xsl:call-template name="show_messages" />
</div>
<input type="hidden" name="splitform_current" value="{current}" />

<div class="buttons">
<div class="submit">
<xsl:choose>
	<xsl:when test="current/@max>current">
		<input type="submit" name="submit_button" value="{buttons/next}" class="next button" />
	</xsl:when>
	<xsl:otherwise>
		<input type="submit" name="submit_button" value="{buttons/submit}" class="submit button" />
	</xsl:otherwise>
</xsl:choose>
<input type="submit" name="submit_button" value="{buttons/previous}" class="previous button">
<xsl:if test="current=0"><xsl:attribute name="disabled">disabled</xsl:attribute></xsl:if>
</input>
</div>
<xsl:if test="buttons/back">
<input type="button" value="{buttons/back}" class="button" onClick="javascript:document.location='/{buttons/back/@link}'" />
</xsl:if>
</div>
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<xsl:apply-templates select="splitforms" />
<xsl:apply-templates select="submit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
