<?php

header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="newsletter-subscribers.csv"');

$keys = $wpdb->get_results("select distinct name from " . $wpdb->prefix . "newsletter_profiles order by name");

// CSV header
echo '"Email";"Name";"Surname";"Sex";Status";"Date";"Token";';

// In table profiles
for ($i = 1; $i <= NEWSLETTER_PROFILE_MAX; $i++) {
    echo '"Profile ' . $i . '";'; // To adjust with field name
}

// Lists
for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
    echo '"List ' . $i . '";';
}

echo '"Feed by mail";"Follow up"';

// Old profiles
foreach ($keys as $key) {
    // Remove some keys?
    echo $key->name . ';';
}
echo "\n";

$page = 0;
while (true) {
    $query = "select * from " . $wpdb->prefix . "newsletter";
    if (!empty($_POST['options']['list'])) {
        $query .= " where list_" . $_POST['options']['list'] . "=1";
    }
    $recipients = $wpdb->get_results($query . " order by email limit " . $page * 500 . ",500");
    for ($i = 0; $i < count($recipients); $i++) {
        echo '"' . $recipients[$i]->email . '";"' . newsletter_sanitize_csv($recipients[$i]->name) .
        '";"' . newsletter_sanitize_csv($recipients[$i]->surname) .
        '";"' . $recipients[$i]->sex .
        '";"'. $recipients[$i]->status . '";"' . $recipients[$i]->created . '";"' . $recipients[$i]->token . '";';

        for ($j = 1; $j <= NEWSLETTER_PROFILE_MAX; $j++) {
            $column = 'profile_' . $j;
            echo newsletter_sanitize_csv($recipients[$i]->$column) . ';';
        }

        for ($j = 1; $j <= NEWSLETTER_LIST_MAX; $j++) {
            $list = 'list_' . $j;
            echo $recipients[$i]->$list . ';';
        }

        echo $recipients[$i]->feed . ';';
        echo $recipients[$i]->followup . ';';

        $profile = $wpdb->get_results("select name,value from " . $wpdb->prefix . "newsletter_profiles where newsletter_id=" . $recipients[$i]->id . " order by name");
        $map = array();
        foreach ($profile as $field) {
            $map[$field->name] = $field->value;
        }

        foreach ($keys as $key) {
            if (isset($map[$key->name])) {
                echo '"' . newsletter_sanitize_csv($map[$key->name]) . '";';
            }
            else echo '"";';
        }
        echo "\n";
        flush();
    }
    if (count($recipients) < 500) break;
    $page++;
}
die();

function newsletter_sanitize_csv($text) {
    $text = str_replace('"', "'", $text);
    $text = str_replace("\n", ' ', $text);
    $text = str_replace("\r", ' ', $text);
    $text = str_replace(";", ' ', $text);
    return $text;
}
?>
