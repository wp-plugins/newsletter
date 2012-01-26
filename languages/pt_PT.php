<?php
$defaults_profile['email_error'] = 'Endereço de email incorreto.';
$defaults_profile['name_error'] = 'O nome não pode estar vazio.';

// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Inscreva-se na newsletter preenchendo os campos abaixo.</p>
<p>Um email de confirmação será enviado para a sua caixa de email:
por favor leia as instruções e complete seu registro.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Foi inscrito corretamente na newsletter.
Dentro de alguns minutos irá receber um email de confirmação. Siga o link no email para confirmar a inscrição.
Se o email demorar mais do que 15 minutos a chegar, verifique a sua caixa de SPAM.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"{name}, confirme sua inscrição no site {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Olá {name},</p>
<p>Recebemos um pedido de inscrição no nosso sistema proveniente deste email. Para confirmar
<a href=\"{subscription_confirm_url}\"><strong>clicando aqui</strong></a>.
Se não consegue abrir o link, acesse através deste endereço:</p>
<p>{subscription_confirm_url}</p>
<p>Se o pedido de inscrição não é proveniente de si, apenas ignore esta mensagem.</p>
<p>Obrigado.</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Sua inscrição foi confirmada!
Obrigado {name}.</p>";

$defaults['confirmed_subject'] =
"Bem vindo(a), {name}";

$defaults['confirmed_message'] =
"<p>A mensagem confirma a sua inscrição no nosso sistema.</p>
<p>Obrigado.</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Cancele a sua inscrição no sistema
<a href=\"{unsubscription_confirm_url}\">clicando aqui</a>.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>Sua inscrição foi cancelada. Inscreva-se novamente quando quiser.</p>";

