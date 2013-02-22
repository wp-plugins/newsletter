<?php
$module = NewsletterStatistics::instance();
$email = Newsletter::instance()->get_email($_GET['id']);
?>
<div class="wrap">
    <?php $help_url = 'http://www.satollo.net/plugins/newsletter/statistics-module'; ?>
    <?php include NEWSLETTER_DIR . '/header.php'; ?>
    
    <h5>Statistics Module</h5>

    <h2>Statistics for "<?php echo esc_html($email->subject); ?>"</h2>

    <table class="widefat" style="width: auto">
        <thead>
            <tr>
        <td>Field</td>
        <td>Value</td>
            </tr>
        </thead>

        <tbody>
            <tr>
        <td>Total sent</td>
        <td><?php echo $email->sent; ?></td>
            </tr>
            <tr>
        <td>Email open</td>
        <td><?php echo $module->get_read_count($email->id); ?></td>
            </tr>
        </tbody>
    </table>
</div>
