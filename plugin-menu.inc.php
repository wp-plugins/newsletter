<?php

$level = $this->options['editor'] ? 7 : 10;

add_menu_page('Newsletter', 'Newsletter', $level, 'newsletter/welcome.php', '', '');
//add_submenu_page('newsletter/welcome.php', 'User Guide', 'User Guide', $level, 'newsletter/main.php');

add_submenu_page('newsletter/welcome.php', 'Welcome & Support', 'Welcome & Support', $level, 'newsletter/welcome.php');

add_submenu_page('newsletter/welcome.php', 'Configuration', 'Configuration', $level, 'newsletter/main.php');


add_submenu_page(null, 'Email statistics', 'Email statistics', $level, 'newsletter/statistics/statistics-email.php');

//add_submenu_page('newsletter/welcome.php', 'Subscribers', 'Subscribers', $level, 'newsletter/users/simple.php');
//add_submenu_page(null, 'New subscriber', 'New subscriber', $level, 'newsletter/users/new.php');
//add_submenu_page(null, 'Simple', 'Simple', $level, 'newsletter/users/index.php');
//add_submenu_page(null, 'Subscribers Edit', 'Subscribers Edit', $level, 'newsletter/users/edit.php');
//add_submenu_page(null, 'Subscribers Statistics', 'Subscribers Statistics', $level, 'newsletter/users/stats.php');
//add_submenu_page(null, 'Massive Management', 'Massive Management', $level, 'newsletter/users/massive.php');
//add_submenu_page(null, 'Import', 'Import', $level, 'newsletter/users/import.php');
//add_submenu_page(null, 'Export', 'Export', $level, 'newsletter/users/export.php');

// Statistics
//add_submenu_page('newsletter/welcome.php', 'Statistics', 'Statistics', $level, 'newsletter/statistics/statistics-index.php');
//add_submenu_page('newsletter/statistics/statistics-index.php', 'Statistics', 'Statistics', $level, 'newsletter/statistics/statistics-view.php');

// Updates
//add_submenu_page('newsletter/welcome.php', 'Updates', 'Updates', $level, 'newsletter/updates/updates-index.php');
//add_submenu_page('newsletter/updates/updates-index.php', 'Updates', 'Updates', $level, 'newsletter/updates/updates-edit.php');
//add_submenu_page('newsletter/updates/updates-index.php', 'Updates', 'Updates', $level, 'newsletter/updates/updates-emails.php');

?>
