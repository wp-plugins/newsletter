<?php

class NewsletterFeed extends NewsletterModule {

    static $instance;

    /**
     * @return NewsletterFeed
     */
    static function instance() {
        if (self::$instance == null) {
            self::$instance = new NewsletterFeed();
        }
        return self::$instance;
    }

    function __construct() {
        $this->themes = new NewsletterThemes('feed');
        parent::__construct('feed', '1.0.1');
        add_filter('newsletter_user_subscribe', array($this, 'hook_user_subscribe'));
        add_filter('newsletter_subscription_extra', array($this, 'hook_subscription_extra'));
    }

    function upgrade() {
        global $wpdb, $charset_collate;
        parent::upgrade();
    }

    function create_email($options) {
        global $wpdb, $newsletter;

        $posts = $this->get_posts();


        $email = array();

        $last_run = 0;
        $theme_options = $this->themes->get_options($options['theme']);
        $theme_url = $this->themes->get_theme_url($options['theme']);
        $theme_subject = '';

        ob_start();
        require $this->themes->get_file_path($options['theme'], 'theme.php');
        $email['message'] = ob_get_clean();

        return $email;
    }

    function hook_user_subscribe($user) {
        if ($this->options['subscription'] == 1 && isset($_REQUEST['feed'])) {
            $user['feed'] = 1;
        }
        // Forced
        if ($this->options['subscription'] == 2) {
            $user['feed'] = 1;
        }
        return $user;
    }

    function hook_subscription_extra($extra) {

        if ($this->options['subscription'] == 1) {
            $field = array();
            $field['label'] = '&nbsp;';
            $field['field'] = '<input type="checkbox" name="feed" value="1"/>&nbsp;' . $this->options['name'];
            $extra[] = $field;
        }
        return $extra;
    }

    function admin_menu() {
        $this->add_menu_page('index', 'Feed by Mail (Demo)');
    }

    /**
     * Extract all post based on Feed by Mail options (passed or the ones saved).
     * Sets some variables inside $newsletter, for compatibility with old themes.
     *
     * @param array $options
     */
    function get_posts($options = null) {
        if ($options == null) $options = $this->options;

        // Compute the categories to exclude
        $excluded_categories = '';
        $categories = get_categories();
        foreach ($categories as $c) {
            if ($options['category_' . $c->cat_ID] == 1) {
                $excluded_categories .= '-' . $c->cat_ID . ',';
            }
        }

        $this->logger->debug('create_email> Excluded categories: ' . $excluded_categories);

        // Extract the max posts
        $max_posts = $options['max_posts'];
        if (!is_numeric($max_posts)) $max_posts = 10;

        // Build the filter
        $filters = array('showposts' => $max_posts, 'post_status' => 'publish');
        if ($excluded_categories != '') $filters['cat'] = $excluded_categories;

        $this->logger->debug($filters);

        // Load the posts
        $posts = get_posts($filters);
        $this->logger->debug('Loaded ' . count($posts) . ' posts');

        // TODO: Kept for compatibility
        $newsletter->feed_posts = $posts;
        $newsletter->feed_max_posts = $max_posts;
        $newsletter->feed_excluded_categories = $excluded_categories;
        $newsletter->feed_options = $this->options;

        return $posts;
    }

    function save_options($options, $sub = '') {
        $this->options = $options;
        parent::save_options($options, $sub);
        $this->themes->save_options($options['theme'], $options);
    }

}

NewsletterFeed::instance();
