<?php

$defaults_profile = array();
$defaults_profile['email_error'] = 'E-mail incorrect.';
$defaults_profile['name_error'] = 'Merci d&prime;indiquer votre nom.';

$defaults = array();
// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Abonnez-vous &agrave; la lettre d&prime;information {blog_title} en remplissants le formulaire ci-dessous.</p>
<p>Un e-mail de confirmation vous sera envoyé.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Votre demande d'inscription est enregistr&eacute;e. Merci ! Un e-mail de confirmation vous a été envoyé. V&eacute;rifier &eacute;galement votre dossier spam.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"Inscription &agrave; la lettre d&prime;information {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Bonjour {name}!</p>
<p>Vous recevez cet e-mail car nous avons enregistr&eacute; une demande d&prime;inscription &agrave; la lettre d&prime;infrmation {blog_title}.
Merci de confirmer votre inscription en cliquant sur le lien suivant : <a href=\"{subscription_confirm_url}\"><strong>je confirme mon inscription.</strong></a>.
Si le lien ne fonctionne pas, merci d'utiliser cette adresse :</p>
<p>{subscription_confirm_url}</p>
<p>Ignorez ce message si vous n'avez pas effectué de demande.</p>
<p>Merci !</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Votre inscription est confirm&eacute;. Merci !</p>";

$defaults['confirmed_subject'] =
"Bienvenue, {name}";

$defaults['confirmed_message'] =
"<p>Votre inscription est confirm&eacute;. Merci !</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>&Ecirc;tes-vous certain de vouloir vous d&eacute;sinscrire de la lettre d&prime;information {blog_title} ? <a href=\"{unsubscription_confirm_url}\">Oui</a>.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>Vous n&prime;&ecirc;tes plus abonn&eacute; &agrave; la lettre d'information {blog_title}. Merci de nous avoir suivi et &agrave; bient&ocirc;t !</p>";



