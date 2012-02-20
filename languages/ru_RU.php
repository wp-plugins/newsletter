<?php

$defaults_profile = array();
$defaults_profile['email_error'] = 'Неверный e-mail.';
$defaults_profile['name_error'] = 'Имя должно быть указано.';

$defaults = array();
// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Вы можете подписаться на получение новостей сайта, используя форму ниже.</p>
<p>На ваш почтовый ящик будет выслано письмо для подтверждения. Пожалуйста, ознакомьтесь с инструкциями в письме, для завершения процедуры.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Вы успешно подписаны на рассылку. Вы получите письмо с подтверждением через несколько минут. Перейдите по ссылке в письме для подтверждения. Если в течении 15 минут письмо все-таки не пришло, проверьте папку со спамом на вашем ящике, на случай если почтовая служба сочла письмо спамом. Если же письма нигде нет, свяжитесь с администратором сайта</a>.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"{name}, Подвердите вашу подписку на новостную ленту {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Здравствуйте, {name},</p>
<p>От Вас поступил запрос на получение новостной рассылки. Вы можете подтвердить его, кликнув на эту <a href=\"{subscription_confirm_url}\"><strong>ссылку</strong></a>. Если ссылка по каким-то причинам не нажимается, вставьте вручную в браузер, ссылку:</p>
<p>{subscription_confirm_url}</p>
<p>Если Вы не посылали запрос, или кто-то это сделал за Вас, просто проигнорируйте это письмо.</p>
<p>Спасибо!</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Ваша подписка подтверждена! Спасибо, {name}!</p>";

$defaults['confirmed_subject'] =
"Добро пожаловать, {name}";

$defaults['confirmed_message'] =
"<p>Вы были успешно подписаны на новостную ленту {blog_title}.</p>
<p>Спасибо!</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Пожалуйста, подведите свой отказ от подписки, кликнув <a href=\"{unsubscription_confirm_url}\">здесь</a>.</p>";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>Это сделает нам немножечко больно, но мы отписали Вас от получения новостей...</p>";

$defaults['unsubscribed_subject'] =
"До свидания, {name}";

