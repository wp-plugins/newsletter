<?php
if (isset($_GET['test'])) {
    header('Content-Type: text/plain');
    echo 'ok';
    return;
}

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

    $user = NewsletterSubscription::instance()->subscribe();

    if ($user->status == 'E')
        NewsletterSubscription::instance()->show_message('error', $user->id);
    if ($user->status == 'C')
        NewsletterSubscription::instance()->show_message('confirmed', $user->id);
    if ($user->status == 'A')
        NewsletterSubscription::instance()->show_message('already_confirmed', $user->id);
    if ($user->status == 'S')
        NewsletterSubscription::instance()->show_message('confirmation', $user->id);
}
?><!DOCTYPE html>
<html>
    <head>
    </head>
    <body>
        <?php NewsletterModule::request_to_antibot_form('Subscribe'); ?>
    </body>
</html>

