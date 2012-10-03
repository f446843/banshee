<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="functions.xslt" />
<xsl:include href="layout_compromise.xslt" />
<xsl:include href="layout_pluralism.xslt" />

<xsl:output method="html" encoding="utf-8" doctype-public="-//W3C//DTD HTML 4.01//EN" doctype-system="http://www.w3.org/TR/html4/strict.dtd" />

<xsl:template match="/output">
<xsl:apply-templates select="layout_compromise" />
<xsl:apply-templates select="layout_pluralism" />
</xsl:template>

</xsl:stylesheet>
