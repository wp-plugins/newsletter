<?php

@include_once 'commons.php';

$options = get_option('newsletter');

if ($action == 'save') {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter', $options);
}

$nc = new NewsletterControls($options);
?>

<?php if ($options['novisual'] != 1) { ?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
    tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "visual",
        theme : "advanced",
        theme_advanced_disable : "styleselect",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location : "top",
        document_base_url : "<?php echo get_option('home'); ?>/"
    });
</script>
    <?php } ?>

<div class="wrap">

    <h2><?php _e('Newsletter Subscription and Unsubscription', 'newsletter'); ?></h2>

    <?php require_once 'header.php'; ?>

    <form method="post" action="">
        <?php wp_nonce_field(); ?>

        <h3><?php _e('Sender and subscription page', 'newsletter'); ?></h3>
       
        <?php _e('<p><strong>It\'s REQUIRED to complete such configuration.</strong></p>', 'newsletter'); ?>

        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Sender email', 'newsletter'); ?></th>
                <td>
                    <?php $nc->text('from_email', 50); ?>
                    <br />
                    <?php _e('Newsletter sender email address: the address subscribers will see the newsletters coming from.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Sender name'); ?></th>
                <td>
                    <?php $nc->text('from_name', 50); ?>
                    <br />
                    <?php _e('The name of the newsletter sender subscribers will see on incoming email.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Subscription page URL', 'newsletter'); ?></th>
                <td>
                    <?php $nc->text('url', 70); ?>
                    <br />
                    <?php _e('This is the page where you placed the <strong>[newsletter]</strong> short tag. (<a href="http://www.satollo.net/plugins/newsletter">Read more on plugin official page</a>)', 'newsleetter'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th><?php _e('Theme to use for emails', 'newsletter'); ?></th>
                <td>
                    <select name="options[theme]">
                        <optgroup label="Included themes">
                            <option <?php echo ('blank'==$options['theme'])?'selected':''; ?> value="blank">Blank</option>
                            <option <?php echo ('messages'==$options['theme'])?'selected':''; ?> value="messages">For messages</option>
                        </optgroup>
                        <optgroup label="Extras themes">
                            <?php
                            $themes = newsletter_get_extras_themes();

                            foreach ($themes as $theme) {
                                echo '<option ' .  (('$'.$theme)==$options['theme']?'selected':'') . ' value="$' . $theme . '">' . $theme . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="Custom themes">
                            <?php
                            $themes = newsletter_get_themes();

                            foreach ($themes as $theme) {
                                echo '<option ' .  (('*'.$theme)==$options['theme']?'selected':'') . ' value="*' . $theme . '">' . $theme . '</option>';
                            }
                            ?>
                        </optgroup>
                    </select>
                    <br />
                    <?php _e('Selected theme has to be one with {message} tag inside, tag that will be replaced with messages. Use the blank theme to send messages as you see them in the editor.', 'newsletter'); ?>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>




        <h3><?php _e('Subscription', 'newsletter'); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <?php $nc->checkbox('noname', __('Do not ask the user name, only email.', 'newsletter')); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Subscription form page', 'newsletter'); ?></th>
                <td>
                    <?php $nc->editor('subscription_text'); ?>
                    <br />
                    <?php _e('This is the text showed to subscriber before the subscription form which is added automatically.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Successfully subscription page', 'newsletter'); ?></th>
                <td>
                    <?php $nc->editor('subscribed_text'); ?>
                    <br />
                    <?php _e('This is the text showed to a user who has pressed "subscribe me" on the previous
step informing that an email to confirm subscription has just been sent. Remeber
the user to check the spam folder and to follow the email instructions.<br />
Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> the user email.', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>



        <h3><?php _e('Confirmation (double opt-in)', 'newsletter'); ?></h3>

        <?php _e('<p>Email sent to the user to confirm his subscription, the successful confirmation
page, the welcome email.</p>', 'newsletter'); ?>

        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <?php $nc->checkbox('noconfirmation', __('Do not use double opt-in. If checked the subscription is direct, so subscribers will be immediately confirmed and will receive the welcome email.', 'newsletter')); ?>
                </td>
            </tr>

            <!-- CONFIRMATION EMAIL -->
            <tr valign="top">
                <th><?php _e('Confirmation email', 'newsletter'); ?></th>
                <td>
                    <?php $nc->email('confirmation'); ?>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> the user name; <strong>{subscription_confirm_url}</strong>
confirmation URL to be clicked by the user to confirm his subscription;
<strong>{unsubscription_url}</strong> the unsubscription link', 'newsletter'); ?>
                </td>
            </tr>

        </table>

        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>



        <h3><?php _e('Welcome message', 'newsletter'); ?></h3>
        <?php _e('<p>Users jump directly to this step if you disabled the double opt-in step.</p>', 'newsletter'); ?>

        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Welcome message', 'newsletter'); ?></th>
                <td>
                    <?php $nc->editor('confirmed_text'); ?>
                    <br />
                    <?php _e('Showed when the user follow the confirmation URL sent to him with previous email
settings or if signed up directly with no double opt-in process.
<br />
Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> for the
user email; <strong>{token}</strong> the subscriber unique token', 'newsletter'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th><?php _e('Conversion tracking code', 'newsletter'); ?></th>
                <td>
                    <?php $nc->textarea('confirmed_tracking'); ?>
                    <br />
                    <?php _e('<strong>Works only with Newsletter Extras installed</strong>', 'newsletter'); ?>
                    <br />
                    <?php _e('The code is injected AS-IS in welcome page and can be used to track conversion
(you can use PHP if needed). Conversion code is usually supply by tracking services,
like Google AdWords, Google Analytics and so on.', 'newsletter'); ?>
                </td>
            </tr>

            <!-- WELCOME/CONFIRMED EMAIL -->
            <tr valign="top">
                <th>
                    <?php _e('Welcome email<br /><small>The right place where to put bonus content link</small>', 'newsletter'); ?>
                </th>
                <td>
                    <?php $nc->email('confirmed'); ?>
                    <br />
                    <?php _e('Tags: <strong>{id}</strong> user id; <strong>{name}</strong> user name;
<strong>{token}</strong> the subscriber unique token; <strong>{unsubscription_url}</strong>
unsubscription link', 'newsletter'); ?>
                </td>
            </tr>

        </table>

        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>



        <h3><?php _e('Unsubscription', 'newsletter'); ?></h3>
        <?php _e('<p>A user starts the unsubscription process clicking the unsubscription link in
a newsletter. This link contains the email to unsubscribe and some unique information
to avoid hacking. The user are requird to confirm the unsubscription: this is the last
step where YOU can communicate with you almost missed user.</p>', 'newsletter'); ?>

        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Unsubscription message', 'newsletter'); ?></th>
                <td>
                    <?php $nc->editor('unsubscription_text'); ?>
                    <br />
                    <?php _e('This text is show to users who click on a "unsubscription link" in a newsletter
email. You have to insert a link in the text that user can follow to confirm the
unsubscription request (see tags).
<br />
Tags: <strong>{name}</strong> user name; <strong>{email}</strong> user email;
<strong>{unsubscription_confirm_url}</strong> URL to confirm unsubscription.', 'newsletter'); ?>
                </td>
            </tr>

            <!-- Text showed to the user on successful unsubscription -->
            <tr valign="top">
                <th><?php _e('Goodbye message', 'newsletter'); ?></th>
                <td>
                    <?php $nc->editor('unsubscribed_text'); ?>
                </td>
            </tr>

            <!-- GOODBYE EMAIL -->
            <tr valign="top">
                <th><?php _e('Goodbye email', 'newsletter'); ?></th>
                <td>
                    <?php $nc->email('unsubscribed'); ?>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> user name.', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>



        <h3><?php _e('Advanced', 'newsletter'); ?></h3>

        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Disable visual editors?', 'newsletter')?></th>
                <td>
                    <?php $nc->yesno('novisual'); ?>
               </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>

    </form>
</div>
