<?php

// Subscription form (traslate "your name", "your email" and the button "subscribe me")
$newsletter_labels['subscription_form'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Votre&nbsp;nom</td><td><input type="text" name="nn" size="30"/></td></tr>
<tr><td>Votre&nbsp;e-mail</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Je m&prime;inscris"/></td></tr>
</table>
</form>';

$newsletter_labels['widget_form'] =
'<form action="{newsletter_url}" method="post">
{text}
<p><input type="text" name="nn" value="Votre nom" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="text" name="ne" value="Votre e-mail" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>
<p><input type="submit" value="Je m&prime;inscris"/></p>
<input type="hidden" name="na" value="s"/>
</form>';

$newsletter_labels['embedded_form'] =
'<form action="{newsletter_url}" method="post">
<p><input type="text" name="ne" value="Votre e-mail" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
&nbsp;<input type="text" name="nn" value="Votre nom" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/>
<input type="submit" value="Je m&prime;inscris"/>
<input type="hidden" name="na" value="s"/></p>
</form>';

// Errors on subscription
$newsletter_labels['error_email'] = 'E-mail incorrect. <a href="javascript:history.back()">Retour</a>.';
$newsletter_labels['error_name'] = 'Merci d&prime;indiquer votre nom. <a href="javascript:history.back()">Retour</a>.';

?>
