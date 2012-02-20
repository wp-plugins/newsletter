<?php

@include_once 'commons.php';

$nc = new NewsletterControls();

if (!$nc->is_action()) {
    $nc->data = get_option('newsletter_main');
}
else {
    if ($nc->is_action('remove')) {

        $wpdb->query("delete from " . $wpdb->prefix . "options where option_name like 'newsletter%'");

        $wpdb->query("drop table " . $wpdb->prefix . "newsletter, " . $wpdb->prefix . "newsletter_stats, " .
                $wpdb->prefix . "newsletter_emails, " . $wpdb->prefix . "newsletter_profiles, " .
                $wpdb->prefix . "newsletter_work");

        echo 'Newsletter plugin destroyed. Please, deactivate it now.';
        return;
    }

    if ($nc->is_action('save')) {
        $errors = null;

        // Validation
        $nc->data['sender_email'] = $newsletter->normalize_email($nc->data['sender_email']);
        if (!$newsletter->is_email($nc->data['sender_email'])) {
            $errors = __('Sender email is not correct');
        }

        $nc->data['return_path'] = $newsletter->normalize_email($nc->data['return_path']);
        if (!$newsletter->is_email($nc->data['return_path'], true)) {
            $errors = __('Return path email is not correct');
        }
        // With some providers the return path must be left empty
        //if (empty($options['return_path'])) $options['return_path'] = $options['sender_email'];

        $nc->data['test_email'] = $newsletter->normalize_email($nc->data['test_email']);
        if (!$newsletter->is_email($nc->data['test_email'], true)) {
            $errors = __('Test email is not correct');
        }

        $nc->data['reply_to'] = $newsletter->normalize_email($nc->data['reply_to']);
        if (!$newsletter->is_email($nc->data['reply_to'], true)) {
            $errors = __('Reply to email is not correct');
        }

        $nc->data['mode'] = (int)$nc->data['mode'];
        $nc->data['logs'] = (int)$nc->data['logs'];

        if ($errors == null) {
            update_option('newsletter_main', $nc->data);
        }
    }

    if ($action == 'test') {
        for ($i=0; $i<5; $i++) {
            if (!empty($nc->data['test_email_' . $i])) {
                $r = $newsletter->mail($nc->data['test_email_' . $i],
                        'Test email from Newsletter Plugin', '<p>This is a test message from Newsletter Plugin. You are reading it, so the plugin is working.</p>',
                        true, null, 1);
            }
        }
        $messages = 'Test emails sent. Check the test mailboxes.';
    }
}


$nc->errors($errors);
$nc->messages($messages);
?>

