<?php

class NewsletterModule {

    /**
     * @var NewsletterLogger
     */
    var $logger;

    /**
     * @var NewsletterStore
     */
    var $store;

    /**
     * The main module options
     * @var array
     */
    var $options;

    /**
     * @var string The module name
     */
    var $module;

    /**
     * The module version
     * @var string
     */
    var $version;

    /**
     * Prefix for all options stored on WordPress options table.
     * @var string
     */
    var $prefix;

    /**
     * @var NewsletterThemes
     */
    var $themes;

    function __construct($module, $version) {
        $this->module = $module;
        $this->version = $version;
        $this->prefix = 'newsletter_' . $module;


        $this->logger = new NewsletterLogger($module);
        $this->options = $this->get_options();
        $this->store = NewsletterStore::singleton();

        //$this->logger->debug($module . ' constructed');
        // Version check
        if (is_admin()) {
            $old_version = get_option($this->prefix . '_version', '');
            if (strcmp($old_version, $this->version) != 0) {
                $this->logger->info('Version changed from ' . $old_version . ' to ' . $this->version);
                // Do all the stuff for this version change
                $this->upgrade();
                update_option($this->prefix . '_version', $this->version);
            }
            add_action('admin_menu', array($this, 'admin_menu'));
        }
    }

    /**
     * Does a basic upgrade work, checking if the options is already present and if not (first
     * installation), recovering the defaults, saving them on database and initializing the
     * internal $options.
     */
    function upgrade() {
        $this->logger->info('upgrade> Start');

        if (empty($this->options) || !is_array($this->options)) {
            $this->options = $this->get_default_options();
            $this->save_options($this->options);
        }
    }

    function upgrade_query($query) {
        global $wpdb, $charset_collate;

        $this->logger->info('upgrade_query> Executing ' . $query);
        $wpdb->query($query);
        if ($wpdb->last_error) {
            $this->logger->info($wpdb->last_error);
        }
    }

    /**
     * Return, eventually, the version of this moduke available on satollo.net.
     * @return string
     */
    static function get_available_version($module_id) {
        $version = get_option('newsletter_module_' . $module_id . '_version', '');
        $time = get_option('newsletter_module_' . $module_id . '_last_check', 0);
        if (empty($version) || $time < time()-86400) {
            $version = @file_get_contents('http://www.satollo.net/wp-content/plugins/file-commerce-pro/version.php?f=' . $module_id);
            add_option('newsletter_module_' . $module_id . '_last_check', time(), '', 'no');
            update_option('newsletter_module_' . $module_id . '_last_check', time());
            add_option('newsletter_module_' . $module_id . '_version', $version);
            update_option('newsletter_module_' . $module_id . '_version', $version);
        }
        return $version;
    }

    /** Returns a prefix to be used for option names and other things which need to be uniquely named. The parameter
     * "sub" should be used when a sub name is needed for another set of options or like.
     *
     * @param string $sub
     * @return string The prefix for names
     */
    function get_prefix($sub = '') {
        return $this->prefix . (!empty($sub) ? '_' : '') . $sub;
    }

    /**
     * Returns the options of a module.
     */
    function get_options($sub = '') {
        $options = get_option($this->get_prefix($sub));
        if ($options == false)
            return array();
        return $options;
    }

    function get_default_options($sub = '') {
        if (!empty($sub))
            $sub .= '-';
        @include NEWSLETTER_DIR . '/' . $this->module . '/languages/' . $sub . 'en_US.php';
        @include WP_CONTENT_DIR . '/extensions/newsletter/' . $this->module . '/languages/' . $sub . 'en_US.php';
        @include NEWSLETTER_DIR . '/' . $this->module . '/languages/' . $sub . WPLANG . '.php';
        @include WP_CONTENT_DIR . '/extensions/newsletter/' . $this->module . '/languages/' . $sub . WPLANG . '.php';
        if (!is_array($options)) {
            return array();
        }
        return $options;
    }

    function reset_options($sub = '') {
        $this->save_options(array_merge($this->get_options($sub), $this->get_default_options($sub)), $sub);
        return $this->options;
    }

    function save_options($options, $sub = '') {
        $this->options = $options;
        update_option($this->get_prefix($sub), $options);

        if (isset($this->themes) && isset($options['theme'])) {
            $this->themes->save_options($options['theme'], $options);
        }

        if (isset($options['log_level']))
            update_option('newsletter_' . $this->module . '_log_level', $options['log_level']);
    }

    function backup_options($sub) {
        $options = $this->get_options($sub);
        add_option($this->get_prefix($sub) . '_backup', '', null, 'no');
        update_option($this->get_prefix($sub) . '_backup', $options);
    }

