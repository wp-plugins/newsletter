<?php
/*
Plugin Name: Newsletter
Plugin URI: http://www.satollo.net/plugins/newsletter
Description: Newsletter is a simple plugin (still in developement) to collect subscribers and send out newsletters
Version: 1.4.5
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

define('NEWSLETTER', '1.4.3');

@include(ABSPATH . 'wp-content/plugins/newsletter-extras/newsletter-extras.php');

require_once(dirname(__FILE__) . '/widget.php');

global $newsletter_labels;
$newsletter_step = 'subscription';
global $newsletter_subscriber;

function newsletter_init_labels() {
    global $newsletter_labels;

    @include_once(dirname(__FILE__) . '/languages/en_US.php');
    if (WPLANG != '') @include_once(dirname(__FILE__) . '/languages/' . WPLANG . '.php');
    @include_once(ABSPATH . 'wp-content/plugins/newsletter-custom/languages/en_US.php');
    if (WPLANG != '') @include_once(ABSPATH . 'wp-content/plugins/newsletter-custom/languages/' . WPLANG . '.php');
}

function newsletter_label($name) {
    global $newsletter_labels;

    if ($newsletter_labels) return $newsletter_labels[$name];
    newsletter_init_labels();
    return $newsletter_labels[$name];
}

function newsletter_echo($name) {
    echo newsletter_label($name);
}

function newsletter_request($name, $default=null ) {
    if (!isset($_REQUEST[$name])) return $default;
    return stripslashes_deep($_REQUEST[$name]);
}

function newsletter_subscribers_count() {
    global $wpdb;

    return $wpdb->get_var("select count(*) from " . $wpdb->prefix . "newsletter where status='C'");
}

function newsletter_embed_form() {
    $options = get_option('newsletter');
    echo '<div class="newsletter-embed-form">';
    if (isset($options['noname'])) {
        echo str_replace('{newsletter_url}', $options['url'], newsletter_label('embedded_form_noname'));
    }
    else {
        echo str_replace('{newsletter_url}', $options['url'], newsletter_label('embedded_form'));
    }
    echo '</div>';
}

if (!is_admin()) {
    add_shortcode('newsletter', 'newsletter_call');
    add_shortcode('newsletter_form', 'newsletter_form_call');
}

function newsletter_form_call($attrs, $content=null) {
    $options = get_option('newsletter');
    echo '<div class="newsletter-embed-form">';
    if (!isset($options['noname'])) {
        return str_replace('{newsletter_url}', $options['url'], newsletter_label('embedded_form'));
    }
    else {
        return str_replace('{newsletter_url}', $options['url'], newsletter_label('embedded_form_noname'));
    }
    echo '</div>';
}

function newsletter_call($attrs, $content=null) {
    global $newsletter_step, $newsletter_subscriber;

    $options = get_option('newsletter');

    $buffer = '';

    // When a user is starting the subscription process
    if ($newsletter_step == 'subscription') {
        $buffer .= $options['subscription_text'];

        if (isset($options['noname'])) {
            $buffer .= newsletter_label('subscription_form_noname');
        }
        else {
            $buffer .= newsletter_label('subscription_form');
        }
        if (!defined('NEWSLETTER_EXTRAS'))
            $buffer .=  '<div style="text-align:right;padding:0 10px;margin:0;"><a style="font-size:9px;color:#bbb;text-decoration:none" href="http://www.satollo.net">by satollo.net</a></div>';
    }

    // When a user asked to subscribe and the connfirmation request has been sent
    if ($newsletter_step == 'subscribed') {
        $text = newsletter_replace($options['subscribed_text'], $newsletter_subscriber);
        $buffer .= $text;
    }

    if ($newsletter_step == 'confirmed') {
        $text = newsletter_replace($options['confirmed_text'], $newsletter_subscriber);
        $buffer .= $text;

        if (isset($options['confirmed_tracking'])) {
            ob_start();
            eval('?>' . $options['confirmed_tracking']);
            $buffer .= ob_get_clean();
        }
    }

    // Here we are when an unsubscription is requested. There are two kind of unsubscription: the
    // ones with email and token, so the user has only to confire and the ones without
    // data, so the user is requested to insert his email. In the latter case an email
    // will be sent to the user with alink to confirm the email removal.
    if ($newsletter_step == 'unsubscription' || $newsletter_step == 'unsubscription_error') {
        $newsletter_subscriber = newsletter_get_subscriber($_REQUEST['ni']);
        $buffer = newsletter_replace($options['unsubscription_text'], $newsletter_subscriber);
        $url = newsletter_add_qs($options['url'], 'na=uc&amp;ni=' . $newsletter_subscriber->id .
            '&amp;nt=' . $_REQUEST['nt']);
        $buffer = newsletter_replace_url($buffer, 'UNSUBSCRIPTION_CONFIRM_URL', $url);
    }

    // Last message shown to user to say good bye
    if ($newsletter_step == 'unsubscribed') {
        $text = $options['unsubscribed_text'];
        $text = newsletter_replace($text, $newsletter_subscriber);
        $buffer .= $text;
    }

    //    if ($newsletter_step == 'unsubscription_mm' || $newsletter_step == 'unsubscription_mm_error')
    //    {
    //        if ($newsletter_step == 'unsubscription_mm_error')
    //        {
    //            $buffer .= '<p>' . $options['unsubscription_mm_error'] . '</p>';
    //        }
    //        $buffer .= $options['unsubscription_mm_text'];
    //        $buffer .= '<form method="post" action="?">';
    //        $buffer .= '<input type="hidden" name="na" value="ue"/>';
    //        $buffer .= '<table cellspacing="3" cellpadding="3" border="0">';
    //        $buffer .= '<tr><td>' . $options['unsubscription_mm_email_label'] . '</td><td><input type="text" name="ne" size="20"/></td></tr>';
    //        $buffer .= '<tr><td colspan="2" style="text-align: center"><input type="submit" value="' . $options['unsubscription_mm_label'] . '"/></td></tr></table>';
    //        $buffer .= '</form>';
    //    }

    //    if ($newsletter_step == 'unsubscription_mm_end')
    //    {
    //        $text = $options['unsubscription_mm_end'];
    //        $text = str_replace('{name}', $newsletter_subscriber->name, $text);
    //        $buffer .= $text;
    //    }

    return '<div class="newsletter">' . $buffer . '</div>';
}

/**
 * Sends out newsletters.
 *
 * I recipients is an array of subscribers, other parameters are ignored and a test
 * batch is started. This parameter has priority over all.
 *
 * If continue is true, the system try to continue a previous batch keeping its
 * configuration (eg. if it was a simulation or not).
 *
 * If continue is false, simulate indicates if the batch is a simulation and forces
 * the subscriber's email to a test one, as specified in the configuration.
 *
 * Return true if the batch is completed.
 */
