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

        $message = str_replace('{excerpt_' . $idx . '}', $excerpt, $message);
        $message = str_replace('{content_' . $idx . '}', $content, $message);
        $message = str_replace('{link_' . $idx . '}', get_permalink(), $message);
        $message = str_replace('{title_' . $idx . '}', get_the_title(), $message);
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
    echo '<h2>Sending test...</h2>';

    $options = newsletter_request('options');
    update_option('newsletter_email', $options);
    $subscribers = array();
    $subscribers[0]->name = $options['test_name_1'];
    $subscribers[0]->email = $options['test_email_1'];
    $subscribers[0]->token = 'FAKETOKEN';
    var_dump($subscribers);
    newsletter_send($options['subject'], $options['message'], $subscribers);
}
?>

<?php
if (isset($_POST['send']))
{
    echo '<h2>Sending...</h2>';
    
    $options = newsletter_request('options');
    update_option('newsletter_email', $options);

    newsletter_send($options['subject'], $options['message']);
}
?>

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
                    <textarea name="options[message]" wrap="off" rows="20" style="width: 600px"><?php echo htmlspecialchars($options['message'])?></textarea>
                    <br />
                    {name} will be replaced with receiver name; {unsubscription_url} will be replaced with the
                    unsubscription url but if you want to create a link with the editor, use UNSUBSCRIPTION_URL as address.
                </td>
            </tr>
        </table>
        

        <p class="submit">
            <input class="button" type="submit" name="save" value="Save"/>
            <input class="button" type="submit" name="send" value="Send"/>
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
            <tr valign="top">
                <th scope="row"><label>Name</label></th>
                <td>
                    <input name="options[test_name_1]" type="text" size="30" value="<?php echo htmlspecialchars($options['test_name_1'])?>"/>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><label>Email</label></th>
                <td>
                    <input name="options[test_email_1]" type="text" size="30" value="<?php echo htmlspecialchars($options['test_email_1'])?>"/>
                </td>
            </tr>
        </table>
    </form>

</div>
