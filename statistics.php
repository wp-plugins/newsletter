<?php

?>

<div class="wrap">

<h2>Statistics</h2>

<?php if (!defined('NEWSLETTER_EXTRAS')) { ?>
    <strong>You need the Newsletter Extras installed to view statistics</strong>
<?php } else { ?>
    <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/statistics.php'; ?>
<?php } ?>


</div>