<div class="wrap">

    <h2>Newsletter Main Configuration</h2>

    <?php include dirname(__FILE__) . '/header.php'; ?>

    <p><a href="javascript:void(jQuery('.hints').toggle())">Show/hide detailed documentation</a></p>

    <form method="post" action="">
        <?php $nc->init(); ?>


        <h3>Main settings</h3>

            <p class="intro">
            Configurations on this sub panel can block emails sent by Newsletter Pro. It's not a plugin limit but odd restrictions imposed by
            hosting providers. It's advisable to careful read the detailed documentation you'll found under every options, specially on the "return path"
            field. Try different combination of setting below before send a support request and do it in this way: one single change - test - other single
            change - test, and so on. Thank you for your collaboration.
            </p>

        <table class="form-table">
            <tr valign="top">
                <th>Sender name and address</th>
                <td>
                    email address (required): <?php $nc->text('sender_email', 40); ?>
                    name (optional): <?php $nc->text('sender_name', 40); ?>

                    <div class="hints">
                        These are the name and email address a subscriber will see on emails he'll receive.
                        Be aware that hosting providers can block email with a sender address not of the same domain of the blog.<br />
                        For example, if your blog is www.myblog.com, using as sender email "info@myblog.com" or
                        "newsletter@myblog.com" is safer than using "myaccount@gmail.com". The name is optional but is more professional
                        to set it (even if some providers with bugged mail server do not send email with a sender name set as reported by
                        a customer).
                    </div>
                </td>
            </tr>
            <tr>
                <th>Generic test subscribers</th>
                <td>
                    <?php for ($i=0; $i<5; $i++) { ?>
                    email: <?php $nc->text('test_email_' . $i, 30); ?> name: <?php $nc->text('test_name_' . $i, 30); ?>
                    sex: <?php $nc->select('test_sex_' . $i, array('n'=>'None', 'f'=>'Female', 'm'=>'Male')); ?><br />
                    <?php } ?>
                    <div class="hints">
                        These names and addresses are used by test functionalities on configuration panel. Be sure to fill at least the first
                        test subscriber.<br />
                        <strong>Do not use as email address the same address set as "sender"</strong> (see above), usually it does not work.<br />
                        <strong>You should make a test every time you change one of the settings above</strong>.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Max emails per hour</th>
                <td>
                    <?php $nc->text('scheduler_max', 5); ?>
                    <div class="hints">
                        The internal engine of Newsletter Pro sends email with the specified rate to stay under
                        provider limits. The default value is 100 a very low value. The right value for you
                        depends on your provider or server capacity.<br />
                        Some examples. Hostgator: 500. Dreamhost: 100, asking can be raised to 200. Go Daddy: 1000 per day using their SMTP,
                        unknown per hour rate. Gmail: 500 per day using their SMTP, unknown per hour rate.<br />
                        My sites are on Hostgator or Linode VPS.<br />
                        If you have a service with no limits on the number of emails, still PHP have memory and time limits. Newsletter Pro
                        does it's best to detect those limits and to respect them so it can send out less emails per hour than excepted.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Newsletter user interaction page</th>
                <td>
                    <?php $nc->page_themes('theme'); ?><br />
                    or specify a blog page address:<br />
                    WordPress page URL: <?php $nc->text('url', 70); ?> (eg. <?php echo get_option('home') . '/newsletter'; ?>, optional)

                    <div class="hints">
                        Newsletter Pro needs to interact with subscribers: subscription form, welcome messages, cancellation messages,
                        profile editing form. If you want all those interactions within you blog theme, create a WordPress page and put
                        in its body <strong>only</strong> the short code [newsletter] (as is). Then open that page in your browser and copy the
                        page address (URL) in this field.<br />
                        If you prefer to keep all those interaction out of your blog in a specific designed web page, use the text area
                        to create a full valid HTML page. That page must contain the tag {message} used by Newspetter Pro to insert its
                        messages. A basic template is already there for your convenience.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Return path</th>
                <td>
                    <?php $nc->text('return_path', 40); ?> (valid email address)
                    <div class="hints">
                        This is the email address where delivery error messages are sent. Error message are sent back from mail systems when
                        an email cannot be delivered to the receiver (full mailbox, unrecognized user and invalid address are the most common
                        errors).<br />
                        <strong>Some providers do not accept this field and block emails is present or if the email address has a
                        different domain of the blog</strong> (see above the sender field notes). If you experience problem sending emails
                        (just do some tests), try to leave it blank.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Reply to</th>
                <td>
                    <?php $nc->text('reply_to', 40); ?> (valid email address)
                    <div class="hints">
                        This is the email address where subscribers will reply (eg. if they want to reply to a newsletter). Leave it blank if
                        you don't want to specify a different address from the sender email above. As for return path, come provider do not like this
                        setting active.
                    </div>
                </td>
            </tr>


        </table>
        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
            <?php $nc->button('test', 'Send a test email'); ?>
        </p>




        <h3>General parameters</h3>

        <table class="form-table">
            <!--
            <tr valign="top">
                <th><?php _e('Popup form number', 'newsletter'); ?></th>
                <td>
                    <?php $nc->text('popup_form', 40); ?>
                    <br />
                    <?php _e('
                    Form to be used for integration with wp-super-popup. Leave it empty to use the default form'); ?>
                </td>
            </tr>
            -->
            <tr valign="top">
                <th>Force receiver</th>
                <td>
                    <?php $nc->text('receiver', 40); ?> (valid email address)
                    <div class="hints">
                        If set, EVERY email sent by newsletter will be sent to this address. Set this only if you need to test
                        the plugin but already have a list of regular subscribers and you want to see what happens sending real
                        newsletters.<br />
                        If set, the subscription process works but new subscribers won't receive confirmation or welcome email!
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Notifications</th>
                <td>
                    <?php $nc->yesno('notify'); ?>
                    <div class="hints">
                    Enable or disable notifications of subscription, unsubscription and other events to blog administrator.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Enable access to editors?</th>
                <td>
                    <?php $nc->yesno('editor'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Logging</th>
                <td>
                    <?php $nc->select('logs', array(0=>'None', 1=>'Only errors', 2=>'Normal', 3=>'Debug')); ?>
                    <div class="hints">
                        Be aware of two things: debug level may expose in your log file subscribers data and the file can
                        grow very quickly (tens of megabytes).
                    </div>
                </td>
            </tr>

            <tr valign="top">
                <th>API key</th>
                <td>
                    <?php $nc->text('api_key', 40); ?>
                    <div class="hints">
                        When non-empty can be used to directly call the API for external integration. See API documentation on
                        documentation panel.
                    </div>
                </td>
            </tr>

            <tr>
                <th>Styling</th>
                <td>
                    <?php $nc->textarea('css'); ?>
                    <div class="hints">
                        Add here your own css to style the forms. The whole form is enclosed in a div with class
                        "newsletter" and it's made with a table (guys, I know about your table less design
                        mission, don't blame me too much!)
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>


        <h3>Content locking</h3>

        <p class="intro">
            Content locking is a special feature that permits to "lock out" pieces of post content hiding them and unveiling
            them only to newsletter subscribers. I use it to hide special content on some post inviting the reader to subscribe the newsletter
            to read them.<br />
            Content on post can be hidden surrounding it with [newsletter_lock] and [/newsletter_lock] short codes.<br />
            A subscribe can see the hidden content after sign up or following a link on newsletters and welcome email generated by
            {unlock_url} tag. That link bring the user to the URL below that should be a single premium post/page where there is the hidden
            content or a list of premium posts with hidden content. The latter option can be implemented tagging all premium posts with a
            WordPress tag or adding them to a specific WordPress category.
        </p>
        <table class="form-table">
            <tr valign="top">
                <th>Unlock destination URL</th>
                <td>
                    <?php $nc->text('lock_url', 70); ?>
                    <div class="hints">
                    This is a web address (URL) where users are redirect when they click on unlocking URL ({unlock_url})
                    inserted in newsletters and welcome message. Usually you will redirect the user on a URL with with locked content
                    (that will become visible) or in a place with a list of link to premium content. I send them on a tag page
                    (http://www.satollo.net/tag/reserved) since I tag every premium content with "reserved".
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Denied content message</th>
                <td>
                    <?php $nc->textarea('lock_message'); ?>
                    <div class="hints">
                        This message is shown in place of protected post or page content which is surrounded with
                        [newsletter_lock] and [/newsletter_lock] short codes.<br />
                        Use HTML to format the message. PHP code is accepted and executed. WordPress short codes provided
                        by other plugins work as well. It's a good
                        practice to add the short code [newsletter_embed] to show a subscription form so readers can sign
                        up the newsletter directly.<br />
                        You can also add a subscription HTML form right here, like:<br />
                        <br />
                        &lt;form&gt;<br />
                            Your email: &lt;input type="text" name="ne"/&gt;<br />
                            &lt;input type="submit" value="Subscribe now!"/&gt;<br />
                        &lt;/form&gt;<br />
                        <br />
                        There is no need to specify a form method or action, Newsletter Pro will take care of. To give more evidence of your
                        alternative content you can style it:<br />
                         <br />
                        &lt;div style="margin: 15px; padding: 15px; background-color: #ff9; border-color: 1px solid #000"&gt;<br />
                            blah, blah, blah...<br />
                        &lt;/div&gt;
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>




        <h3>System check</h3>

        <table class="form-table">
            <tr valign="top">
                <th>Cron</th>
                <td>
                    <?php if (defined('DISABLE_WP_CRON') && DISABLE_WP_CRON) { ?>
                    <strong>The WordPress cron system is disabled.</strong> Emails won't be
                    sent without a cron system active, check your wp-config.php file for definition
                    of DISABLE_WP_CRON and remove it.
                    <?php } ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Database</th>
                <td>
                    <?php $wait_timeout = $wpdb->get_var("select @@wait_timeout"); ?>
                    Wait timeout: <?php echo $wait_timeout; ?> seconds
                    <br />
                    <?php if ($wait_timeout > 300) { ?>
                    The timeout is ok
                    <?php } else { ?>
                        <?php $wpdb->query("set session wait_timeout=300"); ?>
                        <?php if (300 != $wpdb->get_var("select @@wait_timeout")) { ?>
                        Cannot rise wait timout, problems may occur while sending.
                        <?php } else { ?>
                        Wait timeout can be changed
                        <?php } ?>
                    <?php } ?>
                        <div class="hints">
                            Connections to database have a idle time limit. If an email takes long time to be sent (due to
                            SMTP or local mail system slowness) this limit can be reached. Losing a connection is a bad thing for
                            Newsletter Pro, so it tries to raise this limit and to keep the connection alive sending queries.
                        </div>
                </td>
            </tr>
            <tr valign="top">
                <th>PHP Execution time</th>
                <td>
                    Max execution time: <?php echo ini_get('max_execution_time'); ?> seconds
                    <div class="hints">
                        Even if PHP execution max time can usually be changed web servers can have external
                        controller that will kill PHP scripts if they run too long (like
                        fastcgi). Time limit can be a problem while using external SMTPs, if they are slow. Newsletter
                        Pro, while sending, continously check the time limit and stops it self as the timeout is approaching.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Memory limit</th>
                <td>
                    <?php echo @ini_get('memory_limit'); ?>
                    <div class="hints">
                        Newsletter Pro tries (as does WordPress on admin side) to raise the memory limit to
                        256 megabytes while sending.
                    </div>
                </td>
            </tr>

        </table>

        <p class="submit">
            <?php $nc->button_confirm('remove', 'Totally remove this plugin', 'Really sure to totally remove this plugin. All data will be lost!'); ?>
        </p>

    </form>
    <p></p>
</div>
