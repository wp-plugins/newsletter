<?php
$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain('newsletter', 'wp-content/plugins/' . $plugin_dir . '/languages/');
?>

<div class="wrap">
    <h2><?php _e('Subscribers Export', 'newsletter'); ?></h2>

    <p><?php _e('The text below is a list of all your subscribers (confirmed and not) in cvs format. You can copy, save and edit it with Excel or other software. Status column has 2 values: S - subscribed but not confirmed, C - confirmed.', 'newsletter'); ?></p>

    <textarea wrap="off" style="width: 100%; height: 300px; font-size: 11px; font-family: monospace"><?php _e('Email', 'newsletter'); ?>,<?php _e('Name', 'newsletter'); ?>,<?php _e('Status', 'newsletter'); ?>,<?php _e('Token', 'newsletter'); ?>
        <?php
        $query = "select * from " . $wpdb->prefix . "newsletter";
        $recipients = $wpdb->get_results($query . " order by email");
        for ($i=0; $i<count($recipients); $i++) {
            echo $recipients[$i]->email . ';' . $recipients[$i]->name .
                ';' . $recipients[$i]->status . ';' . $recipients[$i]->token . "\n";
        }
        ?></textarea>
</div>
