<?php

error_reporting(E_ALL ^ E_NOTICE);

$newsletter->set_limits();

if (!isset($newsletter_options_main['no_translation'])) {
    $plugin_dir = basename(dirname(__FILE__));
    load_plugin_textdomain('newsletter', 'wp-content/plugins/' . $plugin_dir . '/languages/');
}

$action = $_REQUEST['act'];
$step = null;
if (isset($action) && !check_admin_referer()) die('Invalid call');
$errors = null;
$messages = null;

/**
 * Utility class to generate HTML form fields.
 */
class NewsletterControls {

    var $data;
    var $action = false;

    function is_action($action = null) {
        if ($action == null) return !empty($_REQUEST['act']);
        if (empty($_REQUEST['act'])) return false;
        if ($_REQUEST['act'] != $action) return false;
        if (check_admin_referer ()) return true;
        die('Invalid call');
    }

    function errors($errors) {
        if (is_null($errors)) return;
        echo '<script type="text/javascript">';
        echo 'alert("' . addslashes($errors) . '");';
        echo '</script>';
    }

    function messages($messages) {
        if (is_null($messages)) return;
        echo '<script type="text/javascript">';
        echo 'alert("' . addslashes($messages) . '");';
        echo '</script>';
    }

    function NewsletterControls($options=null) {
        if ($options == null) $this->data = stripslashes_deep($_POST['options']);
        else $this->data = $options;
    }

    function yesno($name) {
        $value = isset($this->data[$name]) ? (int) $this->data[$name] : 0;

        echo '<select style="width: 60px" name="options[' . $name . ']">';
        echo '<option value="0"';
        if ($value == 0) echo ' selected';
        echo '>No</option>';
        echo '<option value="1"';
        if ($value == 1) echo ' selected';
        echo '>Yes</option>';
        echo '</select>&nbsp;&nbsp;&nbsp;';
    }

    function enabled($name) {
        $value = isset($this->data[$name]) ? (int) $this->data[$name] : 0;

        echo '<select style="width: 100px" name="options[' . $name . ']">';
        echo '<option value="0"';
        if ($value == 0) echo ' selected';
        echo '>Disabled</option>';
        echo '<option value="1"';
        if ($value == 1) echo ' selected';
        echo '>Enabled</option>';
        echo '</select>';
    }

    function select($name, $options, $first = null) {
        $value = $this->data[$name];

        echo '<select id="options-' . $name . '" name="options[' . $name . ']">';
        if (!empty($first)) {
            echo '<option value="">' . htmlspecialchars($first) . '</option>';
        }
        foreach ($options as $key => $label) {
            echo '<option value="' . $key . '"';
            if ($value == $key) echo ' selected';
            echo '>' . htmlspecialchars($label) . '</option>';
        }
        echo '</select>';
    }

    function select_grouped($name, $groups) {
        $value = $this->data[$name];

        echo '<select name="options[' . $name . ']">';

        foreach ($groups as $group) {
            echo '<optgroup label="' . htmlspecialchars($group['']) . '">';
            if (!empty($group)) {
            foreach ($group as $key => $label) {
                if ($key == '') continue;
                echo '<option value="' . $key . '"';
                if ($value == $key) echo ' selected';
                echo '>' . htmlspecialchars($label) . '</option>';
            }
            }
            echo '</optgroup>';
        }
        echo '</select>';
    }

    function page_themes($name) {
        $themes[''] = 'Standard page themes';
        $themes['page-1'] = 'Page theme 1';

        $this->select_grouped($name, array(
            array_merge(array('' => 'Custom page themes'), newsletter_page_get_themes()),
            $themes
        ));
    }
    
    function value($name) {
        echo htmlspecialchars($this->data[$name]);
    }

    function value_date($name) {
        $time = $this->data[$name];
        echo gmdate(get_option('date_format') . ' ' . get_option('time_format'), $time + get_option('gmt_offset') * 3600);
    }

    function text($name, $size=20) {
        echo '<input name="options[' . $name . ']" type="text" size="' . $size . '" value="';
        echo htmlspecialchars($this->data[$name]);
        echo '"/>';
    }

    function hidden($name) {
        echo '<input name="options[' . $name . ']" type="hidden" value="';
        echo htmlspecialchars($this->data[$name]);
        echo '"/>';
    }

    function button($action, $label, $function=null) {
        if (!$this->action) echo '<input name="act" type="hidden" value=""/>';
        $this->action = true;
        if ($function != null) {
            echo '<input class="button-secondary" type="button" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\';' . htmlspecialchars($function) . '"/>';
        }
        else {
            echo '<input class="button-secondary" type="button" value="' . $label . '" onclick="this.form.act.value=\'' . $action . '\';this.form.submit()"/>';
        }
    }

    function button_confirm($action, $label, $message, $data='') {
        if (!$this->action) echo '<input name="act" type="hidden" value=""/>';
        $this->action = true;
        echo '<input class="button-secondary" type="button" value="' . $label . '" onclick="this.form.btn.value=\'' . $data . '\';this.form.act.value=\'' . $action . '\';if (confirm(\'' .
        htmlspecialchars($message) . '\')) this.form.submit()"/>';
    }

