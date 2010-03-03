<?php

@include_once 'commons.php';

$options = get_option('newsletter');


if ($action == 'import') {
    @set_time_limit(100000);
    $csv = stripslashes($_POST['csv']);
    $lines = explode("\n", $csv);

    $errors = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == '') continue;
        if ($line[0] == '#') continue;
        $data = explode(';', $line);
        $email = newsletter_normalize_email($data[0]);
        if (!newsletter_is_email($email))
        {
            $errors[] = $line;
            continue;
        }
        $name = newsletter_normalize_name($data[1]);
        $token = md5(rand());
        $r = $wpdb->query("insert into " . $wpdb->prefix . "newsletter (status, email, name, token) values ('C', '" . $wpdb->escape($email) . "','" . $wpdb->escape($name) . "','" . $token . "')");
        // Zero or false mean no row inserted
        if (!$r) $errors[] = $line;
    }
}

$nc = new NewsletterControls();
?>

<div class="wrap">

    <h2><?php _e('Newsletter Import', 'newsletter'); ?> <a target="_blank" href="http://www.satollo.net/plugins/newsletter#import"><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/help.png"/></a></h2>

    <?php require_once 'header.php'; ?>

    <?php if (!empty($errors)) { ?>

    <h3><?php _e('Rows with errors', 'newsletter'); ?></h3>

    <textarea wrap="off" style="width: 100%; height: 150px; font-size: 11px; font-family: monospace"><?php echo htmlspecialchars(implode("\n", $errors))?></textarea>

    <?php } ?>

    <form method="post" action="">
        <?php wp_nonce_field(); ?>

        <h3><?php _e('CSV text with subscribers', 'newsletter'); ?></h3>

        <textarea name="csv" wrap="off" style="width: 100%; height: 300px; font-size: 11px; font-family: monospace"></textarea>
        <p class="submit">
            <?php $nc->button('import', __('Import', 'newsletter')); ?>
        </p>
    </form>

</div>
