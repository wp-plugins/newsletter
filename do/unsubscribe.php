<?php
// Patch to avoid "na" parameter to disturb the call
unset($_REQUEST['na']);
unset($_POST['na']);
unset($_GET['na']);

require_once '../../../../wp-load.php';

$user = NewsletterSubscription::instance()->unsubscribe();
NewsletterSubscription::instance()->show_message('unsubscribed', $user);
