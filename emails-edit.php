<?php
@include_once 'commons.php';
$nc = new NewsletterControls();

if (isset($_GET['id'])) {
    $nc->load($wpdb->prefix . 'newsletter_emails', $_GET['id']);
    if (empty($nc->data['id'])) {
        $nc->data['status'] = 'new';
        $nc->data['subject'] = 'Here the email subject';
        $nc->data['message'] = '<p>An empty email to start.</p>';
        $nc->data['track'] = 1;
    }
}
else {
    if ($nc->is_action('save') || $nc->is_action('send')) {
        $nc->save($wpdb->prefix . 'newsletter_emails');
    }

    if ($nc->is_action('send')) {

        // Fake value representing the WordPress users as target
        if ($nc->data['list'] == -1) {
            $query = "select count(*) from " .  $wpdb->prefix . "users " . $nc->data['query'];
        }
        else {
            if (!empty($nc->data['query'])) $query = "select count(*) from " . $wpdb->prefix . "newsletter " . $nc->data['query'];
            else {
                $query = "select count(*) from " . $wpdb->prefix . "newsletter where status='C'";
                if ($nc->data['list'] != 0) $query .= " and list_" . $nc->data['list'] . "=1";
                if (!empty($nc->data['sex'])) $query .= " and sex='" . $nc->data['sex'] . "'";
            }
        }
        $newsletter->log($query, 3);
        $newsletter->log('total: ' . $wpdb->get_var($query), 3);
        
        $nc->data['total'] = $wpdb->get_var($query);
        $nc->data['sent'] = 0;
        $nc->data['status'] = 'sending';
        $nc->data['last_id'] = 0;
        $nc->save($wpdb->prefix . 'newsletter_emails');
        $nc->load($wpdb->prefix . 'newsletter_emails', $nc->data['id']);
    }

    if ($nc->is_action('pause')) {
        $nc->update($wpdb->prefix . 'newsletter_emails', 'status', 'paused');
        $nc->load($wpdb->prefix . 'newsletter_emails', $nc->data['id']);
    }

    if ($nc->is_action('continue')) {
        $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set status='sending' where id=" . $nc->data['id']);
        $nc->load($wpdb->prefix . 'newsletter_emails', $nc->data['id']);
    }

    if ($nc->is_action('abort')) {
        $wpdb->query("update " . $wpdb->prefix . "newsletter_emails set last_id=0, status='new' where id=" . $nc->data['id']);
        $nc->load($wpdb->prefix . 'newsletter_emails', $nc->data['id']);
    } 

    if ($nc->is_action('delete')) {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter_emails where id=" . $nc->data['id']);
        ?><script>location.href="admin.php?page=newsletter/emails.php";</script><?php
        return;
    }

    if ($nc->is_action('compose')) {
        if ($nc->data['_theme'][0] == '*') $file = ABSPATH . 'wp-content/plugins/newsletter-custom/themes/' . substr($nc->data['_theme'], 1) .
                '/theme.php';
        else $file = dirname(__FILE__) . '/themes/' . $nc->data['_theme'] . '/theme.php';

        ob_start();
        @include($file);
        $nc->data['message'] = ob_get_contents();
        ob_end_clean();
    }

    if ($nc->is_action('test')) {
        $nc->save($wpdb->prefix . 'newsletter_emails');
        $users = newsletter_get_test_subscribers();
        $email = new stdClass();
        $email->message = $nc->data['message'];
        $email->subject = $nc->data['subject'];
        $email->track = $nc->data['track'];
        $email->type = 'email';
        $newsletter->send($email, $users);
    }
}


$options_main = get_option('newsletter_main', array());

$options_profile = get_option('newsletter_profile', array());
$lists = array('0' => 'To all subscribers', '-1'=>'To WordPress users');
for ($i = 1; $i <= 9; $i++) {
    $lists['' . $i] = '(' . $i . ') ' . $options_profile['list_' . $i];
}

// Themes
$themes[''] = 'Packaged themes';
$themes['blank'] = 'Empty email';
$themes['theme-1'] = 'Newsletter theme 1';
//$themes['theme-2'] = 'Newsletter theme 2';
//$themes['theme-3'] = 'Newsletter theme 3';

$nc->errors($errors);
$nc->messages($messages);
?>

