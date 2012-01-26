<?php
@include_once 'commons.php';

$nc = new NewsletterControls();

if (!$nc->is_action()) {
    $nc->data = get_option('newsletter_profile');
}
else {
    if ($nc->is_action('save')) {
        update_option('newsletter_profile', $nc->data);
    }
    if ($nc->is_action('reset')) {
        include dirname(__FILE__) . '/languages/en_US.php';
        @include dirname(__FILE__) . '/languages/' . WPLANG . '.php';
        update_option('newsletter_profile', $defaults_profile);
        $nc->data = $defaults_profile;
    }
}

$nc->errors($errors);
$nc->messages($messages);

$status = array(0=>'Disabled', 1=>'Only on profile page', 2=>'Even on subscription page');
?>
<script type="text/javascript" src="<?php echo get_option('siteurl'); ?>/wp-content/plugins/newsletter/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
    tinyMCE.init({
        mode : "specific_textareas",
        editor_selector : "visual",
        theme : "advanced",
        theme_advanced_disable : "styleselect",
        relative_urls : false,
        remove_script_host : false,
        theme_advanced_buttons3: "",
        theme_advanced_toolbar_location : "top",
        theme_advanced_resizing : true,
        theme_advanced_statusbar_location: "bottom",
        document_base_url : "<?php echo get_option('home'); ?>/",
        content_css : "<?php echo get_option('blogurl'); ?>/wp-content/plugins/newsletter/editor.css?" + new Date().getTime()
    });
</script>

<div class="wrap">

    <h2>Newsletter Profile</h2>

    <?php include dirname(__FILE__) . '/header.php'; ?>
    
    <p>
        User profile is the whole set of user data that he can edit accessing the profile page (usually via the {profile_url} link you
        should add in any newsletter or welcome message.<br />
        Some of this data (at least the email) is collected on subscription and you can decide here what to ask the user on sign up for your
        newsletter.<br />
        It's a good practice to let the subscriber to sign up with a small set of data (eg. only his email or email and name) and then let him to
        add more information on a profile page.<br />
        The form seems complex, but it's not! On first approach, skip profiles and lists.
    </p>

    <form action="" method="post">
    <?php $nc->init(); ?>

        <h3>Profile page</h3>
        <table class="form-table">
            <tr valign="top">
                <th>Profile page text</th>
                <td>
                    <?php $nc->editor('profile_text'); ?>
                    <div class="hints">
                        This is the page content where the profile form is placed. Use the tag {profile_form} (required, don't forget it!) to let Newsletter
                        Pro know how to insert the profile editing form. You can add text before and after the tag to give some kind of explanation to
                        the subscriber.<br />
                        You can use the tag {unsubscription_url} to create a link to let the user to cancel his subscription. Using the tag
                        {unsubscription_confirm_url} the link leads to a direct cancellation without the confirm step.
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit"><?php $nc->button('save', 'Save'); ?></p>
        

        <h3>Main profile fields</h3>
        <table class="form-table">
            <tr>
                <th>User's data/fields</th>
                <td>
                    <table class="widefat">
                        <thead>
                    <tr>
                        <th>Field</th><th>When/Where</th><th>Configuration</th>
                    </tr>
                        </thead>
                    <tr>
                        <td>Email</td><td>&nbsp;</td>
                        <td>
                            label: <?php $nc->text('email'); ?><br/>
                            wrong email message: <?php $nc->text('email_error', 50); ?>
                        </td>
                    </tr>
                    <tr><td>First Name</td><td><?php $nc->select('name_status', $status); ?></td><td>label: <?php $nc->text('name'); ?></td></tr>
                    <tr><td>Last Name</td><td><?php $nc->select('surname_status', $status); ?></td><td>label: <?php $nc->text('surname'); ?></td></tr>
                    <tr>
                        <td>Sex</td><td><?php $nc->select('sex_status', $status); ?></td>
                        <td>
                            label: <?php $nc->text('sex'); ?>
                            "female": <?php $nc->text('sex_female'); ?>
                            "male": <?php $nc->text('sex_male'); ?>
                        </td>
                    </tr>
                    <tr><td>Privacy checkbox</td><td><?php $nc->yesno('privacy_status'); ?></td>
                        <td>
                            text: <?php $nc->text('privacy', 50); ?><br />
                            unchecked message: <?php $nc->text('privacy_error', 50); ?>
                        </td>
                    </tr>
                    </table>
                    <div class="hints">
                    If sex field is disabled subscribers will be stored with unspecified sex. Privacy is applied only on subscription and is
                    a checkbox the use must check to proceed with subscription.
                    </div>
                </td>
            </tr>
            <tr>
                <th>Buttons</th>
                <td>
                    "subscribe": <?php $nc->text('subscribe'); ?> "profile save": <?php $nc->text('save'); ?>
                    <div class="hints">
                    For "subscribe" insert an URL to an image (http://...) to use it as a graphical button.
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit"><?php $nc->button('save', 'Save'); ?></p>
        

        <h3>Extra profile fields</h3>

        <table class="form-table">
            <tr>
                <th>Generic profile fields</th>
                <td>
                    <div class="hints">Fields of type "list" must be configured with a set of options, comma separated
                        like: "first option, second option, third option".
                    </div>
                    <table class="widefat">
                   <thead>
                    <tr>
                        <th>Field</th><th>Label</th><th>When/Where</th><th>Type</th><th>Configuration</th>
                    </tr>
                        </thead>
                    <?php for ($i=1; $i<=19; $i++) { ?>
                     <tr>
                         <td>Profile <?php echo $i; ?></td>
                         <td><?php $nc->text('profile_' . $i); ?></td>
                         <td><?php $nc->select('profile_' . $i . '_status', $status); ?></td>
                         <td><?php $nc->select('profile_' . $i . '_type', array('text'=>'Text', 'select'=>'List')); ?></td>
                         <td>
                             <?php $nc->textarea_fixed('profile_' . $i . '_options', '300px', '50px'); ?>
                         </td>
                     </tr>
                     <?php } ?>
                    </table>
                    <div class="hints">
                        Those fields are collected as texts, Newsletter Pro does not give meaning to them, it just stores them.
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit"><?php $nc->button('save', 'Save'); ?></p>


        <h3>List/Options/Topics</h3>
        <p>
            Remember that lists are not separate lists of users, they are options chose by subscriber and usually they refer
            to topics of your emails.
        </p>
        <table class="form-table">
            <tr>
                <th>Lists/Options</th>
                <td>
                    <table class="widefat">
                       <thead>
                    <tr>
                        <th>Field</th><th>When/Where</th><th>Configuration</th>
                    </tr>
                        </thead>
                    <?php for ($i=1; $i<=9; $i++) { ?>
                        <tr><td>List <?php echo $i; ?></td><td><?php $nc->select('list_' . $i . '_status', $status); ?></td><td>label: <?php $nc->text('list_' . $i); ?></td></tr>
                    <?php } ?>
                    </table>
                    <div class="hints">
                        Disabled lists are not selectable by users but they can be assigned from admin panels, so they can be
                        considered as private lists.
                    </div>
                </td>
            </tr>
        </table>
        <p class="submit"><?php $nc->button('save', 'Save'); ?></p>

       
    </form>
</div>