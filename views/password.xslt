<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="includes/banshee.xslt" />

<!--
//
//  request form template
//
//-->
<xsl:template match="request">
Enter your username and e-mail address to reset your password.
<form action="/{/output/page}" method="post">
<table>
<tr><td>Username:</td><td><input type="text" name="username" class="text" /></td></tr>
<tr><td>E-mail:</td><td><input type="text" name="email" class="text" /></td></tr>
</table>
<input type="submit" name="submit_button" value="Reset password" class="button" />
<input type="button" value="Cancel" class="button" onClick="javascript:document.location='/login'" />
</form>
</xsl:template>

<!--
//
//  Link sent template
//
//-->
<xsl:template match="link_sent">
<p>If you have entered an existing username and e-mail address, a link to reset your password has been sent to the supplied e-mail address.</p>
<p>Don't close your browser!!</p>
</xsl:template>

<!--
//
//  Reset form template
//
//-->
<xsl:template match="reset">
Enter a new password for your account:
<xsl:call-template name="show_messages" />
<form action="/{/output/page}" method="post" onSubmit="javascript:hash_passwords(); return true;">
<input type="hidden" name="key" value="{key}" />
<input type="hidden" id="username" value="{username}" />
<input type="hidden" id="password_hashed" name="password_hashed" value="no" />
<table>
<tr><td>Password:</td><td><input type="password" id="password" name="password" class="text" /></td></tr>
<tr><td>Repeat:</td><td><input type="password" id="repeat" name="repeat" class="text" /></td></tr>
</table>
<input type="submit" name="submit_button" value="Save password" class="button" />
</form>
</xsl:template>

<!--
//
//  Content template
//
//-->
<xsl:template match="content">
<h1>Forgot password</h1>
<xsl:apply-templates select="request" />
<xsl:apply-templates select="link_sent" />
<xsl:apply-templates select="reset" />
<xsl:apply-templates select="result" />
</xsl:template>

</xsl:stylesheet>
