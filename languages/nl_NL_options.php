<?php
// This file contains the default options values
$newsletter_default_options['from_email'] = get_option('admin_email');
$newsletter_default_options['from_name'] = get_option('blogname');

// Subscription page introductory text (befor the subscription form)
$newsletter_default_options['subscription_text'] =
"<p>Via onderstaand formuliertje kan je je inschrijven voor onze nieuwsbrief. Via deze nieuwsbrief houden we je op de hoogte van komende activiteiten.</p>
<p>Nadat je het formuliertje verstuurd hebt, zal je een bevestigingsmail ontvangen. Lees deze mail aandachtig om jouw inschrijving te bevestigen.</p>";

// Message show after a subbscription request has made.
$newsletter_default_options['subscribed_text'] =
"<p>Je hebt je ingeschreven op de nieuwsbrief.</p>
<p>Binnen enkele minuten zal je een bevestigingsmail ontvangen. Volg de link in die mail om jouw inschrijving te bevestigen. Indien je problemen hebt met het ontvangen van de bevestigingsmail kan je ons via het contactformulier bereiken.</p>";

// Confirmation email subject (double opt-in)
$newsletter_default_options['confirmation_subject'] =
"{name}, Bevestig jouw inschrijving op de nieuwsbrief van {blog_title}";

// Confirmation email body (double opt-in)
$newsletter_default_options['confirmation_message'] =
"<p>Hallo {name},</p>
<p>We ontvingen jouw inschrijving op onze nieuwsbrief. Gelieve de inschrijving te bevestigen door <a href=\"{subscription_confirm_url}\"><strong>hier</strong></a> te klikken. Als het klikken op de link voor jou niet werkt, kan je de volgende link in jouw browser copieren.</p>
<p>{subscription_confirm_url}</p>
<p>Indien je deze mail ontvangt en toch geen inschrijving gevraagd hebt, hoef je niets te doen. De inschrijving wordt dan automatisch geannuleerd.</p>
<p>Dank u wel.</p>";

// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$newsletter_default_options['confirmed_text'] =
"<p>Je hebt zonet jouw inschrijving bevestigd.</p><p>bedankt {name} !</p>";

$newsletter_default_options['confirmed_subject'] =
"Welkom, {name}";

$newsletter_default_options['confirmed_message'] =
"<p>Uw inschrijving op de niewsbrief van {blog_title} is bevestigd.</p>
<p>Bedankt !</p>";

// Unsubscription request introductory text
$newsletter_default_options['unsubscription_text'] =
"<p>Gelieve uw uitschrijving te bevestigen door <a href=\"{unsubscription_confirm_url}\">hier</a> te klikken.";

// When you finally loosed your subscriber
$newsletter_default_options['unsubscribed_text'] =
"<p>U bent uit onze lijst verwijderd.</p>";

$newsletter_default_options['unsubscribed_subject'] =
"Tot ziens, {name}";

$newsletter_default_options['unsubscribed_message'] =
"<p>Uw uitschrijving op de nieuwsbrief van {blog_title} is bevestigd.</p>
<p>Tot ziens.</p>";

$newsletter_default_options['subscription_form'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Jouw&nbsp;naam</td><td><input type="text" name="nn" size="30"/></td></tr>
<tr><td>Jouw&nbsp;email</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Inschrijven"/></td></tr>
</table>
</form>';
?>
