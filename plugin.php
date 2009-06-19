<?php
/*
Plugin Name: Newsletter
Plugin URI: http://www.satollo.net/plugins/newsletter
Description: Newsletter is a simple plugin (still in developement) to collect subscribers and send out newsletters
Version: 1.1.2
Author: Satollo
Author URI: http://www.satollo.net
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

/*	Copyright 2008  Satollo  (email: info@satollo.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

define('NEWSLETTER', true);

require_once(dirname(__FILE__) . '/widget.php');

global $newsletter_default_options;


global $newsletter_labels;
function newsletter_init_labels()
{
    global $newsletter_labels;

    @include(dirname(__FILE__) . '/languages/en_US.php');
    if (WPLANG != '') @include(dirname(__FILE__) . '/languages/' . WPLANG . '.php');
    @include_once(ABSPATH . 'wp-content/newsletter/labels.php');
}

function newsletter_label($name)
{
    global $newsletter_labels;

    if ($newsletter_labels) return $newsletter_labels[$name];
    newsletter_init_labels();
    return $newsletter_labels[$name];
}

function newsletter_echo($name)
{
    echo newsletter_label($name);
}


$newsletter_step = 'subscription';

$newsletter_subscriber;

function newsletter_request($name, $default=null )
{
    if ( !isset($_POST[$name]) ) {
        return $default;
    }
    if ( get_magic_quotes_gpc() ) {
        return newsletter_stripslashes($_POST[$name]);
    }
    else {
        return $_POST[$name];
    }
}

function newsletter_stripslashes($value)
{
    $value = is_array($value) ? array_map('newsletter_stripslashes', $value) : stripslashes($value);
    return $value;
}

function newsletter_embed_form()
{
    $options = get_option('newsletter');
    echo str_replace('{newsletter_url}', $options['url'], newsletter_label('embedded_form'));
}

add_shortcode('newsletter', 'newsletter_call');

function newsletter_call($attrs, $content=null)
{
    global $newsletter_step, $newsletter_subscriber;

    $options = get_option('newsletter');

    $buffer = '';

    // When a user is starting the subscription process
    if ($newsletter_step == 'subscription')
    {
        $buffer .= $options['subscription_text'];
        $buffer .= newsletter_label('subscription_form');
    }

    // When a user has asked to subscribe and the connfirmation request has been sent
    if ($newsletter_step == 'subscribed')
    {
        $text = newsletter_replace($options['subscribed_text'], $newsletter_subscriber);
        $buffer .= $text;
    }

    if ($newsletter_step == 'confirmed')
    {
        $text = newsletter_replace($options['confirmed_text'], $newsletter_subscriber);
        $buffer .= $text;
    }

    // Here we are when an unsubscription is requested. There are two kind of unsubscription: the
    // ones with email and token, so the user has only to confire and the ones without
    // data, so the user is requested to insert his email. In the latter case an email
    // will be sent to the user with alink to confirm the email removal.
    if ($newsletter_step == 'unsubscription' || $newsletter_step == 'unsubscription_error')
    {
        $newsletter_subscriber = newsletter_get_subscriber($_REQUEST['ne']);
        $buffer = newsletter_replace($options['unsubscription_text'], $newsletter_subscriber);
        $url = $options['url'] . '?na=uc&amp;ne=' . urlencode($_REQUEST['ne']) .
        '&amp;nt=' . $_REQUEST['nt'];
        $buffer = newsletter_replace_url($buffer, 'UNSUBSCRIPTION_CONFIRM_URL', $url);
    }

    if ($newsletter_step == 'unsubscribed')
    {
        $text = $options['unsubscribed_text'];
        $text = newsletter_replace($text, $newsletter_subscriber);
        $buffer .= $text;
    }

    if ($newsletter_step == 'unsubscription_mm' || $newsletter_step == 'unsubscription_mm_error')
    {
        if ($newsletter_step == 'unsubscription_mm_error')
        {
            $buffer .= '<p>' . $options['unsubscription_mm_error'] . '</p>';
        }
        $buffer .= $options['unsubscription_mm_text'];
        $buffer .= '<form method="post" action="?">';
        $buffer .= '<input type="hidden" name="na" value="ue"/>';
        $buffer .= '<table cellspacing="3" cellpadding="3" border="0">';
        $buffer .= '<tr><td>' . $options['unsubscription_mm_email_label'] . '</td><td><input type="text" name="ne" size="20"/></td></tr>';
        $buffer .= '<tr><td colspan="2" style="text-align: center"><input type="submit" value="' . $options['unsubscription_mm_label'] . '"/></td></tr></table>';
        $buffer .= '</form>';
    }

    if ($newsletter_step == 'unsubscription_mm_end')
    {
        $text = $options['unsubscription_mm_end'];
        $text = str_replace('{name}', $newsletter_subscriber->name, $text);
        $buffer .= $text;
    }

    return $buffer;
}

/**
 * Sends out newsletters. If "from last" is true, the procedure read the last email
 * to which newsletter was sent and restart from it.
 * If max is sprecified, the function stops after that number of email.
 *
 * Return false IF emails have not been sent for each possible recipient. When return
 * false, stores on the database the last address to which the newsletter was sent.
 * Se the function can be called a second time to compete the task.
 *
 * If recipients are specified, from_last is not used but max is respected.
 */
