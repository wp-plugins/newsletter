<script>
    jQuery(document).ready(function () {

        jQuery(".wrap h3").each(function () {
            jQuery(this).nextUntil('h3').hide();
        });

        jQuery(".wrap h3").click(function () {
            jQuery(this).nextUntil('h3').toggle();
        });
    });
</script>
<div class="wrap">
    <h2>Newsletter/Newsletter Pro User Guide</h2>

    <p>Welcome to Newsletter/Neswletter Pro from Stefano Lissa.</p>
    <p>
        I hope Newsletter/Neswletter Pro will be a powerful tool for your business. I made the whole configuration
        as simple as possible but keeping an eye on flexibility, specially in respect of all the
        world languages. I'm Italian and I know how much stressing job is the plug-in translation!
    </p>
    <p>
        This guide is common to Newsletter and Newsletter Pro, so here you will find some chapters with
        instructions on how to operate with Newsletter Pro features: if you're running Newsletter free, just
        skip them.
    </p>
    <p>
        If you want to know more about Newsletter Pro, take a look to
        <a href="http://www.satollo.net/plugins/newsletter" target="_blank">this page</a> (opens on a new window)
        or go directly to the <a href="http://members.satollo.net" target="_blank">Member's site</a> (opens in a new window).
    </p>
    <p>
        <strong>References to pages where leave comments or find support can be find
        on <a href="http://www.satollo.net/plugins/newsletter" target="_blank">this page</a> (opens on a new window).</strong>
    </p>
    <p>
        Every non obvious options you find on configuration panels has a little
        documentation box. Many users asked me to include the documentation on Newsletter panels
        and even if that make them a little bit "verbose", I think it's of great help.
    </p>
    <p>
        Please, take the time to read the info box of each option, specially on main configuration
        panel, because some values, even if formally correct, can be the cause of errors (due to
        your hosting provider policy or limits).
    </p>
    <p>
        <strong>If you have questions due to missing part on this documentation, send me an email to
        stefano@satollo.net so I can complete it and clear any doubt</strong>.
    </p>


    <h3>Newsletter configuration structure</h3>

    <p>
        Newsletter panels are organized as described below. It is separated in modules, so if you don't
        need the feed by mail service or you don't need the follow up service, you can ignore them safely.<br />
        Every panel contains it's own documentation, but here you can find special cases or examples.
    </p>
    <ul>
        <li><strong>User Guide.</strong> It's the panel where you are now...</li>
        <li><strong>Main Configuration.</strong> Really important. It's the first panel you need to check. Almost every value has a preset or can
            be left empty but you at least make some sending test. Other advanced functions are configurable on main panel:
            <ul>
                <li>SMTP (only Newsletter Pro). To use an external SMTP to send emails.</li>
                <li>Locked content (only Newsletter Pro). To create premium content on your blog locking out parts of posts letting only
                    subscriber to see them.</li>
            </ul>
        </li>

        <li><strong>User Profile.</strong> Really important. It's where you set what user's data you want to collect on subscription. There
            you can translate almost every thing of Newsletter.</li>
        <li><strong>Subscription Process.</strong> Really important. It's where you decide how the subscription process works. There you
            can set double or single opt in, emails and messages that are involved on subscription and cancellation.</li>
        <li><strong>Emails.</strong> Where to create and send emails. For Newsletter Pro owners there is access to single email statistics (
            clicks, links clicked, ...).</li>
        <li><strong>Users Management.</strong> There you can search, add, edit, remove subscribers. For Newsletter Pro owners there is a statistics
            sub-panel.</li>
        <li><strong>Feed by mail (only Newsletter Pro).</strong> Configure if and how you subscriber will receive a summary of you
            blog latest posts. There you can create your feed by mail theme (programming skills needed) as no other
            services can do!</li>
        <li><strong>Follow up (only Newsletter Pro).</strong> Don't let your subscribers without messages for long time after they
            signed up. On this panel you can program a series of emails to be sent automatically.</li>
        <li><strong>Import/Export.</strong> Some functionalities to import subscribers from CSV file and to export them.</li>
       
        <li><strong>Forms.</strong> You can manually create subscription forms to be recalled on different places. Before use that panel remember that
            Newsletter enables you to customize the subscription form on every configuration which require it (eg. the widget).</li>
    </ul>


    <h3>Main configuration panel</h3>
    <p>
        Every setting in the main configuration panel is adequately described there, just be sure to read every note because some
        apparently legal values can block the emails from your account.
    </p>
    <p>
        One important parameter is the number of email per hour you want Newsletter to send. This limit is applied to
        newsletters, feed by mail and follow up emails.
    </p>

    <h4>Sending process and SMTP</h4>
    <p>
        Newsletter Pro uses the mailing functions of WordPress to send emails so you can use any plugin
        that extend the WordPress mailing system and it will act on Newsletter Pro emails too.
    </p>
    <p>
        Optionally, on main configuration panel, you can choose to use an "external" SMTP service. In that case
        Newsletter Pro sends emails on it's own, using directly the WordPress included libraries. Using the SMTP
        of Newsletter Pro than installing a plugin that forces WordPress standard mailing function to use the same
        SMTP is usually more efficient. Newsletter Pro will use that SMTP for any message it needs to send (not only newsletters
        even welcome and confirmation messages will be sent via SMTP).
    </p>
    <p>
        If the Mailer plugin is installed, Newsletter Pro adds some special headers on outgoing emails so Mailer
        can detect them and schedule correctly every message. Newsletters are send with priority 2 while
        any other message (usually confirmation and welcome messages) is sent with priority 0 (real time).
    </p>
    <p>
        Mailer or other plugins that throttles emails going out from your blog are not required, since Newsletter has it's
        own email throttling system.
    </p>

    <h4>Locked content</h4>

    <p>
        <strong>Read carefully if you use a cache system!</strong>
    </p>
    <p>
        With Newsletter Pro you can lock out some content of you blog, making it available only
        to confirmed subscriber. The feature can be configured on main configuration panel.
    </p>
    <p>
        Using the feature is very simple: on a post identify the content you want to lock out and
        surround it with shot code [newsletter_lock]...[/newsletter_lock]. You can lock out multiple
        piece of content, too.
    </p>
    <p>
        In place of the hidden content a short message is shown, as configured in main configuration panel
        (where there are some tips on what to write in it). When a user subscribe and confirm the subscription or
        click on his personal unlocking url ({unlock_url} that you should put only on welcome email) a cookie
        is added to his browser and the lock is removed every where on the blog.
    </p>
    <p>
        <strong>Warning</strong>. Be sure to not cache the posts with locked content, otherwise there are chances that
        subscribers see always the lock message or, worse, the unlocked content is shown to every one. Newsletter
        Pro is already integrated with Hyper Cache, for other cache systems just add a cache bypass based on
        presence of "newsletter" cookie.
    </p>






    <h3>The subscription process</h3>

    <p>
        The main thing you should care of is the subscription configuration. It's not so hard but
        there are some choices you need to take and that suite your audience and may be your
        local laws.
    </p>
    <p>
        Subscription can be single opt in or double opt in. Choosing the one or the other will change
        the configuration panel to show you only the needed options.
    </p>
    <p>
        Single opt in is not too much legal, since you assume that the subscribed email address is correct and
        owned by who performed the subscription. Double opt in subscription (which is required for example by DreamHost)
        activate the subscriber only after he confirm his email address (by an activation email send by Newsletter).
    </p>



    <h3>User profile</h3>

    <p>
        User profile is the set of data you can collect about subscribers during the subscription or on their profile panel:
    </p>

    <ul>
        <li>email, first name, last name</li>
        <li>sex</li>
        <li>privacy check box (for countries which requires it)</li>
        <li>lists/topics preference (up to 9)</li>
        <li>generic textual/selection list profile fields (up to 19)</li>
        <li>ip address and date of subscription (for legal purposes)</li>
    </ul>
    <p>
        Even if Newsletter Pro lets you to collect all that data, it's recommended to ask the subscriber
        the smallest set of fields possible, to avoid subscription loss. It's usually more profitable to give
        the subscriber a complete profile form where to fill in more data on a second time. You
        can offer the complete profile form just after the sign up or invite the user to complete his data
        with a follow up email or with a link to the profile form on newsletters you'll send.
    </p>

    <p>
        Subscription and profile forms, as generated by Newsletter Pro, are controlled under the "user profile"
        panel. There you can decide what fields to ask on subscription time and what fields on profile editing step.
    </p>

    <p>
        You can also <strong>translate every single word</strong> in you language!
    </p>

    <p>
        Check the hints in the panel for detailed explanation of every single field.
    </p>


    <h3>Custom forms</h3>
    <p>
        You need HTML skills to create custom forms, but it is so easy that a consultant
        should charge a very low fee to do it for you (or ask to a nephew...).
    </p>
    <p>
        As general rule, you can create a custom form <strong>on every subscription point</strong>: the
        Newsletter Pro main page, on Newsletter Pro widgets, on alternative messages for
        locked content. 
    </p>
    <p>
        To create a custom form is very easy, here an example that asks the user only it's email:
    </p>
    <p>
        <code>
            &lt;form&gt;<br />
            &lt;input type="text" name="ne"/&gt;<br />
            &lt;input type="submit"/&gt;<br />
            &lt;/form&gt;<br />
        </code>
        as you can see the form tag has not attributes (method, action, ...): they are added
        automatically by Newsletter Pro. If you need to fire a JavaScript call before the form submission
        add an "onclick" event to the submit button.
    </p>

    <p>
        The field names you can add to a custom form are:
    </p>
    <ul>
        <li>ne - is the user email</li>
        <li>nn - is the user name (first name or complete name)</li>
        <li>ns - is the user surname/last name</li>
        <li>npN - where N go from 1 to 19, are the custom profile fields (be aware they
            are not the same thing of profile fields in Newsletter Pro 2.1)</li>
        <li>nl[] - must be checkbox fields name when they represent a list/option/topic and the list/option/topic number (1 to 9)
            must be the field value (see below).</li>
    </ul>
    <p>
        For lists on a custom form you can ask the user to check what lists he want to subscribe or
        put an "hidden" list field to "force" the subscription on that list. The latter option is used
        when different forms collect users on different lists without asking directly to the subscriber.
    </p>
    <p>
        An example of check box field for a list is:
    </p>
    <p>
        <code>&lt;input type="checkbox" name="nl[]" value="3"/&gt;</code>
    </p>

    <h3>Emails</h3>
    <p>
        Emails are created from the "emails" panel. When you enter the panel you'll se the emails archived and their status.
    </p>
    <p>
        Each email has a subject and a body, but some other information are stored: targeted users, if link clicks can be tracked,
        a status, the number of receivers and how many copies have already been sent.
    </p>
    <p>
        You can create as many email as you want and you can even send many of them simultaneously.
    </p>

    <h4>Email status</h4>
    <p>
        An email has few possible statuses:
    </p>
    <ul>
        <li>new - when it has just been created</li>
        <li>sending - when you start the sending process and Newsletter Pro is taking care of it</li>
        <li>sent - when the sending engine has completed the work</li>
        <li>paused - if you want to pause the sending process, may be to correct an error</li>
    </ul>
    <p>
        When you abort an email sending, the email return to the "new" status and every sending progress information is reset.
    </p>

    <h3>Email autocomposer and themes</h3>
    <p>
        When a new email is create, it can composed starting from a blank sheet or can be auto composed with
        a theme. I call it auto compose, because a theme can generate actual content getting it from the blog
        (a list of latest posts, tag cloud and so on).
    </p>
    <p>
        Themes are PHP file stored under "themes" folder. There are some pre packaged themes that can be used as
        starting point for your specific theme.
    </p>
    <h4>How to create a custom theme</h4>
    <p>
        To create a new theme follow the steps below (names must be lowercase):
    </p>

    <ol>
        <li>create a folder named "newsletter-custom" under your WordPress plugins directory</li>
        <li>create a folder named "themes" under the previous created "newsletter-custom"</li>
        <li>inside "themes" create a folder with a name of your choice (the name will be your custom theme name), for example "my-theme": that will be your theme folder</li>
        <li>in your theme folder there must be the file "theme.php" which will be the main file of your theme (and usually the only one)</li>
    </ol>

    <p>
        To start with an already working theme, start with "theme.php" file of "themes/theme-1" folder.
    </p>


    <h3>Follow up or autoresponder</h3>
    <p>
        Follow up or auto-responder is when you send a sequence of emails to new subscribers. A follow up can be a series
        of lessons on a topic, a product/service presentation broken up on small parts or anything else.
        Those emails are typically sent every few days (configurable, of course).
    </p>

    <p>
        Once you have created your follow up emails (up to 10) and activated the system,
        it starts to work for you contacting the new subscribers as if you're writing to each of them all in
        autopilot.
    </p>

    <p>
        A subscriber can unsubscribe from the follow up without removing him self from the list:
        simply the "follow up" status will be set to "stop". This point is of great importance,
        because you can let the user to stop annoying (to them) messages without loosing him.
        That "follow up unsubscription" is done via a link you should add to each follow up email.
        The link is inserted on every occurrence of {followup_unsubscription_url} place holder.
    </p>


    <h4>Follow up theme</h4>
    <p>
        To give a common skin to each follow up message, the autoresponder applies a theme to every email
        body on configuration panel, so they can be considered only the "content" of follow up messages.
    </p>

    <p>
        Follow up themes are store under the folder "themes-followup" and prepackaged there are a couple of themes, one
        almost empty (it contains only a footer text with unsubscription link) and the other a little bit
        richer.
    </p>

    <p>
        Any theme must contain a special tag, {message}, which will be replaced with the current follow up
        email content.
    </p>

    <p>
        To modify a theme follow the same guide lines for feed by mail themes, just use a themes-followup folder
        instead of themes-feed.
    </p>


    <h3>Feed by mail</h3>

    <p>
        Feed by mail is a Newsletter Pro service that sends an excerpt of last posts
        to subscribers who signed up for it. It's something like Google FeedBurner and other
        external services. What makes the difference is:
    </p>
    <ul>
        <li>you can choose on what days of the week you want to send the summary (you can choose the delivery hour too)</li>
        <li>you can easily track links on those auto generated emails and see clicks statistics</li>
        <li>you can program with PHP your own theme to generate the summary adding other contents of your blog, for example a tag cloud</li>
        <li>subscribers can sign in and sign out of feed by mail service still remaining subscribed to your newsletter</li>
        <li>programming a specific theme (with PHP) you can compose summary emails totally different for each user set (for example, males and females)</li>
        <li>you can automatically add the service to every new subscriber</li>
    </ul>
    <p>
        On feed by mail panel all options are documented, I suggest to subscribe only your self to feed by mail and see the messages Newsletter
        Pro delivers to your mailbox.
    </p>

    <h4>How to create a new feed by mail theme</h4>
    <p>
        Packaged feed by mail themes are stored under the themes-feed folder. Every theme is a subfolder containing,
        at least, the theme.php file. Packaged themes should be your start point for a new theme, but do not modify
        them directly, otherwise on next Newsletter Pro version upgrade modifications will be lost.
    </p>

    <p>
        To create a new theme, as for generic newsletter themes, follow the steps below (names must be lowercase):
    </p>

    <ol>
        <li>create a folder named "newsletter-custom" under your WordPress plugins directory</li>
        <li>create a folder named "themes-feed" under the previous created "newsletter-custom"</li>
        <li>inside "themes-feed" create a folder with a name of your choice (the name will be your custom feed by mail theme name), for example "my-feed-theme": that will be your theme folder</li>
        <li>in your theme folder there must be the file "theme.php" which will be the main file of your theme (and usually the only one)</li>
    </ol>

    <p>
        To start with an already working theme, start with "theme.php" file of "themes-feed/feed-1" folder or the more complex
        "themes-feed/feed-2" folder: they are well documented.
    </p>

    <p>
        Do not use one of the "theme.php" files for normal newsletters, the ones you can found under "themes"
        folder, because they do not work!
    </p>

    <h3>Themes tech details</h3>
    <p>
        Themes for feed by mail and follow up e-mails are "executed" for each user when it's time to generate
        an email body. So the generated text can be customized user by user accessing the "current user" data.
    </p>

    <p>
        The "current user" is an object stored under the $newsletter->user variable and object properties are
        the values of columns of the table "wp_newsletter" (the prefix "wp_" can be different in your blog).
    </p>
    <p>The user object properties are (use them with the syntax $newsletter->user->property_name):</p>
    <table>
        <tr><td>id</td><td>the user unique identification number</td></tr>
        <tr><td>name</td><td>the user first name</td></tr>
        <tr><td>surname</td><td>the user last name</td></tr>
        <tr><td>sex</td><td>the user sex: m, f, n</td></tr>
        <tr><td>list_n</td><td>list n subscription status: 1 means he's subscribed</td></tr>
        <tr><td>email</td><td>the user email</td></tr>
        <tr><td>followup</td><td>follow up subscription status: 1 means he's subscribed</td></tr>
        <tr><td>feed</td><td>feed by mail subscription status: 1 means he's subscribed</td></tr>
    </table>
    

</div>
