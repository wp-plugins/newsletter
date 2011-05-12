<?php

@include_once 'commons.php';

$options = get_option('newsletter');

if ($action == 'save') {
    $options = stripslashes_deep($_POST['options']);
    $options['confirmed_url'] = trim($options['confirmed_url']);
    if ($errors == null) {
        update_option('newsletter', $options);
    }
}

if ($action == 'reset') {
    @include_once(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '') @include_once(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');
    $options = array_merge($options, $newsletter_default_options);
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
    theme_advanced_resizing : true,
    theme_advanced_statusbar_location: "bottom",
    document_base_url : "<?php echo get_option('home'); ?>/",
    content_css : "<?php echo get_option('blogurl'); ?>/wp-content/plugins/newsletter/editor.css?" + new Date().getTime()
});
</script>
    <?php } ?>

<script>
    jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // CAUTION: Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

jQuery(document).ready(function () {
    var cookie = jQuery.cookie("np-options");
    if (!cookie) cookie = "0-";
    jQuery(".wrap h3").each(function (index) {
        var div = jQuery(this).next('div');
        div.toggle(cookie.indexOf(index + "-") >= 0);
   });

   jQuery(".wrap h3").click(function () {
        var cookie = "";
        jQuery(this).next('div').toggle(500);
        jQuery(".wrap h3").each(function (index) {
            if (jQuery(this).next('div').is(":visible")) cookie += index + "-";
        });
        jQuery.cookie("np-options", cookie);
   });
});
</script>

