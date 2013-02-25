<?php
@include_once NEWSLETTER_INCLUDES_DIR . '/controls.php';

$controls = new NewsletterControls();

if ($controls->is_action('trigger')) {
    $newsletter->hook_newsletter();
    $controls->messages = 'Delivery engine triggered.';
}

if ($controls->is_action('trigger_followup')) {
    NewsletterFollowup::instance()->send();
    $controls->messages = 'Follow up delivery engine triggered.';
}

if ($controls->is_action('engine_on')) {
    wp_clear_scheduled_hook('newsletter');
    wp_schedule_event(time() + 30, 'newsletter', 'newsletter');
    $controls->messages = 'Delivery engine reactivated.';
}

if ($controls->is_action('upgrade')) {
    // TODO: Compact them in a call to Newsletter which should be able to manage the installed modules
    Newsletter::instance()->upgrade();
    NewsletterUsers::instance()->upgrade();
    NewsletterSubscription::instance()->upgrade();
    NewsletterEmails::instance()->upgrade();
    $controls->messages = 'Upgrade forced!';
}

if ($controls->is_action('delete_transient')) {
    delete_transient($_POST['btn']);
    $controls->messages = 'Deleted.';
}

$x = wp_next_scheduled('newsletter');
if ($x === false) {
    $controls->errors = 'The delivery engine is off (it should never be off). See the System Check below to reactivate it.';
}
?>
<div class="wrap">

    <?php $help_url = 'http://www.satollo.net/plugins/newsletter'; ?>
    <?php include NEWSLETTER_DIR . '/header.php'; ?>

    <h2>Welcome and Support</h2>

    <?php $controls->show(); ?>

    <form method="post" action="">
        <?php $controls->init(); ?>

        <h3>First steps</h3>
        <p>
            <strong>Newsletter works out of box</strong>. You don't need to create lists or configure it. Just use your WordPress
            appearance panel, enter the widgets panel and add the Newsletter widget to your sidebar.
        </p>
        <p>
            To get the most out of Newsletter, to translate messages and so on, it's important to understand the single configuration panels:
        </p>
        <ol>
            <li>
                <strong>Configuration</strong>: is where you find the main setting, like the SMTP, the sender address and name,
                the delivery engine speed and so on.
            </li>
            <li>
                <strong>Subscription</strong>: is where you configure the subscription process and it's one of the most important panel
                to explore and understand. Subscription is not limited to collect email addresses! There you define the fields of the
                subscription box, optionally a dedicated page for subscription and profile edit and so on.
            </li>
            <li>
                <strong>Newsletters</strong>: is where you create and send messages to your subscribers. You choose a theme,
                set some parameters, preview the message and finally compose it.
            </li>
            <li>
                <strong>Subscribers</strong>: is where you manage your subscribers like edit, create, export/import and so on.
            </li>
            <li>
                <strong>Statistics</strong>: is where you configure the statistic system; statistics of single email (open, clicks)
                are accessible directly from email lists.
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
                    <td>Main<br><small>The main configuration of Newsletter and some minor features</small></td>
                    <td><?php echo Newsletter::VERSION; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Subscription<br><small>All about the subscription and unsubscription processes</small></td>
                    <td><?php echo NewsletterSubscription::VERSION; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Subscribers<br><small>The subscribers management tool</small></td>
                    <td><?php echo NewsletterUsers::VERSION; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Newsletters<br><small>The newsletters composer with themes</small></td>
                    <td><?php echo NewsletterEmails::VERSION; ?></td>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td>Statistics<br><small>Statistics collector</small></td>
                    <td><?php echo NewsletterStatistics::instance()->version; ?></td>
                    <td>&nbsp;</td>
                </tr>
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
                    <td>Feed by Mail (test version)<br><small>Automatically generate and send email with blog contents</small></td>
                    <?php if (class_exists('NewsletterFeed')) { ?>
                        <td><?php echo NewsletterFeed::instance()->version; ?></td>
                    <?php } else { ?>
                        <td>Not installed</td>
                    <?php } ?>
                    <td><?php echo NewsletterModule::get_available_version(35); ?></td>
                </tr>
                <tr>
                    <td>Follow Up (test version))<br><small>Sends email serie after subscriber sign up</small></td>
                    <?php if (class_exists('NewsletterFollowup')) { ?>
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
            <!--
            <li><a href="http://www.satollo.net/plugins/newsletter/feed-by-mail-module" target="_blank">Feed by Mail Module</a></li>
            <li><a href="http://www.satollo.net/plugins/newsletter/follow-up-module" target="_blank">Follow Up Module</a></li>
            -->
        </ul>


    </form>

</div>
