<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<xsl:template match="content">
<h1>GoogleMaps static maps demo</h1>
<img src="/demos/googlemaps/image" alt="map" class="map" />

<div class="route">
<h2>Route from <xsl:value-of select="route/origin" /> to <xsl:value-of select="route/destination" /></h2>
<div class="info">Travel distance: <xsl:value-of select="route/distance" /> in <xsl:value-of select="route/duration" /> hours</div>
<ul class="route">
<xsl:for-each select="route/step">
<li class="step"><xsl:value-of select="." disable-output-escaping="yes" /> (distance: <xsl:value-of select="@distance" />, duration: <xsl:value-of select="@duration" />)</li>
</xsl:for-each>
</ul>
</div>

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</xsl:template>

</xsl:stylesheet>
