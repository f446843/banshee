<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Captcha demo</h1>
<p><img src="/captcha.png" /></p>
<form action="/demos/captcha" method="post">
<p>Enter captcha code: <input type="text" name="code" class="input" /> <input type="submit" value="Check" class="button" /></p>
</form>

<xsl:if test="valid">
<p>Code of previous captcha correct: <xsl:value-of select="valid" /></p>
</xsl:if>

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</xsl:template>

</xsl:stylesheet>
