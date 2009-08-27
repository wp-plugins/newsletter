<?php

if (isset($_POST['defaults']))
{
    @include_once(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '') @include_once(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');
    update_option('newsletter', $newsletter_default_options);
}

if (isset($_POST['save']))
{
    $options = newsletter_request('options');
    update_option('newsletter', $options);
}

$options = get_option('newsletter');

?>

<?php if (!$options['novisual']) { ?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>

<script type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        theme_advanced_disable : "styleselect",
        relative_urls : false,
        remove_script_host : false,
        document_base_url : "<?php echo get_option('home'); ?>/"

    });
</script>
<?php } ?>

<div class="wrap">
    <form method="post">
        <h2>Newsletter</h2>

        <p><strong>TAGS HAVE BEEN CHANEGED! PLEASE CORRECT YOUR LINKS IN TEXTS AND EMAILS.</strong></p>
        
        <p><strong>First time user?</strong> <a target="_blank" href="http://www.satollo.net/plugins/newsletter">
        Read how to use this plugin <strong>carefully</strong></a>. It's not as simple as it
        appears.</p>

        <p>To ask questions <a href="http://www.satollo.net/newsletter-help">leave a comment on 
        this page</a>. To write me: <a href="mailto:info@satollo.net">info@satollo.net</a>.</p>
        
        <p>
            My other plugins:
            <a href="http://www.satollo.net/plugins/post-layout">Post Layout</a>,
            <a href="http://www.satollo.net/plugins/post-layout-pro">Post Layout Pro</a>,
            <a href="http://www.satollo.com/english/wordpress/feed-layout">Feed Layout</a>,
            <a href="http://www.satollo.com/plugins/hyper-cache">Hyper Cache</a>,
            <a href="http://www.satollo.com/plugins/comment-notifier">Comment Notifier</a>.
        </p>

        <h3>Sender and subscription page</h3>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="options[from_email]">Sender email</label></th>
                <td>
                    <input name="options[from_email]" type="text" size="50" value="<?php echo htmlspecialchars($options['from_email'])?>"/>
                    <br />
                    Newsletter sender email address: the email address subscribers will see the email coming from.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="options[from_name]">Sender name</label></th>
                <td>
                    <input name="options[from_name]" type="text" size="50" value="<?php echo htmlspecialchars($options['from_name'])?>"/>
                    <br />
                    The name of the newsletter sender subscribers will see on incoming email. Please, use english characters.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Subscription page URL</label></th>
                <td>
                    <input name="options[url]" type="text" size="50" value="<?php echo htmlspecialchars($options['url'])?>"/>
                    <br />
                    This is the page where you placed the <strong>[newsletter]</strong> short tag.
                    Have you created the newsletter
                    subscription page as explained in the
                    <a href="http://www.satollo.net/plugins/newsletter">newsletter documentation</a>?
                </td>
            </tr>
        </table>

        <h3>Subscription form</h3>
        <p>Remeber to create the subscription page and to configure the subscription page URL above.</p>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Introduction</label></th>
                <td>
                    <textarea id="subscription_text" name="options[subscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['subscription_text'])?></textarea>
                    <br />
                    This is the text showed to subscriber before the subscription form which is added automatically.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Success subscription text</label></th>
                <td>
                    <textarea name="options[subscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['subscribed_text'])?></textarea>
                    <br />
                    This is the text showed to a user who has pressed "subscribe me" on the previous step
                    informing that an email to confirm subscription has just been sent. Remeber the user
                    to check the spam folder and to follow the email instructions.<br />
                    Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> the user email.
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>

        <h3>Confirmation (double opt-in)</h3>
        <p>Email sent to the user to confirm his subscription, the successful confirmation
        text, the welcome email.</p>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="options[confirmation_subject]">Confirmation email subject</label></th>
                <td>
                    <input name="options[confirmation_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmation_subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> the user name.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="options[confirmation_message]">Confirmation email message</label></th>
                <td>
                    <textarea name="options[confirmation_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmation_message'])?></textarea>
                    <br />
                    Tags: <strong>{name}</strong> the user name; <strong>{subscription_confirm_url}</strong>
                    confirmation URL to be clicked by the user to confirm his subscription.
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label for="options[confirmed_text]">Successful confirmation page</label></th>
                <td>
                    <textarea name="options[confirmed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_text'])?></textarea>
                    <br />
                    Showed when the user follow the confirmation URL sent to him with previous email settings.
                    <br />
                    Tags: <strong>{name}</strong> the user name; <strong>{email}</strong> for the user email.
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Welcome email subject</label></th>
                <td>
                    <input name="options[confirmed_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmed_subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> user name.
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Welcome email message</label></th>
                <td>
                    <textarea name="options[confirmed_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_message'])?></textarea>
                    <br />
                    Tags: <strong>{name}</strong> user name.
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
                <th scope="row"><label for="options[unsubscription_text]">Unsubscription text</label></th>
                <td>
                    <textarea name="options[unsubscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_text'])?></textarea>
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
                <th scope="row"><label for="options[unsubscribed_text]">Good bye text</label></th>
                <td>
                    <textarea name="options[unsubscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscribed_text'])?></textarea>
                    Latest message showed to the user to say "good bye".
                    <br />
                    Tags: none.
                </td>
            </tr>
        </table>

        <!--
