<?php

$level = $this->options_main['editor'] ? 7 : 10;

add_menu_page('Newsletter', 'Newsletter', $level, 'newsletter/intro.php', '', '');
add_submenu_page('newsletter/intro.php', 'User Guide', 'User Guide', $level, 'newsletter/intro.php');

add_submenu_page('newsletter/intro.php', 'Main Configuration', 'Main Configuration', $level, 'newsletter/main.php');
add_submenu_page('newsletter/intro.php', 'Subscription Process', 'Subscription Process', $level, 'newsletter/options.php');
add_submenu_page('newsletter/intro.php', 'User Profile', 'User Profile', $level, 'newsletter/profile.php');

add_submenu_page('newsletter/intro.php', 'Emails', 'Emails', $level, 'newsletter/emails.php');
add_submenu_page('newsletter/emails.php', 'Email Edit', 'Email Edit', $level, 'newsletter/emails-edit.php');

add_submenu_page('newsletter/intro.php', 'Subscribers', 'Subscribers', $level, 'newsletter/users.php');
add_submenu_page('newsletter/users.php', 'Subscribers Edit', 'Subscribers Edit', $level, 'newsletter/users-edit.php');

add_submenu_page('newsletter/intro.php', 'Import/Export', 'Import/Export', $level, 'newsletter/import.php');
?>