<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "visual",
        theme : "advanced",
        plugins: "table,fullscreen",
        theme_advanced_disable : "styleselect",
        theme_advanced_buttons1_add: "forecolor,blockquote,code",
        theme_advanced_buttons3 : "tablecontrols,fullscreen",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_toolbar_location : "top",
        document_base_url : "<?php echo get_option('home'); ?>/"
    });
</script>


<div class="wrap">

    <h2>Email Composer</h2>

    <form method="post" action="admin.php?page=newsletter/emails-edit.php">
        <?php $nc->init(); ?>
        <?php $nc->hidden('id'); ?>
        <?php $nc->hidden('status'); ?>

        <table class="form-table">

            <tr valign="top">
                <th>Auto compose</th>
                <td>
                    <?php $nc->select_grouped('_theme', array(
                            array_merge(array(''=>'Custom themes'), newsletter_get_themes()),
                            $themes
                            ));
                    ?>
                    <?php $nc->button('compose', 'Auto compose'); ?>
                    <div class="hints">
                        Auto composition does not save this email, remember to press save if you are satisfied of the result.
                    </div>
                </td>
            </tr>

            <tr valign="top">
                <th>Subject</th>
                <td>
                    <?php $nc->text('subject', 70); ?>
                   <div class="hints">
                        Tags: <strong>{name}</strong> receiver name.
                   </div>
                </td>
            </tr>

            <tr valign="top">
                <th>Message</th>
                <td>
                    <?php $nc->data['editor'] == 0?$nc->editor('message', 20):$nc->textarea_fixed('message', '100%', 400); ?>
                    <br />
                    <?php $nc->select('editor', array(0=>'Edit with visual editor', 1=>'Edit as plain text')); ?>
                    <div class="hints">
                        Tags: <strong>{name}</strong> receiver name;
                        <strong>{unsubscription_url}</strong> unsubscription URL;
                        <strong>{token}</strong> the subscriber token; <strong>{profile_url}</strong> link to user subscription options page;
                        <strong>{np_aaa}</strong> user profile data named "aaa".
                    </div>
                </td>
            </tr>

            <tr valign="top">
                <th>To...</th>
                <td>
                    list: <?php $nc->select('list', $lists); ?>
                    sex: <?php $nc->select('sex', array(''=>'All', 'n'=>'No sex specified', 'f'=>'Females', 'm'=>'Males')); ?>
                    <div class="hints">
                        When sending to WordPress users, they cannot cancel the subscription and the cannot
                        be tracked.
                    </div>
                </td>
            </tr>
            <!--
            <tr valign="top">
                <th>Query<br/><small>Really advanced!</small></th>
                <td>
                    select * from wp_newsletter<br />
                    <?php $nc->textarea('query'); ?>
                    <br />
                    and id>... order by id limit ...
                    <div class="hints">
                        If you want to specify a different query to extract subscriber from Newsletter Pro database, here you
                        can write it. Be aware that the query starts and ends as specified, so your SQL snippet needs to create a
                        complete and working query.<br />
                        Leave this area empty to leave Newsletter Pro doing the work.<br />
                        When you specify a query, options like the target list will be ignored.<br />
                        For examples of queries study the documentation panel.
                    </div>
                </td>
            </tr>
            -->

        </table>

        <p class="submit">
            <?php if ($nc->data['status'] != 'sending') $nc->button('save', 'Save'); ?>
            <?php if ($nc->data['status'] != 'sending') $nc->button_confirm('test', 'Save and test', 'Save and send test emails to test addresses?'); ?>

            <?php if ($nc->data['status'] == 'new') $nc->button_confirm('send', 'Send', 'Start a real delivery?'); ?>
            <?php if ($nc->data['status'] == 'sending') $nc->button_confirm('pause', 'Pause', 'Pause the delivery?'); ?>
            <?php if ($nc->data['status'] == 'paused') $nc->button_confirm('continue', 'Continue', 'Continue the delivery?'); ?>
            <?php if ($nc->data['status'] != 'new') $nc->button_confirm('abort', 'Abort', 'Abort the delivery?'); ?>
            <?php if ($nc->data['id'] != 0) $nc->button_confirm('delete', 'Delete', 'Delete?'); ?>
            (email status: <?php echo $nc->data['status']; ?>)
        </p>

    </form>
</div>