function newsletter_send_batch($continue=true, $list=0, $simulate=true, $recipients=null) {
    global $wpdb;

    newsletter_log('newsletter_send_batch() - starting');

    $options = get_option('newsletter');
    $options_email = get_option('newsletter_email');

    $test = ($recipients != null);

    if (!$test) {
    // Get infos on the last batch sent
        $last = get_option('newsletter_last');
        newsletter_log('newsletter_send_batch() - last batch info: ' . print_r($last, true));

        // Check the "continue" ask validity (may be broken by database error on a
        // previous sending process
        if ($continue && !is_array($last)) return array();
        if ($continue && !isset($last['id'])) return $last;
        
        if (!$continue) {
            $last = array();
            $last['simulate'] = $simulate;
            $last['list'] = $list;
            $last['scheduled'] = false;
            $last['error'] = false;
        }
        else {
            $simulate = $last['simulate'];
            $list = $last['list'];
        }

        $query = "select * from " . $wpdb->prefix . "newsletter where status='C' and list=" . $list;
        if (isset($last['id'])) $query .= " and id>" . $last['id'];
        $recipients = $wpdb->get_results($query . " order by id");

        if (count($recipients) == 0) {
            newsletter_log('newsletter_send_batch() - no more recipients');
            unset($last['id']);
            update_option('newsletter_last', $last);
            return $last;
        }
        else {
        // If the batch is new store the total recipients
            if (!isset($last['id'])) $last['total'] = count($recipients);
        }
        if (!isset($last['sent'])) $last['sent'] = 0;
    }

    //    if ($filter)
    //    {
    //        $tmp = explode('=', $filter);
    //        if (count($tmp) == 2)
    //        {
    //            $filter[0] = array(trim($tmp[0])=>strtolower(trim($tmp[1])));
    //        }
    //        else $filter = null;
    //    }

    // This batch is empty...


    // Not all hosting provider allow this...
    @set_time_limit(100000);


    $start_time = time();
    $max_time = (int)(ini_get('max_execution_time') * 0.8);


    if ($last['scheduled']) {
        $max = $options_email['scheduler_max'];
        if (!is_numeric($max)) $max = 10;
    }
    else {
        $max = $options_email['max'];
        if (!is_numeric($max)) $max = 0;
    }

    if (!$last['scheduled']) {
        echo 'Queue: ' . count($recipients) . ' emails<br />';
        echo 'Max time: ' . $max_time . ' seconds<br />';
        echo 'Max emails: ' . $max . '<br />';
        echo 'Sending to: <br />';
    }
    // Count total emails sent

    $idx = 0;

    foreach ($recipients as $r) {
    //$profile = unserialize($r->profile);
    //        if (is_array($profile))
    //        {
    //            if (strtolower(trim($profile[$filter[0]['key']])) == $filter[0]
    //        }

        $m = $options_email['message'];

        $url = newsletter_add_qs($options['url'],
            'na=u&amp;ni=' . $r->id . '&amp;nt=' . $r->token);

        $m = newsletter_replace_url($m, 'UNSUBSCRIPTION_URL', $url);
        $m = newsletter_replace($m, $r);

        if (defined('NEWSLETTER_EXTRAS') && isset($options_email['track'])) $m = newsletter_relink($m, $r->id, $options_email['name']);

        $s = $options_email['subject'];
        $s = newsletter_replace($s, $r);

        //newsletter_log('Spedizione notifica a: ' . $r->email);
        if ($simulate) {
            $x = newsletter_mail($options_email['simulate_email'], $s, $m, true);
        }
        else {
            $x = newsletter_mail($r->email, $s, $m, true);
        }

        if (!$x) {
            newsletter_log('newsletter_send_batch() - Sending failed');
        }

        if (!$last['scheduled']) {
            echo htmlspecialchars($r->name) . ' [' . $r->id . ', ' . $r->email . '], ';
            flush();
        }
        $idx++;

        if (!$test) {
            $last['sent']++;

            $last['id'] = $r->id;
            $last['email'] = $r->email;
            $last['name'] = $r->name;

            // every 20 email, save the status on database
            if ($idx % 20 == 1) {
                newsletter_log('newsletter_send_batch() - Saving batch info: ' . print_r($last, true));
                // Message will be lost in scheduled operations
                if (!update_option('newsletter_last', $last)) {
                    $last['error'] = true;
                    newsletter_log('newsletter_send_batch() - Unable to save batch info to db: ' . $wpdb->last_error, true);
                    newsletter_log('newsletter_send_batch() - Unsaved batch: ' . print_r($last, true));
                    $last['message'] = 'FATAL ERROR. Unable to save batch info to db, see the log (db error: ' . $wpdb->last_error . ').';
                    return $last;
                }
            }

            // Check for the max emails per batch
            if ($max != 0 && $idx >= $max) {
                newsletter_log('newsletter_send_batch() - Batch limit reached');
                $last['message'] = 'Batch email limit reached, if scheduled the sending process will restart automatically';
                if (!update_option('newsletter_last', $last)) {
                    $last['error'] = true;
                    newsletter_log('newsletter_send_batch() - Unable to save batch info to db: ' . $wpdb->last_error, true);
                    newsletter_log('newsletter_send_batch() - Unsaved batch: ' . print_r($last, true));
                    $last['message'] = 'FATAL ERROR. Unable to save batch info to db, see the log (db error: ' . $wpdb->last_error . ').';
                }
                return $last;
            }

            // Timeout check, max time is zero if set_time_limit works
            if (($max_time != 0 && (time()-$start_time) > $max_time)) {
                newsletter_log('newsletter_send_batch() - Timeout reached');
                $last['message'] = 'Timeout reached';
                if (!update_option('newsletter_last', $last)) {
                    $last['error'] = true;
                    newsletter_log('newsletter_send_batch() - Unable to save batch info to db: ' . $wpdb->last_error, true);
                    newsletter_log('newsletter_send_batch() - Unsaved batch: ' . print_r($last, true));
                    $last['message'] = 'FATAL ERROR. Unable to save batch info to db, see the log (db error: ' . $wpdb->last_error . ').';
                }
                return $last;
            }
        }
    }

    if (!$test) {
        unset($last['id']);
        unset($last['email']);
        unset($last['name']);
        newsletter_log('newsletter_send_batch() - Batch completed');
        if (!update_option('newsletter_last', $last)) {
            $last['error'] = true;
            $last['message'] = 'FATAL ERROR. Unable to save batch info to db, see the log (db error: ' . $wpdb->last_error . '). The batch was completed so you can reset it by hand.';
            newsletter_log('newsletter_send_batch() - Unable to save batch info to db: ' . $wpdb->last_error, true);
            newsletter_log('newsletter_send_batch() - The batch was completed', true);
        }
    }

    return $last;
}

