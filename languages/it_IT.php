<?php

// Errors on subscription
$defaults_profile = array();
$defaults_profile['email_error'] = 'L\'indirizzo email non è corretto.';
$defaults_profile['error_name'] = 'Il nome non è stato inserito.';

$defaults_profile['profile_text'] = '{profile_form}<p>Se vuoi eliminare la tua iscrizione <a href="{unsubscription_confirm_url}">clicca qui</a></p>';
$defaults_profile['email'] = 'Email'; // Email field label
$defaults_profile['email_error'] = 'L\'indirizzo email non è corretto';
$defaults_profile['name_status'] = 1;
$defaults_profile['name'] = 'Nome';
$defaults_profile['name_error'] = 'Il nome non può essere vuoto';
$defaults_profile['surname'] = 'Cognome';
$defaults_profile['surname_status'] = 0;
$defaults_profile['sex_status'] = 0;
$defaults_profile['sex'] = 'I\'m';
$defaults_profile['sex_male'] = 'Maschio';
$defaults_profile['sex_female'] = 'Femmina';
$defaults_profile['privacy_status'] = 0;
$defaults_profile['privacy'] = 'Accetto l\'informativa sulla privacy.';
$defaults_profile['privacy_error'] = 'Devi accettare l\'informativa sulla privacy per iscriverti.';
$defaults_profile['subscribe'] = 'Procedi';
$defaults_profile['save'] = 'Salva'; // Profile "save" button


$defaults = array();
// Subscription page introductory text
$defaults['subscription_text'] =
"<p>Per iscriversi alla newsletter, lascia nome ed email qui sotto:
riceverai una email con la quale potrai confermare l'iscrizione.</p>";

// Subscription registration message
$defaults['subscribed_text'] =
"<p>L'iscrizione è quasi completa: controlla la tua
casella di posta, c'è un messaggio per te con il quale confermare l'iscrizione.</p>";

// Confirmation email (double opt-in)
$defaults['confirmation_subject'] =
"{name}, conferma l'iscrizione alle newsletter di {blog_title}";

$defaults['confirmation_message'] =
"<p>Ciao {name},</p>
<p>hai richiesto l'iscrizione alla newsletter di {blog_title}.
Conferma l'iscrizione <a href=\"{subscription_confirm_url}\"><strong>cliccando qui</strong></a>
oppure copia il link qui sotto nel tu programma di navigazione:</p>
<p>{subscription_confirm_url}</p>
<p>Grazie!</p>";

$defaults['confirmed_subject'] =
"Benvenuto {name}!";

$defaults['confirmed_message'] =
"<p>Con questo messaggio ti confermo l'iscrizione alla newsletter.</p>
<p>Grazie!</p>";

// Subscription confirmed text
$defaults['confirmed_text'] =
"<p>{name}, la tua iscrizione è stata confermata.
Buona lettura!</p>";


$defaults['unsubscription_text'] =
"<p>{name}, vuoi eliminare la tua iscrizione?
Se sì... mi dispace, ma non ti trattengo oltre:</p>
<p><a href=\"{unsubscription_confirm_url}\">Sì, voglio eliminare la mia iscrizione per sempre</a>.</p>";

$defaults['unsubscribed_text'] =
"<p>La tua iscrizione è stata definitivamente eliminata.</p>";



