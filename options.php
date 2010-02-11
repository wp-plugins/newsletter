<?php

$options = get_option('newsletter');

if (!isset($options['no_translation'])) {
	$plugin_dir = basename(dirname(__FILE__));
	load_plugin_textdomain('newsletter', 'wp-content/plugins/' . $plugin_dir . '/languages/');
}

if (isset($_POST['defaults'])) {
    @include(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '') @include(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');
    update_option('newsletter', $newsletter_default_options);
}

if (isset($_POST['save'])) {
    $options = newsletter_request('options');
    update_option('newsletter', $options);
}

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
        background-color: #fff;
    }
</style>

<div class="wrap" id="newsletter">

    <h2><?php _e('Newsletter', 'newsletter'); ?></h2>

    <?php require_once 'header.php'; ?>

    <p>
        <?php _e('Questions, help, critiques and whatever else <a target="_blank" href="http://www.satollo.net/plugins/newsletter">click here</a>!', 'newsletter'); ?>
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
            <tr valign="top">
                <th>Theme to use for messages</th>
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
                    Selected theme has to be one with {message} tag inside, tag that will be replaced
                    with messages. Use the blank theme to send messages as you see them in the editor.
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
                    <?php _e('This is the text showed to a user who has pressed "subscribe me" on the previous step informing that an email to confirm subscription has just been sent. Remeber the user to check the spam folder and to follow the email instructions.<br />Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> the user email.', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save','newsletter'); ?>"/>
        </p>



        <h3><?php _e('Confirmation', 'newsletter'); ?> (<?php _e('double opt-in', 'newsletter'); ?>)</h3>

        <p><?php _e('Email sent to the user to confirm his subscription, the successful confirmation page, the welcome email.', 'newsletter'); ?></p>

        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[noconfirmation]" value="1" <?php echo isset($options['noconfirmation'])?'checked':''; ?> />
                    <?php _e('Do not use double opt-in. If checked the subscription is direct, so subscribers will be immediately confirmed and will receive the welcome email.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><label for="options[confirmation_subject]"><?php _e('Confirmation email subject', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[confirmation_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmation_subject'])?>"/>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> the user name.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><label for="options[confirmation_message]"><?php _e('Confirmation email message', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[confirmation_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmation_message'])?></textarea>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> the user name; <strong>{subscription_confirm_url}</strong>confirmation URL to be clicked by the user to confirm his subscription.', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
        </p>



        <h3><?php _e('Welcome message', 'newsletter'); ?></h3>

        <p><?php _e('Users jump directly to this step if you disabled the double opt-in step.', 'newsletter'); ?></p>

        <table class="form-table">
            <tr valign="top">
                <th><label for="options[confirmed_text]"><?php _e('Successful confirmation page', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[confirmed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_text'])?></textarea>
                    <br />
                    <?php _e('Showed when the user follow the confirmation URL sent to him with previous email settings or if signed up directly with no double opt-in process.', 'newsletter'); ?>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> for the user email; <strong>{token}</strong> the subscriber unique token', 'newsletter'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th><?php _e('Conversion tracking code', 'newsletter'); ?></th>
                <td>
                    <?php if (newsletter_has_extras()) { ?>
                    <textarea name="options[confirmed_tracking]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_tracking'])?></textarea>
                    <?php } else { ?>
                    <p><strong><?php _e('Available with Newsletter Extras package', 'newsletter'); ?></strong></p>
                    <?php } ?>
                    <br />
                    <?php _e('That code is injected AS-IS in welcome page and can be used to track conversion (you can use PHP if needed). Conversion code is usually supply by tracking services, like Google AdWords, Google Analytics and so on.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top"><td colspan="2"><h4><?php _e('Welcome email', 'newsletter'); ?></h4></td></tr>
            <tr valign="top">
                <th><?php _e('Welcome email subject', 'newsletter'); ?></th>
                <td>
                    <input name="options[confirmed_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmed_subject'])?>"/>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> user name.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><label><?php _e('Welcome email message', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[confirmed_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_message'])?></textarea>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> user name; <strong>{token}</strong> the subscriber unique token', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
        </p>



        <h3><?php _e('Unsubscription', 'newsletter'); ?></h3>

        <p><?php _e('A user starts the unsubscription process clicking the unsubscription link in a newsletter. This lkink contains the email to unsubscribe and some unique information to avoid hacking. The user are requird to confirm the unsubscription: this is the last step where YOU can communicate with you almost missed user.', 'newsletter'); ?></p>

        <table class="form-table">
            <tr valign="top">
                <th><label for="options[unsubscription_text]"><?php _e('Unsubscription text', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[unsubscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_text'])?></textarea>
                    <br />
                    <?php _e('This text is show to users who click on a "unsubscription link" in a newsletter email. You have to insert a link in the text that user can follow to confirm the unsubscription request (see tags).', 'newsletter'); ?>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> user name; <strong>{email}</strong> user email; <strong>{unsubscription_confirm_url}</strong> URL to confirm unsubscription.', 'newsletter'); ?>
                </td>
            </tr>

            <!-- Text showed to the user on successful unsubscription -->
            <tr valign="top">
                <th><label for="options[unsubscribed_text]"><?php _e('Good bye text', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[unsubscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscribed_text'])?></textarea><br />
                    <?php _e('Latest message showed to the user to say "good bye".', 'newsletter'); ?>
                    <br />
                    <?php _e('Tags: none.', 'newsletter'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th><label><?php _e('Goodbye email subject', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[unsubscribed_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscribed_subject'])?>"/>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> user name.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><label><?php _e('Goodbye email message', 'newsletter'); ?></label></th>
                <td>
                    <textarea class="visual" name="options[unsubscribed_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscribed_message'])?></textarea>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> user name.', 'newsletter'); ?>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
        </p>

        <!--
<h2><?php _e('Unsubscription for mass mail mode', 'newsletter'); ?></h2>
<p><?php _e('This section is not working!', 'newsletter'); ?></p>

<table class="form-table">
<tr valign="top">
<th><label><?php _e('Unsubscription text', 'newsletter'); ?></label></th>
<td>
<textarea name="options[unsubscription_mm_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_mm_text'])?></textarea>
</td>
</tr>

<tr valign="top">
<th><label><?php _e('Unsubscription error', 'newsletter'); ?></label></th>
<td>
<input name="options[unsubscription_mm_error]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_error'])?>"/>
<br />
<?php _e('Shown with the unsubscription message then the email to unsubscribe is not found.', 'newsletter'); ?>
</td>
</tr>
<tr valign="top">
<th><label><?php _e('"Email to unsubscribe" label', 'newsletter'); ?></label></th>
<td>
<input name="options[unsubscription_mm_email_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_email_label'])?>"/>
<br />
Used when the newsletter is sent with "mass mail" mode.
</td>
</tr>
<tr valign="top">
<th><label><?php _e('"Confirm unsubscription" label', 'newsletter'); ?></label></th>
<td>
<input name="options[unsubscription_mm_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_label'])?>"/>
<br />
<?php _e('The button text to confirm unsubscription or to send an unsubscription request for the specified email address when "mass mail" mode is used for sending newsletters.', 'newsletter'); ?>
</td>
</tr>
<tr valign="top">
<th><label><?php _e('Unsubscription end text', 'newsletter'); ?></label></th>
<td>
<textarea name="options[unsubscription_mm_end_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_mm_end_text'])?></textarea>
<br />
<?php _e('This text is shown when a user type in an email to be removed and the confirmation email has been sent.', 'newsletter'); ?>
</td>
</tr>

<tr valign="top">
<th><label for="options[unsubscription_subject]"><?php _e('Unsubscription email subject', 'newsletter'); ?></label></th>
<td>
<input name="options[unsubscription_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_subject'])?>"/>
</td>
</tr>
<tr valign="top">
<th><label for="options[unsubscription_message]"><?php _e('Unsubscription email message', 'newsletter'); ?></label></th>
<td>
<textarea name="options[unsubscription_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_message'])?></textarea>
<br />
<?php _e('Email sent to confirm unsubscription when the request is made specifying an email address to remove. Use {unsubscription_link} to place the link where the user has to click on; use {unsubscription_url} toplace the plain unsubscription URL.', 'newsletter'); ?>
</td>
</tr>
<tr valign="top">
<th><label for="options[unsubscription_link]"><?php _e('Unsubscription link text', 'newsletter'); ?></label></th>
<td>
<input name="options[unsubscription_link]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_link'])?>"/>
<br />
<?php _e('The text of the link for unsubscription to be placed in the unsubscription email.', 'newsletter'); ?>
</td>
</tr>
</table>
        -->
        <!--
                <h2><?php _e('Lists', 'newsletter'); ?></h2>
                <table class="form-table">
                    <tr valign="top">
                        <th>&nbsp;</th>
                        <td>
                            <?php _e('List 0 is the general one', 'newsletter'); ?><br />
        <?php for ($i=1; $i<=10; $i++) { ?>
                            <?php _e('List', 'newsletter'); ?> <?php echo $i; ?> <input name="options[list_<?php echo $i; ?>]" type="text" size="50" value="<?php echo htmlspecialchars($options['list_' . $i])?>"/>
                            <br />
        <?php } ?>

                        </td>
                    </tr>
                </table>
        -->

        <h3><?php _e('Advanced', 'newsletter'); ?></h3>

        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[no_translation]" value="1" <?php echo $options['no_translation']!= null?'checked':''; ?> />
                    Show always in original english
                </td>
            </tr>
            <tr valign="top">
                <th>Logging</th>
                <td>
                    <select name="options[logs]">
                            <option <?php echo (0==$options['logs'])?'selected':''; ?> value="0">None</option>
                            <option <?php echo (1==$options['logs'])?'selected':''; ?> value="1">Normal</option>
                            <option <?php echo (2==$options['logs'])?'selected':''; ?> value="2">Debug</option>
                    </select>
                    (debug level saves user data on file system, use only to debug problems)
                </td>
            </tr>
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[novisual]" value="1" <?php echo $options['novisual']!= null?'checked':''; ?> />
                    <label for="options[novisual]"><?php _e('do not use visual editors', 'newsletter'); ?></label>
                </td>
            </tr>
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[editor]" value="1" <?php echo $options['editor']!= null?'checked':''; ?> />
                    <label for="options[editor]"><?php _e('allow editors to user the newsletter plugin', 'newsletter'); ?></label>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
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
                    <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
                </p>
        -->

        <!--
        <h2><?php _e('Zanzara client', 'newsletter'); ?></h2>
        <p><?php _e('Obsolete', 'newsletter'); ?></p>
        <table class="form-table">
            <tr valign="top">
                <th><label><?php _e('Export key', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[key]" type="text" size="50" value="<?php echo htmlspecialchars($options['key'])?>"/>
                    <br />
                    <?php _e('Do not search for Zanzara, is a my private software', 'newsletter'); ?>
                </td>
            </tr>
            <tr>
                <th><label><?php _e('SMTP address', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[smtp_host]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_host'])?>"/>
                </td>
            </tr>
            <tr>
                <th><label><?php _e('SMTP user', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[smtp_user]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_user'])?>"/>
                </td>
            </tr>
            <tr>
                <th><label><?php _e('SMTP password', 'newsletter'); ?></label></th>
                <td>
                    <input name="options[smtp_password]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_password'])?>"/>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
            <input class="button" type="submit" name="defaults" value="<?php _e('Revert to defaults', 'newsletter'); ?>"/>
        </p>
        -->

    </form>
</div>
