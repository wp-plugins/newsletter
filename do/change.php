<?php

include '../../../../wp-load.php';

$user = Newsletter::instance()->get_user_from_request(true);
$field = $_REQUEST['nf'];
$value = $_REQUEST['nv'];
$url = $_REQUEST['nu'];

switch ($field) {
    case 'sex':
        if (!in_array($value, array('f', 'm', 'n'))) die('Invalid sex value');
        NewsletterUsers::instance()->set_user_field($user->id, 'sex', $value);
        break;
    // Should be managed by Feed by Mail
    case 'feed':
        if ($value != 1) die('Invalid feed value');
        NewsletterUsers::instance()->set_user_field($user->id, 'feed', $value);
        break;
    default:
        die('Invalid field');
}
if (isset($url)) {
    header("Location: $url");
} else {
    NewsletterSubscription::instance()->show_message('profile', $user, NewsletterSubscription::instance()->options['profile_saved']);
}
