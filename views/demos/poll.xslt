<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />
<xsl:include href="../banshee/poll.xslt" />

<xsl:template match="content">
<h1>Poll</h1>
<xsl:apply-templates select="active_poll" />
<p>This page contains a poll demonstration. If you haven't voted yet, try it now.</p>

<a href="/demos" class="button">Back</a>
</xsl:template>

</xsl:stylesheet>
