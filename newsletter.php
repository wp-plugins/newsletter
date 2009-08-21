<?php
$options = get_option('newsletter_email');

if (isset($_POST['save']))
{
    if (!check_admin_referer()) die('No hacking please');
    $options = newsletter_request('options');
    update_option('newsletter_email', $options);
}

if (isset($_POST['auto']))
{
    global $post;

    if (!check_admin_referer()) die('No hacking please');

    // Load the theme
    if ($_POST['theme'][0] == '*')
    {
        $message = file_get_contents(ABSPATH . '/wp-content/newsletter/themes/' . substr($_POST['theme'], 1));
    }
    else
    {
        $message = file_get_contents(dirname(__FILE__) . '/themes/' . $_POST['theme']);
    }

    $myposts = get_posts('numberposts=10');
    $idx = 1;
    foreach($myposts as $post)
    {
        $content = $post->post_content;
        $x = strpos($content, '<!--more-->');
        if ($x !== false) $content = substr($content, 0, $x);
        $content = preg_replace('/\[[^\]]*\]/', '', $content);
        //$content = apply_filters('the_content', $content);

        $excerpt = strip_tags($content);
        if (strlen($excerpt) > 200) {
            $x = strpos($excerpt, ' ', 200);
            $excerpt = substr($excerpt, 0, $x) . '[...]';
        }

        $image = '';
        $x = stripos($content, '<img');

        if ($x !== false) {
            $x = stripos($content, 'src="', $x);
            if ($x !== false) {
                $x += 5;
                $y = strpos($content, '"', $x);
                $image = substr($content, $x, $y-$x);
            }
        }

        if ($image == '') $image = get_option('siteurl') . '/wp-content/plugins/newsletter/images/empty.gif';


        $message = str_replace('{excerpt_' . $idx . '}', $excerpt, $message);
        $message = str_replace('{content_' . $idx . '}', $content, $message);
        $message = str_replace('{link_' . $idx . '}', get_permalink(), $message);
        $message = str_replace('{title_' . $idx . '}', get_the_title(), $message);
        $message = str_replace('{image_' . $idx . '}', $image, $message);
        // image replacement
        $idx++;
    }
    $message = str_replace('{blog_title}', get_option('blogname'), $message);
    $message = str_replace('{home_url}', get_option('home'), $message);
    $options['message'] = $message;
}

$last = get_option('newsletter_last');
?>

<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode : "textareas",
        theme : "advanced",
        plugins: "table",
        theme_advanced_disable : "styleselect",
        theme_advanced_buttons3 : "tablecontrols",
        relative_urls : false,
        remove_script_host : false,
        document_base_url : "<?php echo get_option('home'); ?>/"

    });
</script>

<div class="wrap">
    <form method="post">
        <?php wp_nonce_field(); ?>

        <h2>Newsletter Composer</h2>

        <h3>Last batch infos</h3>
        <?php if (!$last) { ?>
        <p>No batch info found.</p>
        <?php } else { ?>
        <p>
            Total emails to send: <?php echo $last['total']; ?><br />
            Emails sent till now: <?php echo $last['sent']; ?><br />
            Last email (if empty the batch has completed): <?php echo $last['email']; ?><br />
        </p>
        <?php } ?>



    <?php if (isset($_POST['test'])) { ?>
        <?php if (!check_admin_referer()) die('No hacking please'); ?>
        <h3>Sending to test subscribers</h3>
        <p>
            <?php
            update_option('newsletter_last', array());
            $options = newsletter_request('options');
            update_option('newsletter_email', $options);
            $subscribers = array();
            for ($i=1; $i<=10; $i++)
            {
                if (!$options['test_email_' . $i]) continue;
                $s = newsletter_get_subscriber($options['test_email_' . $i]);
                if ($s) $subscribers[$i-1]= $s;
                else {
                    $subscribers[$i-1]->name = $options['test_name_' . $i];
                    $subscribers[$i-1]->email = $options['test_email_' . $i];
                    $subscribers[$i-1]->token = 'FAKETOKEN';
                }
            }
            newsletter_send($options['subject'], $options['message'], $subscribers);
            ?>
        </p>

    <?php } ?>


