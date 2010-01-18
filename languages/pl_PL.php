<?php

// Subscription form (traslate "your name", "your email" and the button "subscribe me")
$newsletter_labels['subscription_form'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Twoje&nbsp;imię</td><td><input type="text" name="nn" size="30"/></td></tr>
<tr><td>Twój&nbsp;e-mail</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Zapisz mnie"/></td></tr>
</table>
</form>';

$newsletter_labels['subscription_form_noname'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Twój&nbsp;e-mail</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Zapisz mnie"/></td></tr>
</table>
</form>';

$newsletter_labels['widget_form'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="nn" value="Twoje imię" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="text" name="ne" value="Twój e-mail" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Zapisz mnie"/></p>
<input type="hidden" name="na" value="s"/>
</form>';

$newsletter_labels['widget_form_noname'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="ne" value="Twój e-mail" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Zapisz mnie"/></p>
<input type="hidden" name="na" value="s"/>
</form>';

$newsletter_labels['embedded_form'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="Twój e-mail" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
&nbsp;<input type="text" name="nn" value="Twoje imię" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Zapisz mnie"/>
<input type="hidden" name="na" value="s"/></p>
</form>';

$newsletter_labels['embedded_form_noname'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="Twój e-mail" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Zapisz mnie"/>
<input type="hidden" name="na" value="s"/></p>
</form>';

// Errors on subscription
$newsletter_labels['error_email'] = 'Nieprawidłowy adres e-mail. <a href="javascript:history.back()">Powrót</a>.';
$newsletter_labels['error_name'] = 'Imię nie może być puste. <a href="javascript:history.back()">Powrót</a>.';

?>
