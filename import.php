<?php
if (isset($_POST['import']))
{
    if (!check_admin_referer()) die('No hacking please');
    $csv = newsletter_request('csv');
    $lines = explode("\n", $csv);

    $errors = array();
    foreach ($lines as $line)
    {
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

    <h2>Subscribers Import/Export</h2>

<?php if ($errors) { ?>
    <h3>Rows with errors</h3>

    <textarea wrap="off" style="width: 100%; height: 150px; font-size: 11px; font-family: monospace"><?php echo htmlspecialchars(implode("\n", $errors))?></textarea>

        <?php } ?>

    <form method="post">
        <?php wp_nonce_field(); ?>
        <h3>Import</h3>
        <p>On the textarea below you can copy a text in CSV (comma separated values)
            with format:<br /><br />
            user email;user name
            <br /><br />
            and then import them. If an email is already stored, it won't be imported. If an
            email is wrong it won't be imported. Even when there are errors on CSV lines, the import
            will continue to the end. After the import process has ended, a box will appear with all
            the line not imported due to duplications or errors. Imported subscriber will be set as confirmed.
        </p>
        <p>Empty rows and rows staring with sharp (#) are skipped. Emails will be normalized and a
        subscriber token generated for each imported email.</p>

        <textarea name="csv" wrap="off" style="width: 100%; height: 300px; font-size: 11px; font-family: monospace"></textarea>
        <p class="submit">
            <input class="button" type="submit" name="import" value="Import"/>
        </p>
    </form>

    <h3>Export</h3>
    <p>The text below is a list of all your subscribers (confirmed and not) in a
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
