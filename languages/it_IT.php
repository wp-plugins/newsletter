<?php

$newsletter_labels['subscription_form'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Il&nbsp;tuo&nbsp;nome</td><td><input type="text" name="nn" size="30"/></td></tr>
<tr><td>La&nbsp;tua&nbsp;email</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Iscrivimi!"/></td></tr>
</table>
</form>';

$newsletter_labels['subscription_form_noname'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>La&nbsp;tua&nbsp;email</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Iscrivimi!"/></td></tr>
</table>
</form>';

$newsletter_labels['widget_form'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="nn" value="Il tuo nome" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="text" name="ne" value="La tua email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Iscrivimi"/></p>
<input type="hidden" name="na" value="s"/>
</form>';

$newsletter_labels['widget_form_noname'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="ne" value="La tua email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Iscrivimi"/></p>
<input type="hidden" name="na" value="s"/>
</form>';

// Errors on subscription
$newsletter_labels['error_email'] = 'L\'indirizzo email non è corretto. <a href="javascript:history.back()">Torna indietro</a>.';
$newsletter_labels['error_name'] = 'Il nome non è tato inserito. <a href="javascript:history.back()">Torna indietro</a>.';

$newsletter_labels['embedded_form'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="La tua email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
&nbsp;<input type="text" name="nn" value="Il tuo nome" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Conferma"/>
<input type="hidden" name="na" value="s"/></p>
</form>';

$newsletter_labels['embedded_form_noname'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="La tua email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Iscrivimi"/>
<input type="hidden" name="na" value="s"/></p>
</form>';

?>
