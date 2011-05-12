<?php

$defaults_profile = array();
$defaults_profile['email_error'] = 'Foutief email-adres.';
$defaults_profile['name_error'] = 'Gelieve jouw naam in te vullen.';

$defaults = array();
// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Via onderstaand formuliertje kan je je inschrijven voor onze nieuwsbrief. Via deze nieuwsbrief houden we je op de hoogte van komende activiteiten.</p>
<p>Nadat je het formuliertje verstuurd hebt, zal je een bevestigingsmail ontvangen. Lees deze mail aandachtig om jouw inschrijving te bevestigen.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Je hebt je ingeschreven op de nieuwsbrief.</p>
<p>Binnen enkele minuten zal je een bevestigingsmail ontvangen. Volg de link in die mail om jouw inschrijving te bevestigen. Indien je problemen hebt met het ontvangen van de bevestigingsmail kan je ons via het contactformulier bereiken.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"{name}, Bevestig jouw inschrijving op de nieuwsbrief van {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Hallo {name},</p>
<p>We ontvingen jouw inschrijving op onze nieuwsbrief. Gelieve de inschrijving te bevestigen door <a href=\"{subscription_confirm_url}\"><strong>hier</strong></a> te klikken. Als het klikken op de link voor jou niet werkt, kan je de volgende link in jouw browser copieren.</p>
<p>{subscription_confirm_url}</p>
<p>Indien je deze mail ontvangt en toch geen inschrijving gevraagd hebt, hoef je niets te doen. De inschrijving wordt dan automatisch geannuleerd.</p>
<p>Dank u wel.</p>";

// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Je hebt zonet jouw inschrijving bevestigd.</p><p>bedankt {name} !</p>";

$defaults['confirmed_subject'] =
"Welkom, {name}";

$defaults['confirmed_message'] =
"<p>Uw inschrijving op de niewsbrief van {blog_title} is bevestigd.</p>
<p>Bedankt !</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Gelieve uw uitschrijving te bevestigen door <a href=\"{unsubscription_confirm_url}\">hier</a> te klikken.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>U bent uit onze lijst verwijderd.</p>";

$defaults['unsubscribed_subject'] =
"Tot ziens, {name}";

$defaults['unsubscribed_message'] =
"<p>Uw uitschrijving op de nieuwsbrief van {blog_title} is bevestigd.</p>
<p>Tot ziens.</p>";


