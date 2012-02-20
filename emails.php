<?php
@include_once 'commons.php';
$nc = new NewsletterControls();

$emails = $wpdb->get_results("select * from " . $wpdb->prefix . "newsletter_emails where type='email' order by id desc");

if ($nc->is_action('send')) {
    $newsletter->hook_newsletter();
}
?>

<div class="wrap">

<h2>Messages</h2>

<?php include dirname(__FILE__) . '/header.php'; ?>

<form method="post" action="admin.php?page=newsletter/emails.php">
    <?php $nc->init(); ?>

<p><a href="admin.php?page=newsletter/emails-edit.php&amp;id=0" class="button">New message</a></p>
<p>
    Delivery engine next run: <?php echo wp_next_scheduled('newsletter')-time(); ?> seconds
    <?php $nc->button('send', 'Trigger now'); ?>
</p>

    <table class="widefat">
        <thead>
            <tr>
                <th>Id</th>
                <th>Subject</th>
                <th>Date</th>
                <th>Status</th>
                <th>&nbsp;</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($emails as &$email) { ?>
            <tr>
                <td><?php echo $email->id; ?></td>
                <td><a href="admin.php?page=newsletter/emails-edit.php&amp;id=<?php echo $email->id; ?>"><?php echo htmlspecialchars($email->subject); ?></a></td>
                <td><?php echo $email->date; ?></td>
                <td>
                    <?php echo $email->status; ?>
                    (<?php echo $email->sent; ?>/<?php echo $email->total; ?>)
                </td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</form>
</div>
