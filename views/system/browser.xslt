<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Unsupported browser</h1>

<p>The browser you are using is not supported by this website. Update your browser to the latest version or switch to another one.</p>

<div class="browsers">
<div class="browser"><a href="http://www.getfirefox.com/" target="_blank"><img src="/images/browsers/firefox.gif" alt="Firefox" /></a>Firefox</div>
<div class="browser"><a href="http://www.google.com/chrome" target="_blank"><img src="/images/browsers/chrome.gif" alt="Chrome" /></a>Chrome</div>
<div class="browser"><a href="http://www.opera.com/" target="_blank"><img src="/images/browsers/opera.gif" alt="Opera" /></a>Opera</div>
<div class="browser"><a href="http://www.microsoft.com/ie" target="_blank"><img src="/images/browsers/ie.gif" alt="IE" /></a>Internet Explorer</div>
<div class="browser"><a href="http://www.apple.com/safari" target="_blank"><img src="/images/browsers/safari.gif" alt="Safari" /></a>Safari</div>
</div>

<br clear="both" />
<br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br />
</xsl:template>

</xsl:stylesheet>
