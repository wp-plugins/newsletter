<?php

$options = get_option('newsletter');

if (!isset($options['no_translation'])) {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain('newsletter', 'wp-content/plugins/' . $plugin_dir . '/languages/');
}

if (isset($_POST['import'])) {
    if (!check_admin_referer()) die('No hacking please');
    @set_time_limit(100000);
    $csv = newsletter_request('csv');
    $lines = explode("\n", $csv);

    $errors = array();
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line == '') continue;
        if ($line[0] == '#') continue;
        $data = explode(';', $line);
        if (!newsletter_is_email($data[0])) {
            $errors[] = $line;
            continue;
        }
        $email = newsletter_normalize_email($data[0]);
        $name = $data[1];
        $token = md5(rand());
        $r = $wpdb->query("insert into " . $wpdb->prefix . "newsletter (status, email, name, token) values ('C', '" . $wpdb->escape($email) . "','" . $wpdb->escape($name) . "','" . $token . "')");
        // Zero or false mean no row inserted
        if (!$r) $errors[] = $line;
    }
}

?>

<div class="wrap">

    <h2><?php _e('Subscribers Import', 'newsletter'); ?></h2>

<?php require_once 'header.php'; ?>

    <?php if ($errors) { ?>

    <h3><?php _e('Rows with errors', 'newsletter'); ?></h3>
    <textarea wrap="off" style="width: 100%; height: 150px; font-size: 11px; font-family: monospace"><?php echo htmlspecialchars(implode("\n", $errors))?></textarea>

<?php } ?>

    <form method="post">
<?php wp_nonce_field(); ?>
        <h3><?php _e('Import', 'newsletter'); ?></h3>
        <p><?php _e('On the textarea below you can copy a text in CSV (comma separated values) with format:<br /><br /> <pre>user email;user name</pre><br /><br />and then import them. If an email is already stored, it won\'t be imported. If an email is wrong it won\'t be imported. Even when there are errors on CSV lines, the import will continue to the end. After the import process has ended, a box will appear with all the line not imported due to duplications or errors. Imported subscriber will be set as confirmed.', 'newsletter'); ?>
        </p>
        <p><?php _e('Empty rows and rows staring with sharp (#) are skipped. Emails will be normalized and a subscriber token generated for each imported email.', 'newsletter'); ?></p>

        <textarea name="csv" wrap="off" style="width: 100%; height: 300px; font-size: 11px; font-family: monospace"></textarea>
        <p class="submit">
            <input class="button" type="submit" name="import" value="<?php _e('Import', 'newsletter'); ?>"/>
        </p>
    </form>

</div>
