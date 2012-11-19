<?php
require_once NEWSLETTER_INCLUDES_DIR . '/controls.php';
$module = NewsletterStatistics::instance();
$controls = new NewsletterControls();
$emails = Newsletter::instance()->get_emails();

if ($controls->is_action('save')) {
    $module->save_options($controls->data);
    $controls->messages = 'Saved.';
}
?>

<div class="wrap">
    <h2>Statistics</h2>
    <p>
        This is the basic version of Newsletter Statistics module. Single email statistics can be accessed directly from
        email list on Emails, Updates, Follow Up panels. Below the complete list.
    </p>

    <form method="post">
        <?php $controls->init(); ?>

        <table class="form-table">
            <tr>
                <th>Log level</th>
                <td>
                    <?php $controls->log_level(); ?>
                </td>
            </tr>
        </table>
        <p><?php $controls->button('save', 'Save'); ?>
    </form>

    <table class="widefat" style="width: auto">
      <thead>
        <tr>
          <th>Id</th>
          <th>Subject</th>
          <th>Date</th>
          <th>Type</th>
          <th>Status</th>
          <th>&nbsp;</th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($emails as &$email) { ?>
          <tr>
            <td><?php echo $email->id; ?></td>
            <td><?php echo htmlspecialchars($email->subject); ?></td>
            <td><?php echo $email->date; ?></td>
            <td><?php echo $email->type; ?></td>
            <td>
              <?php echo $email->status; ?>
              (<?php echo $email->sent; ?>/<?php echo $email->total; ?>)
            </td>
            <td>
                <a class="button" href="<?php echo NewsletterStatistics::instance()->get_statistics_url($email->id); ?>">statistics</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
</div>

</div>
