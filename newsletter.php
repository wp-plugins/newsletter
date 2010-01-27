<?php
$options = get_option('newsletter_email');
$options_newsletter = get_option('newsletter');

if (isset($_POST['save']) && check_admin_referer()) {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter_email', $options);
}

// Auto composition
if (isset($_POST['auto']) && check_admin_referer()) {
// Load the theme
    if ($_POST['theme'][0] == '*') {
        $file = ABSPATH . '/wp-content/plugins/newsletter-custom/themes/' . substr($_POST['theme'], 1) . '/theme.php';
    }
    else if ($_POST['theme'][0] == '$') {
            $file = ABSPATH . '/wp-content/plugins/newsletter-extras/themes/' . substr($_POST['theme'], 1) . '/theme.php';
        }
        else {
            $file = dirname(__FILE__) . '/themes/' . $_POST['theme'] . '/theme.php';
        }


    // Execute the theme file and get the content generated
    ob_start();
    @include($file);
    $options['message'] = ob_get_contents();
    ob_end_clean();
}

// Reset the batch
if (isset($_POST['reset']) && check_admin_referer()) {
    newsletter_reset_batch();
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
    //$options = stripslashes_deep($_POST['options']);
    //update_option('newsletter_email', $options);
    $last = newsletter_load_batch_file();
    update_option('newsletter_last', $last);
}

$last = null;
?>

<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins: "table",
        theme_advanced_disable : "styleselect",
        theme_advanced_buttons1_add: "forecolor,blockquote,code",
        theme_advanced_buttons3 : "tablecontrols",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_toolbar_location : "top",
        document_base_url : "<?php echo get_option('home'); ?>/"
    });
</script>

<style>
    #newsletter h3 {
        margin-bottom: 0px;
        margin-top: 30px;
    }
    #newsletter .form-table {
        border: 1px solid #ccc;
        background-color: #f4f4f4;
    }
</style>