function newsletter_add_qs($url, $qs, $amp=true) {
    if (strpos($url, '?') !== false) {
        if ($amp) return $url . '&amp;' . $qs;
        else return $url . '&' . $qs;
    }
    else return $url . '?' . $qs;
}

/**
 * Add a request of newsletter subscription into the database with status "S" (waiting
 * confirmation) and sends out the confirmation request email to the subscriber.
 * The email will contain an URL (or link) the user has to follow to complete the
 * subscription (double opt-in).
 */
function newsletter_subscribe($email, $name='', $profile=null) {
    global $wpdb, $newsletter_subscriber;

    $options = get_option('newsletter');

    $email = newsletter_normalize_email($email);

    $name = newsletter_normalize_name($name);

    $list = 0;

    if ($profile == null) $profile = array();

    // Check if this email is already in our database: if so, just resend the
    // confirmation email.
    $newsletter_subscriber = newsletter_get_subscriber_by_email($email, $list);
    if (!$newsletter_subscriber) {
        $token = md5(rand());

        if (isset($options['noconfirmation'])) {
            $status = 'C';
        }
        else {
            $status = 'S';
        }
        $wpdb->insert($wpdb->prefix . 'newsletter', array(
            'email'=>$email,
            'name'=>$name,
            'token'=>$token,
            'list'=>$list,
            'status'=>$status,
            'profile'=>serialize($profile)));

        $newsletter_subscriber = newsletter_get_subscriber($wpdb->insert_id);
    }

    if (isset($options['noconfirmation'])) {
        newsletter_log('Invio del welcome a ' . print_r($newsletter_subscriber, true));
        newsletter_send_welcome($newsletter_subscriber);
    }
    else {
        newsletter_log('Invio della conferma ' . print_r($newsletter_subscriber, true));
        newsletter_send_confirmation($newsletter_subscriber);
    }

    $message = 'There is a new subscriber to ' . get_option('blogname') . ' newsletter:' . "\n\n" .
        $name . ' <' . $email . '>' . "\n\n" .
        'Have a nice day,' . "\n" . 'your Newsletter plugin.';

    $subject = '[' . get_option('blogname') . '] New subscription';
    newsletter_notify_admin($subject, $message);
}


