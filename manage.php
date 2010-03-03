<?php

@include_once 'commons.php';

$options = get_option('newsletter');

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

if ($_POST['a'] == 'save' && check_admin_referer()) {
    newsletter_save(stripslashes_deep($_POST['subscriber']));
    $_POST['a'] = 'search';
}

if ($_POST['a'] == 'search' && check_admin_referer()) {
    $status = isset($_POST['unconfirmed'])?'S':null;
    $order = $_POST['order'];
    $list = newsletter_search(newsletter_request('text'), $status, $order);
}

$options = null;
$nc = new NewsletterControls($options, 'manage');

?>
<script type="text/javascript">
    function newsletter_detail(id)
    {
        document.getElementById("action").value = "detail";
        document.getElementById("id").value = id;
        document.getElementById("channel").submit();
    }
    function newsletter_edit(id)
    {
        document.getElementById("action").value = "edit";
        document.getElementById("id").value = id;
        document.getElementById("channel").submit();
    }
    function newsletter_save()
    {
        document.getElementById("action").value = "save";
        document.getElementById("channel").submit();
    }
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
        if (!confirm("<?php _e('Resend the subscription confirmation email?', 'newsletter'); ?>")) return;
        document.getElementById("action").value = "resend";
        document.getElementById("id").value = id;
        document.getElementById("channel").submit();
    }

</script>

<div class="wrap">
    <h2><?php _e('Newsletter Subscribers', 'newsletter'); ?></h2>

    <?php require_once 'header.php'; ?>

    <form id="channel" method="post" action="">
        <?php wp_nonce_field(); ?>
        <input type="hidden" id="action" name="a" value="search"/>
        <input type="hidden" id="id" name="id" value=""/>
        <input type="hidden" id="status" name="status" value=""/>

        <div style="display: <?php if ($_POST['a'] == 'edit') echo 'none'; else echo 'block'; ?>">
            <table class="form-table">
                <tr valign="top">
                    <th><?php _e('Search', 'newsletter'); ?></th>
                    <td>
                        <input name="text" type="text" size="50" value="<?php echo htmlspecialchars(newsletter_request('text'))?>"/>
                        <input type="submit" value="<?php _e('Search', 'newsletter'); ?>" />
                        <br />
                        <?php _e('Press without filter to show all. Max 100 results will be shown. Use export panel to get all subscribers.', 'newsletter'); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">&nbsp;</th>
                    <td>
                        <input name="unconfirmed" type="checkbox" <?php echo isset($_POST['unconfirmed'])?'checked':''; ?>/>
                        <?php _e('Only not yet confirmed', 'newsletter'); ?>
                    </td>
                </tr>
                <tr valign="top">
                    <th><?php _e('Order', 'newsletter'); ?></th>
                    <td>
                        <select name="order">
                            <option value="id">id</option>
                            <option value="email">email</option>
                        </select>
                    </td>
                </tr>
            </table>
        </div>

        <?php
        if ($_POST['a'] == 'edit' && check_admin_referer()) {
            $subscriber = newsletter_get_subscriber($_POST['id']);
            ?>
        <input type="hidden" name="subscriber[id]" value="<?php echo $subscriber->id; ?>"/>
        <table class="form-table">
            <tr valign="top">
                <th>Name</th>
                <td><input type="text" name="subscriber[name]" size="40" value="<?php echo htmlspecialchars($subscriber->name); ?>"/></td>
            </tr>
            <tr valign="top">
                <th>Email</th>
                <td><input type="text" name="subscriber[email]" size="40" value="<?php echo htmlspecialchars($subscriber->email); ?>"/></td>
            </tr>
        </table>
        <p class="submit"><input type="button" value="Save" onclick="newsletter_save()"/></p>

            <?php } ?>

    </form>


    <?php if ($_POST['a'] == 'edit') { ?>
</div>
    <?php return; } ?>


<form method="post" action="">
    <?php wp_nonce_field(); ?>
    <p class="submit">
    <!--<input type="submit" value="Remove all" name="removeall" onclick="return confirm('Are your sure, really sure?')"/>-->
        <input type="submit" value="<?php _e('Remove all unconfirmed', 'newsletter'); ?>" name="removeallunconfirmed" onclick="return confirm('<?php _e('Are your sure, really sure?', 'newsletter'); ?>')"/>
    </p>
</form>



<h3><?php _e('Subscriber\'s statistics', 'newsletter'); ?></h3>
<?php _e('Confirmed subscriber', 'newsletter'); ?>: <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='C'"); ?>
<br />
<?php _e('Unconfirmed subscriber', 'newsletter'); ?>: <?php echo $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='S'"); ?>

<h3><?php _e('Results', 'newsletter'); ?></h3>

<?php
if ($list) {
    echo '<table class="bordered-table" border="1" cellspacing="5">';
    echo '<tr><th>Id</th><th>' . __('Email', 'newsletter') . '</th><th>' . __('Name', 'newsletter') . '</th><th>' . __('Status', 'newsletter') . '</th><th>' . __('Actions', 'newsletter') . '</th><th>' . __('Profile', 'newsletter') . '</th></tr>';
    foreach($list as $s) {
        echo '<tr>';
        echo '<td>' . $s->id . '</td>';
        echo '<td>' . $s->email . '</td>';
        echo '<td>' . $s->name . '</td>';
        echo '<td><small>' . ($s->status=='S'?'Not confirmed':'Confirmed') . '</small></td>';
        echo '<td><small>';
        echo '<a href="javascript:void(newsletter_edit(' . $s->id . '))">' . __('edit', 'newsletter') . '</a>';
        echo ' | <a href="javascript:void(newsletter_remove(' . $s->id . '))">' . __('remove', 'newsletter') . '</a>';
        echo ' | <a href="javascript:void(newsletter_set_status(' . $s->id . ', \'C\'))">' . __('confirm', 'newsletter') . '</a>';
        echo ' | <a href="javascript:void(newsletter_set_status(' . $s->id . ', \'S\'))">' . __('unconfirm', 'newsletter') . '</a>';
        echo ' | <a href="javascript:void(newsletter_resend(' . $s->id . '))">' . __('resend confirmation', 'newsletter') . '</a>';
        echo '</small></td>';
        echo '<td><small>';
        $query = $wpdb->prepare("select name,value from " . $wpdb->prefix . "newsletter_profiles where newsletter_id=%d", $s->id);
        $profile = $wpdb->get_results($query);
        foreach ($profile as $field) {
            echo htmlspecialchars($field->name) . ': ' . htmlspecialchars($field->value) . '<br />';
        }
        echo 'Token: ' . $s->token;

        echo '</small></td>';

        echo '</tr>';
    }
    echo '</table>';
}
?>

</div>
