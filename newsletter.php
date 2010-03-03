<?php

@include_once 'commons.php';

$options = get_option('newsletter_email');
$options_newsletter = get_option('newsletter');

if (isset($_POST['save']) && check_admin_referer()) {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter_email', $options);
}

// Auto composition
if (isset($_POST['auto']) && check_admin_referer()) {
// Load the theme
    $options = stripslashes_deep($_POST['options']);

    $file = newsletter_get_theme_dir($options['theme']) . '/theme.php';

    // Execute the theme file and get the content generated
    ob_start();
    @include($file);
    $options['message'] = ob_get_contents();
    ob_end_clean();

    if ($options['novisual']) {
        $options['message'] = "<html>\n<head>\n<style type=\"text/css\">\n" . newsletter_get_theme_css($options_email['theme']) .
            "\n</style>\n</head>\n<body>\n" . $options['message'] . "\n</body>\n</html>";
    }
}

// Reset the batch
if (isset($_POST['reset']) && check_admin_referer()) {
    newsletter_delete_batch_file();
    wp_clear_scheduled_hook('newsletter_cron_hook');
    delete_option('newsletter_batch', array());
}

if (isset($_POST['scheduled_simulate']) && check_admin_referer()) {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter_email', $options);
    newsletter_send_scheduled(0, true);
}

if (isset($_POST['scheduled_send']) && check_admin_referer()) {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter_email', $options);
    newsletter_send_scheduled(0, false);
}

if (isset($_POST['restore']) && check_admin_referer()) {
    $batch = newsletter_load_batch_file();
    update_option('newsletter_batch', $batch);
    newsletter_delete_batch_file();
}

// Theme style

$css_url = null;
$theme_dir = newsletter_get_theme_dir($options['theme']);
if (file_exists($theme_dir . '/style.css')) {
    $css_url = newsletter_get_theme_url($options['theme']) . '/style.css';
}

$nc = new NewsletterControls($options, 'composer');

?>
<?php if (!isset($options['novisual'])) { ?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins: "table,fullscreen",
        theme_advanced_disable : "styleselect",
        theme_advanced_buttons1_add: "forecolor,blockquote,code",
        theme_advanced_buttons3 : "tablecontrols,fullscreen",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_toolbar_location : "top",
        document_base_url : "<?php echo get_option('home'); ?>/"
    <?php
    if ($css_url != null) {
        echo ', content_css: "' . $css_url . '?' . time() . '"';
    }
    ?>
        });
</script>
<?php } ?>