    function editor($name, $rows=5, $cols=75) {
        echo '<textarea class="visual" name="options[' . $name . ']" style="width: 100%" wrap="off" rows="' . $rows . '" cols="' . $cols . '">';
        echo htmlspecialchars($this->data[$name]);
        echo '</textarea>';
    }

    function textarea($name, $width='100%', $height='50') {
        echo '<textarea class="dymanic" name="options[' . $name . ']" wrap="off" style="width:' . $width . ';height:' . $height . '">';
        echo htmlspecialchars($this->data[$name]);
        echo '</textarea>';
    }

    function textarea_fixed($name, $width='100%', $height='50') {
        echo '<textarea name="options[' . $name . ']" wrap="off" style="width:' . $width . ';height:' . $height . '">';
        echo htmlspecialchars($this->data[$name]);
        echo '</textarea>';
    }

    function email($prefix) {
        echo 'Subject:<br />';
        $this->text($prefix . '_subject', 70);
        echo '<br />Message:<br />';
        $this->editor($prefix . '_message');
    }

    function checkbox($name, $label='') {
        echo '<input type="checkbox" id="' . $name . '" name="options[' . $name . ']" value="1"';
        if (!empty($this->data[$name])) echo ' checked="checked"';
        echo '/>';
        if ($label != '') echo ' <label for="' . $name . '">' . $label . '</label>';
    }

    function hours($name) {
        $hours = array();
        for ($i = 0; $i < 24; $i++) {
            $hours['' . $i] = '' . $i;
        }
        $this->select($name, $hours);
    }

    function days($name) {
        $days = array(0 => 'Every day', 1 => 'Monday', 2 => 'Tuesday', 3 => 'Wednesday', 4 => 'Thursday', 5 => 'Friday', 6 => 'Saturday', 7 => 'Sunday');
        $this->select($name, $days);
    }

    function init() {
        echo '<script type="text/javascript">
    jQuery(document).ready(function(){
        jQuery("textarea.dynamic").focus(function() {
            jQuery("textarea.dynamic").css("height", "50px");
            jQuery(this).css("height", "400px");
        });
    });
</script>
';
        echo '<input name="act" type="hidden" value=""/>';
        echo '<input name="btn" type="hidden" value=""/>';
        $this->action = true;
        wp_nonce_field();
    }

    function save($table, $data=null) {
        global $wpdb;
        if ($data == null) $data = $this->data;
        $keys = array_keys($data);
        foreach ($keys as $key) {
            if ($key[0] == '_') unset($data[$key]);
        }
        $id = $data['id'];
        unset($data['id']);
        if (empty($id)) {
            $wpdb->insert($table, $data);
            $id = $wpdb->insert_id;
        }
        else {
            $wpdb->update($table, $data, array('id' => $id));
        }
        $this->data = $wpdb->get_row("select * from " . $table . " where id=" . $id, ARRAY_A);
    }

    function load($table, $id) {
        global $wpdb;
        if ($id == 0) $this->data = array('id' => 0);
        else $this->data = $wpdb->get_row("select * from " . $table . " where id=" . $id, ARRAY_A);
    }

    function update($table, $field, $value, $id=null) {
        global $wpdb;
        if ($id == null) $id = $this->data['id'];
        $wpdb->query("update " . $table . " set " . $field . "='" . mysql_escape_string($value) . "' where id=" . $id);
        $this->data[$field] = $value;
    }

}

$newsletter_options_main = get_option('newsletter_main', array());

function newsletter_search($text, $status='', $order='email', $list = null, $link = null) {
    global $wpdb;

    if (empty($order)) $order = 'email';
    if ($order == 'id') $order = 'id desc';

    $query = "select * from " . $wpdb->prefix . "newsletter where 1=1";
    if (!empty($status)) {
        $query .= " and status='" . $wpdb->escape($status) . "'";
    }

    if (trim($text) != '') {
        $query .= " and (email like '%" .
                $wpdb->escape($text) . "%' or name like '%" . $wpdb->escape($text) . "%')";
    }
    
    if (!empty($list)) {
        $query .= " and list_" . ((int)$list) . "=1";
    }
    
    if (!empty($link)) {
        list($newsletter, $url) = explode('|', $link);
        $query .= " and id in (select distinct newsletter_id from " . $wpdb->prefix . "newsletter_stats where newsletter='" .
                $wpdb->escape($newsletter) . "' and url='" . $wpdb->escape($url) . "')";
    }

    $query .= ' order by ' . $order;

    //if (empty($link)) $query .= ' limit 100';

    $recipients = $wpdb->get_results($query);

    if (!$recipients) return null;
    return $recipients;
}