    function get_last_run($sub = '') {
        return get_option($this->get_prefix($sub) . '_last_run', 0);
    }

    function save_last_run($time, $sub = '') {
        update_option($this->get_prefix($sub) . '_last_run', $time);
    }

    function add_to_last_run($delta, $sub = '') {
        $time = $this->get_last_run($sub);
        $this->save_last_run($time + $delta, $sub);
    }

    /**
     * Checks if the semaphore of that name (for this module) is still red giving that it should last only
     * $time seconds.
     *
     * @param string $name
     * @param int $time Max time in second this semaphore should stay red
     * @return boolean False if the semaphore is red and you should not proceed.
     */
    function check_transient($name, $time) {
        usleep(rand(0, 1000000));
        if (($value = get_transient($this->get_prefix() . '_' . $name)) !== false) {
            $this->logger->error('Blocked by transient ' . $this->get_prefix() . '_' . $name . ' set ' . (time() - $value) . ' seconds ago');
            return false;
        }
        set_transient($this->get_prefix() . '_' . $name, time(), $time);
        return true;
    }

    function delete_transient($sub = '') {
        delete_transient($this->get_prefix($sub));
    }

    /** Returns a random token of the specified size (or 10 characters if size is not specified).
     *
     * @param int $size
     * @return string
     */
    static function get_token($size = 10) {
        return substr(md5(rand()), 0, $size);
    }

    static function add_qs($url, $qs, $amp = true) {
        if (strpos($url, '?') !== false) {
            if ($amp)
                return $url . '&amp;' . $qs;
            else
                return $url . '&' . $qs;
        }
        else
            return $url . '?' . $qs;
    }

    static function normalize_email($email) {
        $email = strtolower(trim($email));
        if (!is_email($email))
            return null;
        return $email;
    }

    static function normalize_name($name) {
        $name = str_replace(';', ' ', $name);
        $name = strip_tags($name);
        return $name;
    }

    static function normalize_sex($sex) {
        $sex = trim(strtolower($sex));
        if ($sex != 'f' && $sex != 'm') $sex = 'n';
        return $sex;
    }

    static function is_email($email, $empty_ok = false) {
        $email = strtolower(trim($email));
        if ($empty_ok && $email == '')
            return true;

        if (!is_email($email))
            return false;
        if (strpos($email, 'mailinator.com') !== false)
            return false;
        if (strpos($email, 'guerrillamailblock.com') !== false)
            return false;
        if (strpos($email, 'emailtemporanea.net') !== false)
            return false;
        return true;
    }

    /**
     * Converts a GMT date into timestamp.
     *
     * @param type $s
     * @return type
     */
    static function m2t($s) {

        // TODO: use the wordpress function I don't remeber the name
        $s = explode(' ', $s);
        $d = explode('-', $s[0]);
        $t = explode(':', $s[1]);
        return gmmktime((int) $t[0], (int) $t[1], (int) $t[2], (int) $d[1], (int) $d[2], (int) $d[0]);
    }

    static function format_date($time) {
        if (empty($time)) return '-';
        return gmdate(get_option('date_format') . ' ' . get_option('time_format'), $time + get_option('gmt_offset') * 3600);
    }

    static function date($time = null, $now = false, $left = false) {
        if (is_null($time)) {
            $time = time();
        }
        if ($time == false) {
            $buffer = 'none';
        }
        else {
            $buffer = gmdate(get_option('date_format') . ' ' . get_option('time_format'), $time + get_option('gmt_offset') * 3600);
        }
        if ($now) {
            $buffer .= ' (now: ' . gmdate(get_option('date_format') . ' ' .
                            get_option('time_format'), time() + get_option('gmt_offset') * 3600);
            $buffer .= ')';
        }
        if ($left) {
            $buffer .= ', ' . gmdate('H:i:s', $time - time()) . ' left';
        }
        return $buffer;
    }

    /**
     * Return an array of array with on first element the array of recent post and on second element the array
     * of old posts.
     *
     * @param array $posts
     * @param int $time
     */
    static function split_posts(&$posts, $time = 0) {
        $result = array(array(), array());
        foreach ($posts as &$post) {
            if (self::is_post_old($post, $time))
                $result[1][] = $post;
            else
                $result[0][] = $post;
        }
        return $result;
    }

    static function is_post_old(&$post, $time = 0) {
        return self::m2t($post->post_date_gmt) <= $time;
    }