function newsletter_save($subscriber) {
    global $wpdb;

    $email = newsletter_normalize_email($email);
    $name = newsletter_normalize_name($name);
    $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter set email=%s, name=%s where id=%d",
            $subscriber['email'], $subscriber['name'], $subscriber['id']));
}


/**
 * Resends the confirmation message when asked by user manager panel.
 */
function newsletter_send_confirmation($subscriber) {
    $options = get_option('newsletter');

    newsletter_log('newsletter_send_confirmation() - Sending a confirmation request message');

    newsletter_log('newsletter_send_confirmation() - URL: ' . $url);

    $message = $options['confirmation_message'];

    // The full URL to the confirmation page
    $url = newsletter_add_qs($options['url'], 'na=c&amp;ni=' . $subscriber->id .
        '&amp;nt=' . $subscriber->token);
    $message = newsletter_replace_url($message, 'SUBSCRIPTION_CONFIRM_URL', $url);

    // URL to the unsubscription page (for test purpose)
    $url = newsletter_add_qs($options['url'], 'na=u&amp;ni=' . $subscriber->id .
        '&amp;nt=' . $subscriber->token);
    $message = newsletter_replace_url($message, 'UNSUBSCRIPTION_URL', $url);

    $message = newsletter_replace($message, $subscriber);

    $subject = newsletter_replace($options['confirmation_subject'], $subscriber);

    newsletter_mail($subscriber->email, $subject, $message);
}

/**
 * Return a subscriber by his email. The email will be sanitized and normalized
 * before issuing the query to the database.
 */
function newsletter_get_subscriber($id) {
    global $wpdb;

    $recipients = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix .
        "newsletter where id=%d", $id));
    if (!$recipients) return null;
    return $recipients[0];
}

function newsletter_get_subscriber_by_email($email, $list=0) {
    global $wpdb;

    $recipients = $wpdb->get_results($wpdb->prepare("select * from " . $wpdb->prefix .
        "newsletter where email=%s and list=%d", $email, $list));
    if (!$recipients) return null;
    return $recipients[0];
}

function newsletter_get_all() {
    global $wpdb;

    $recipients = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter order by email");
    return $recipients;
}

function newsletter_search($text, $status=null, $order='email') {
    global $wpdb;

    if ($order == 'id') $order = 'id desc';

    $query = "select * from " . $wpdb->prefix . "newsletter where 1=1";
    if ($status != null) {
        $query .= " and status='" . $wpdb->escape($status) . "'";
    }

    if ($text == '') {
        $recipients = $wpdb->get_results($query . " order by " . $order);
    }
    else {
        $recipients = $wpdb->get_results($query . " and email like '%" .
            $wpdb->escape($text) . "%' or name like '%" . $wpdb->escape($text) . "%' order by " . $order);
    }
    if (!$recipients) return null;
    return $recipients;
}

