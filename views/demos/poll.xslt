<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />
<xsl:include href="../includes/poll.xslt" />

<xsl:template match="content">
<h1>Poll</h1>
<xsl:apply-templates select="active_poll" />
<p>This page contains a poll demonstration. If you haven't voted yet, try it now.</p>

<input type="button" value="Back" onClick="document.location='/demos'" class="button" />
</xsl:template>

</xsl:stylesheet>
