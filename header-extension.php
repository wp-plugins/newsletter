<?php
/*
 * Header file for the extensions administrative panels.
 * 
 * - no top noticies
 * - no donation link
 */
?>
<?php if (NEWSLETTER_HEADER) { ?>
<div id="newsletter-header">
    <a href="http://www.satollo.net/plugins/newsletter/newsletter-documentation" target="_blank">Documentation</a>
    <a href="http://www.satollo.net/forums" target="_blank">Forum</a>

    <!--<a href="http://www.satollo.net/plugins/newsletter/newsletter-collaboration" target="_blank">Collaboration</a>-->

    <form style="display: inline; margin: 0;" action="http://www.satollo.net/wp-content/plugins/newsletter/do/subscribe.php" method="post" target="_blank">
        Subscribe<!-- to satollo.net--> <input type="email" name="ne" required placeholder="Your email">
        <input type="submit" value="Go">
    </form>

    <a href="https://www.facebook.com/satollo.net" target="_blank"><img style="vertical-align: bottom" src="<?php echo plugins_url('newsletter'); ?>/images/facebook.png"></a>

    <!--
    <a href="http://www.satollo.net/plugins/newsletter/newsletter-delivery-engine" target="_blank">Engine next run in <?php echo wp_next_scheduled('newsletter') - time(); ?> s</a>
    -->
</div>
<?php } ?>

<?php $newsletter->warnings(); ?>