function newsletter_get_unconfirmed() {
    global $wpdb;

    $recipients = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter where status='S' order by email");
    return $recipients;
}


/**
 * Normalize an email address,making it lowercase and trimming spaces.
 */
function newsletter_normalize_email($email) {
    return strtolower(trim($email));
}

function newsletter_normalize_name($name) {
    $name = str_replace(';', ' ', $name);
    $name = strip_tags($name);
    return $name;
}

add_action('init', 'newsletter_init');
/**
 * Intercept the request parameters which drive the subscription and unsubscription
 * process.
 */
function newsletter_init() {
    global $newsletter_step, $wpdb, $newsletter_subscriber;
    global $hyper_cache_stop;

    // "na" always is the action to be performed - stands for "newsletter action"
    $action = $_REQUEST['na'];
    if (!$action) return;

    $hyper_cache_stop = true;

    if (defined('NEWSLETTER_EXTRAS')) newsletter_extra_init($action);

    $options = get_option('newsletter');


    // Subscription request from a subscription form (in page or widget), can be
    // a direct subscription with no confirmation
    if ($action == 's') {
        if (!newsletter_is_email($_REQUEST['ne'])) {
            die(newsletter_label('error_email'));
        }
        // If not set, the subscription form is not requesting the name, so we do not
        // raise errors.
        if (isset($_REQUEST['nn'])) {
            if (trim($_REQUEST['nn']) == '') {
                die(newsletter_label('error_name'));
            }
        }
        else {
            $_REQUEST['nn'] = '';
        }

        $profile1 = $_REQUEST['np'];
        if (!isset($profile1) || !is_array($profile1)) $profile1 = array();

        // keys starting with "_" are removed because used internally
        $profile = array();
        foreach ($profile1 as $k=>$v) {
            if ($k[0] == '_') continue;
            $profile[$k] = $v;
        }

        $profile['_ip'] = $_SERVER['REMOTE_ADDR'];
        $profile['_referrer'] = $_SERVER['HTTP_REFERER'];

        // Check if the group is good
        newsletter_subscribe($_REQUEST['ne'], $_REQUEST['nn'], $profile);

        if (isset($options['noconfirmation'])) {
            $newsletter_step = 'confirmed';
        }
        else {
            $newsletter_step = 'subscribed';
        }
        return;
    }

    // A request to confirm a subscription
    if ($action == 'c') {
        $id = $_REQUEST['ni'];
        newsletter_confirm($id, $_REQUEST['nt']);
        header('Location: ' . newsletter_add_qs($options['url'], 'na=cs&ni=' . $id, false));
        die();
    }

    // Show the confirmed message after a redirection (to avoid mutiple email sending).
    // Redirect is sent by action "c".
    if ($action == 'cs') {
        $newsletter_subscriber = newsletter_get_subscriber($_REQUEST['ni']);
        $newsletter_step = 'confirmed';
    }

    // Unsubscription process has 2 options: if email and token are specified the user
    // will only be asked to confirm. If there is no infos of who remove (when
    // mass mail mode is used) the user will be asked to type the emailto be removed.
    if ($action == 'u') {
        $newsletter_step = 'unsubscription';
    }

/*
    // Export for Zanzara
    if ($action == 'z') {
        if (!$_GET['nk'] || $_GET['nk'] != $options['key']) return;

        $options_email = get_option('newsletter_email');
        header('Content-Type: text/xml;charset=UTF-8');

        echo '<' . '?xml version="1.0" encoding="UTF-8"?' . '>' . "\n";
        echo '<java version="1.6.0_12" class="java.beans.XMLDecoder">' . "\n";
        echo '<object class="zanzara.Newsletter">' . "\n";

        echo '<void property="message">' . "\n";
        echo '<string><![CDATA['. $options_email['message'] . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="newsletterUrl">' . "\n";
        echo '<string><![CDATA['. $options['url'] . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="fromEmail">' . "\n";
        echo '<string><![CDATA['. $options['from_email'] . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="fromName">' . "\n";
        echo '<string><![CDATA['. $options['from_name'] . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="homeUrl">' . "\n";
        echo '<string><![CDATA['. get_option('home') . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="blogTitle">' . "\n";
        echo '<string><![CDATA['. get_option('blogname') . ']]></string>' . "\n";
        echo '</void>' . "\n";

        echo '<void property="subject">' . "\n";
        echo '<string><![CDATA[' . $options_email['subject'] . ']]></string>' . "\n";
        echo '</void>' . "\n";

        echo '<void property="smtpHost">' . "\n";
        echo '<string><![CDATA[' . $options['smtp_host'] . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="smtpUser">' . "\n";
        echo '<string><![CDATA[' . $options['smtp_user'] . ']]></string>' . "\n";
        echo '</void>' . "\n";
        echo '<void property="smtpPassword">' . "\n";
        echo '<string><![CDATA[' . $options['smtp_password'] . ']]></string>' . "\n";
        echo '</void>' . "\n";

        echo '<void property="recipients">' . "\n";
        echo '<string><![CDATA[';

        $query = "select * from " . $wpdb->prefix . "newsletter where status='C'";
        $recipients = $wpdb->get_results($query . " order by email");
        for ($i=0; $i<count($recipients); $i++) {
            echo $recipients[$i]->email . ';' . $recipients[$i]->name .
                ';' . $recipients[$i]->token . ';' . $recipients[$i]->id . ';' . $recipients[$i]->group . "\n";
        }
        echo ']]></string>' . "\n";
        echo '</void>' . "\n";

        echo '<void property="testRecipients">' . "\n";
        echo '<string><![CDATA[';
        for ($i=1; $i<=10; $i++) {
            if (!$options_email['test_email_' . $i]) continue;
            echo $options_email['test_email_' . $i] . ';' . $options_email['test_name_' . $i] .
                ';FAKETOKEN;0;0' . "\n";
        }
        echo ']]></string>' . "\n";
        echo '</void>' . "\n";

        echo '</object>' . "\n";
        echo '</java>' . "\n";
        die();
    }
*/

    // User confirmed he want to unsubscribe clicking the link on unsubscription
    // page
    if ($action == 'uc') {
        newsletter_unsubscribe($_REQUEST['ni'], $_REQUEST['nt']);
        $newsletter_step = 'unsubscribed';
    }
}


