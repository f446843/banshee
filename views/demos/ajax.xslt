<?xml version="1.0" ?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:include href="../banshee/main.xslt" />

<xsl:template match="content">
<h1>AJAX</h1>
<p>
<form id="ajax_form_1" onSubmit="javascript:ajax.post('demos/ajax', 'ajax_form_1', show_answer); return false;">
1 + 2 = <input type="text" name="answer" id="answer" class="text" style="width:20px" />
<input type="submit" name="submit_button_1" value="Check my answer" class="button" />
</form>
<span id="feedback"></span>
</p>

<p>
<form id="ajax_form_2" clicked_submit_button="" onSubmit="javascript:ajax.post('demos/ajax', 'ajax_form_2', show_records); return false;">
Number of records: <input type="text" name="records" id="records" class="text" style="width:20px" />
<input type="submit" name="submit_button_2" value="Show them" class="button" />
</form>
<span id="data"></span>
</p>

<p><form id="ajax_form_3" clicked_submit_button="" onSubmit="javascript:ajax.post('demos/ajax', 'ajax_form_3', show_text); return false;">
Text: <input type="text" name="text" class="text" style="width:100px" />
<input type="submit" name="submit_button_3" value="Show it" class="button" />
</form>
<span id="data"></span>
</p>

<p>
<br />
<a href="/demos" class="button">Back</a>
</p>
</xsl:template>

</xsl:stylesheet>