<div class="wrap">

    <?php $nc->errors($errors); ?>

    <h2>Newsletter Subscription and Cancellation Process</h2>
    <p>
        In this panel you can configure the subscription and cancellation process, setting every message, the single or double opt in and
        even a customized subscription form.<br />
        All tags that can be used on texts below are listed under <a href="#documentation">documentation</a> paragraph.
    </p>


    <form method="post" action="">
        <?php $nc->init(); ?>

        <h3>Working mode</h3>
        <div>
        <p>Choose how the subscription process to your newsletter works.</p>
        <table class="form-table">
            <tr valign="top">
                <th>Opt In</th>
                <td>
                    <?php $nc->select('noconfirmation', array(0=>'Double Opt In', 1=>'Single Opt In')); ?>
                    <div class="hints">
                        <strong>Double Opt In</strong> means subscribers need to confirm their email address by an activation link sent them on a activation email message.<br />
                        <strong>Single Opt In</strong> means subscribers do not need to confirm their email address.<br />
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>
        </div>
        
        <h3>Subscription</h3>
        <div>
        <table class="form-table">
            <tr valign="top">
                <th>Subscription page</th>
                <td>
                    <?php $nc->editor('subscription_text'); ?>
                    <div class="hints">
                    This is the text shown to subscriber before the subscription form (which is added automatically). To create a custom form,
                    code it directly editing the text in HTML mode (Newsletter will auto discover it).
                    See documentation about custom forms. A little example:<br />
                    &lt;form&gt;<br />
                    Your email: &lt;input type="text" name="ne"/&gt;<br />
                    &lt;input type="submit" value="Subscribe now!"/&gt;<br />
                    &lt;/form&gt;<br />
                    Field names are: "ne" email, "nn" name, "ns" surname, "nx" sex (values: f, m, n),
                    "nl[]" list (the field value must be the list number, you can use checkbox, radio, select or even hidden
                    HTML input tag), "npN" custom profile (with N from 1 to 19).
                    </div>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>
        </div>



        <h3>Confirmation (only for double opt-in)</h3>
        <div>
        <table class="form-table">
            <tr valign="top">
                <th>Confirmation required message</th>
                <td>
                    <?php $nc->editor('subscribed_text'); ?>
                    <div class="hints">
                        This is the text showed to a user who has pressed "subscribe me" on the previous
                        step informing that an email to confirm subscription has just been sent. Remember
                        the user to check the spam folder and to follow the email instructions.
                    </div>
                </td>
            </tr>

            <!-- CONFIRMATION EMAIL -->
            <tr valign="top">
                <th>Confirmation email</th>
                <td>
                    <?php $nc->email('confirmation'); ?>
                    <div class="hints">
                        Message sent by email to new subscribers with instructions to confirm their subscription
                        (for double opt-in process). Do not forget to add the <strong>{subscription_confirm_url}</strong>
                        that users must click to activate their subscription.<br />
                        Sometime can be useful to add a <strong>{unsubscription_url}</strong> to let users to
                        cancel if they wrongly subscribed your service.
                    </div>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>
        </div>


        
        <h3>Welcome message/page</h3>
        <div>
        <table class="form-table">
            <tr valign="top">
                <th>Welcome message</th>
                <td>
                    <?php $nc->editor('confirmed_text'); ?>
                    <div class="hints">
                        Showed when the user follow the confirmation URL sent to him with previous email
                        settings or if signed up directly with no double opt-in process. You can use the <strong>{profile_form}</strong> tag to let the user to
                        complete it's profile.
                    </div>
                </td>
            </tr>

            <tr valign="top">
                <th>Alternative custom welcome page</th>
                <td>
                    <?php $nc->text('confirmed_url', 70); ?>
                    <div class="hints">
                        A full page address (http://yourblog.com/welcome) to be used instead of message above. If empty the message is
                        used.
                    </div>
                </td>
            </tr>

            <tr valign="top">
                <th>Conversion tracking code<br/><small>ADVANCED</small></th>
                <td>
                    <?php $nc->textarea('confirmed_tracking'); ?>
                    <div class="hints">
                        The code is injected AS-IS in welcome page and can be used to track conversion
(you can use PHP if needed). Conversion code is usually supply by tracking services,
like Google AdWords, Google Analytics and so on.</div>
                </td>
            </tr>

            <!-- WELCOME/CONFIRMED EMAIL -->
            <tr valign="top">
                <th>
                    Welcome email<br /><small>The right place where to put bonus content link</small>
                </th>
                <td>
                    <?php $nc->email('confirmed'); ?>
                    <div class="hints">
                        Email sent to the user to confirm his subscription, the successful confirmation
                        page, the welcome email. This is the right message where to put a <strong>{unlock_url}</strong> link to remember to the
                        user where is the premium content (if any, main configuration panel).<br />
                        It's a good idea to add the <strong>{unsubscription_url}</strong> too and the <strong>{profile_url}</strong>
                        letting users to cancel or manage/complete their profile.
                   </div>
                </td>
            </tr>

        </table>

        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>
        </div>


        <h3>Cancellation</h3>
        <div>
        <p class="intro">
            A user starts the cancellation process clicking the unsubscription link in
            a newsletter. This link contains the email to unsubscribe and some unique information
            to avoid hacking. The user are required to confirm the unsubscription: this is the last
            step where YOU can communicate with your almost missed user.
            <br />
            To create immediate cancellation, you can use the <strong>{unsubscription_confirm_url}</strong>
            in your newsletters and upon click on that link goodbye message and email are used directly
            skipping the confirm request.
        </p>

        <table class="form-table">
            <tr valign="top">
                <th>Cancellation message</th>
                <td>
                    <?php $nc->editor('unsubscription_text'); ?>
                    <div class="hints">
                        This text is show to users who click on a "unsubscription link" in a newsletter
                        email. You <strong>must</strong> insert a link in the text that user can follow to confirm the
                        unsubscription request using the tag <strong>{unsubscription_confirm_url}</strong>.
                    </div>
                </td>
            </tr>

            <!-- Text showed to the user on successful unsubscription -->
            <tr valign="top">
                <th>Goodbye message</th>
                <td>
                    <?php $nc->editor('unsubscribed_text'); ?>
                    <div class="hints">
                        Shown to users after the cancellation has been completed.
                    </div>
                </td>
            </tr>

            <!-- GOODBYE EMAIL -->
            <tr valign="top">
                <th>Goodbye email</th>
                <td>
                    <?php $nc->email('unsubscribed'); ?>
                    <div class="hints">
                        Sent after a cancellation, is the last message you send to the user before his removal
                        from your newsletter subscribers. Leave the subject empty to disable this message.
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>
        </div>
        
        <h3>Other configurations (advanced)</h3>
        <div>
        <table class="form-table">
        <!--
            <tr>
                <th>Email theme</th>
                <td>
                    <?php if ($nc->data['email_theme_novisual'] == 1) $nc->textarea('email_theme'); else $nc->editor('email_theme'); ?><br />
                    <?php $nc->select('email_theme_novisual', array(0=>'Edit with visual editor', 1=>'Edit as plain text')); ?> (save to apply)
                    <div class="hints">
                        Subscription and cancellation processes send out emails as you configured above. To apply a general theme to those
                        emails, you can create an HTML layout here. You can also use PHP code, just remember to disable the visual editor and to
                        keep it disabled.<br />
                        <strong>Messages are inserted in the theme where you put the {message} tag, don't forget it!</strong>
                    </div>
                </td>
            </tr>
            -->
            <tr valign="top">
                <th>Disable visual editors?</th>
                <td>
                    <?php $nc->yesno('novisual'); ?>
                </td>
            </tr>
        </table>


        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
            <?php $nc->button_confirm('reset', 'Reset all', 'Are you sure you want to reset all?'); ?>
        </p>
        </div>

        <a name="documentation"></a>
        <h3>Documentation</h3>
<div>
    <h4>User's data</h4>
    <p>
        <strong>{name}</strong>
        The user name<br />
        <strong>{surname}</strong>
        The user surname<br />
        <strong>{email}</strong>
        The user email<br />
        <strong>{ip}</strong>
        The IP address from where the subscription started<br />
        <strong>{id}</strong>
        The user id<br />
        <strong>{token}</strong>
        The user secret token<br />
        <strong>{profile_N}</strong>
        The user profile field number N (from 1 to 19)<br />
    </p>

    <h4>Action URLs and forms</h4>
    <p>
        <strong>{subscription_confirm_url}</strong>
        URL to build a link to confirmation of subscription when double opt-in is used. To be used on confirmation email.<br />
        <strong>{unsubscription_url}</strong>
        URL to build a link to start the cancellation process. To be used on every newsletter to let the user to cancel.<br />
        <strong>{unsubscription_confirm_url}</strong>
        URL to build a link to an immediate cancellation action. Can be used on newsletters if you want an immediate cancellation or
        on cancellation page (displayed on {unsubscription_url}) to ask a cancellation confirmation.<br />
        <strong>{profile_url}</strong>
        URL to build a link to user's profile page (see the User Profile panel)<br />
        <strong>{unlock_url}</strong>
        Special URL to build a link that on click unlocks protected contents. See Main Configuration panel.<br />
        <strong>{profile_form}</strong>
        Insert the profile form with user's data. Usually it make sense only on welcome page.<br />
    </p>
</div>

    </form>
</div>