/**
 * Deletes a subscription (no way back). Fills the global $newsletter_subscriber
 * with subscriber data to be used to build up messages.
 */
function newsletter_unsubscribe($id, $token) {
    global $newsletter_subscriber, $wpdb;

    // Save the subscriber for good bye page
    $newsletter_subscriber = newsletter_get_subscriber($id);

    $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "newsletter where id=%d" .
        " and token=%s", $id, $token));

    $message = newsletter_replace($options['unsubscribed_message'], $newsletter_subscriber);

    // URL to the unsubscription page (for test purpose)
    //    $url = newsletter_add_qs($options['url'], 'na=u&amp;ni=' . $newsletter_subscriber->id .
    //        '&amp;nt=' . $newsletter_subscriber->token);
    //    $message = newsletter_replace_url($message, 'UNSUBSCRIPTION_URL', $url);

    $subject = newsletter_replace($options['unsubscribed_subject'], $newsletter_subscriber);

    newsletter_mail($newsletter_subscriber->email, $subject, $message);


    // Admin notification
    $message = 'There is an unsubscription to ' . get_option('blogname') . ' newsletter:' . "\n\n" .
        $newsletter_subscriber->name . ' <' . $newsletter_subscriber->email . '>' . "\n\n" .
        'Don\'t worry, for one lost two gained!' . "\n\n" .
        'Have a nice day,' . "\n" . 'your Newsletter plugin.';

    $subject = '[' . get_option('blogname') . '] Unsubscription';
    newsletter_notify_admin($subject, $message);
}

/*
 * Deletes a specific subscription. Called only from the admin panel.
 */
function newsletter_delete($id) {
    global $wpdb;

    $wpdb->query($wpdb->prepare("delete from " . $wpdb->prefix . "newsletter where id=%d", $id));
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

/**
 * Confirms a subscription identified by id and token, changing it's status on
 * database. Fill the global $newsletter_subscriber with user data.
 * If the subscription id already confirmed, the welcome email is still sent to
 * the subscriber (the welcome email can contains somthing reserved to the user
 * and he may has lost it).
 * If id and token do not match, the function does nothing.
 */
function newsletter_confirm($id, $token) {
    global $wpdb, $newsletter_subscriber;

    $newsletter_subscriber = newsletter_get_subscriber($id);

    $options = get_option('newsletter');

    $count = $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter set status='C' where id=%d" .
        " and token=%s", $id, $token));

    if ($count > 0) {
        $newsletter_subscriber = newsletter_get_subscriber($id);
        newsletter_send_welcome($newsletter_subscriber);
    }
}