function newsletter_send_batch($subject, $message, $simulate=true)
{
    global $wpdb;

    $options = get_option('newsletter');

    // Get infos on the last batch sent
    $last = get_option('newsletter_last');
    if (!is_array($last)) $last = array();

    $query = "select * from " . $wpdb->prefix . "newsletter where status='C'";
    if ($last['email']) $query .= " and email>'" . $wpdb->escape($last['email']) . "'";
    $recipients = $wpdb->get_results($query . " order by email");

    // This batch is empty...
    if (!$recipients)
    {
        $last['email'] = '';
        update_option('newsletter_last', $last);
        return true;
    }
    else
    {
        // If the batch id new store the total recipients
        if (!$last['email']) $last['total'] = count($recipients);
    }

    echo 'Queue: ' . count($recipients) . ' emails<br />';
    $start_time = time();
    $max_time = (int)(ini_get('max_execution_time') * 0.8);
    echo 'Max time: ' . $max_time . ' seconds<br />';
    echo 'Sending to: ';

    // Count the total emails sent
    if (!$last['sent']) $last['sent'] = 0;
    $idx = 0;
    foreach ($recipients as $r)
    {
        $m = $message;
        
        $url = newsletter_add_qs($options['url'],
            'na=u&amp;ne=' . urlencode($r->email) . '&amp;nt=' . $r->token);

        $m = newsletter_replace_url($m, 'UNSUBSCRIPTION_URL', $url);
        $m = newsletter_replace($m, $r);

        $s = $subject;
        $s = newsletter_replace($s, $r);

        //newsletter_log('Spedizione notifica a: ' . $r->email);
        if (!$simulate)
        {
            $x = newsletter_mail($r->email, $s, $m, true);
        }
        else
        {
            sleep(3);
        }

        echo $r->email . ' ';
        flush();
        $idx++;
        $last['sent']++;

        // Timeout check
        if ($max_time != 0 && (time()-$start_time) > $max_time)
        {
            $last['email'] = $r->email;
            update_option('newsletter_last', $last);
            return false;
        }
    }
    $last['email'] = '';
    update_option('newsletter_last', $last);
    return true;
}

/**
 * Send a newsletter to recipients (usually test subscribers). There is no
 * timeout management.
 */
function newsletter_send($subject, $message, $recipients = null)
{
    $options = get_option('newsletter');

    if (!$recipients) return true;

    foreach ($recipients as $r)
    {
        $m = $message;
        
        $url = newsletter_add_qs($options['url'],
            'na=u&amp;ne=' . urlencode($r->email) . '&amp;nt=' . $r->token);

        $m = newsletter_replace_url($m, 'UNSUBSCRIPTION_URL', $url);
        $m = newsletter_replace($m, $r);

        $s = $subject;
        $s = newsletter_replace($s, $r);

        $x = newsletter_mail($r->email, $s, $m, true);

        echo $r->email . ' ';
        flush();
    }
}

function newsletter_add_qs($url, $qs)
{
    if (strpos($url, '?') !== false) return $url . '&amp;' . $qs;
    else return $url . '?' . $qs;
}

/**
 * Add a request of newsletter subscription into the database with status "W" (waiting
 * confirmation) and sends out the confirmation request email to the subscriber.
 * The email will contain an URL (or link) the user has to follow to complete the
 * subscription (double opt-in).
 */
