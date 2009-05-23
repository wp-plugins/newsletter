<?php
if (isset($_POST['import']))
{
    $csv = newsletter_request('csv');
    $lines = explode("\n", $csv);

    $errors = array();
    foreach ($lines as $line)
    {
        $line = trim($line);
        if ($line == '') continue;
        if ($line[0] == '#') continue;
        $data = explode(';', $line);
        $email = newsletter_normalize_email($data[0]);
        $name = $data[1];
        $token = md5(rand());
        $r = $wpdb->query("insert into " . $wpdb->prefix . "newsletter (email, name, token) values ('" . $wpdb->escape($email) . "','" . $wpdb->escape($name) . "','" . $token . "')");
        // Zero or false mean no row inserted
        if (!$r) $errors[] = $line;
    }
}

?>

<div class="wrap">
    <form method="post">
        <h2>Subscribers Import</h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Message</label></th>
                <td>
                    <textarea name="csv" wrap="off" rows="20" cols="75"></textarea>
                    <br />
                    Copy here a text with csv format: "email@example.com;Name Surname". Empty rows or
                    rows starting with "#" are skipped.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Rows with errors</label></th>
                <td>
                    <textarea name="csv" wrap="off" rows="20" cols="75"><?php echo htmlspecialchars(implode("\n", $errors))?></textarea>
                    <br />
                </td>
            </tr>
        </table>
        

        <p class="submit">
            <input class="button" type="submit" name="import" value="Import"/>
        </p>
    </form>

</div>
