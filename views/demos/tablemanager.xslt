<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/tablemanager.xslt" />

<xsl:template match="content">
<xsl:apply-templates select="tablemanager" />
</xsl:template>

</xsl:stylesheet>
