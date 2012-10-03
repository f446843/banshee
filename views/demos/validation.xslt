<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Input validation</h1>
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post">
<table class="edit">
<tr><td>String:</td><td><input type="text" name="string" value="{string}" class="text" /></td></tr>
<tr><td>Number:</td><td><input type="text" name="number" value="{number}" class="text" /></td></tr>
<tr><td>Enum:</td><td><input type="text" name="enum" value="{enum}" class="text" /></td></tr>
</table>

<input type="submit" value="Validate data" class="button" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</form>
</xsl:template>

</xsl:stylesheet>
