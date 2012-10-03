<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Newsletter template
//
//-->
<xsl:template match="newsletter">
<xsl:call-template name="show_messages" />
<form action="/admin/newsletter" method="post">
<p>Title: <input type="text" name="title" value="{title}" class="text" /></p>
<textarea name="content" class="text"><xsl:value-of select="content" /></textarea>
<input type="submit" name="submit_button" value="Send newsletter" class="button" onClick="javascript:return confirm('SEND: Are you sure?')" />
<input type="submit" name="submit_button" value="Preview newsletter" class="button" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Send newsletter</h1>
<xsl:apply-templates select="newsletter" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