function newsletter_get_test_subscribers() {
    global $newsletter, $wpdb;
    $subscribers = array();
    for ($i = 0; $i < 5; $i++) {
        if (!empty($newsletter->options_main['test_email_' . $i])) {
            $subscriber = new stdClass();
            $subscriber->name = $newsletter->options_main['test_name_' . $i];
            $subscriber->email = $newsletter->options_main['test_email_' . $i];
            $subscriber->sex = $newsletter->options_main['test_sex_' . $i];
            $subscriber->token = 'notokenitsatest';
            $subscriber->id = 0;
            $subscriber->feed_time = 0;
            $subscriber->followup_time = 0;

            $subscribers[] = $subscriber;
        }
    }
    $others = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter where test=1");
    if (!empty($others)) {
        foreach ($others as &$other) {
            $subscribers[] = $other;
        }
    }
    return $subscribers;    
}

function newsletter_delete_all($status=null) {
    global $wpdb;

    if ($status == null) {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter");
    }
    else {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter where status='" . $wpdb->escape($status) . "'");
    }
}

function newsletter_set_status_all($status) {
    global $wpdb;

    $wpdb->query("update " . $wpdb->prefix . "newsletter set status='" . $status . "'");
}

function newsletter_set_status($id, $status) {
    global $wpdb;

    $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter set status=%s where id=%d", $status, $id));
}

function newsletter_date($time=null, $now=false, $left=false) {
    if (is_null($time)) $time = time();
    if ($time === false) $buffer = 'none';
    else $buffer = gmdate(get_option('date_format') . ' ' . get_option('time_format'), $time + get_option('gmt_offset') * 3600);
    if ($now) {
        $buffer .= ' (now: ' . gmdate(get_option('date_format') . ' ' .
                        get_option('time_format'), time() + get_option('gmt_offset') * 3600);
        if ($left) {
            $buffer .= ', ' . gmdate('H:i:s', $time - time()) . ' left';
        }
        $buffer .= ')';
    }
    return $buffer;
}

/**
 * Retrieves a list of custom themes located under wp-plugins/newsletter-custom/themes.
 * Return a list of theme names (which are folder names where the theme files are stored.
 */
function newsletter_get_themes() {
    $handle = @opendir(ABSPATH . 'wp-content/plugins/newsletter-custom/themes');
    $list = array();
    if (!$handle) return $list;
    while ($file = readdir($handle)) {
        if ($file == '.' || $file == '..') continue;
        if (!is_dir(ABSPATH . 'wp-content/plugins/newsletter-custom/themes/' . $file)) continue;
        if (!is_file(ABSPATH . 'wp-content/plugins/newsletter-custom/themes/' . $file . '/theme.php')) continue;
        $list['*' . $file] = $file;
    }
    closedir($handle);
    return $list;
}

function newsletter_page_get_themes() {
    $handle = @opendir(ABSPATH . 'wp-content/plugins/newsletter-custom/themes-page');
    $list = array();
    if (!$handle) return $list;
    while ($file = readdir($handle)) {
        if ($file == '.' || $file == '..') continue;
        if (!is_dir(ABSPATH . 'wp-content/plugins/newsletter-custom/themes-page/' . $file)) continue;
        if (!is_file(ABSPATH . 'wp-content/plugins/newsletter-custom/themes-page/' . $file . '/theme.php')) continue;
        $list['*' . $file] = $file;
    }
    closedir($handle);
    return $list;
}

/**
 * $subscriber needs to be an array. If the key 'id' is set, the subscriber data will
 * be update, otherwise a new subscriber will be inserted.
 * When inserted as new, subscriber's data is returned with id, token and so on.
 *
 * @global <type> $wpdb
 * @param <type> $subscriber Save a new subscriber setting status to confirmed, creating
 */
function newsletter_save($subscriber) {
    global $wpdb, $newsletter;

    $email = $newsletter->normalize_email($email);
    $name = $newsletter->normalize_name($name);
    if (isset($subscriber['id'])) {
        $wpdb->update($wpdb->prefix . 'newsletter', $subscriber, array('id' => $subscriber['id']));
    }
    else {
        $subscriber['status'] = 'C';
        $subscriber['token'] = md5(rand());

        $wpdb->insert($wpdb->prefix . 'newsletter', $subscriber);
        $subscriber['id'] = $wpdb->insert_id;
    }
    return $subscriber;
}

function newsletter_get_subscriber_strict($id, $token) {
    if (is_null($token)) {
        newsletter_fatal(__FUNCTION__, 'Ivalid token');
        return null;
    }
    $s = newsletter_get_subscriber($id, $token);
    if (is_null($s)) {
        newsletter_fatal(__FUNCTION__, 'Subscriber not found or invalid token');
    }
    return $s;
}

function newsletter_get_subscriber($id, $token=null, $check_token=false) {
    global $wpdb;

    $recipients = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix .
                            "newsletter where id=%d", $id));
    if (!$recipients) return null;
    if ((!is_null($token) || $check_token) && $recipients[0]->token != $token) return null;
    return $recipients[0];
}
?>
