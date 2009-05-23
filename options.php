<?php

if (isset($_POST['save']))
{
    $options = newsletter_request('options');
    foreach ($options as $key=>$value) if ($value == '') unset($options[$key]);
    update_option('newsletter', $options);
}

// Merging between the default options and te user settings: $newsletter_fields is
// initialized in plugin.php.
$options = get_option('newsletter');
if ($options) $options = array_merge($newsletter_default_options, $options);
else $options = $newsletter_default_options;

if ($options['from_name'] == '') $options['from_name'] = get_option('blogname');
if ($options['from_email'] == '') $options['from_email'] = get_option('admin_email');

?>

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

<div class="wrap">
    <form method="post">
        <h2>Newsletter</h2>
        <p>If you want to revert a fields to the original value, empty it and save.</p>
<p>To write me:
    <a href="mailto:info@satollo.com">info@satollo.com</a>. To <strong>donate</strong>: <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=2545483">here</a>.</p>
    <p>
        My other plugins:
        <a href="http://www.satollo.com/english/wordpress/post-layout">Post Layout</a>,
        <a href="http://www.satollo.com/english/wordpress/feed-layout">Feed Layout</a>,
        <a href="http://www.satollo.com/english/wordpress/hyper-cache">Hyper Cache</a>,
        <a href="http://www.satollo.com/english/wordpress/comment-notifier">Comment Notifier</a>.
    </p>
        
        <h2>Sender and subscription page</h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="options[from_email]">Sender email</label></th>
                <td>
                    <input name="options[from_email]" type="text" size="50" value="<?php echo htmlspecialchars($options['from_email'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label for="options[from_name]">Sender name</label></th>
                <td>
                    <input name="options[from_name]" type="text" size="50" value="<?php echo htmlspecialchars($options['from_name'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Full URL of the newsletter page</label></th>
                <td>
                    <input name="options[url]" type="text" size="50" value="<?php echo htmlspecialchars($options['url'])?>"/>
                    <br />
                    This is the page where you placed the [newsletter] short tag.
                </td>
            </tr>              
        </table>
        
        <h2>Subscription form</h2>
        <!--
        <table>
        <tr><td>
        -->
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label>Introduction</label></th>
                <td>
                    <textarea id="subscription_text" name="options[subscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['subscription_text'])?></textarea>
                    <br />
                    This is the text showed to subscriber before the subscription form. Be nice with your subscriber!
                </td>
            </tr>  
<!--            
            <tr valign="top">
                <th scope="row"><label>"Your name" label</label></th>
                <td>
                    <input id="name_label" name="options[name_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['name_label'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>"Your email" label</label></th>
                <td>
                    <input id="email_label" name="options[email_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['email_label'])?>"/>
                </td>
            </tr>            

            <tr valign="top">
                <th scope="row"><label>"Subscribe" label</label></th>
                <td>
                    <input id="subscribe_label" name="options[subscribe_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['subscribe_label'])?>"/>
                </td>
            </tr>

            <tr valign="top">
                <th scope="row"><label>Privacy text</label></th>
                <td>
                    <input id="privacy_text" name="options[privacy_text]" type="text" size="50" value="<?php echo htmlspecialchars($options['privacy_text'])?>"/>
                </td>
            </tr>
-->
            <tr valign="top">
                <th scope="row"><label>Success subscription text</label></th>
                <td>
                    <textarea name="options[subscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['subscribed_text'])?></textarea>
                </td>
            </tr> 
        </table>
        <!--
        </td>
        <td style="border-left: 1px solid #666">
            This is how the subscription form look like (even if your theme CSS can give it a different appareance)
            <div style="border: 1px solid #000">
            <div id="_subscription_text"></div>
            <table border="1">
            <tr><td id="_name_label"></td><td><input type="text" size="20"/></td></tr>
            <tr><td id="_email_label"></td><td><input type="text" size="20"/></td></tr>
            <tr><td colspan="2" id="_privacy_text"><input id="_subscribe_label" type="button" value=""/></td></tr>
            </table>
            </div>
            <input type="button" value="update" onclick="upd()"/>
            <script>
            function upd()
            {
                tinyMCE.get('subscription_text').save();
                document.getElementById('_name_label').innerHTML = document.getElementById('name_label').value;
                document.getElementById('_email_label').innerHTML = document.getElementById('email_label').value;
                document.getElementById('_subscription_text').innerHTML = document.getElementById('subscription_text').value;
                document.getElementById('_privacy_text').innerHTML = document.getElementById('privacy_text').value;
                document.getElementById('_subscribe_label').value = document.getElementById('subscribe_label').value;
            }
            </script>
        </td>
        </tr>
        </table>
        -->
        
        <h2>Confirmation (double opt-in)</h2>
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="options[confirmation_subject]">Confirmation email subject</label></th>
                <td>
                    <input name="options[confirmation_subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmation_subject'])?>"/>
                    <br />
                    {name} will be replaced with the user name
                </td>
            </tr>        
            <tr valign="top">
                <th scope="row"><label for="options[confirmation_message]">Confirmation email message</label></th>
                <td>
                    <textarea name="options[confirmation_message]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmation_message'])?></textarea>
                    <br />
                    {name} will be replaced with the user name. {confirmation_url} will be replaced with a full confirmation URL, BUT if you want to create a link
                    with the editor button, use CONFIRMATION_URL as address.                  
                </td>
            </tr> 
<!--            
            <tr valign="top">
                <th scope="row"><label>Confirm link text</label></th>
                <td>
                    <input name="options[confirmation_link]" type="text" size="50" value="<?php echo htmlspecialchars($options['confirmation_link'])?>"/>
                </td>
            </tr>        
-->            
            <tr valign="top">
                <th scope="row"><label for="options[confirmed_text]">Confirmed text</label></th>
                <td>
                    <textarea name="options[confirmed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['confirmed_text'])?></textarea>
                    <br />
                    {name} will be replaced with the user name                    
                </td>
            </tr>             
        </table>           
        
        <h2>Unsubscription</h2>
        
        <p>A user start the unsubscription process clicking the unsubscription link in a newsletter. This lkink contains the email to unsubscribe and some
        unique information to avoid hacking. The user are requird to confirm the unsubscription: this is the last step where YOU can communicate with you
        almost missed user.</p>
        
        <table class="form-table">
            <tr valign="top">
                <th scope="row"><label for="options[unsubscription_text]">Unsubscription text</label></th>
                <td>
                    <textarea name="options[unsubscription_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscription_text'])?></textarea>
                    <br />
                    This text is show to users who click on a "unsubscription link" in a email. You have to insert a link in the text containing 
                    "UNSUBSCRIPTION_CONFIRM_URL" (without double quotes).
                </td>
            </tr>
<!--            
            <tr valign="top">
                <th scope="row"><label for="options[unsubscription_label]">"Confirm unsubscription" label</label></th>
                <td>
                    <input name="options[unsubscription_label]" type="text" size="50" value="<?php echo htmlspecialchars($options['unsubscription_label'])?>"/>
                    <br />
                    The button text to confirm unsubscription or to send an unsubscription request for the specified
                    email address when "mass mail" mode is used for sending newsletters.
                </td>
            </tr>
-->            
            <!-- Text showed to the user on successful unsubscription -->
            <tr valign="top">
                <th scope="row"><label for="options[unsubscribed_text]">Unsubscribed text</label></th>
                <td>
                    <textarea name="options[unsubscribed_text]" wrap="off" rows="5" cols="75"><?php echo htmlspecialchars($options['unsubscribed_text'])?></textarea>
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

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>
    </form>

</div>