function newsletter_send_welcome($subscriber) {
    $options = get_option('newsletter');

    if ($options['confirmed_subject'] == '') return;

    $message = newsletter_replace($options['confirmed_message'], $subscriber);

    // URL to the unsubscription page (for test purpose)
    $url = newsletter_add_qs($options['url'], 'na=u&amp;ni=' . $subscriber->id .
        '&amp;nt=' . $subscriber->token);
    $message = newsletter_replace_url($message, 'UNSUBSCRIPTION_URL', $url);

    $subject = newsletter_replace($options['confirmed_subject'], $subscriber);

    newsletter_mail($subscriber->email, $subject, $message);
}

/*
 * Changes the status of a subscription identified by its id.
 */
function newsletter_set_status($id, $status) {
    global $wpdb;

    $wpdb->query($wpdb->prepare("update " . $wpdb->prefix . "newsletter set status=%s where id=%d", $status, $id));
}

/*
 * Sends a notification message to the blog admin.
 */
function newsletter_notify_admin(&$subject, &$message) {
    $to = get_option('admin_email');
    $headers .= "Content-type: text/plain; charset=UTF-8\n";
    wp_mail($to, $subject, $message, $headers);
}

/**
 * Sends out an email (html or text). From email and name is retreived from
 * Newsletter plugin options. Return false on error. If the subject is empty
 * no email is sent out without warning.
 * The function uses wp_mail() to really send the message.
 */
function newsletter_mail($to, &$subject, &$message, $html=true) {
    global $wpdb;

    if ($subject == '') return true;

    $options = get_option('newsletter');

    $headers  = "MIME-Version: 1.0\n";
    if ($html) $headers .= "Content-type: text/html; charset=UTF-8\n";
    else $headers .= "Content-type: text/plain; charset=UTF-8\n";

    // Special character are manager by wp_mail()
    $headers .= 'From: "' . $options['from_name'] . '" <' . $options['from_email'] . ">\n";

    return wp_mail($to, $subject, $message, $headers);
}


add_action('activate_newsletter/plugin.php', 'newsletter_activate');
function newsletter_activate() {
    global $wpdb;

    $options = get_option('newsletter');

    // Load the default options
    @include_once(dirname(__FILE__) . '/languages/en_US_options.php');
    if (WPLANG != '') @include_once(dirname(__FILE__) . '/languages/' . WPLANG . '_options.php');
    //@include_once(ABSPATH . 'wp-content/newsletter/languages/custom_options.php');

    if (is_array($options)) $options = array_merge($newsletter_default_options, $options);
    else $options = $newsletter_default_options;

    // SQL to create the table
    $sql = 'create table if not exists ' . $wpdb->prefix . 'newsletter (
        `id` int not null auto_increment primary key,
        `name` varchar (100) not null default \'\',
        `email` varchar (100) not null default \'\',
        `token` varchar (50) not null default \'\',
        `status` varchar (1) not null default \'S\',
        `group` int not null default 0,
        `profile` text
        )';

    @$wpdb->query($sql);

    if (!isset($options['version']) || $options['version'] < '1.4.0') {

        $sql = "alter table " . $wpdb->prefix . "newsletter drop primary key";
        @$wpdb->query($sql);

        $sql = "alter table " . $wpdb->prefix . "newsletter add column id int not null auto_increment primary key";
        @$wpdb->query($sql);

        $sql = "alter table " . $wpdb->prefix . "newsletter add column list int not null default 0";
        @$wpdb->query($sql);

        $sql = "alter table " . $wpdb->prefix . "newsletter drop key email_token";
        @$wpdb->query($sql);

        $sql = "alter table " . $wpdb->prefix . "newsletter add column profile text";
        @$wpdb->query($sql);

        $sql = "ALTER TABLE " . $wpdb->prefix . "newsletter ADD UNIQUE email_list (email, list)";
        @$wpdb->query($sql);
    }

    if (!isset($options['version']) || $options['version'] < '1.4.1') {
        $sql = "alter table " . $wpdb->prefix . "newsletter add column created timestamp not null default current_timestamp";
        @$wpdb->query($sql);
    }

    newsletter_log('Plugin activated', true);

    $options['version'] = NEWSLETTER;
    update_option('newsletter', $options);

    if (defined('NEWSLETTER_EXTRAS')) newsletter_extra_activate();
}

if (is_admin()) {
    add_action('admin_menu', 'newsletter_admin_menu');
    function newsletter_admin_menu() {
        $options = get_option('newsletter');
        $level = $options['editor']?7:10;

        if (function_exists('add_menu_page')) {
            add_menu_page('Newsletter', 'Newsletter', $level, 'newsletter/options.php', '', '');
        }

        if (function_exists('add_submenu_page')) {
            add_submenu_page('newsletter/options.php', 'Configuration', 'Configuration', $level, 'newsletter/options.php');
            add_submenu_page('newsletter/options.php', 'Composer', 'Composer', $level, 'newsletter/newsletter.php');
            add_submenu_page('newsletter/options.php', 'Import', 'Import', $level, 'newsletter/import.php');
            add_submenu_page('newsletter/options.php', 'Export', 'Export', $level, 'newsletter/export.php');
            add_submenu_page('newsletter/options.php', 'Manage', 'Manage', $level, 'newsletter/manage.php');
            add_submenu_page('newsletter/options.php', 'Statistics', 'Statistics', $level, 'newsletter/statistics.php');
        }
    }
}

