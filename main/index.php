<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';

$controls = new NewsletterControls();

?>
<div class="wrap main-index">


    <?php $help_url = 'http://www.satollo.net/plugins/newsletter'; ?>
    <?php include NEWSLETTER_DIR . '/header.php'; ?>

    <h2>Welcome and Support</h2>

    <?php $controls->show(); ?>

    <form method="post" action="">
        <?php $controls->init(); ?>

        <h3>Documentation?</h3>

        <p>
            With my horrible English, everything can be found starting from <a href="http://www.satollo.net/plugins/newsletter" target="_blank">Newsletter Official page</a>
            and every configuration panel has some included documentation just to avoid the most common mistakes.
        </p>

        <h3>Few minutes to get the most from Newsletter</h3>

        <ol>
            <li>
                <em>It (should) work!</em> Newsletter <strong>works out of box</strong>, you should only
                <a href="widgets.php"><strong>add the Newsletter Widget</strong></a> to the siderbar and subscriptions will start to get in.
            </li>

            <li>
                <em>Subscription page.</em> If you feel more confortable with a <strong>subscription page</strong>, let Newsletter to create one for you: on
                <a href="admin.php?page=newsletter_subscription_options">subscription configuration panel</a>. You can keep both the
                widget and the page, of course.
            </li>

            <li>
                <em>Translations.</em> The <strong>administrative panels</strong> are only in (my bad) English but any other public
                message and label and button can be translated on <a href="admin.php?page=newsletter_subscription_options">subscription configuration panel</a>:
                please <strong>explore it</strong>.
            </li>

            <li>
                <em>More about subscription.</em> The subscription and unsubscription processes to a mailing
                list <strong>must be clear</strong> to the blog owner. <a href="http://www.satollo.net/plugins/newsletter/subscription-module" target="_blank">You can find more on Satollo.net</a>.
            </li>
        </ol>

        <h3>Something is not working (it could happen)</h3>

        <ol>
            <li>
                <em>No emails are sent.</em> This mostly a problem of your provider. <strong>Make a test</strong> using the instructions you find on
                diagnostic panel.
            </li>
            <li>
                <em>I get a 500/fatal error during subscription.</em> This mostly a problem of file permissions. On diagnostic
                panel there is a check about it ad on <a target="_blank" href="http://www.satollo.net/plugins/newsletter/subscription-module#errors">Satollo.net there are some solutions</a>.
            </li>
        </ol>

        <h3>I want to create and send a newsletter</h3>

        <ol>
            <li>
                <em>I want to create a newsletter.</em> Use the <a href="http://www.satollo.net/wp-admin/admin.php?page=newsletter_emails_index">newsletters panel</a>
                <strong>choose a theme</strong>, preview, twick if needed and create your message.
            </li>
            <li>
                <em>I want to test my newsletter.</em> Save the newsletter and move to the
                <a href="http://www.satollo.net/wp-admin/admin.php?page=newsletter_users_index">subscribers panel</a>.
                Create some subscribers by hand using your email addresses and mark them as test subscribers. They will be
                used for newsletter tests.
            </li>
            <li>
                <em>I want to send my newsletter.</em> Simple, press the send button. The email is created and put on
                <a href="http://www.satollo.net/plugins/newsletter/newsletter-delivery-engine" target="_blank">delivery engine queue</a>.
                On newsletter list, it will be shown as "sending".
            </li>
            <li>
                <em>The newsletter is going out too slowly.</em> The <a href="http://www.satollo.net/plugins/newsletter/newsletter-delivery-engine" target="_blank">delivery engine</a> sends
                emails as quickly as configured, see the <a href="admin.php?page=newsletter_main_main">main
                configuration panel</a>. Look at your provider documentation as well, since it surely has a hourly limit.
            </li>
        </ol>

        <h3>Modules</h3>
        <p>
            Below the list of available modules that can be used with Newsletter plugin. Some modules are "core" part
            of Newsletter and are automatically updated with Newsletter official updates. Other modules are extensions and
            can be downloaded from <a href="http://www.satollo.net/downloads" target="_blank">www.satollo.net/downloads</a>.
            Some of them are commercial and other still on development (here for testers).
        </p>

        <table class="widefat" style="width: auto">
            <thead>
                <tr>
                    <th>Module</th>
                    <th>Version</th>
                    <th>Available version</th>
                </tr>
            </thead>
            <!-- TODO: Should be a cicle of installed modules -->
            <tbody>
                <tr>
                    <td>Reports<br><small>Extends the statistics system with a better report</small></td>
                    <?php if (class_exists('NewsletterReports')) { ?>
                        <td><?php echo NewsletterReports::instance()->version; ?></td>
                    <?php } else { ?>
                        <td>Not installed</td>
                    <?php } ?>
                    <td><?php echo NewsletterModule::get_available_version(34); ?></td>
                </tr>
                <tr>
                    <td>Feed by Mail<br><small>Automatically generate and send email with blog contents</small></td>
                    <?php if (NewsletterModule::extension_exists('feed') && class_exists('NewsletterFeed')) { ?>
                        <td><?php echo NewsletterFeed::instance()->version; ?></td>
                    <?php } else { ?>
                        <td>Not installed</td>
                    <?php } ?>
                    <td><?php echo NewsletterModule::get_available_version(35); ?></td>
                </tr>
                <tr>
                    <td>Follow Up (test version))<br><small>Sends email serie after subscriber sign up</small></td>
                    <?php if (NewsletterModule::extension_exists('followup') &&  class_exists('NewsletterFollowup')) { ?>
                        <td><?php echo NewsletterFollowup::instance()->version; ?></td>
                    <?php } else { ?>
                        <td>Not installed</td>
                    <?php } ?>
                    <td><?php echo NewsletterModule::get_available_version(37); ?></td>
                </tr>
                <tr>
                    <td>SendGrid (test version)<br><small>Integration with <a href="http://www.satollo.net/affiliate/sendgrid">SendGrid</a> SMTP and bounce report</small></td>
                    <?php if (class_exists('NewsletterSendgrid')) { ?>
                        <td><?php echo NewsletterSendgrid::instance()->version; ?></td>
                    <?php } else { ?>
                        <td>Not installed</td>
                    <?php } ?>
                    <td><?php echo NewsletterModule::get_available_version(40); ?></td>
                </tr>
                <tr>
                    <td>MailJet (test version)</td>
                    <?php if (class_exists('NewsletterMailjet')) { ?>
                        <td><?php echo NewsletterMailjet::instance()->version; ?></td>
                    <?php } else { ?>
                        <td>Not installed</td>
                    <?php } ?>
                    <td><?php echo NewsletterModule::get_available_version(38); ?></td>
                </tr>
            </tbody>
        </table>


        <h3>Support</h3>
        <p>
            There are some options to find or ask for support. Users with Newsletter Pro or Newsletter Pro Extensions can
            use the <a href="http://www.satollo.net/support-form" target="_blank">support form</a> even if the resources below are the first option.
        </p>
        <ul>
            <li><a href="http://www.satollo.net/plugins/newsletter" target="_blank">The official Newsletter page</a> contains information and links extended documentationand FAQ</li>
            <li><a href="http://www.satollo.net/forums/forum/newsletter-plugin" target="_blank">The official Newsletter forum</a> where to find solutions or create new requests</li>
            <li><a href="http://www.satollo.net/tag/newsletter" target="_blank">Newsletter articles and comments</a> are a source of solutions</li>
            <li>Write directly to me at stefano@satollo.net</li>
        </ul>

        <h3>Collaboration</h3>
        <p>
            Any kind of collaboration for this free plugin is welcome (of course). I set up a
            <a href="http://www.satollo.net/plugins/newsletter/newsletter-collaboration" target="_blank">How to collaborate</a>
            page.
        </p>

        <h3>Documentation</h3>
        <p>
            Below are the pages on www.satollo.net which document Newsletter. Since the site evolves, more page can be available and
            the full list is always up-to-date on main Newsletter page.
        </p>

        <ul>
            <li><a href="http://www.satollo.net/plugins/newsletter" target="_blank">Official Newsletter page</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-configuration" target="_blank">Main configuration</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-diagnostic" target="_blank">Diagnostic</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-faq" target="_blank">FAQ</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-delivery-engine" target="_blank">Delivery Engine</a></li>


            <li><a href="http://www.satollo.net/plugins/newsletter/subscription-module" target="_blank">Subscription Module</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-forms" target="_blank">Subscription Forms</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-preferences" target="_blank">Subscriber's preferences</a></li>

            <li><a href="http://www.satollo.net/plugins/newsletter/newsletters-module" target="_blank">Newsletters Module</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/newsletter-themes" target="_blank">Themes</a></li>

            <li><a href="http://www.satollo.net/plugins/newsletter/subscribers-module" target="_blank">Subscribers Module</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/statistics-module" target="_blank">Statistics Module</a></li>

            <li><a href="http://www.satollo.net/plugins/newsletter/feed-by-mail-module" target="_blank">Feed by Mail Module</a></li>
            <!--<li><a href="http://www.satollo.net/plugins/newsletter/follow-up-module" target="_blank">Follow Up Module</a></li>
            -->
        </ul>


    </form>

</div>
