<?php
// This file contains the default options values

// Subscription page introductory text (befor the subscription form)
$newsletter_default_options['subscription_text'] =
"<p>Subscribe to my newsletter filling the form below.
I try to make you happy.</p>
<p>A confirmation email will be sent to your email address:
please read the instruction inside it to complete the subscription.</p>";

// Message show after a subbscription request has made.
$newsletter_default_options['subscribed_text'] =
"<p>You successfully subscribed to my newsletter.
You'll receive in few minutes a confirmation email. Follow the link
in it to confirm the subscription. If the email takes more than 15
minutes to appear in your mailbox, check the spam folder.</p>";

// Confirmation email subject (double opt-in)
$newsletter_default_options['confirmation_subject'] =
"{name}, confirm your subscription to {blog_title}";

// Confirmation email body (double opt-in)
$newsletter_default_options['confirmation_message'] =
"<p>Hi {name},</p>
<p>I received a subscription request for this email address. You can confirm it
<a href=\"{home_url}/SUBSCRIPTION_CONFIRM_URL\"><strong>clicking here</strong></a>.
If you cannot click the link, use the following link:</p>
<p>{subscription_confirm_url}</p>
<p>If this subscription request has not been made from you, just ignore this message.</p>
<p>Thank you.</p>";


// Subscription confirmed text (after a user clicked the confirmation link
// on the email he received
$newsletter_default_options['confirmed_text'] =
"<p>Your subscription has been confirmed!
Thank you {name}!</p>";

$newsletter_default_options['confirmed_subject'] =
"Welcome aboard, {name}";

$newsletter_default_options['confirmed_message'] =
"<p>The message confirm your subscription to {blog_title} newsletter.</p>
<p>Thank you!</p>";

// Unsubscription request introductory text
$newsletter_default_options['unsubscription_text'] =
"<p>Please confirm you want to unsubscribe my newsletter
<a href=\"{home_url}/UNSUBSCRIPTION_CONFIRM_URL\">clicking here</a>.";

// When you finally loosed your subscriber
$newsletter_default_options['unsubscribed_text'] =
"<p>That make me cry, but I have removed your subscription...</p>";
?>
