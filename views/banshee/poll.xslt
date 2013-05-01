<?xml version="1.0" ?>
<xsl:stylesheet	version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<xsl:template match="active_poll">
<div class="active_poll">
<h3><xsl:value-of select="question" /></h3>
<form action="{/output/@url}" method="post">
<ul class="answers">
<xsl:for-each select="answers/answer">
	<li>
	<xsl:choose>
		<xsl:when test="../../@can_vote='yes'">
			<input type="radio" name="vote" value="{@id}" /><xsl:value-of select="." />
		</xsl:when>
		<xsl:otherwise>
			<xsl:value-of select="answer" /> - <xsl:value-of select="percentage" />%
			<div class="percentage" style="width:{percentage}px" />
		</xsl:otherwise>
	</xsl:choose>
	</li>
</xsl:for-each>
</ul>
<xsl:if test="answers/@votes">
<p>Number of votes: <xsl:value-of select="answers/@votes" /></p>
</xsl:if>

<xsl:if test="@can_vote='yes'">
<input type="submit" name="submit_button" value="Vote" class="button" />
</xsl:if>
</form>
</div>
</xsl:template>

</xsl:stylesheet>
