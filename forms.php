<?php
@include_once 'commons.php';
?>

<div class="wrap">

<h2>Newsletter Forms</h2>

<?php if (!defined('NEWSLETTER_EXTRAS')) { ?>
    <strong>You need the <a href="http://www.satollo.net/plugins/newsletter-extras">Newsletter Extras</a> installed to use this panel</strong>
<?php } else { ?>
    <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/forms.php'; ?>
<?php } ?>

</div>