<h2>Unsubscription for mass mail mode</h2>
<p>This section is not working!</p>

<table class="form-table">
<tr valign="top">
<th scope="row"><label>Unsubscription text</label></th>
<td>
<textarea name="options[unsubscription_mm_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_mm_text'])?></textarea>
</td>
</tr>

<tr valign="top">
<th scope="row"><label>Unsubscription error</label></th>
<td>
<input name="options[unsubscription_mm_error]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_error'])?>"/>
<br />
Shown with the unsubscription message then the email to unsubscribe is not found.
</td>
</tr>
<tr valign="top">
<th scope="row"><label>"Email to unsubscribe" label</label></th>
<td>
<input name="options[unsubscription_mm_email_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_email_label'])?>"/>
<br />
Used when the newsletter is sent with "mass mail" mode.
</td>
</tr>
<tr valign="top">
<th scope="row"><label>"Confirm unsubscription" label</label></th>
<td>
<input name="options[unsubscription_mm_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_mm_label'])?>"/>
<br />
The button text to confirm unsubscription or to send an unsubscription request for the specified
email address when "mass mail" mode is used for sending newsletters.
</td>
</tr>
<tr valign="top">
<th scope="row"><label>Unsubscription end text</label></th>
<td>
<textarea name="options[unsubscription_mm_end_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_mm_end_text'])?></textarea>
<br />
This text is shown when a user type in an email to be removed and the confirmation email
has been sent.
</td>
</tr>

<tr valign="top">
<th scope="row"><label for="options[unsubscription_subject]">Unsubscription email subject</label></th>
<td>
<input name="options[unsubscription_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_subject'])?>"/>
</td>
</tr>            
<tr valign="top">
<th scope="row"><label for="options[unsubscription_message]">Unsubscription email message</label></th>
<td>
<textarea name="options[unsubscription_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_message'])?></textarea>
<br />
Email sent to confirm unsubscription when the request is made specifying an email
address to remove. Use {unsubscription_link} to place the link where the user has
to click on; use {unsubscription_url} toplace the plain unsubscription URL.
</td>
</tr> 
<tr valign="top">
<th scope="row"><label for="options[unsubscription_link]">Unsubscription link text</label></th>
<td>
<input name="options[unsubscription_link]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_link'])?>"/>
<br />
The text of the link for unsubscription to be placed in the unsubscription email.
</td>
</tr> 
</table>
        -->
        <h2>Advanced</h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[sendmail]" value="1" <?php echo $options['sendmail']!= null?'checked':''; ?> />
                    <label for="options[sendmail]">add -f parameter to send mail to correct the Return-Path</label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[logs]" value="1" <?php echo $options['logs']!= null?'checked':''; ?> />
                    <label for="options[logs]">write logs</label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[novisual]" value="1" <?php echo $options['novisual']!= null?'checked':''; ?> />
                    <label for="options[novisual]">do not use visual editors</label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row">&nbsp;</th>
                <td>
                    <input type="checkbox" name="options[editor]" value="1" <?php echo $options['editor']!= null?'checked':''; ?> />
                    <label for="options[editor]">allow editors to user the newsletter plugin</label>
                </td>
            </tr>
        </table>

        <h2>Zanzara client</h2>

        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Export key</label></th>
                <td>
                    <input name="options[key]" type="text" size="50" value="<?php echo htmlspecialchars($options['key'])?>"/>
                    <br />
                    Do not search for Zanzara, is a my private software
                </td>
            </tr>
            <tr>
                <th scope="row"><label>SMTP address</label></th>
                <td>
                    <input name="options[smtp_host]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_host'])?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>SMTP user</label></th>
                <td>
                    <input name="options[smtp_user]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_user'])?>"/>
                </td>
            </tr>
            <tr>
                <th scope="row"><label>SMTP password</label></th>
                <td>
                    <input name="options[smtp_password]" type="text" size="50" value="<?php echo htmlspecialchars($options['smtp_password'])?>"/>
                </td>
            </tr>
            </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
            <input class="button" type="submit" name="defaults" value="Revert to defaults"/>
        </p>
    </form>

</div>
