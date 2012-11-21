<?php
header('Content-Type: text/plain');

include '../../../../wp-load.php';

if (!check_admin_referer())
    die('Only the administrator can view the preview');

// Used by theme code
$theme_options = NewsletterEmails::instance()->get_current_theme_options();

include(NewsletterEmails::instance()->get_current_theme_file_path('theme-text.php'));
