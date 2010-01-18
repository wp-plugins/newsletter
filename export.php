<?php

?>

<div class="wrap">

    <h2>Subscribers Export</h2>

    <p>The text below is a list of all your subscribers (confirmed and not) in 
        cvs format. You can copy, save and edit it with Excel or other software. Status
    column has 2 values: S - subscribed but not confirmed, C - confirmed.</p>

    <textarea wrap="off" style="width: 100%; height: 300px; font-size: 11px; font-family: monospace">Email,Name,Status,Token
<?php
    $query = "select * from " . $wpdb->prefix . "newsletter where status='C'";
    $recipients = $wpdb->get_results($query . " order by email");
    for ($i=0; $i<count($recipients); $i++)
    {
        echo $recipients[$i]->email . ';' . $recipients[$i]->name .
            ';' . $recipients[$i]->status . ';' . $recipients[$i]->token . "\n";
    }
?></textarea>

</div>
