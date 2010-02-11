<div class="wrap">

<h2>Statistics</h2>

<?php if (!newsletter_has_extras('1.0.3')) { ?>
    <strong>You need the <a href="http://www.satollo.net/plugins/newsletter/extras">Newsletter Extras</a> installed to view statistics</strong>
<?php } else { ?>
    <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/emails.php'; ?>
<?php } ?>

</div>