function newsletter_subscribe($email, $name)
{
    global $wpdb, $newsletter_subscriber;

    $options = get_option('newsletter');

    //newsletter_log('Iscrizione di ' . $email);

    $email = newsletter_normalize_email($email);

    // Check if this emailis already in our database: if so, just resend the
    // confirmation email.
    $newsletter_subscriber = newsletter_get_subscriber($email);
    if (!$newsletter_subscriber)
    {
        $token = md5(rand());
        $wpdb->query("insert into " . $wpdb->prefix . "newsletter (email, name, token) values ('" . $wpdb->escape($email) . "','" . $wpdb->escape($name) . "','" . $token . "')");
        $newsletter_subscriber = newsletter_get_subscriber($email);
        newsletter_log('nuova iscrizione');
    }
    else
    {
        $token = $newsletter_subscriber->token;
    }

    // The full URL to the confirmation page
    $url = $options['url'] . '?na=c&amp;ne=' . urlencode($email) . '&amp;nt=' . $token;

    $message = newsletter_replace_url($options['confirmation_message'], 'SUBSCRIPTION_CONFIRM_URL', $url);
    $message = newsletter_replace($message, $newsletter_subscriber);

    $subject = newsletter_replace($options['confirmation_subject'], $newsletter_subscriber);

    newsletter_mail($email, $subject, $message);

    $message = 'New subscription: ' . $name . ' <' . $email . '>';
    $subject = 'A new subscription';
    newsletter_notify_admin($subject, $message);
}

function newsletter_send_confirmation($subscriber)
{
    $options = get_option('newsletter');

    newsletter_log('newsletter_send_confirmation() - Sending a confirmation request message');
    // The full URL to the confirmation page
    $url = $options['url'] . '?na=c&amp;ne=' . urlencode($subscriber->email) .
    '&amp;nt=' . $subscriber->token;

    newsletter_log('newsletter_send_confirmation() - URL: ' . $url);

    $message = newsletter_replace_url($options['confirmation_message'], 'SUBSCRIPTION_CONFIRM_URL', $url);
    $message = newsletter_replace($message, $subscriber);

    $subject = newsletter_replace($options['confirmation_subject'], $subscriber);

    newsletter_mail($subscriber->email, $subject, $message);
}

/**
 * Return a subscriber by his email. The email will be sanitized and normalized
 * before issuing the query to the database.
 */
function newsletter_get_subscriber($email)
{
    global $wpdb;
    
    $recipients = $wpdb->get_results("select * from " . $wpdb->prefix .
    "newsletter where email='" . $wpdb->escape(newsletter_normalize_email($email)) . "'");
    if (!$recipients) return null;
    return $recipients[0];
}

function newsletter_get_all()
{
    global $wpdb;

    $recipients = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter order by email");
    return $recipients;
}

function newsletter_search($text)
{
    global $wpdb;
    if ($text == '')
    {
        $recipients = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter order by email");
    }
    else
    {
        $recipients = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter where email like '%" .
            $wpdb->escape($text) . "%' or name like '%" . $wpdb->escape($text) . "%' order by email");
    }
    if (!$recipients) return null;
    return $recipients;
}


/**
 * Normalize an email address,making it lowercase and trimming spaces.
 */
function newsletter_normalize_email($email)
{
    return strtolower(trim($email));
}

add_action('init', 'newsletter_init');
/**
 * Intercept the request parameters which drive the subscription and unsubscription
 * process.
 */
