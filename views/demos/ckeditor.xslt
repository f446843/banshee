<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<form method="post" action="/demos/ckeditor">
<textarea id="editor" name="editor"></textarea>
<input type="submit" name="save" value="Submit" class="button" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos'" />
</form>
</xsl:template>

<!--
//
//  Result template
//
//-->
<xsl:template match="result">
<div style="border:1px solid #000000 ; width:600px ; height:300px"><xsl:value-of select="editor" /></div>
<br />

<input type="button" value="Back" class="button" onClick="javascript:document.location='/demos/ckeditor'" />
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>CKEditor</h1>
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
