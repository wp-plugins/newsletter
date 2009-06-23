<?php

function widget_newsletter_init()
{
    function widget_newsletter($args)
    {
        $options = get_option('newsletter');

        // $args is an array of strings that help widgets to conform to
        // the active theme: before_widget, before_title, after_widget,
        // and after_title are the array keys. Default tags: li and h2.
        extract($args);

        // Each widget can store its own options. We keep strings here.
        $optionsw = get_option('newsletter_widget');
        $title = $optionsw['title'];
        $text = $optionsw['text'];

        // These lines generate our output. Widgets can be very complex
        // but as you can see here, they can also be very, very simple.
        echo $before_widget . $before_title . $title . $after_title;

        $buffer = str_replace('{newsletter_url}', $options['url'], newsletter_label('widget_form'));
        $buffer = str_replace('{text}', $optionsw['text'], $buffer);
        echo $buffer;
        
        echo $after_widget;
    }

    function widget_newsletter_control()
    {
        // Get our options and see if we're handling a form submission.
        $options = get_option('newsletter_widget');
        if (!is_array($options))
        {
            $options = array('title'=>'Newsletter subscription');
            $options = array('text'=>'');
        }

        if ( $_POST['newsletter-submit'] )
        {
            // Remember to sanitize and format use input appropriately.
            $options['title'] = strip_tags(stripslashes($_POST['newsletter-title']));
            $options['text'] = strip_tags(stripslashes($_POST['newsletter-text']));
            update_option('newsletter_widget', $options);
        }

        // Be sure you format your options to be valid HTML attributes.
        $title = htmlspecialchars($options['title'], ENT_QUOTES);
        $text = htmlspecialchars($options['text'], ENT_QUOTES);

        // Here is our little form segment. Notice that we don't need a
        // complete form. This will be embedded into the existing form.
        echo 'Title<br /><input id="newsletter-title" name="newsletter-title" type="text" value="'.$title.'" />';
        echo '<br /><br />';
        echo 'Introduction<br /><textarea style="width: 350px;" id="newsletter-text" name="newsletter-text">'.$text.'</textarea>';
        echo '<input type="hidden" id="newsletter-submit" name="newsletter-submit" value="1" />';
    }

    register_sidebar_widget('Newsletter', 'widget_newsletter');
    register_widget_control('Newsletter', 'widget_newsletter_control', 370, 200);
}

add_action('widgets_init', 'widget_newsletter_init');
//add_action('init', widget_newsletter_register);
?>
