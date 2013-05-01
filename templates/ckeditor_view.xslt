<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="banshee/main.xslt" />

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>CKEditor</h1>
<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>

<textarea id="editor" name="editor"></textarea>

<script type="text/javascript">
&lt;!--
	CKEDITOR.replace("editor", { skin : "office2003" });
//-->
</script>
</xsl:template>

</xsl:stylesheet>
