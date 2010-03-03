<?php
@include_once 'commons.php';
?>
<div class="wrap">

<h2><?php _e('Newsletter feed by mail', 'newsletter'); ?></h2>

<?php if (newsletter_has_extras('1.0.5')) { ?>
    <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/feed.php'; ?>
<?php } else { ?>
    <strong>You need the <a href="http://www.satollo.net/plugins/newsletter-extras">Newsletter Extras 1.0.5+</a> installed to use this panel</strong>
<?php } ?>

</div>