<?php

if ($_POST['a'] == 'resend' && check_admin_referer()) {
    newsletter_send_confirmation(newsletter_get_subscriber(newsletter_request('id')));
    $_POST['a'] = 'search';
}

if ($_POST['a'] == 'remove' && check_admin_referer()) {
    newsletter_delete(newsletter_request('id'));
    $_POST['a'] = 'search';
}

if ($_POST['removeall'] && check_admin_referer()) {
    newsletter_delete_all();
}

if ($_POST['removeallunconfirmed'] && check_admin_referer()) {
    newsletter_delete_all('S');
}

if ($_POST['showallunconfirmed'] && check_admin_referer()) {
    $list = newsletter_get_unconfirmed();
}

if ($_POST['a'] == 'status' && check_admin_referer()) {
    newsletter_set_status(newsletter_request('id'), newsletter_request('status'));
    $_POST['a'] = 'search';
}

if ($_POST['a'] == 'search' && check_admin_referer()) {
    $status = isset($_POST['unconfirmed'])?'S':null;
    $order = $_POST['order'];
    $list = newsletter_search(newsletter_request('text'), $status, $order);
}

?>
<script type="text/javascript">
    function newsletter_remove(id)
    {
        document.getElementById("action").value = "remove";
        document.getElementById("id").value = id;
        document.getElementById("channel").submit();
    }
    function newsletter_set_status(id, status)
    {
        document.getElementById("action").value = "status";
        document.getElementById("id").value = id;
        document.getElementById("status").value = status;
        document.getElementById("channel").submit();
    }
    function newsletter_resend(id)
    {
        if (!confirm("Resend the subscription confirmation email?")) return;
        document.getElementById("action").value = "resend";
        document.getElementById("id").value = id;
        document.getElementById("channel").submit();
    }

</script>
<style type="text/css">
    .newsletter-results {
        border-collapse: collapse;
    }
    .newsletter-results td, .newsletter-results th {
        border: 1px solid #999;
        padding: 5px;
    }
</style>
<div class="wrap">
    <h2>Subscribers Management</h2>

    <?php require_once 'header.php'; ?>

    <form id="channel" method="post" action="">
        <?php wp_nonce_field(); ?>
        <input type="hidden" id="action" name="a" value="search"/>
        <input type="hidden" id="id" name="id" value=""/>
        <input type="hidden" id="status" name="status" value=""/>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Search</label></th>
                <td>
                    <input name="text" type="text" size="50" value="<?php echo htmlspecialchars(newsletter_request('text'))?>"/>
                    <input type="submit" value="Search" /> (press without filter to show all)
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                    <input name="unconfirmed" type="checkbox" <?php echo isset($_POST['unconfirmed'])?'checked':''; ?>/>
                    Only not yet confirmed
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">Order</th>
                <td>
                    <select name="order">
                        <option value="id">id</option>
                        <option value="email">email</option>
                    </select>
                </td>
            </tr>
        </table>
    </form>
    <form method="post" action="">
        <?php wp_nonce_field(); ?>
        <p class="submit">
        <!--<input type="submit" value="Remove all" name="removeall" onclick="return confirm('Are your sure, really sure?')"/>-->
            <input type="submit" value="Remove all unconfirmed" name="removeallunconfirmed" onclick="return confirm('Are your sure, really sure?')"/>
        </p>
    </form>

    <h3>Subscriber's statistics</h3>
    Confirmed subscriber: <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='C'"); ?>
    <br />
    Unconfirmed subscriber: <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='S'"); ?>

    <h3>Results</h3>

    <?php
    if ($list) {
        echo '<table class="newsletter-results" border="1" cellspacing="5">';
        echo '<tr><th>Id</th><th>Email</th><th>Name</th><th>Status</th><th>Profile</th><th>Token</th><th>Actions</th></tr>';
        foreach($list as $s) {
            echo '<tr>';
            echo '<td>' . $s->id . '</td>';
            echo '<td>' . $s->email . '</td>';
            echo '<td>' . $s->name . '</td>';
            echo '<td>' . ($s->status=='S'?'Not confirmed':'Confirmed') . '</td>';
            echo '<td>';

            $profile = unserialize($s->profile);
            if (is_array($profile)) {
                foreach ($profile as $key=>$value) {
                    echo htmlspecialchars($key) . ': ' . htmlspecialchars($value) . '<br />';
                }
            }
            echo '</td>';
            echo '<td>' . $s->token . '</td>';
            echo '<td>';
            echo '<a href="javascript:void(newsletter_remove(' . $s->id . '))">remove</a>';
            echo ' | <a href="javascript:void(newsletter_set_status(' . $s->id . ', \'C\'))">confirm</a>';
            echo ' | <a href="javascript:void(newsletter_set_status(' . $s->id . ', \'S\'))">unconfirm</a>';
            echo ' | <a href="javascript:void(newsletter_resend(' . $s->id . '))">resend confirmation</a>';
            echo '</td>';
            echo '</tr>';
        }
        echo '</table>';
    }
    ?>

</div>
