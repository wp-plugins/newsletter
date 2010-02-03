
<div class="wrap">
    <form method="post" action="">
        <h2>Conversion</h2>
        <p>From version 1.4.8 a new database structure has been adopted, so it's better to
            convert you actual data to the new format. No data will be lost or has been lost since your
            last update.
        </p>
        
<?php

if (isset($_POST['convert'])) {
    $query = "select id,profile from " . $wpdb->prefix . "newsletter";
    $recipients = $wpdb->get_results($query);
    foreach ($recipients as $s) {
        $profile = unserialize($s->profile);
        if ($profile) {
            foreach ($profile as $name=>$value) {
                @$wpdb->insert($wpdb->prefix . 'newsletter_profiles', array(
                    'newsletter_id'=>$s->id,
                    'name'=>$name,
                    'value'=>$value));
            }
            @$wpdb->query('update ' . $wpdb->prefix . 'newsletter set profile=null where id=' . $s->id);
        }
    }
    echo "DONE!";
}
?>



        <p class="submit">
            <input class="button" type="submit" name="convert" value="Convert"/>
        </p>
    </form>
</div>