    static function get_post_image($post_id = null, $size = 'thumbnail', $alternative = null) {
        global $post;

        if (empty($post_id))
            $post_id = $post->ID;
        if (empty($post_id))
            return $alternative;

        $image_id = function_exists('get_post_thumbnail_id') ? get_post_thumbnail_id($post_id) : false;
        if ($image_id) {
            $image = wp_get_attachment_image_src($image_id, $size);
            return $image[0];
        } else {
            $attachments = get_children(array('post_parent' => $post_id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => 'ASC', 'orderby' => 'menu_order ID'));

            if (empty($attachments)) {
                return $alternative;
            }

            foreach ($attachments as $id => $attachment) {
                $image = wp_get_attachment_image_src($id, $size);
                return $image[0];
            }
        }
    }

    function get_styles() {

        $list = array('' => 'none');

        $dir = NEWSLETTER_DIR . '/' . $this->module . '/styles';
        $handle = @opendir($dir);

        if ($handle !== false) {
            while ($file = readdir($handle)) {
                if ($file == '.' || $file == '..')
                    continue;
                if (substr($file, -4) != '.css')
                    continue;
                $list[$file] = substr($file, 0, strlen($file) - 4);
            }
            closedir($handle);
        }

        $dir = WP_CONTENT_DIR . '/extensions/newsletter/' . $this->module . '/styles';
        $handle = @opendir($dir);

        if ($handle !== false) {
            while ($file = readdir($handle)) {
                if ($file == '.' || $file == '..')
                    continue;
                if (isset($list[$file]))
                    continue;
                if (substr($file, -4) != '.css')
                    continue;
                $list[$file] = substr($file, 0, strlen($file) - 4);
            }
            closedir($handle);
        }
        return $list;
    }

    function get_style_url($style) {
        if (is_file(WP_CONTENT_DIR . '/extensions/newsletter/' . $this->module . '/styles/' . $style))
            return WP_CONTENT_URL . '/extensions/newsletter/' . $this->module . '/styles/' . $style;
        else
            return NEWSLETTER_URL . '/' . $this->module . '/styles/' . $style;
    }

    function admin_menu() {

    }

    function add_menu_page($page, $title) {
        global $newsletter;
        $file = WP_CONTENT_DIR . '/extensions/newsletter/' . $this->module . '/' . $page . '.php';
        if (!is_file($file)) {
            $file = NEWSLETTER_DIR . '/' . $this->module . '/' . $page . '.php';
        }
        $name = 'newsletter_' . $this->module . '_' . $page;
        eval('function ' . $name . '(){global $newsletter, $wpdb;require \'' . $file . '\';}');
        add_submenu_page('newsletter_main_index', $title, $title, $newsletter->options['editor'] ? 7 : 10, $name, $name);
    }

    function add_admin_page($page, $title) {
        global $newsletter;
        $file = WP_CONTENT_DIR . '/extensions/newsletter/' . $this->module . '/' . $page . '.php';
        if (!is_file($file)) {
            $file = NEWSLETTER_DIR . '/' . $this->module . '/' . $page . '.php';
        }

        $name = 'newsletter_' . $this->module . '_' . $page;
        eval('function ' . $name . '(){global $newsletter, $wpdb;require \'' . $file . '\';}');
        add_submenu_page(null, $title, $title, $newsletter->options['editor'] ? 7 : 10, $name, $name);
    }

    function get_admin_page_url($page) {
        return '?page=newsletter_' . $this->module . '_' . $page;
    }

    /** Returns all the emails of the give type (message, feed, followup, ...) and in the given format
     * (default as objects). Return false on error or at least an empty array. Errors should never
     * occur.
     *
     * @global wpdb $wpdb
     * @param string $type
     * @return boolean|array
     */
    function get_emails($type = null, $format = OBJECT) {
        global $wpdb;
        if ($type == null) {
            $list = $wpdb->get_results("select * from " . NEWSLETTER_EMAILS_TABLE . " order by id desc", $format);
        } else {
            $list = $wpdb->get_results($wpdb->prepare("select * from " . NEWSLETTER_EMAILS_TABLE . " where type=%s order by id desc", $type), $format);
        }
        if ($wpdb->last_error) {
            $this->logger->error($wpdb->last_error);
            return false;
        }
        if (empty($list)) {
            return array();
        }
        return $list;
    }

    function get_email($id, $format = OBJECT) {
        return $this->store->get_single(NEWSLETTER_EMAILS_TABLE, $id, $format);
    }

}

/**
 * Kept for compatibility.
 *
 * @param type $post_id
 * @param type $size
 * @param type $alternative
 * @return type
 */
function nt_post_image($post_id = null, $size = 'thumbnail', $alternative = null) {
    return NewsletterModule::get_post_image($post_id, $size, $alternative);
}

function newsletter_get_post_image($post_id = null, $size = 'thumbnail', $alternative = null) {
    echo NewsletterModule::get_post_image($post_id, $size, $alternative);
}
