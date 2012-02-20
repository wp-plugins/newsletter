<?php
$defaults_profile['email_error'] = 'Endereço de email incorreto.';
$defaults_profile['name_error'] = 'O nome não pode estar vazio.';

// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Inscreva-se na minha newsletter preenchendo os campos abaixo.
Tentarei lhe fazer feliz.</p>
<p>Um email de confirmação será enviado a sua caixa de entrada:
por favor leia as instruções e complete seu registro.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Você foi inscrito corretamente na newsletter.
Em alguns minutos você receberá um email de confirmação. Siga o link para confirmar a inscrição.
Se o email demorar mais do que 15 minutos para chegar, cheque sua caixa de SPAM.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"{name}, confirme sua inscrição no site {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Oi {name},</p>
<p>Recebemos um pedido de inscrição nos nossos informativos deste email. Você pode confirmar
<a href=\"{subscription_confirm_url}\"><strong>clicando aqui</strong></a>.
Se você não puder seguir o link, acesse este endereço:</p>
<p>{subscription_confirm_url}</p>
<p>Se o pedido de inscrição não veio de você, apenas ignore esta mensagem.</p>
<p>Obrigado.</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Sua inscrição foi confirmada!
Obrigado {name}.</p>";

$defaults['confirmed_subject'] =
"Bem vindo(a) a bordo, {name}";

$defaults['confirmed_message'] =
"<p>A mensagem confirma a sua inscrição nos nossos informativos.</p>
<p>Obrigado.</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Cancele a sua inscrição nos informativos
<a href=\"{unsubscription_confirm_url}\">clicando aqui</a>.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>Sua inscrição foi cancelada. Inscreva-se novamente quando quiser.</p>";



?>
