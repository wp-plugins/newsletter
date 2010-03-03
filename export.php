<?php
@include_once 'commons.php';

?>

<div class="wrap">
    <h2><?php _e('Newsletter Export', 'newsletter'); ?> <a target="_blank" href="http://www.satollo.net/plugins/newsletter#export"><img src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/help.png"/></a></h2>

    <textarea wrap="off" style="width: 100%; height: 400px; font-size: 11px; font-family: monospace">
email;name;status;token

<?php
        $query = "select * from " . $wpdb->prefix . "newsletter";
        $recipients = $wpdb->get_results($query . " order by email");
        for ($i=0; $i<count($recipients); $i++) {
            echo $recipients[$i]->email . ';' . $recipients[$i]->name .
                ';' . $recipients[$i]->status . ';' . $recipients[$i]->token . "\n";
        }
        ?></textarea>
</div>
