<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<xsl:template match="content">
<h1>OpenStreetMap static maps demo</h1>
<p><img src="/demos/openstreetmap/image" alt="map" class="map" /></p>

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</xsl:template>

</xsl:stylesheet>