function newsletter_init()
{
    global $newsletter_step, $wpdb, $newsletter_subscriber;
    global $hyper_cache_stop;

    $options = get_option('newsletter');

    // "na" always is the action to be performed - stands for "newsletter action"
    $action = $_REQUEST['na'];
    if (!$action) return;

    $hyper_cache_stop = true;
    
    if ($action == 'subscribe' || $action == 's')
    {
        if (!newsletter_is_email($_REQUEST['ne'])) {
            die(newsletter_label('error_email'));
        }
        if (trim($_REQUEST['nn']) == '') {
            die(newsletter_label('error_name'));
        }
        newsletter_subscribe($_REQUEST['ne'], $_REQUEST['nn']);
        $newsletter_step = 'subscribed';
    }

    if ($action == 'confirm' || $action == 'c')
    {
        newsletter_confirm($_REQUEST['ne'], $_REQUEST['nt']);
        $newsletter_step = 'confirmed';
    }

    // Unsubscription process has 2 options: if email and token are specified the user
    // will only be asked to confirm. If there is no infos of who remove (when
    // mass mail mode is used) the user will be asked to type the emailto be removed.
    if ($action == 'unsubscribe' || $action == 'u')
    {
        if (!$_REQUEST['ne'] || !$_REQUEST['nt'])
        $newsletter_step = 'unsubscription_mm';
        else
        $newsletter_step = 'unsubscription';
    }

    // Sends the unsubscription confirmation email
    if ($action == 'ue')
    {
        $email = newsletter_normalize_email($_REQUEST['ne']);
        $recipients = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter where email='" . $wpdb->escape($email) . "'");
        if (!$recipients)
        {
            $newsletter_step = 'unsubscription_mm_error';
            return;
        }

        $name = $recipients[0]->name;
        $token = $recipients[0]->token;
        $url = $options['url'] . '?na=uc&amp;ne=' . urlencode($email) . '&amp;nt=' . $token;
        $message = $options['unsubscription_mm_message'];
        $message = str_replace('{unsubscription_url}', $url, $message);
        $message = str_replace('{unsubscription_link}',
            '<a href="' . $url .
            '">' . $options['unsubscription_mm_link'] . '</a>', $message);

        $message = str_replace('{name}', $name, $message);

        $subject = $options['unsubscription_mm_subject'];
        $subject = str_replace('{name}', $name, $subject);
        newsletter_mail($email, $subject, $message);

        $newsletter_step = 'unsubscription_mm_end';
    }

    if ($action == 'unsubscribe_confirm' || $action == 'uc')
    {
        newsletter_unsubscribe($_REQUEST['ne'], $_REQUEST['nt']);
        $newsletter_step = 'unsubscribed';
    }

}

/**
 * Deletes a subscription (no way back). Fills the global $newsletter_subscriber
 * with subscriber data.
 *
 * @param <type> $email
 * @param <type> $token
 */
function newsletter_unsubscribe($email, $token)
{
    global $newsletter_subscriber, $wpdb;

    // Save the subscriber for good bye page
    $newsletter_subscriber = newsletter_get_subscriber($email);

    $wpdb->query("delete from " . $wpdb->prefix . "newsletter where email='" . $wpdb->escape($email) . "'" .
        " and token='" . $wpdb->escape($token) . "'");

    $message = 'Unsubscription: ' . $name . ' <' . $email . '>';
    $subject = 'Unsubscription';
    newsletter_notify_admin($subject, $newsletter);
}

function newsletter_delete($email)
{
    global $wpdb;

    $wpdb->query("delete from " . $wpdb->prefix . "newsletter where email='" . $wpdb->escape($email) . "'");
}

function newsletter_delete_all($status=null)
{
    global $wpdb;

    if ($status == null)
    {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter");
    }
    else
    {
        $wpdb->query("delete from " . $wpdb->prefix . "newsletter where status='" . $wpdb->escape($status) . "'");
    }
}

/**
 * Confirms a subscription identified by emailand token, changing it's status on
 * database. Fill the global $newsletter_subscriber with user data.
 *
 * @param string $email
 * @param string $token
 */
function newsletter_confirm($email, $token)
{
    global $wpdb, $newsletter_subscriber;

    $options = get_option('newsletter');
    
    $wpdb->query("update " . $wpdb->prefix . "newsletter set status='C' where email='" . $wpdb->escape($email) . "'" .
        " and token='" . $wpdb->escape($token) . "'");

    $newsletter_subscriber = newsletter_get_subscriber($email);

    $message = newsletter_replace($options['confirmed_message'], $newsletter_subscriber);

    $subject = newsletter_replace($options['confirmed_subject'], $newsletter_subscriber);

    newsletter_mail($email, $subject, $message);
}

function newsletter_set_status($email, $status)
{
    global $wpdb;

    $wpdb->query("update " . $wpdb->prefix . "newsletter set status='" . $status . "' where email='" . $wpdb->escape($email) . "' limit 1");
}

function newsletter_notify_admin(&$subject, &$message)
{
    $to = get_option('admin_email');
    $headers  = "MIME-Version: 1.0\n";
    $headers .= "Content-type: text/plain; charset=UTF-8\n";
    mail($to, $subject, $message, $headers);
}

/**
 * Sends out an email (html or text).
 */
