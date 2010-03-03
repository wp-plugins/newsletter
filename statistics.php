<?php
@include_once 'commons.php';
?>

<div class="wrap">

    <h2><?php _e('Newsletter Statistics', 'newsletter'); ?></h2>

    <?php if (!defined('NEWSLETTER_EXTRAS')) { ?>
    <strong>You need the <a href="http://www.satollo.net/plugins/newsletter-extras">Newsletter Extras</a> installed to view statistics</strong>
    <?php } else { ?>
        <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/statistics.php'; ?>
    <?php } ?>

</div>
