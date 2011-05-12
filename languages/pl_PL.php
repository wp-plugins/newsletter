<?php

$defaults_profile = array();
$defaults_profile['email_error'] = 'Nieprawidłowy adres e-mail.';
$defaults_profile['name_error'] = 'Imię nie może być puste.';

$defaults = array();
// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Zapisz się do newslettera wypełniając pola poniżej.
Postaramy się Cię uszczęśliwić.</p>
<p>Zostanie wysłany do Ciebie e-mail potwierdzający:
przeczytaj instrukcje w nim zawarte, aby potwierdzić subskrypcję.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Zostałeś zapisany do subskrypcji.
W ciągu kilku minut otrzymasz e-mail potwierdzający.
Kliknij w odnośnik w nim zawarty aby potwierdzić subskrypcję. Jeśli e-mail nie pojawi się w Twojej skrzynce przez 15 minut - sprawdź folder spam.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"{name}, potwierdź swoją subskrypcję w {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Witaj {name},</p>
<p>Otrzymaliśmy prośbę o wpis do subskrypcji dla tego adresu e-mail. Możesz potwierdzić ją
<a href=\"{subscription_confirm_url}\"><strong>klikając tutaj</strong></a>.
Jeśli nie możesz kliknąć odnośnika, użyj poniższego linku:</p>
<p>{subscription_confirm_url}</p>
<p>Jeśli to nie Ty wpisywałeś się do subskrypcji, po prostu zignoruj tę wiadomość.</p>
<p>Dziękujemy.</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Twoja subskrypcja została potwierdzona!
Dziękujemy {name}!</p>";

$defaults['confirmed_subject'] =
"Witaj, {name}";

$defaults['confirmed_message'] =
"<p>Wiadomość potwierdzająca subskyrpcję {blog_title}.</p>
<p>Dziękujemy!</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Proszę potwierdzić rezygnację z subskrypcji
<a href=\"{unsubscription_confirm_url}\">klikając tutaj</a>.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>To smutne, ale usunęliśmy Twój e-mail z subskrypcji...</p>";