/**
 * Fills a text with sunscriber data and blog data replacing some place holders.
 */
function newsletter_replace($text, $subscriber) {
    $text = str_replace('{home_url}', get_option('home'), $text);
    $text = str_replace('{blog_title}', get_option('blogname'), $text);
    $text = str_replace('{email}', $subscriber->email, $text);
    $text = str_replace('{id}', $subscriber->id, $text);
    $text = str_replace('{name}', $subscriber->name, $text);
    $text = str_replace('{token}', $subscriber->token, $text);
    return $text;
}

/**
 * Replaces the URL placeholders. There are two kind of URL placeholders: the ones
 * lowercase and betweeb curly brakets and the ones all uppercase. The tag to be passed
 * is the one all uppercase but the lowercase one will also be replaced.
 */
function newsletter_replace_url($text, $tag, $url) {
    $home = get_option('home') . '/';
    $tag_lower = strtolower($tag);
    $text = str_replace($home . '{' . $tag_lower . '}', $url, $text);
    $text = str_replace($home . '%7B' . $tag_lower . '%7D', $url, $text);
    $text = str_replace('{' . $tag_lower . '}', $url, $text);

    // for compatibility
    $text = str_replace($home . $tag, $url, $text);

    return $text;
}

function newsletter_is_email($email, $empty_ok=false) {
    $email = strtolower(trim($email));
    if ($empty_ok && $email == '') return true;

    if (eregi("^([a-z0-9_\.-])+@(([a-z0-9_-])+\\.)+[a-z]{2,6}$", trim($email))) {
        if (strpos($email, 'mailinator.com') !== false) return false;
        if (strpos($email, 'guerrillamailblock.com') !== false) return false;
        return true;
    }
    else
        return false;
}

/**
 * Write a line of log in the log file if the logs are enabled or force is
 * set to true.
 */
function newsletter_log($text, $force=false) {
    $options = get_option('newsletter');

    if (!$force && !isset($options['logs'])) return;

    $file = fopen(dirname(__FILE__) . '/newsletter.log', 'a');
    if (!$file) return;
    fwrite($file, date('Y-m-d h:i') . ' ' . $text . "\n");
    fclose($file);
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
        $list[] = $file;
    }
    closedir($handle);
    return $list;
}

function newsletter_get_extras_themes() {
    $handle = @opendir(ABSPATH . 'wp-content/plugins/newsletter-extras/themes');
    $list = array();
    if (!$handle) return $list;
    while ($file = readdir($handle)) {
        if ($file == '.' || $file == '..') continue;
        if (!is_dir(ABSPATH . 'wp-content/plugins/newsletter-extras/themes/' . $file)) continue;
        if (!is_file(ABSPATH . 'wp-content/plugins/newsletter-extras/themes/' . $file . '/theme.php')) continue;
        $list[] = $file;
    }
    closedir($handle);
    return $list;
}

/**
 * Resets the batch status.
 */
function newsletter_reset_batch() {
    update_option('newsletter_last', array());
    wp_clear_scheduled_hook('newsletter_cron_hook');
}

function newsletter_has_extras($version=null)
{
    if (!defined('NEWSLETTER_EXTRAS')) return false;
    if ($version == null) return true;
    if ($version >= NEWSLETTER_EXTRAS) return true;
    return false;
}

/** 
 * Find an image for a post checking the media uploaded for the post and
 * choosing the first image found.
 */
function nt_post_image($post_id, $size='thumbnail', $alternative=null) {

    $attachments = get_children(array('post_parent'=>$post_id, 'post_status'=>'inherit', 'post_type'=>'attachment', 'post_mime_type'=>'image', 'order'=>'ASC', 'orderby'=>'menu_order ID' ) );

    if (empty($attachments)) {
        return $alternative;
    }

    foreach ($attachments as $id=>$attachment) {
        $image = wp_get_attachment_image_src($id, $size);
        //$image = $image[0];
        return $image[0];
    }
    return null;
}

function nt_option($name, $def = null) {
    $options = get_option('newsletter_email');
    $option = $options['theme_' . $name];
    if (!isset($option)) return $def;
    else return $option;
}

?>
