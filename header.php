<div id="newsletter-header">
    <a href="<?php echo $help_url?$help_url:'http://www.satollo.net/plugins/newsletter/newsletter-configuration'; ?>" target="_blank">Get Help</a>
    <a href="http://www.satollo.net/plugins/newsletter/newsletter-faq" target="_blank">FAQ</a>
    <a href="http://www.satollo.net/forums" target="_blank">Forum</a>
    <a href="http://www.satollo.net/plugins/newsletter/newsletter-collaboration" target="_blank">Collaboration</a>

    <form style="display: inline; margin: 0;" action="http://www.satollo.net/wp-content/plugins/newsletter/do/subscribe.php" method="post" target="_blank">
        Subscribe to satollo.net <input type="email" name="ne" required placeholder="Your email">
        <input type="submit" value="Go">
    </form>

    <a href="https://www.facebook.com/satollo.net" target="_blank"><img style="vertical-align: bottom" src="<?php echo NEWSLETTER_URL; ?>/images/facebook.png"></a>

    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=5Y6JXSA7BSU2L" target="_blank"><img style="vertical-align: bottom" src="<?php echo NEWSLETTER_URL; ?>/images/donate.png"></a>
    <a href="http://www.satollo.net/donations" target="_blank">Even <b>1$</b> helps: read more</a>

    Engine next run in <?php echo wp_next_scheduled('newsletter') - time(); ?> s
</div>

<?php $newsletter->warnings(); ?>
