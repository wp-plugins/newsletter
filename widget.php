<?php

function widget_newsletter_init()
{
    function widget_newsletter($args)
    {
        global $newsletter_options, $newsletter_labels;
        // $args is an array of strings that help widgets to conform to
        // the active theme: before_widget, before_title, after_widget,
        // and after_title are the array keys. Default tags: li and h2.
        extract($args);

        // Each widget can store its own options. We keep strings here.
        $options = get_option('newsletter_widget');
        $title = $options['title'];

        // These lines generate our output. Widgets can be very complex
        // but as you can see here, they can also be very, very simple.
        echo $before_widget . $before_title . $title . $after_title;

        echo str_replace('{newsletter_url}', $newsletter_options['url'], $newsletter_labels['widget_form']);

        echo $after_widget;
    }

    function widget_newsletter_control()
    {
        global $newsletter_labels;

        // Get our options and see if we're handling a form submission.
        $options = get_option('newsletter_widget');
        if (!is_array($options))
        {
            $options = array('title'=>'Newsletter subscription');
        }

        if ( $_POST['newsletter-submit'] )
        {
            // Remember to sanitize and format use input appropriately.
            $options['title'] = strip_tags(stripslashes($_POST['newsletter-title']));
            update_option('newsletter_widget', $options);
        }

        // Be sure you format your options to be valid HTML attributes.
        $title = htmlspecialchars($options['title'], ENT_QUOTES);

        // Here is our little form segment. Notice that we don't need a
        // complete form. This will be embedded into the existing form.
        echo '<p style="text-align:right;"><label for="newsletter-title">Title<input style="width: 200px;" id="newsletter-title" name="newsletter-title" type="text" value="'.$title.'" /></label></p>';
        echo '<input type="hidden" id="newsletter-submit" name="newsletter-submit" value="1" />';
    }

    register_sidebar_widget('Newsletter', 'widget_newsletter');
    register_widget_control('Newsletter', 'widget_newsletter_control', 300, 100);
}

add_action('widgets_init', 'widget_newsletter_init');
//add_action('init', widget_newsletter_register);
?>
