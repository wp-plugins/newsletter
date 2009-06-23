<?php

if ($_POST['a'] == 'resend')
{
    newsletter_send_confirmation(newsletter_get_subscriber(newsletter_request('email')));
    $_POST['a'] = 'search';
}

if ($_POST['a'] == 'remove')
{
    newsletter_delete(newsletter_request('email'));
    $_POST['a'] = 'search';
}

if ($_POST['removeall'])
{
    newsletter_delete_all();
}

if ($_POST['removeallunconfirmed'])
{
    newsletter_delete_all('S');
}

if ($_POST['a'] == 'status')
{
    newsletter_set_status(newsletter_request('email'), newsletter_request('status'));
    $_POST['a'] = 'search';
}

if ($_POST['a'] == 'search')
{
    $list = newsletter_search(newsletter_request('text'));
}

?>
<script type="text/javascript">
function newsletter_remove(email)
{
    document.getElementById("action").value = "remove";
    document.getElementById("email").value = email;
    document.getElementById("channel").submit();
}
function newsletter_set_status(email, status)
{
    document.getElementById("action").value = "status";
    document.getElementById("email").value = email;
    document.getElementById("status").value = status;
    document.getElementById("channel").submit();
}
function newsletter_resend(email)
{
    if (!confirm("Resend the subscription confirmation email?")) return;
    document.getElementById("action").value = "resend";
    document.getElementById("email").value = email;
    document.getElementById("channel").submit();
}

</script>
<div class="wrap">
        <h2>Subscribers Management</h2>
        <p>Still in developement, any ideas will be great: write me to info@satollo.com.</p>
    <form id="channel" method="post" action="">
        <input type="hidden" id="action" name="a" value="search"/>
        <input type="hidden" id="email" name="email" value=""/>
        <input type="hidden" id="status" name="status" value=""/>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Search by email</label></th>
                <td>
                    <input name="text" type="text" size="50" value="<?php echo htmlspecialchars(newsletter_request('text'))?>"/>
                    <input type="submit" value="Search" />
                </td>
            </tr>
        </table>
    </form>
    <!--
    <form method="post" action="">
            <p class="submit">
    <input type="submit" value="Remove all" name="removeall" onclick="return confirm('Are your sure, really sure?')"/>
    <input type="submit" value="Remove all unconfirmed" name="removeallunconfirmed" onclick="return confirm('Are your sure, really sure?')"/>
    </p>
    </form>
    -->

    <h3>Statistics</h3>
    Confirmed subscriber: <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='C'"); ?>
    <br />
    Unconfirmed subscriber: <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='S'"); ?>

    <h3>Results</h3>
    <style type="text/css">
    .newsletter-results {
        border-collapse: collapse;
    }
    .newsletter-results td, .newsletter-results th {
        border: 1px solid #999;
        padding: 5px;
    }
    </style>
    <?php
        if ($list)
        {
            echo '<table class="newsletter-results" border="1" cellspacing="5">';
            echo '<tr><th>Email</th><th>Name</th><th>Status</th><th>Actions</th></tr>';
            foreach($list as $s)
            {
                echo '<tr>';
                echo '<td>' . $s->email . '</td>';
                echo '<td>' . $s->name . '</td>';
                echo '<td>' . ($s->status=='S'?'Not confirmed':'Confirmed') . '</td>';
                echo '<td>';
                echo '<a href="javascript:void(newsletter_remove(\'' . $s->email . '\'))">remove</a>';
                echo ' | <a href="javascript:void(newsletter_set_status(\'' . $s->email . '\', \'C\'))">confirm</a>';
                echo ' | <a href="javascript:void(newsletter_set_status(\'' . $s->email . '\', \'S\'))">unconfirm</a>';
                echo ' | <a href="javascript:void(newsletter_resend(\'' . $s->email . '\'))">resend confirmation</a>';
                echo '</td>';
                echo '</tr>';
            }
            echo '</table>';
        }
    ?>

</div>