<div class="wrap" id="newsletter">

    <h2>Newsletter Composer</h2>

    <?php require_once 'header.php'; ?>

    <form method="post" action="">
        <?php wp_nonce_field(); ?>


        <?php if (isset($_POST['restart']) && check_admin_referer()) { ?>

        <h3>Continuing with previous batch</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                $last = newsletter_send_batch(true);
                if (isset($last['id'])) echo '<p>Batch not completed, see more below.</p>';
                ?>
        </div>

        <?php } ?>


        <?php if (isset($_POST['simulate']) && check_admin_referer()) { ?>

        <h3>Simulation</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                newsletter_reset_batch();

                $last = newsletter_send_batch(false, 0, true);

                if (isset($last['id'])) echo '<p>Batch not completed, see more below.</p>';
                ?>
        </div>

        <?php } ?>



        <?php if (isset($_REQUEST['send']) && check_admin_referer()) { ?>

        <h3>Sending</h3>
        <div class="form-table">
                <?php
                $options = stripslashes_deep($_POST['options']);
                update_option('newsletter_email', $options);
                newsletter_reset_batch();
                $last = newsletter_send_batch(false, 0, false);
                if (isset($last['id'])) echo '<p>Batch not completed, see more below.</p>';
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
                newsletter_send_batch(false, 0, false, $subscribers);
                ?>
        </div>

        <?php } ?>



        <h3>Last batch info</h3>
        <p>Here you find information about last batch. A sending batch may have completed
            or not and may be a simulation or not. When a batch is not complete you can use the "restart"
            button and the batch starts again from the last email address processed.</p>

        <?php if (!isset($last)) $last = get_option('newsletter_last'); ?>
        <?php if (!is_array($last) || empty($last)) { ?>

        <p><strong>No batch info found, it's ok!</strong></p>

        <?php } else { ?>

        <table class="form-table">
            <tr>
                <th>Total emails to send</th>
                <td><?php echo $last['total']; ?></td>
            </tr>
            <tr>
                <th>Emails sent till now</th>
                <td><?php echo $last['sent']; ?></td>
            </tr>
            <!--
            <tr>
                <td>List</td>
                <td><?php echo $last['list']; ?></td>
            </tr>
            -->
            <tr>
                <th>Sending type</th>
                <td><?php echo $last['simulate']?"simluation":"real"; ?></td>
            </tr>
            <tr>
                <th>Scheduled</th>
                <td>
                        <?php echo $last['scheduled']?"yes":"no"; ?>
                    (next:  <?php echo date('j/m/Y h:i', wp_next_scheduled('newsletter_cron_hook')); ?>,
                    now: <?php echo date('j/m/Y h:i'); ?>)

                </td>
            </tr>
            <tr>
                <th>Last subscriber</td>
                <td><?php echo htmlspecialchars($last['name']); ?> [<?php echo $last['email']; ?>]</th>
            </tr>
            <tr>
                <th>Last id</th>
                <td><?php echo $last['id']; ?> (debug info)</td>
            </tr>
            <tr>
                <th>Message</th>
                <td><?php echo $last['message']; ?></td>
            </tr>
        </table>

        <p class="submit">
                <?php if (isset($last['id'])) { ?>
            <input class="button" type="submit" name="restart" value="Restart batch"  onclick="return confirm('Continue with this batch?')"/>
                <?php } ?>
            <input class="button" type="submit" name="reset" value="Reset batch"  onclick="return confirm('Reset the batch status?')"/>
        </p>


        <?php } ?>



        <?php
        $batch_file = newsletter_load_batch_file();
        if ($batch_file != null) {
        ?>
        <h3>Warning</h3>
            <p>There is a batch saved to disk. That means an error occurred while sending.
                Would you try to restore
                that batch?<br />
                <input class="button" type="submit" name="restore" value="Restore batch data"  onclick="return confirm('Restore batch data?')"/>
                <br />
                (It won't be deleted from disk so you can try many times. It will be deleted only when you
                start a new sending process)
            </p>

        <?php } ?>






        <h3>Newsletter message</h3>

        <table class="form-table">
            <?php if (defined('NEWSLETTER_EXTRAS')) { ?>
            <tr valign="top">
                <th>Newsletter name</th>
                <td>
                    <input name="options[name]" type="text" size="20" value="<?php echo htmlspecialchars($options['name'])?>"/>
                    <br />
                    This symbolic name will be used to track the link clicks and associate them to a specific newsletter.
                    Keep the name compact and significative.
                </td>
            </tr>
            <tr valign="top">
                <th>Tracking</th>
                <td>
                    <input name="options[track]" value="1" type="checkbox" <?php echo $options['track']?'checked':''; ?>/>
                    Track link clicks
                    <br />
                    When this option is enabled, each link in the email text will be rewritten and clicks
                    on them intercepted.
                </td>
            </tr>
            <?php } else { ?>
            <tr valign="top">
                <th>Tracking</th>
                <td>Tracking options available with Newsletter Extras package</td>
            </tr>
            <?php } ?>

            <tr valign="top">
                <th>Subject</th>
                <td>
                    <input name="options[subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> receiver name.
                </td>
            </tr>
            <tr valign="top">
                <th>Message</th>
                <td>
                    <textarea name="options[message]" wrap="off" rows="20" style="width: 100%"><?php echo htmlspecialchars($options['message'])?></textarea>
                    <br />
                    Tags:
                    <strong>{name}</strong> receiver name;
                    <strong>{unsubscription_url}</strong> unsubscription URL;
                    <strong>{token}</strong> the subscriber token.
                    <pre><?php //echo htmlspecialchars(newsletter_relink($options['message']))?></pre>
                </td>
            </tr>
            <tr valign="top">
                <th>Theme</th>
                <td>
                    <select name="theme">
                        <optgroup label="Included themes">
                            <option value="default">Default</option>
                            <option value="with-picture">With picture</option>
                        </optgroup>
                        <optgroup label="Extras themes">
                            <?php
                            $themes = newsletter_get_extras_themes();

                            foreach ($themes as $theme) {
                                echo '<option value="$' . $theme . '">' . $theme . '</option>';
                            }
                            ?>
                        </optgroup>
                        <optgroup label="Custom themes">
                            <?php
                            $themes = newsletter_get_themes();

                            foreach ($themes as $theme) {
                                echo '<option value="*' . $theme . '">' . $theme . '</option>';
                            }
                            ?>
                        </optgroup>
                    </select>
                    <input class="button" type="submit" name="auto" value="Auto compose"/>
                </td>
            </tr>
            <tr valign="top">
                <th>Number of posts on theme</th>
                <td>
                    <input name="options[theme_posts]" type="text" size="5" value="<?php echo htmlspecialchars($options['theme_posts'])?>"/>
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


        <h3>Scheduler</h3>
        <p>Scheduler helps to send out a long list of emails slowly to not overload the server.</p>
        <?php if (defined('NEWSLETTER_EXTRAS')) { ?>
        <table class="form-table">
            <tr valign="top">
                <th>Interval between sending tasks</th>
                <td>
                    <input name="options[scheduler_interval]" type="text" size="5" value="<?php echo htmlspecialchars($options['scheduler_interval'])?>"/>
                    (minutes, minimum value is 10)
                </td>
            </tr>
            <tr valign="top">
                <th>Max number of emails per task</th>
                <td>
                    <input name="options[scheduler_max]" type="text" size="5" value="<?php echo htmlspecialchars($options['scheduler_max'])?>"/>
                    (good value is 20 but if you use an external SMTP go with 5)
                </td>
            </tr>
        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>
        <?php } else { ?>
        <p><strong>Available only with Newsletter Extras package</strong></p>
        <?php } ?>

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
        <p>Configuration for not scheduled sending process.</p>
        <table class="form-table">
            <tr valign="top">
                <th>Max emails in a single batch</th>
                <td>
                    <input name="options[max]" type="text" size="5" value="<?php echo htmlspecialchars($options['max'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th>Receiver address for simulation</th>
                <td>
                    <input name="options[simulate_email]" type="text" size="40" value="<?php echo htmlspecialchars($options['simulate_email'])?>"/>
                    <br />When you simulate a sending process, emails are really sent but all to this
                    email address. That helps to test out problems with mail server.
                </td>
            </tr>

        </table>
        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
        </p>
        <!--
        <tr valign="top">
            <td>
                Filter<br />
                <input name="options[filter]" type="text" size="30" value="<?php echo htmlspecialchars($options['filter'])?>"/>
            </td>
        </tr>
        -->


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
