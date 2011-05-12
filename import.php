<?php

@include_once 'commons.php';

$options = stripslashes_deep($_POST['options']);

$options_profile = get_option('newsletter_profile');

if ($action == 'import') {
    @set_time_limit(100000);
    $csv = stripslashes($_POST['csv']);
    $lines = explode("\n", $csv);

    $error = array();
    // Subscriber array with base user data from selected lists and other default values
    $subscriber = array();
    for ($i=1; $i<=NEWSLETTER_LIST_MAX; $i++)
    {
        $list = 'list_' . $i;
        if (isset($options[$list])) $subscriber[$list] = 1;
        else {
            if ($options['mode'] == 'overwrite') $subscriber[$list] = 0;
        }
    }

    foreach ($lines as $line) {
        // Parse the CSV line
        $line = trim($line);
        if ($line == '') continue;
        if ($line[0] == '#') continue;
        $separator = $options['separator'];
        if ($separator == 'tab') $separator = "\t";
        $data = explode($separator, $line);


        // Builds a subscriber data structure
        $subscriber['email'] = $newsletter->normalize_email($data[0]);
        if (!$newsletter->is_email($subscriber['email']))
        {
            $error[] = '[INVALID EMAIL] ' . $line;
            continue;
        }
        $subscriber['name'] = $newsletter->normalize_name($data[1]);
        $subscriber['surname'] = $newsletter->normalize_name($data[2]);
        // May by here for a previous saving
        unset($subscriber['id']);

        // Try to load the user by email
        $user = $wpdb->get_row($wpdb->prepare("select * from " . $wpdb->prefix .
                            "newsletter where email=%s", $subscriber['email']), ARRAY_A);

        // If the user is new, we simply add it
        if (empty($user)) {
            newsletter_save($subscriber);
            continue;
        }

        if ($options['mode'] == 'skip') {
            $error[] = '[DUPLICATE] ' . $line;
            continue;
        }

        if ($options['mode'] == 'overwrite') {
            $subscriber['id'] = $user['id'];
            newsletter_save($subscriber);
            continue;
        }

        if ($options['mode'] == 'update') {
            newsletter_save(array_merge($user, $subscriber));
        }
    }
}

$nc = new NewsletterControls();
$nc->errors($errors);
$nc->messages($messages);


$lists = array('0' => 'All');
for ($i = 1; $i <= NEWSLETTER_LIST_MAX; $i++) {
    $lists['' . $i] = '(' . $i . ') ' . $options_profile['list_' . $i];
}
?>

<div class="wrap">
    <h2>Newsletter Import/Export</h2>
    <form method="post" action="">
        <?php $nc->init(); ?>
        <h3>Export</h3>
        <table class="form-table">
            <tr>
                <td>
                    <?php $nc->select('list', $lists); ?>
                    <?php $nc->button('export', 'Export'); ?>
                </td>
            </tr>
        </table>
    </form>

    <h3>Import</h3>
    <p>
        Please consider to break up your input list if you get errors, blank pages or partially imported lists: it can be a time/resource limit
        of your provider. It's safe to import the same list a second time, no duplications will occur.
    </p>
    <p>
        Import list format is:<br /><br />
        <b>email</b><i>[separator]</i><b>name</b><i>[new line]</i><br />
        <b>email</b><i>[separator]</i><b>name</b><i>[new line]</i><br />
        <br />
        where the [separator] must be selected from the available ones. The "name" field is optional, while the "email" field is
        mandatory. Empty lines and lines starting with "#" will be skipped.
    </p>


    <?php require_once 'header.php'; ?>

    <?php if (!empty($error)) { ?>

    <h3><?php _e('Rows with errors', 'newsletter'); ?></h3>

    <textarea wrap="off" style="width: 100%; height: 150px; font-size: 11px; font-family: monospace"><?php echo htmlspecialchars(implode("\n", $error))?></textarea>

    <?php } ?>

    <form method="post" action="">
        <?php $nc->init(); ?>

        <h3><?php _e('CSV text with subscribers', 'newsletter'); ?></h3>
         <table class="form-table">
            <tr valign="top">
                <th>Lists to associate</th>
                <td>
                    <?php for ($i=1; $i<=NEWSLETTER_LIST_MAX; $i++) { ?>
                        <?php $nc->checkbox('list_' . $i, '(' . $i . ') ' . htmlspecialchars($options_profile['list_' . $i])); ?><br />
                    <?php } ?>
                    <div class="hints">
                        Every new imported or updated subscribers will be associate with selected lists above.
                    </div>
                </td>
            </tr>
            <!--
            <tr valign="top">
                <th>Follow up</th>
                <td>
                    <?php $nc->select('followup', array('none'=>'None', 'activate'=>'Activate')); ?>
                </td>
            </tr>
            -->
            <tr valign="top">
                <th>Import mode</th>
                <td>
                    If the email is already present:
                    <?php $nc->select('mode', array('update'=>'Update', 'overwrite'=>'Overwrite', 'skip'=>'Skip')); ?>
                        <div class="hints">
                        <strong>Update</strong>: the user data is updated: if a user was associated to some lists, those associations will be
                        kept and the new one added; his name is updated as well.<br />
                        <strong>Overwrite</strong>: recreate the subscriber with specified data (CSV+lists).<br />
                        <strong>Skip</strong>: leave untouched user's data when he's already subscribed.
                    </div>
                </td>
            </tr>
            <tr valign="top">
                <th>Separator</th>
                <td>
                    <?php $nc->select('separator', array(';'=>'Semicolon', ','=>'Comma', 'tab'=>'Tabulation')); ?>
                </td>
            </tr>


            <tr valign="top">
                <th>CSV text</th>
                <td>
                    <textarea name="csv" wrap="off" style="width: 100%; height: 300px; font-size: 11px; font-family: monospace"></textarea>
                </td>
        </table>

        <p class="submit">
            <?php $nc->button('import', 'Import'); ?>
        </p>
    </form>

</div>
