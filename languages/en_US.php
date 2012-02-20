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

$defaults_profile = array();
$defaults_profile['profile_text'] = '{profile_form}<p>If you want to cancel your subscription, <a href="{unsubscription_confirm_url}">click here</a></p>';
$defaults_profile['email'] = 'Email'; // Email field label
$defaults_profile['email_error'] = 'Your email seems wrong, check it please.';
$defaults_profile['name_status'] = 1;
$defaults_profile['name'] = 'Name';
$defaults_profile['name_error'] = 'Your name cannot be empty';
$defaults_profile['surname'] = 'Last Name';
$defaults_profile['surname_status'] = 0;
$defaults_profile['sex_status'] = 0;
$defaults_profile['sex'] = 'I\'m';
$defaults_profile['sex_male'] = 'Male';
$defaults_profile['sex_female'] = 'Female';
$defaults_profile['privacy_status'] = 0;
$defaults_profile['privacy'] = 'Subscribing you accept the privacy informative.';
$defaults_profile['privacy_error'] = 'You must accept the privacy informative to subscribe.';
$defaults_profile['subscribe'] = 'Subscribe now!';
$defaults_profile['save'] = 'Save'; // Profile "save" button

// Default values for main configuration
$sitename = strtolower($_SERVER['SERVER_NAME']);
if (substr($sitename, 0, 4) == 'www.') $sitename = substr($sitename, 4);

$defaults_main = array(
    'smtp_enabled'=>0,
    'return_path'=>'',
    'reply_to'=>'',
    'test_email_0'=>get_option('admin_email'),
    'test_name_0'=>'Subscriber',
    'sender_email'=>'newsletter@' . $sitename,
    'sender_name'=>get_option('blogname'),
    'theme'=>'page-1',
    'lock_message'=>'<div style="margin: 15px; padding: 15px; background-color: #ff9; border-color: 1px solid #000">
        This content is protected, only newsletter subscribers can access it. Subscribe now!
        [newsletter_form]
        </div>'
);


// Default values for subscription panel (transaled on other language files)
$defaults = array();
// Subscription page introductory text (befor the subscription form)
$defaults['subscription_text'] =
"<p>Subscribe to my newsletter by filling the form below.
I'll try to make you happy.</p>
<p>A confirmation email will be sent to your mailbox:
please read the instructions to complete the subscription.</p>";

// Message show after a subbscription request has made.
$defaults['subscribed_text'] =
"<p>You successfully subscribed to my newsletter.
You'll receive in few minutes a confirmation email. Follow the link
in it to confirm the subscription. If the email takes more than 15
minutes to appear in your mailbox, check the spam folder.</p>";

// Confirmation email subject (double opt-in)
$defaults['confirmation_subject'] =
"Confirm now your subscription to {blog_title}";

// Confirmation email body (double opt-in)
$defaults['confirmation_message'] =
"<p>Hi {name},</p>
<p>I received a subscription request for this email address. You can confirm it
<a href=\"{subscription_confirm_url}\"><strong>clicking here</strong></a>.
If you cannot click the link, use the following link:</p>
<p>{subscription_confirm_url}</p>
<p>If this subscription request has not been made from you, just ignore this message.</p>
<p>Thank you.</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$defaults['confirmed_text'] =
"<p>Your subscription has been confirmed!
Thank you {name}!</p>";

$defaults['confirmed_subject'] =
"Welcome aboard, {name}";

$defaults['confirmed_message'] =
"<p>The message confirm your subscription to {blog_title} newsletter.</p>
<p>Thank you!</p>
<p>If you want to cancel your unsubscription, <a href=\"{unsubscription_url}\">click here</a>, if you want to change your
subscription data, <a href=\"{profile_url}\">click here</a>.</p>";

// Unsubscription request introductory text
$defaults['unsubscription_text'] =
"<p>Please confirm you want to unsubscribe my newsletter
<a href=\"{unsubscription_confirm_url}\">clicking here</a>.";

// When you finally loosed your subscriber
$defaults['unsubscribed_text'] =
"<p>That make me cry, but I have removed your subscription...</p>";

$defaults['unsubscribed_subject'] =
"Goodbye, {name}";

$defaults['unsubscribed_message'] =
"<p>The message confirm your unsubscription to {blog_title} newsletter.</p>
<p>Good bye!</p>";

$defaults['email_theme'] = '{message}'; // do not translate!