<div class="wrap">

    <h2>Newsletter Composer</h2>
    
    <?php if (!touch(dirname(__FILE__) . '/test.tmp')) { ?>
    <div class="error fade" style="background-color:red;"><p><strong>It seems that Newsletter plugin folder is not writable. Make it writable to let
                Newsletter write logs and save date when errors occour.</strong></p></div>
    <?php } ?>

    <?php require_once 'header.php'; ?>

    <form method="post" action="">
        <?php wp_nonce_field(); ?>

        <?php if (isset($_POST['restart']) && check_admin_referer()) { ?>

        <h3>Continuing with previous batch</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                $batch = get_option('newsletter_batch');

                if (defined('NEWSLETTER_EXTRAS') && $batch['scheduled']) {
                    newsletter_cron_task();
                }
                else {
                    newsletter_send_batch();
                }
                ?>
        </div>

        <?php } ?>


        <?php if (isset($_POST['simulate']) && check_admin_referer()) { ?>

        <h3>Simulation</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                $batch = array();
                $batch['id'] = 0;
                $batch['list'] = 0;
                $batch['scheduled'] = false;
                $batch['simulate'] = true;

                update_option('newsletter_batch', $batch);

                newsletter_send_batch();
                ?>
        </div>

        <?php } ?>



        <?php if (isset($_REQUEST['send']) && check_admin_referer()) { ?>

        <h3>Sending</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                $batch = array();
                $batch['id'] = 0;
                $batch['list'] = 0;
                $batch['scheduled'] = false;
                $batch['simulate'] = false;

                update_option('newsletter_batch', $batch);

                newsletter_send_batch();
                ?>
        </div>

        <?php } ?>



        <?php if (isset($_POST['test']) && check_admin_referer()) { ?>

        <h3>Sending to test subscribers</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                $subscribers = array();
                for ($i=1; $i<=10; $i++) {
                    if (!$options['test_email_' . $i]) continue;
                    $subscribers[$i-1]->name = $options['test_name_' . $i];
                    $subscribers[$i-1]->email = $options['test_email_' . $i];
                    $subscribers[$i-1]->token = 'FAKETOKEN';
                }
                newsletter_send_test($subscribers);
                ?>
        </div>

        <?php } ?>



        <?php
        $batch_file = newsletter_load_batch_file();
        if ($batch_file != null) {
            ?>
        <h3>Warning!!!</h3>
        <p>There is a batch saved to disk. That means an error occurred while sending.
            Would you try to restore
            that batch?<br />
            <input class="button" type="submit" name="restore" value="Restore batch data"  onclick="return confirm('Restore batch data?')"/>
        </p>
        <?php } ?>

        <h3>Batch info</h3>

        <?php $batch = get_option('newsletter_batch'); ?>
        <?php if (!is_array($batch) || empty($batch)) { ?>

        <p><strong>No batch info found, it's ok!</strong></p>

        <?php } else { ?>

        <table class="form-table">
            <tr>
                <th>Status</th>
                <td>
                        <?php
                        if ($batch['scheduled']) {

                            if ($batch['completed']) echo 'Completed';
                            else {
                                $time = wp_next_scheduled('newsletter_cron_hook');
                                if ($time == 0) {
                                    echo 'Not completed but no next run found (errors?)';
                                }
                                else {
                                    echo 'Not completed, next run on ' . date('j/m/Y h:i', $time);
                                    echo ' (' . ((int)(($time-time())/60)) . ' minutes left)';
                                }
                            }
                        }
                        else {
                            if ($batch['completed']) echo 'Completed';
                            else echo 'Not completed (you should restart it)';
                        }
                        ?>
                    <br />
                    <?php echo $batch['message']; ?>
                </td>
            </tr>
            <tr>
                <th>Emails sent/total</th>
                <td><?php echo $batch['sent']; ?>/<?php echo $batch['total']; ?> (last id: <?php echo $batch['id']; ?>)</td>
            </tr>
            <!--
            <tr>
                <td>List</td>
                <td><?php echo $batch['list']; ?></td>
            </tr>
            -->
            <tr>
                <th>Sending type</th>
                <td><?php echo $batch['simulate']?"Simluation":"Real"; ?>/<?php echo $batch['scheduled']?"Scheduled":"Not scheduled"; ?></td>
            </tr>
        </table>

        <p class="submit">
                <?php if (!$batch['completed']) { ?>
            <input class="button" type="submit" name="restart" value="Restart batch"  onclick="return confirm('Continue with this batch?')"/>
                <?php } ?>
            <input class="button" type="submit" name="reset" value="Reset batch"  onclick="return confirm('Reset the batch status?')"/>
        </p>

        <?php } ?>



        <h3>Newsletter message</h3>

        <table class="form-table">
            <tr valign="top">
                <th>Newsletter name and tracking</th>
            <?php if (defined('NEWSLETTER_EXTRAS')) { ?>
                <td>
                    <input name="options[name]" type="text" size="25" value="<?php echo htmlspecialchars($options['name'])?>"/>
                    <input name="options[track]" value="1" type="checkbox" <?php echo $options['track']?'checked':''; ?>/>
                    Track link clicks
                    <br />
                    When this option is enabled, each link in the email text will be rewritten and clicks
                    on them intercepted.
                    The symbolic name will be used to track the link clicks and associate them to a specific newsletter.
                    Keep the name compact and significative.
                </td>

            <?php } else { ?>
                <td>Tracking options available with Newsletter Extras package</td>
            <?php } ?>
            </tr>

            <tr valign="top">
                <th>Subject</th>
                <td>
                    <?php $nc->text('subject', 70); ?>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> receiver name.', 'newsletter'); ?>
                </td>
            </tr>

            <tr valign="top">
                <th>Message</th>
                <td>
                    <?php $nc->checkbox('novisual', 'disable the visual editor'); ?>
                    (save to apply and be sure to <a href="http://www.satollo.net/plugins/newsletter#composer">read here</a>)
                    <br />
                    <textarea name="options[message]" wrap="off" rows="20" style="font-family: monospace; width: 100%"><?php echo htmlspecialchars($options['message'])?></textarea>
                    <br />
                    <?php _e('Tags: <strong>{name}</strong> receiver name;
<strong>{unsubscription_url}</strong> unsubscription URL;
<strong>{token}</strong> the subscriber token.', 'newsletter'); ?>
                </td>
            </tr>
            
            <tr valign="top">
                <th>Theme</th>
                <td>
                    <select name="options[theme]">
                        <optgroup label="Included themes">
                            <option <?php echo ('blank'==$options['theme'])?'selected':''; ?> value="blank">Blank</option>
                            <option <?php echo ('default'==$options['theme'])?'selected':''; ?> value="default">Default</option>
                            <option <?php echo ('with-picture'==$options['theme'])?'selected':''; ?> value="with-picture">With picture</option>
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
                    <input class="button" type="submit" name="auto" value="Auto compose"/>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
            <input class="button" type="submit" name="test" value="Test"/>
            <input class="button" type="submit" name="simulate" value="Simulate"  onclick="return confirm('Simulate? The test address will receive all emails!')"/>
            <input class="button" type="submit" name="send" value="Send" onclick="return confirm('Start a real newsletter sending batch?')"/>
            <?php if (defined('NEWSLETTER_EXTRAS')) { ?>
            <input class="button" type="submit" name="scheduled_simulate" value="Scheduled simulation" onclick="return confirm('Start a scheduled simulation?')"/>
            <input class="button" type="submit" name="scheduled_send" value="Scheduled send" onclick="return confirm('Start a scheduled real send?')"/>
            <?php } ?>
        </p>

        <h3>Theme parameters</h3>
        <p>Themes may not use such parameters!</p>
        <table class="form-table">
            <tr valign="top">
                <th>Number of posts on theme</th>
                <td>
                    <input name="options[theme_posts]" type="text" size="5" value="<?php echo htmlspecialchars($options['theme_posts'])?>"/>
                </td>
            </tr>
        </table>



        <!--
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        List:
        <select name="options[list]">
            <option value="0">General</option>
        <?php for ($i=1; $i<=10; $i++) { ?>
            <option value="<?php echo $i; ?>"><?php echo htmlspecialchars($options_newsletter['list_' . $i]); ?></option>
        <?php } ?>
        </select>
        -->


        <h3>Sending options</h3>
        <table class="form-table">
            <tr valign="top">
                <th>Max emails in a single batch</th>
                <td>
                    <?php $nc->text('max', 5); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Receiver address for simulation</th>
                <td>
                    <?php $nc->text('simulate_email', 50); ?>
                    <br />
                    <?php _e('When you simulate a sending process, emails are really sent but all to this
email address. That helps to test out problems with mail server.', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Return path</th>
                <td>
                    <?php $nc->text('return_path', 50); ?>
                    <br />
                    <?php _e('Force the return path to this email address. Return path is used from mail server to
send back messages with delivery errors.', 'newsletter'); ?>
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="<?php _e('Save', 'newsletter'); ?>"/>
        </p>
        <!--
        <tr valign="top">
            <td>
                Filter<br />
                <input name="options[filter]" type="text" size="30" value="<?php echo htmlspecialchars($options['filter'])?>"/>
            </td>
        </tr>
        -->

        <h3>Sending options for scheduler</h3>
        
        <?php if (defined('NEWSLETTER_EXTRAS')) { ?>
        <p>Scheduler is described <a href="http://www.satollo.net/plugins/newsletter-extras">here</a>.</p>
        <table class="form-table">
            <tr valign="top">
                <th>Interval between sending tasks</th>
                <td>
                    <?php $nc->text('scheduler_interval', 5); ?>
                    (minutes, minimum value is 1)
                </td>
            </tr>
            <tr valign="top">
                <th>Max number of emails per task</th>
                <td>
                    <?php $nc->text('scheduler_max', 5); ?>
                    (good value is 20 to 50)
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>
        <?php } else { ?>
        <p><strong>Available only with <a href="http://www.satollo.net/plugins/newsletter-extras">Newsletter Extras</a> package</strong></p>
        <?php } ?>


        <h3>Test subscribers</h3>
        <p>
            Define more test subscriber to see how your email looks on different clients:
            GMail, Outlook, Thunderbird, Hotmail, ...
        </p>

        <table class="form-table">
            <?php for ($i=1; $i<=10; $i++) { ?>
            <tr valign="top">
                <th>Subscriber <?php echo $i; ?></th>
                <td>
                    name: <input name="options[test_name_<?php echo $i; ?>]" type="text" size="30" value="<?php echo htmlspecialchars($options['test_name_' . $i])?>"/>
                    &nbsp;&nbsp;&nbsp;
                    email:<input name="options[test_email_<?php echo $i; ?>]" type="text" size="30" value="<?php echo htmlspecialchars($options['test_email_' . $i])?>"/>
                </td>
            </tr>
            <?php } ?>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>

    </form>
</div>
