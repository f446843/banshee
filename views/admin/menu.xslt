<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../includes/banshee.xslt" />

<!--
//
//  Edit template
//
//-->
<xsl:template match="edit">
<xsl:call-template name="show_messages" />
<xsl:if test="parent">
    <a href="/admin/menu/{parent/@id}">Up one level to <xsl:value-of select="parent" /></a>
</xsl:if>

<form action="/admin/menu" method="post">
<input type="hidden" name="menu_id" value="{menu/@id}" />
<span class="label_text">Text</span><span class="label_link">Link</span>
<ul class="editmenu" id="editmenu">
<xsl:for-each select="menu/item">
<li id="item-{@id}">
	<input type="hidden" name="menu[{@id}][children]" value="{children}" />
	<input type="text" name="menu[{@id}][text]" value="{text}" class="text" />
	<input type="text" name="menu[{@id}][link]" value="{link}" class="text" />
	<xsl:if test="@id>=0">
		<input type="button" value="view" class="operation" onClick="javascript:document.location='/admin/menu/{@id}'" />
	</xsl:if>
	<xsl:if test="@id&lt;0">
		<div class="no_view"></div>
	</xsl:if>
	<xsl:if test="children=0">
		<input type="button" value="delete" class="operation" onClick="javascript:delete_item('item-{@id}')" />
	</xsl:if>
	<xsl:if test="children!=0">
		<div class="no_delete" />
	</xsl:if>
	<img src="/images/sort.png" class="grip" alt="sort" />
</li>
</xsl:for-each>
</ul>
<p>
	<input type="button" value="Add" class="button" onClick="javascript:add_item('editmenu', {max_menu_id})" />
</p>
<input type="submit" name="submit_button" value="Update" class="button" />
<input type="button" value="Back" class="button" onClick="javascript:document.location='/admin'" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1><img src="/images/icons/menu.png" class="title_icon" />Menu administration</h1>
<xsl:apply-templates select="edit" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
