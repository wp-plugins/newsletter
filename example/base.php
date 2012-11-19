<?php
/**
 * This is only a module example. Pay attention to correct all the "NewsletterBase" references, even most of them
 * are only useful for code completion inside IDEs like Netbeans.
 */
require_once NEWSLETTER_INCLUDES_DIR . '/module.php';

class NewsletterBase extends NewsletterModule {

    /**
     *  Use to track the current version. A version change, see the construction, triggers an upgrade.
     */
    const VERSION = '1.0.0';

    /**
     * Internal variable used to keep the module instance when the singleton pattern is used (recommended).
     * @var NewsletterBase
     */
    static private $instance;

    /**
     * Create an instance of this module. Cannot done on NewsletterModule since there is no way to reference the
     * extending class (need PHP 5.3 to do that).
     * @return NewsletterBase
     */
    static function instance() {
        if (self::$instance == null) {
            self::$instance = new NewsletterBase();
        }
        return self::$instance;
    }

    function __construct() {
        // Pay attetion to module name that must be tha same of the containing folder basename. The module name
        // can be replaced with basename(dirname(__FILE__)) but it's a lot less efficient.
        parent::__construct('base', self::VERSION);
    }

    /**
     * Called when there is a version change or when explicitely triggered. Add here the installation/upgrade code.
     *
     * @global type $wpdb
     * @global type $charset_collate
     */
    function upgrade() {
        global $wpdb, $charset_collate;

        // That call loads initialize the module options if they are not present on database. Multilangua options can
        // be stored under the "language" folder of this module. See documentation on site.
        parent::upgrade();

        // Use $this->upgrade_query() se the query will be logged.
        //$this->upgrade_query("create table if not exists {$wpdb->prefix}newsletter_table (id int auto_increment, primary key (id)) $charset_collate");
    }

}

// Registering here the actions/filters we avoid to initialize the module when not needed.
add_action('newsletter_admin_menu', 'newsletter_base_admin_menu');

/**
 * Add menu pages for this module.
 * @global Newsletter $newsletter
 */
function newsletter_base_admin_menu() {
    global $newsletter;

    // The global $newsletter variable can be used in place of Newsletter::instance() that is less performant. Newsletter
    // class is always initialized and an instance associated to the $newsletter global variable.
    $newsletter->add_menu_page('statistics', 'index', 'Statistics');
    $newsletter->add_admin_page('statistics', 'view', 'Statistics');
}