<?php if (isset($_POST['simulate']) || isset($_POST['simulate2'])) { ?>
        <?php if (!check_admin_referer()) die('No hacking please'); ?>

        <h3>Sending for simulation</h3>
        <p>There is a little delay between each email sending to simulate mailing process.</p>
        <?php
        if (isset($_POST['simulate']))
        {
            $options = newsletter_request('options');
            update_option('newsletter_email', $options);
            update_option('newsletter_last', array());
        }
        echo '<p>';
        $res = newsletter_send_batch($options['subject'], $options['message'], true);
        echo '</p>';
        if (!$res)
        {
            echo '</p><form action="" method="post">Still some emails to send.';
            echo '<input type="submit" name="simulate2" value="Proceed"/>';
            echo '</form>';
        }
        ?>


        <?php } ?>


    <?php if (isset($_REQUEST['send']) || isset($_POST['send2'])) { ?>
        <?php if (!check_admin_referer()) die('No hacking please'); ?>

        <h3>Sending for real</h3>
        <?php
        if (isset($_POST['send']))
        {
            $options = newsletter_request('options');
            update_option('newsletter_email', $options);
            update_option('newsletter_last', array());
        }
        echo '<p>';
        $res = newsletter_send_batch($options['subject'], $options['message'], false);
        echo '</p>';
        if (!$res)
        {
            echo '</p><form action="" method="post">Still some emails to send.';
            wp_nonce_field();
            echo '<input type="submit" name="send2" value="Proceed"/>';
            echo '</form>';
        }
        ?>

        <?php } ?>

        <!--
/*
* \(<a[^>]href=["']{0,1})(.*)(["']{0,1}[^>]>)\i
[15.45.01] Davide Pozza: (<\s*[A]\s[^>]*[\n\s]*)(href\s*=\s*([^>|\s]*))[^>]*>
*/
-->


        <h3>Newsletter message</h3>
        <p>PHP execution timeout is set to <?php set_time_limit(0); echo ini_get('max_execution_time'); ?> (information
        for debug purpose).</p>
        <table class="form-table">
            <tr valign="top">
                <td>
                    Subject<br />
                    <input name="options[subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['subject'])?>"/>
                    <br />
                    Tags: <strong>{name}</strong> receiver name.
                </td>
            </tr>
            <tr valign="top">
                <td>
                    Message<br />
                    <textarea name="options[message]" wrap="off" rows="20" style="width: 100%"><?php echo htmlspecialchars($options['message'])?></textarea>
                    <br />
                    Tags: <strong>{name}</strong> receiver name;
                    <strong>{unsubscription_url}</strong> unsubscription URL.
                </td>
            </tr>
        </table>


        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
            <input class="button" type="submit" name="send" value="Send" onclick="return confirm('Send for real?')"/>
            <input class="button" type="submit" name="simulate" value="Simulate"  onclick="return confirm('Send for simulation?')"/>
            <?php if ($last['email'] != '') { ?>
            <input class="button" type="submit" name="restart" value="Restart send process"/>
            (last email: <?php echo get_option('newsletter_last'); ?>)
            <?php } ?>
            <input class="button" type="submit" name="test" value="Send test email"/>


            Theme:
            <select name="theme">
                <?php
                if ($handle = @opendir(ABSPATH . 'wp-content/plugins/newsletter/themes'))
                {
                    while ($file = readdir($handle))
                    {
                        if ($file == '.' || $file == '..') continue;
                        echo '<option value="' . $file . '">' . $file . '</option>';
                    }
                    closedir($handle);
                }

                if ($handle = @opendir(ABSPATH . 'wp-content/newsletter/themes'))
                {
                    while ($file = readdir($handle))
                    {
                        if ($file == '.' || $file == '..') continue;
                        echo '<option value="*' . $file . '">* ' . $file . '</option>';
                    }
                    closedir($handle);
                }
                ?>
            </select>
            <input class="button" type="submit" name="auto" value="Auto compose"/>
            <input class="button" type="submit" name="export" value="Export for Zanzara"/>
        </p>

        <h3>Test subscribers</h3>
        <p>Define more test subscriber to see how your email looks on different clients:
        GMail, Outlook, Thunderbird, Hotmail, ...</p>

        <table class="form-table">
            <?php for ($i=1; $i<=10; $i++) { ?>
            <tr valign="top">
                <th scope="row"><label>Subscriber <?php echo $i; ?></label></th>
                <td>
                    name: <input name="options[test_name_<?php echo $i; ?>]" type="text" size="30" value="<?php echo htmlspecialchars($options['test_name_' . $i])?>"/>
                    &nbsp;&nbsp;&nbsp;
                    email:<input name="options[test_email_<?php echo $i; ?>]" type="text" size="30" value="<?php echo htmlspecialchars($options['test_email_' . $i])?>"/>
                </td>
            </tr>
            <?php } ?>
        </table>
    </form>

</div>
