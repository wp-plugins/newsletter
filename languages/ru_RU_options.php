<?php
// This file contains the default options values
$newsletter_default_options['from_email'] = get_option('admin_email');
$newsletter_default_options['from_name'] = get_option('blogname');



// Subscription page introductory text (befor the subscription form)
$newsletter_default_options['subscription_text'] =
"<p>Вы можете подписаться на получение новостей сайта, используя форму ниже.</p>
<p>На ваш почтовый ящик будет выслано письмо для подтверждения. Пожалуйста, ознакомьтесь с инструкциями в письме, для завершения процедуры.</p>";

// Message show after a subbscription request has made.
$newsletter_default_options['subscribed_text'] =
"<p>Вы успешно подписаны на рассылку. Вы получите письмо с подтверждением через несколько минут. Перейдите по ссылке в письме для подтверждения. Если в течении 15 минут письмо все-таки не пришло, проверьте папку со спамом на вашем ящике, на случай если почтовая служба сочла письмо спамом. Если же письма нигде нет, свяжитесь с администратором сайта</a>.</p>";

// Confirmation email subject (double opt-in)
$newsletter_default_options['confirmation_subject'] =
"{name}, Подвердите вашу подписку на новостную ленту {blog_title}";

// Confirmation email body (double opt-in)
$newsletter_default_options['confirmation_message'] =
"<p>Здравствуйте, {name},</p>
<p>От Вас поступил запрос на получение новостной рассылки. Вы можете подтвердить его, кликнув на эту <a href=\"{subscription_confirm_url}\"><strong>ссылку</strong></a>. Если ссылка по каким-то причинам не нажимается, вставьте вручную в браузер, ссылку:</p>
<p>{subscription_confirm_url}</p>
<p>Если Вы не посылали запрос, или кто-то это сделал за Вас, просто проигнорируйте это письмо.</p>
<p>Спасибо!</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$newsletter_default_options['confirmed_text'] =
"<p>Ваша подписка подтверждена! Спасибо, {name}!</p>";

$newsletter_default_options['confirmed_subject'] =
"Добро пожаловать, {name}";

$newsletter_default_options['confirmed_message'] =
"<p>Вы были успешно подписаны на новостную ленту {blog_title}.</p>
<p>Спасибо!</p>";

// Unsubscription request introductory text
$newsletter_default_options['unsubscription_text'] =
"<p>Пожалуйста, подведите свой отказ от подписки, кликнув <a href=\"{unsubscription_confirm_url}\">здесь</a>.</p>";

// When you finally loosed your subscriber
$newsletter_default_options['unsubscribed_text'] =
"<p>Это сделает нам немножечко больно, но мы отписали Вас от получения новостей...</p>";

$newsletter_default_options['unsubscribed_subject'] =
"До свидания, {name}";

$newsletter_default_options['unsubscribed_message'] =
"<p>The message confirm your unsubscription to {blog_title} newsletter.</p>
<p>Good bye!</p>";

$newsletter_default_options['subscription_form'] =
'<form method="post" action="" style="text-align: center">
<input type="hidden" name="na" value="s"/>
<table cellspacing="3" cellpadding="3" border="0" width="50%">
<tr><td>Ваше&nbsp;имя</td><td><input type="text" name="nn" size="30"/></td></tr>
<tr><td>Ващ&nbsp;e-mail</td><td><input type="text" name="ne" size="30"/></td></tr>
<tr><td colspan="2" style="text-align: center"><input type="submit" value="Подписать меня"/></td></tr>
</table>
</form>';
?>
