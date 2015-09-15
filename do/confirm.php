<?php
header('Content-Type: text/html;charset=UTF-8');
header('X-Robots-Tag: noindex,nofollow,noarchive');
header('Cache-Control: no-cache,no-store,private');

// Patch to avoid "na" parameter to disturb the call
unset($_REQUEST['na']);
unset($_POST['na']);
unset($_GET['na']);
if (!defined('ABSPATH')) {
    require_once '../../../../wp-load.php';
}

if (NewsletterModule::antibot_form_check()) {
    $user = NewsletterSubscription::instance()->confirm();
    if ($user->status == 'E') {
        NewsletterSubscription::instance()->show_message('error', $user->id);
    } else {
        NewsletterSubscription::instance()->show_message('confirmed', $user);
    }
}
?><!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php NewsletterModule::request_to_antibot_form('Confirm'); ?>
    </body>
</html>