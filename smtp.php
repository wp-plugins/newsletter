<?php
@include_once 'commons.php';
?>

<div class="wrap">

<h2><?php _e('Newsletter SMTP', 'newsletter'); ?></h2>

<?php if (newsletter_has_extras('1.0.4')) { ?>
    <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/smtp.php'; ?>
<?php } else { ?>
    <strong>You need <a href="http://www.satollo.net/plugins/newsletter-extras">Newsletter Extras 1.0.4+</a> installed to configure SMTP</strong>
<?php } ?>

</div>