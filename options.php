<?php

$plugin_dir = basename(dirname(__FILE__));
load_plugin_textdomain('newsletter', 'wp-content/plugins/' . $plugin_dir, $plugin_dir);


if (isset($_POST['defaults'])) {
    @include(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '') @include(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');
    update_option('newsletter', $newsletter_default_options);
}

if (isset($_POST['save'])) {
    $options = newsletter_request('options');
    update_option('newsletter', $options);
}

$options = get_option('newsletter');

?>

<?php if (!$options['novisual']) { ?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
    tinyMCE.init({
        //mode : "textareas",
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

<style>
    #newsletter h3 {
        margin-bottom: 0px;
        margin-top: 30px;
    }
    #newsletter h4 {
        font-size: 1.3em;
        border-bottom: 1px solid #999;
    }
    #newsletter .form-table {
        border: 1px solid #999;
        background-color: #f4f4f4;
    }
</style>

<div class="wrap" id="newsletter">

    <h2>Newsletter</h2>

    <?php require_once 'header.php'; ?>

    <p>
        Questions, help, critiques and whatever else <a target="_blank" href="http://www.satollo.net/plugins/newsletter">click here</a>!
    </p>

    <form method="post" action="">
        <input type="hidden" value="<?php echo NEWSLETTER; ?>" name="version"/>


        <h3><?php _e('Sender and subscription page', 'newsletter'); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Sender email', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[from_email]" type="text" size="50" value="<?php echo htmlspecialchars($options['from_email'])?>"/>
                    <br />
                    <?php _e('Newsletter sender email address: the address subscribers will see the newsletters coming from.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Sender name', 'newsletter'); ?></th>
                <td>
                    <input name="options[from_name]" type="text" size="50" value="<?php echo htmlspecialchars($options['from_name'])?>"/>
                    <br />
                    <?php _e('The name of the newsletter sender subscribers will see on incoming email.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Subscription page URL', 'newsletter'); ?></th>
                <td>
                    <input name="options[url]" type="text" size="50" value="<?php echo htmlspecialchars($options['url'])?>"/>
                    <br />
                    <?php _e('This is the page where you placed the <strong>[newsletter]</strong> short tag.','newsletter'); ?>
                    (<a href="http://www.satollo.net/plugins/newsletter"><?php _e('Read more on plugin official page', 'newsletter'); ?></a>)
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save','newsletter'); ?>"/>
        </p>




        <h3><?php _e('Subscription form', 'newsletter'); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[noname]" value="1" <?php echo isset($options['noname'])?'checked':''; ?> />
                    <?php _e('Do not ask the user name, only email.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Subscription form page', 'newsletter'); ?></th>
                <td>
                    <textarea class="visual" id="subscription_text" name="options[subscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['subscription_text'])?></textarea>
                    <br />
                    <?php _e('This is the text showed to subscriber before the subscription form which is added automatically.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Successfully subscription page', 'newsletter'); ?></th>
                <td>
                    <textarea class="visual" name="options[subscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['subscribed_text'])?></textarea>
                    <br />
                    <?php _e('This is the text showed to a user who has pressed "subscribe me" on the previous step
                    informing that an email to confirm subscription has just been sent. Remeber the user
                    to check the spam folder and to follow the email instructions.<br />
                    Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> the user email.', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save','newsletter'); ?>"/>
        </p>



        <h3><?php _e('Confirmation (double opt-in)', 'newsletter'); ?></h3>

        <p><?php _e('Email sent to the user to confirm his subscription, the successful confirmation
        page, the welcome email.', 'newsletter'); ?></p>

        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[noconfirmation]" value="1" <?php echo isset($options['noconfirmation'])?'checked':''; ?> />
                    <?php _e('Do not use double opt-in. If checked the subscription is direct, so
                    subscribers will be immediately confirmed and will receive the welcome email.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><label for="options[confirmation_subject]"><?php _e('Confirmation email subject', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[confirmation_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmation_subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> the user name.
                </td>
            </tr>
            <tr valign="top">
                <th><label for="options[confirmation_message]"><?php _e('Confirmation email message', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[confirmation_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmation_message'])?></textarea>
                    <br />
                    Tags: <strong>{name}</strong> the user name; <strong>{subscription_confirm_url}</strong>
                    confirmation URL to be clicked by the user to confirm his subscription;
                    <strong>{unsubscription_url}</strong> URL to be clickd to remove the subscription (confirmed
                    or not).
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>



        <h3><?php _e('Welcome message', 'newsletter'); ?></h3>

        <p><?php _e('Users jump directly to this step if you disabled the double opt-in step.', 'newsletter'); ?></p>

        <table class="form-table">
            <tr valign="top">
                <th><label for="options[confirmed_text]">Successful confirmation page</label></th>
                <td>
                    <textarea class="visual" name="options[confirmed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_text'])?></textarea>
                    <br />
                    Showed when the user follow the confirmation URL sent to him with previous email settings or if signed up
                    directly with no double opt-in process.
                    <br />
                    Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> for the user email;
                    <strong>{token}</strong> the subscriber unique token
                </td>
            </tr>

            <tr valign="top">
                <th>Conversion tracking code</th>
                <td>
                    <?php if (newsletter_has_extras()) { ?>
                    <textarea name="options[confirmed_tracking]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_tracking'])?></textarea>
                    <?php } else { ?>
                    <p><strong>Available with Newsletter Extras package</strong></p>
                    <?php } ?>
                    <br />
                    That code is injected AS-IS in welcome page and can be used to track conversion (you can use PHP if needed).
                    Conversion code is usually supply by tracking services, like Google AdWords, Google Analytics and so on.
                </td>
            </tr>
            <tr valign="top"><td colspan="2"><h4>Welcome email</h4></td></tr>
            <tr valign="top">
                <th>Welcome email subject</th>
                <td>
                    <input name="options[confirmed_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmed_subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> user name.
                </td>
            </tr>
            <tr valign="top">
                <th><label>Welcome email message</label></th>
                <td>
                    <textarea class="visual" name="options[confirmed_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_message'])?></textarea>
                    <br />
                    Tags: <strong>{name}</strong> user name; <strong>{token}</strong> the subscriber unique token;
                    <strong>{unsubscription_url}</strong> URL to be clickd to remove the subscription (confirmed
                    or not).
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>



        <h3>Unsubscription</h3>

        <p>A user starts the unsubscription process clicking the unsubscription link in a newsletter. This lkink contains the email to unsubscribe and some
            unique information to avoid hacking. The user are requird to confirm the unsubscription: this is the last step where YOU can communicate with you
            almost missed user.</p>

        <table class="form-table">
            <tr valign="top">
                <th><label for="options[unsubscription_text]">Unsubscription text</label></th>
                <td>
                    <textarea class="visual" name="options[unsubscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_text'])?></textarea>
                    <br />
                    This text is show to users who click on a "unsubscription link" in a newsletter email.
                    You have to insert a link in the text that user can follow to confirm the unsubscription
                    request (see tags).
                    <br />
                    Tags: <strong>{name}</strong> user name; <strong>{email}</strong> user email; <strong>{unsubscription_confirm_url}</strong> URL to confirm unsubscription.
                </td>
            </tr>

            <!-- Text showed to the user on successful unsubscription -->
            <tr valign="top">
                <th><label for="options[unsubscribed_text]">Good bye text</label></th>
                <td>
                    <textarea class="visual" name="options[unsubscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscribed_text'])?></textarea>
                    Latest message showed to the user to say "good bye".
                    <br />
                    Tags: none.
                </td>
            </tr>

            <tr valign="top">
                <th><label>Goodbye email subject</label></th>
                <td>
                    <input name="options[unsubscribed_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscribed_subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> user name.
                </td>
            </tr>
            <tr valign="top">
                <th><label>Goodbye email message</label></th>
                <td>
                    <textarea class="visual" name="options[unsubscribed_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscribed_message'])?></textarea>
                    <br />
                    Tags: <strong>{name}</strong> user name.
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>

        <!--
<h2>Unsubscription for mass mail mode</h2>
<p>This section is not working!</p>

<table class="form-table">
<tr valign="top">
<th><label>Unsubscription text</label></th>
<td>
<textarea name="options[unsubscription_mm_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_mm_text'])?></textarea>
</td>
</tr>

<tr valign="top">
<th><label>Unsubscription error</label></th>
<td>
<input name="options[unsubscription_mm_error]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_error'])?>"/>
<br />
Shown with the unsubscription message then the email to unsubscribe is not found.
</td>
</tr>
<tr valign="top">
<th><label>"Email to unsubscribe" label</label></th>
<td>
<input name="options[unsubscription_mm_email_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_email_label'])?>"/>
<br />
Used when the newsletter is sent with "mass mail" mode.
</td>
</tr>
<tr valign="top">
<th><label>"Confirm unsubscription" label</label></th>
<td>
<input name="options[unsubscription_mm_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_label'])?>"/>
<br />
The button text to confirm unsubscription or to send an unsubscription request for the specified
email address when "mass mail" mode is used for sending newsletters.
</td>
</tr>
<tr valign="top">
<th><label>Unsubscription end text</label></th>
<td>
<textarea name="options[unsubscription_mm_end_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_mm_end_text'])?></textarea>
<br />
This text is shown when a user type in an email to be removed and the confirmation email
has been sent.
</td>
</tr>

<tr valign="top">
<th><label for="options[unsubscription_subject]">Unsubscription email subject</label></th>
<td>
<input name="options[unsubscription_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_subject'])?>"/>
</td>
</tr>            
<tr valign="top">
<th><label for="options[unsubscription_message]">Unsubscription email message</label></th>
<td>
<textarea name="options[unsubscription_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_message'])?></textarea>
<br />
Email sent to confirm unsubscription when the request is made specifying an email
address to remove. Use {unsubscription_link} to place the link where the user has
to click on; use {unsubscription_url} toplace the plain unsubscription URL.
</td>
</tr> 
<tr valign="top">
<th><label for="options[unsubscription_link]">Unsubscription link text</label></th>
<td>
<input name="options[unsubscription_link]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_link'])?>"/>
<br />
The text of the link for unsubscription to be placed in the unsubscription email.
</td>
</tr> 
</table>
        -->
        <!--
                <h2>Lists</h2>
                <table class="form-table">
                    <tr valign="top">
                        <th>&nbsp;</th>
                        <td>
                            List 0 is the general one<br />
        <?php for ($i=1; $i<=10; $i++) { ?>
                            List <?php echo $i; ?> <input name="options[list_<?php echo $i; ?>]" type="text" size="50" value="<?php echo htmlspecialchars($options['list_' . $i])?>"/>
                            <br />
        <?php } ?>

                        </td>
                    </tr>
                </table>
        -->

        <h3>Advanced</h3>

        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[logs]" value="1" <?php echo $options['logs']!= null?'checked':''; ?> />
                    write logs
                </td>
            </tr>
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[novisual]" value="1" <?php echo $options['novisual']!= null?'checked':''; ?> />
                    <label for="options[novisual]">do not use visual editors</label>
                </td>
            </tr>
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[editor]" value="1" <?php echo $options['editor']!= null?'checked':''; ?> />
                    <label for="options[editor]">allow editors to user the newsletter plugin</label>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>

        <!--
                <h2><?php _e('Really advanced options', 'newsletter'); ?></h2>

                <table class="form-table">
                    <tr valign="top">
                        <th>&nbsp;</th>
                        <td>
                            <input type="checkbox" name="options[subscription_form_enabled]" value="1" <?php echo $options['subscription_form_enabled']!= null?'checked':''; ?> />
                            <label for="options[subscription_form]"><?php _e('Use the custom subscription form below', 'newsletter'); ?></label>
                            <br />
                            <textarea cols="75" rows="5" name="options[subscription_form]"><?php echo htmlspecialchars($options['subscription_form'])?></textarea>
                        </td>
                    </tr>
                </table>
                <p class="submit">
                    <input class="button" type="submit" name="save" value="Save"/>
                </p>
        -->

        <!--
        <h2>Zanzara client</h2>
        <p>Obsolete</p>
        <table class="form-table">
            <tr valign="top">
                <th><label>Export key</label></th>
                <td>
                    <input name="options[key]" type="text" size="50" value="<?php echo htmlspecialchars($options['key'])?>"/>
                    <br />
                    Do not search for Zanzara, is a my private software
                </td>
            </tr>
            <tr>
                <th><label>SMTP address</label></th>
                <td>
                    <input name="options[smtp_host]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_host'])?>"/>
                </td>
            </tr>
            <tr>
                <th><label>SMTP user</label></th>
                <td>
                    <input name="options[smtp_user]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_user'])?>"/>
                </td>
            </tr>
            <tr>
                <th><label>SMTP password</label></th>
                <td>
                    <input name="options[smtp_password]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_password'])?>"/>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
            <input class="button" type="submit" name="defaults" value="Revert to defaults"/>
        </p>
        -->

    </form>
</div>
