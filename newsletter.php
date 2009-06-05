<?php
$options = get_option('newsletter_email');
if (isset($_POST['save']))
{
    $options = newsletter_request('options');
    update_option('newsletter_email', $options);
}

if (isset($_POST['auto']))
{
    global $post;

    // Load the theme
    $message = file_get_contents(dirname(__FILE__) . '/themes/' . $_POST['theme']);

    $myposts = get_posts('numberposts=5');
    $idx = 1;
    foreach($myposts as $post)
    {
        $content = $post->post_content;
        $x = strpos($content, '<!--more-->');
        if ($x !== false) $content = substr($content, 0, $x);
        $content = apply_filters('the_content', $content);

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
        <h2>Newsletter Composer</h2>

<?php
if (isset($_POST['test']))
{
    update_option('newsletter_last', array());
    echo '<h2>Sending test...</h2>';

    $options = newsletter_request('options');
    update_option('newsletter_email', $options);
    $subscribers = array();
    for ($i=1; $i<=10; $i++)
    {
        if (!$options['test_email_1']) continue;

        $subscribers[0]->name = $options['test_name_1'];
        $subscribers[0]->email = $options['test_email_1'];
        $subscribers[0]->token = 'FAKETOKEN';
    }
    //var_dump($subscribers);
    newsletter_send($options['subject'], $options['message'], $subscribers, 0, false);
}
?>

<?php
if (isset($_POST['simulate']))
{
    update_option('newsletter_last', array());
    $options = newsletter_request('options');
    update_option('newsletter_email', $options);
?>
    <h2>Sending simulation</h2>
    <p>There is a little delay between each email sending to simulate mailing process.</p>
    <script type="text/javascript">
    location.href=location.href + "&sendbatchsimulate=1";
    </script>

<?php 
    return;
}
?>

<?php
if (isset($_GET['sendbatchsimulate']))
{
    echo '<h2>Sending batch simulation</h2>';
    echo '<p><strong>NEVER CLOSE THIS BROWSER WINDOW!</strong></p>';

    $res = newsletter_send($options['subject'], $options['message']);
    if (!$res)
    {
        echo '<script type="text/javascript">';
        echo 'location.href=location.href';
        echo '</script>';
        return;
    }
}

?>



<?php
if (isset($_POST['send']) || isset($_POST['restart']))
{
    echo '<h2>Sending...</h2>';
    if (isset($_POST['send'])) update_option('newsletter_last', array());
    $options = newsletter_request('options');
    update_option('newsletter_email', $options);

    echo '<script type="text/javascript">';
    echo 'location.href=location.href + "&sendbatch=1";';
    echo '</script>';
    return;
}
?>

<?php
if (isset($_GET['sendbatch']))
{
    echo '<h2>Sending batch</h2>';
    echo '<p><strong>NEVER CLOSE THIS BROWSER WINDOW!</strong></p>';

    $res = newsletter_send($options['subject'], $options['message'], null, 0, false);
    if (!$res)
    {
        echo '<script type="text/javascript">';
        echo 'location.href=location.href';
        echo '</script>';
        return;
    }
}
/*
 * \(<a[^>]href=["']{0,1})(.*)(["']{0,1}[^>]>)\i
[15.45.01] Davide Pozza: (<\s*[A]\s[^>]*[\n\s]*)(href\s*=\s*([^>|\s]*))[^>]*>
 */
?>

<?php $last = get_option('newsletter_last'); ?>
<h2>Last batch</h2>
<?php if (!$last) { ?>
<p>No batch info found.</p>
<?php } else { ?>
<p>
    Total: <?php echo $last['total']; ?><br />
    Sent: <?php echo $last['sent']; ?><br />
    Last email: <?php echo $last['email']; ?> (if empty the batch has completed)<br />
</p>
<?php } ?>

<h2>Newsletter message</h2>
<p>PHP execution timeout is set to <?php echo ini_get('max_execution_time'); ?> (information
for debug purpose).</p>
        <table class="form-table">
            <tr valign="top">
                <td>
                    Subject<br />
                    <input name="options[subject]" type="text" size="50" value="<?php echo htmlspecialchars($options['subject'])?>"/>
                    <br />
                    {name} will be replaced with receiver name
                </td>
            </tr>        
            <tr valign="top">
                <td>
                    Message<br />
                    <textarea name="options[message]" wrap="off" rows="20" style="width: 100%"><?php echo htmlspecialchars($options['message'])?></textarea>
                    <br />
                    {name} will be replaced with receiver name; {unsubscription_url} will be replaced with the
                    unsubscription url but if you want to create a link with the editor, use UNSUBSCRIPTION_URL as address.
                </td>
            </tr>
        </table>
        

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
            <input class="button" type="submit" name="send" value="Send"/>
            <input class="button" type="submit" name="simulate" value="Simulate" onclick="return confirm('Simulation erases last batch data. Proceed?')"/>
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
            ?>
            </select>
            <input class="button" type="submit" name="auto" value="Auto compose"/>
        </p>

        <h2>Test subscriber</h2>
        <p>
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
