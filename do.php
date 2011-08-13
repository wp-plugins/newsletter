<?php

include '../../../wp-load.php';

$action = $_REQUEST['a'];
if (empty($action)) return;

$user = $newsletter->check_user();

if ($user == null) {
    echo 'Subscriber not found, sorry.';
    die();
}

$options = get_option('newsletter', array());
$options_main = get_option('newsletter_main', array());

if ($action == 'c') {
    setcookie('newsletter', $user->id . '-' . $user->token, time() + 60 * 60 * 24 * 365, '/');
    $wpdb->query("update " . $wpdb->prefix . "newsletter set status='C' where id=" . $user->id);

    $newsletter->mail($user->email, $newsletter->replace($options['confirmed_subject'], $user), $newsletter->replace($options['confirmed_message'], $user));
    $newsletter->notify_admin($user, 'Newsletter subscription');

    $url = $options_main['url'];
    if (empty($url)) $url = get_option('home');
    
    header('Location: ' . $newsletter->add_qs($url, 'na=c&ni=' . $user->id . '&nt=' . $user->token, false));
    die();
}

if ($action == 'uc') {
    $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter set status='U' where id=%d and token=%s", $user->id, $user->token));
    setcookie("newsletter", "", time() - 3600);
    $newsletter->mail($user->email, $newsletter->replace($options['unsubscribed_subject'], $user), $newsletter->replace($options['unsubscribed_message'], $user));
    $newsletter->notify_admin($user, 'Newsletter cancellation');
    
    $url = $options_main['url'];
    if (empty($url)) $url = get_option('home');
    
    header('Location: ' . $newsletter->add_qs($url, 'na=uc&ni=' . $user->id . '&nt=' . $user->token, false));
    die();
}
?>
Unknown action.