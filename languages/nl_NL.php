<?php

// Subscription form (traslate "your name", "your email" and the button "subscribe me")
$newsletter_labels['subscription_form'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Jouw&nbsp;naam</td><td><input type="text" name="nn" size="30"/></td></tr>
<tr><td>Jouw&nbsp;email</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Inschrijven"/></td></tr>
</table>
</form>';


$newsletter_labels['subscription_form_noname'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Jouw&nbsp;email</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Inschrijven"/></td></tr>
</table>
</form>';

$newsletter_labels['widget_form'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="nn" value="Jouw naam" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="text" name="ne" value="Jouw email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Inschrijven"/></p>
<input type="hidden" name="na" value="s"/>
</form>';


$newsletter_labels['widget_form_noname'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="ne" value="Jouw email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Inschrijven"/></p>
<input type="hidden" name="na" value="s"/>
</form>';



$newsletter_labels['embedded_form'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="Jouw email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
&nbsp;<input type="text" name="nn" value="Jouw naam" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Inschrijven"/>
<input type="hidden" name="na" value="s"/></p>
</form>';

// Example of embedded form without name

$newsletter_labels['embedded_form_noname'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="Jouw email" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Inschrijven"/>
<input type="hidden" name="na" value="s"/></p>
</form>';


// Errors on subscription
$newsletter_labels['error_email'] = 'Foutief email-adres. <a href="javascript:history.back()">Ga terug</a>.';
$newsletter_labels['error_name'] = 'Gelieve jouw naam in te vullen. <a href="javascript:history.back()">Ga terug</a>.';

?>
