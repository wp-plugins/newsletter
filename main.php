<?php

@include_once 'commons.php';

$options = get_option('newsletter_main');

if ($action == 'save') {
    $options = stripslashes_deep($_POST['options']);
    update_option('newsletter_main', $options);
}

$nc = new NewsletterControls($options);

?>

<div class="wrap">

    <h2><?php _e('Newsletter Configuration', 'newsletter'); ?></h2>

    <?php require_once 'header.php'; ?>

    <form method="post" action="">
        <?php wp_nonce_field(); ?>
        <input type="hidden" value="<?php echo NEWSLETTER; ?>" name="options[version]"/>

        <h3><?php _e('General parameters', 'newsletter'); ?></h3>
        <table class="form-table">
            <tr valign="top">
                <th><?php _e('Enable access to editors?', 'newsletter'); ?></th>
                <td>
                    <?php $nc->yesno('editor'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Always show panels in english?', 'newsletter'); ?></th>
                <td>
                    <?php $nc->yesno('no_translation'); ?>
                    <br />
                    <?php _e('The author does NOT mainatin translations, so if you have a doubt about some texts, disable the translations', 'newsletter'); ?>
                </td>
            </tr>
            <tr valign="top">
                <th><?php _e('Logging', 'newsletter'); ?></th>
                <td>
                    <?php $nc->select('logs', array(0=>'None', 1=>'Normal', 2=>'Debug')); ?>
                    <br />
                    <?php _e('Debug level saves user data on file system, use only to debug problems.', 'newsletter'); ?>
                </td>
            </tr>
        </table>
        <p class="submit">
            <?php $nc->button('save', __('Save', 'newsletter')); ?>
        </p>
    </form>
</div>
