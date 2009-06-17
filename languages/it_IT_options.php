<?php

// Subscription page introductory text
$newsletter_default_options['subscription_text'] =
"<p>Per iscriversi alla newsletter, lascia nome ed email qui sotto:
riceverai una email con la quale potrai confermare l'iscrizione.</p>";

// Subscription registration message
$newsletter_default_options['subscribed_text'] =
"<p>L'iscrizione è quasi completa: controlla la tua
casella di posta, c'è un messaggio per te con il quale confermare l'iscrizione.</p>";

// Confirmation email (double opt-in)
$newsletter_default_options['confirmation_subject'] =
"{name}, conferma l'iscrizione alle newsletter di {blog_title}";

$newsletter_default_options['confirmation_message'] =
"<p>Ciao {name},</p>
<p>hai richiesto l'iscrizione alla newsletter di {blog_title}.
Conferma l'iscrizione <a href=\"{subscription_confirm_url}\"><strong>cliccando qui</strong></a>
oppure copia il link qui sotto nel tu programma di navigazione:</p>
<p>{subscription_confirm_url}</p>
<p>Grazie!</p>";

$newsletter_default_options['confirmed_subject'] =
"Benvenuto {name}!";

$newsletter_default_options['confirmed_message'] =
"<p>Con questo messaggio ti confermo l'iscrizione alla newsletter.</p>
<p>Grazie!</p>";

// Subscription confirmed text
$newsletter_default_options['confirmed_text'] =
"<p>{name}, la tua iscrizione è stata confermata.
Buona lettura!</p>";


$newsletter_default_options['unsubscription_text'] =
"<p>{name}, vuoi eliminare la tua iscrizione? 
Se sì... mi dispace, ma non ti trattengo oltre:</p>
<p><a href=\"{unsubscription_confirm_url}\">Sì, voglio eliminare la mia iscrizione per sempre</a>.</p>";

$newsletter_default_options['unsubscribed_text'] =
"<p>La tua iscrizione è stata definitivamente eliminata.</p>";
?>
