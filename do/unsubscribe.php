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
header('Content-Type: text/html;charset=UTF-8');
header('X-Robots-Tag: noindex,nofollow,noarchive');
header('Cache-Control: no-cache,no-store,private');
if (NewsletterModule::antibot_form_check()) {
    $user = NewsletterSubscription::instance()->unsubscribe();
    if ($user->status == 'E') {
        NewsletterSubscription::instance()->show_message('unsubscription_error', $user);
    } else {
        NewsletterSubscription::instance()->show_message('unsubscribed', $user);
    }
    return;
}
?><!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php NewsletterModule::request_to_antibot_form('Unsubscribe'); ?>
    </body>
</html>
