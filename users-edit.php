<?php
@include_once 'commons.php';
$nc = new NewsletterControls();

if (isset($_GET['id'])) {
    $nc->load($wpdb->prefix . 'newsletter', $_GET['id']);
    if (empty($nc->data['id'])) {
        $nc->data['status'] = 'C';
        $nc->data['token'] = md5(rand());
    }
}
else {
    if ($nc->is_action('save')) {
        for($i=1; $i<=NEWSLETTER_LIST_MAX; $i++) {
            if (!isset($nc->data['list_' . $i])) $nc->data['list_' . $i] = 0;
        }
        $nc->save($wpdb->prefix . 'newsletter');
    }
}

$nc->errors($errors);
$nc->messages($messages);

$options_profile = get_option('newsletter_profile');
?>
<div class="wrap">
    <h2>Newsletter Subscriber Editing</h2>
    <p><a href="admin.php?page=newsletter/users.php">Back to the list</a></p>
    <form method="post" action="admin.php?page=newsletter/users-edit.php">
        <?php $nc->init(); ?>
        <?php $nc->hidden('id'); ?>
        <?php $nc->hidden('token'); ?>

        <table class="form-table">
            <tr valign="top">
                <th>&nbsp;</th>
                <td>
                    id: <?php $nc->value('id'); ?>
                    created: <?php $nc->value('created'); ?>
                    ip: <?php $nc->value('ip'); ?>
                    token: <?php $nc->value('token'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Name and email</th>
                <td>
                    first name: <?php $nc->text('name', 30); ?> last name: <?php $nc->text('surname', 30); ?>
                    email: <?php $nc->text('email', 40); ?> sex: <?php $nc->select('sex', array('n'=>'Not specified', 'f'=>'female', 'm'=>'male')); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Status</th>
                <td>
                    <?php $nc->select('status', array('C'=>'Confirmed', 'S'=>'Not confirmed', 'U'=>'Unsubscribed')); ?>
                </td>
            </tr>
            <tr valign="top">
                <th>Test subscriber?</th>
                <td>
                    <?php $nc->yesno('test'); ?>
                    <div class="hints">
                        A test subscriber is a normal subscriber that is used when sending test are made, too.
                    </div>
                </td>
            </tr>            
            <tr valign="top">
                <th>Lists</th>
                <td>
                    <table>
                        <tr>
                            <td valign="top">
                                <?php
                                for ($i=1; $i<=5; $i++) {
                                    $nc->checkbox('list_' . $i, '(' . $i . ') ' . htmlspecialchars($options_profile['list_' . $i]) . '<br />');
                                }
                                ?>
                            </td>
                            <td valign="top">
                                <?php
                                for ($i=6; $i<=9; $i++) {
                                    $nc->checkbox('list_' . $i, '(' . $i . ') ' . htmlspecialchars($options_profile['list_' . $i]) . '<br />');
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr valign="top">
                <th>Profiles</th>
                <td>
                    <table>
                <?php
                for ($i=1; $i<20; $i++) {
                    echo '<tr><td>(' . $i . ') ';
                    echo $options_profile['profile_' . $i];
                    echo '</td><td>';
                    $nc->text('profile_' . $i, 50);
                    echo '</td></tr>';
                }
                ?>
                    </table>
                </td>
            </tr>
        </table>

        <p class="submit">
            <?php $nc->button('save', 'Save'); ?>
        </p>

    </form>
</div>
