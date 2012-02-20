<?php
// Those default options are used ONLY on FIRST setup and on plugin updates but limited to
// new options that may have been added between your and new version.
//
// This is the main language file, too, which is always loaded by Newsletter. Other language
// files are loaded according the WPLANG constant defined in wp-config.php file. Those language
// specific files are "merged" with this one and the language specific configuration
// keys override the ones in this file.
//
// Language specific files only need to override configurations containing texts
// language dependant.
//
// All language file are UTF-8 encoded!

$defaults_profile['profile_text'] = '{profile_form}<p>Pokud už si neprejete odebírat informace ze sveta forexu, <a href="{unsubscription_confirm_url}">kliknete zde.</a></p>';
$defaults_profile['email'] = 'Email'; // Email field label
$defaults_profile['email_error'] = 'Špatne napsaný email, prosím zkontrolujte ho.';
$defaults_profile['name_status'] = 1;
$defaults_profile['name'] = 'Jméno';
$defaults_profile['name_error'] = 'Vaše jméno nemuže zustat prázdné';
$defaults_profile['surname'] = 'Príjmení';
$defaults_profile['surname_status'] = 0;
$defaults_profile['sex_status'] = 0;
$defaults_profile['sex'] = 'I\'m';
$defaults_profile['sex_male'] = 'Muž';
$defaults_profile['sex_female'] = 'Žena';
$defaults_profile['privacy_status'] = 0;
$defaults_profile['privacy'] = 'Prihlášením k odberu informací souhlasíte se zpracováním osobních informací.';
$defaults_profile['privacy_error'] = 'K prihlásení k odberu informací musíte souhlasit se zpracováním osobních informací.';
$defaults_profile['subscribe'] = 'Prihlásit k odberu!';
$defaults_profile['save'] = 'Uložit'; // Profile "save" button


$defaults_main['lock_message'] = '<div style="margin: 15px; padding: 15px; background-color: #ff9; border-color: 1px solid #000">
        Tento obsah je chránený, pouze lidé prihlášení k odberu newsletteru mohou vstoupit. Prihlašte se nyní!
        [newsletter_form]
        </div>';


// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Zde se mužete prihlásit k odberu novinek.</p>
<p>Bude vám odeslán konfirmacní email, prosím o jeho potvrzení.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>Úspešne jste se prihlásili k odberu newsletteru. Behem pár minut obdržíte confirmacní email. Klidnete na link pro potvrzení emailu. Pokud vám confirmacní email neprijde do 15 minut, zkontrolujte si složku se spamem.
</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"Potvrzení zájmu o odber novinek z {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Dobrý den {name},</p>
<p>Obrželi jsem žádost o zasílání novinek pro tento email. Mužete jí potvrdit kliknutím 
<a href=\"{subscription_confirm_url}\"><strong>zde</strong></a>.
Pokud se vám nedarí klinout na link, použijte následující odkaz:</p>
<p>{subscription_confirm_url}</p>
<p>Pokud žádost o zasílání novinek nevyšla od vás, stací jenom ignorovat tento email.</p>
<p>Predem moc dekujeme.</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Vaše žádost byla potvrzena!
Dekujeme {name}!</p>";

$defaults['confirmed_subject'] =
"Vítejte {name},";

$defaults['confirmed_message'] =
"<p>Tato zpráva potvrzuje vyhovení vaší žádosti o {blog_title} newsletter.</p>
<p>Díky!</p>
<p>Pokud si už nadále neprejete dostávat novinky, <a href=\"{unsubscription_url}\">kliknete zde</a>, pokud si prejete zmenit své vstupní data, <a href=\"{profile_url}\">kliknete zde</a>.</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Prosím potvrtte, že se chcete odhlásit z odebírání novinek
<a href=\"{unsubscription_confirm_url}\">zde</a>.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>Velice nás to mrzí, ale byl jste práve odebrán z odberu novinek.</p>";

$defaults['unsubscribed_subject'] =
"Nashledanou {name},";

$defaults['unsubscribed_message'] =
"<p>Tento email potvrzuje vaše odhlášení z {blog_title} newslettru.</p>
<p>Nashledanou!</p>";




