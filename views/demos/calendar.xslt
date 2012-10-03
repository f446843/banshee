<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<form action="/demos/calendar" method="post">
<input type="text" id="date" name="date" value="{date}" class="input" />
<input type="button" id="opencal" value=".." />
<input type="submit" value="Submit" class="button" />
</form>

<br /><br />

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</xsl:template>

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
Entered date: <xsl:value-of select="." />
<xsl:call-template name="redirect" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>The DHTML Calendar</h1>
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
