<style>
    table.clicks td {
        border: 1px solid #666;
        padding: 2px;
        font-size: 10px;
    }
    table.clicks {
        border-collapse: collapse;
    }
    #newsletter .form-table {
        border: 1px solid #999;
        background-color: #fff;
    }
</style>
<div class="wrap" id="newsletter">

<h2>Newsletter Statistics</h2>

<?php if (!defined('NEWSLETTER_EXTRAS')) { ?>
    <strong>You need the <a href="http://www.satollo.net/plugins/newsletter-extras">Newsletter Extras</a> installed to view statistics</strong>
<?php } else { ?>
    <?php require_once ABSPATH . 'wp-content/plugins/newsletter-extras/statistics.php'; ?>
<?php } ?>

</div>