function newsletter_mail($to, &$subject, &$message, $html=true)
{
    global $wpdb;

    $options = get_option('newsletter');

    $from_email = $options['from_email'];
    $from_name = $options['from_name'];

    $headers  = "MIME-Version: 1.0\n";
    if ($html)
    $headers .= "Content-type: text/html; charset=UTF-8\n";
    else
    $headers .= "Content-type: text/plain; charset=UTF-8\n";

    $headers .= 'From: "' . $from_name . '" <' . $from_email . ">\n";

    $subject = '=?UTF-8?B?' . base64_encode($subject) . '?=';

    if ($options['sendmail'])
    {
        return mail($to, $subject, $message, $headers, "-f" . $from_email);
    }
    else
    {
        return mail($to, $subject, $message, $headers);
    }
}


add_action('activate_newsletter/plugin.php', 'newsletter_activate');
function newsletter_activate()
{
    global $wpdb;

    // SQL to create the table
    $sql = 'create table if not exists ' . $wpdb->prefix . 'newsletter (
        `name` varchar (100) not null default \'\',
        `email` varchar (100) not null default \'\',
        `token` varchar (50) not null default \'\',
        `status` varchar (1) not null default \'S\',
        primary key (`email`),
        key `email_token` (`email`,`token`)
        )';

    $wpdb->query($sql);
    
    $options = get_option('newsletter');

    // Load the default options
    @include_once(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '') @include_once(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');
    //@include_once(ABSPATH . 'wp-content/newsletter/languages/custom_options.php');

    if (is_array($options)) $options = array_merge($newsletter_default_options, $options);
    else $options = $newsletter_default_options;

    newsletter_log('Plugin activated', true);
    
    update_option('newsletter', $options);
}

add_action('admin_menu', 'newsletter_admin_menu');
function newsletter_admin_menu()
{
    if (function_exists('add_menu_page'))
    {
        add_menu_page('Newsletter', 'Newsletter', 10, 'newsletter/options.php', '', '');
    }
    
    if (function_exists('add_submenu_page'))
    {
        add_submenu_page('newsletter/options.php', 'Configuration', 'Configuration', 10, 'newsletter/options.php');
        add_submenu_page('newsletter/options.php', 'Composer', 'Composer', 10, 'newsletter/newsletter.php');
        add_submenu_page('newsletter/options.php', 'Import', 'Import', 10, 'newsletter/import.php');
        add_submenu_page('newsletter/options.php', 'Manage', 'Manage', 10, 'newsletter/manage.php');
    }
}

/**
 * Fills a text with sunscriber data and blog data replacing some place holders.
 */
function newsletter_replace($text, $subscriber)
{
    $text = str_replace('{home_url}', get_option('home'), $text);
    $text = str_replace('{blog_title}', get_option('blogname'), $text);
    $text = str_replace('{email}', $subscriber->email, $text);
    $text = str_replace('{name}', $subscriber->name, $text);
    return $text;
}

/**
 * Replaces the URL placeholders. There are two kind of URL placeholders: the ones
 * lowercase and betweeb curly brakets and the ones all uppercase. The tag to be passed
 * is the one all uppercase but the lowercase one will also be replaced.
 */
function newsletter_replace_url($text, $tag, $url)
{
    $home = get_option('home') . '/';
    $tag_lower = strtolower($tag);
    $text = str_replace($home . '{' . $tag_lower . '}', $url, $text);
    $text = str_replace($home . '%7B' . $tag_lower . '%7D', $url, $text);
    $text = str_replace('{' . $tag_lower . '}', $url, $text);

    // for compatibility
    $text = str_replace($home . $tag, $url, $text);

    return $text;
}

function newsletter_is_email($email, $empty_ok=false)
{
    $email = trim($email);
    if ($empty_ok && $email == '') return true;

    if (eregi("^([a-z0-9_\.-])+@(([a-z0-9_-])+\\.)+[a-z]{2,6}$", trim($email)))
    return true;
    else
    return false;
}

/**
 * Write a line of log in the log file if the logs are enabled or force is
 * set to true.
 */
function newsletter_log($text, $force=false)
{
    $options = get_option('newsletter');

    if (!$force && !$options['logs']) return;
    
    $file = fopen(dirname(__FILE__) . '/newsletter.log', 'a');
    if (!$file) return;
    fwrite($file, date('Y-m-d') . ' ' . $text . "\n");
    fclose($file);
}
?>
