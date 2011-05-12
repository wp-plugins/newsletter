<?php

/**
 * Newsletter widget version 2.0: it'll replace the old version left for compatibility.
 */
class NewsletterWidget extends WP_Widget {

    function NewsletterWidget() {
        parent::WP_Widget(false, $name = 'Newsletter',
                array('description'=>'Newsletter widget to add subscription forms on sidebars'),
                array('width'=>'350px'));
    }

    function widget($args, $instance) {
        global $newsletter;
        extract($args);

        echo $before_widget;
        if (!empty($instance['title'])) {
            echo $before_title . $instance['title'] . $after_title;
        }

        $buffer = $instance['text'];
        $options = get_option('newsletter');
        $options_profile = get_option('newsletter_profile');
        if (stripos($instance['text'], '<form') === false) {
            $form .= '<div class="newsletter newsletter-widget"><form action="' . $newsletter->options_main['url'] . '" method="post">';
            $form .= '<input type="hidden" name="na" value="s"/>';
            $form .= '<input type="hidden" name="nr" value="widget"/>';
            if ($options_profile['name_status'] == 2)
                $form .= '<p><input type="text" name="nn" value="' . $options_profile['name'] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';
            if ($options_profile['surname_status'] == 2)
                $form .= '<p><input type="text" name="ns" value="' . $options_profile['surname'] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';
            $form .= '<p><input type="text" name="ne" value="' . $options_profile['email'] . '" onclick="if (this.defaultValue==this.value) this.value=\'\'" onblur="if (this.value==\'\') this.value=this.defaultValue"/></p>';
            if ($options_profile['sex_status'] == 2) {
                $form .= '<p><select name="nx" class="newsletter-sex">';
                $form .= '<option value="m">' . $options_profile['sex_male'] . '</option>';
                $form .= '<option value="f">' . $options_profile['sex_female'] . '</option>';
                $form .= '</select></p>';
            }
            $form .= '<p><input type="submit" value="' . $options_profile['subscribe'] . '"/></p>';
            $form .= '</form></div>';
            if (strpos($buffer, '{subscription_form}') !== false) $buffer = str_replace('{subscription_form}', $form, $buffer);
            else $buffer .= $form;
        }
        else {
            $buffer = str_ireplace('<form', '<form method="post" action="' . $newsletter->options_main['url'] . '"', $buffer);
            $buffer = str_ireplace('</form>', '<input type="hidden" name="na" value="s"/></form>', $buffer);
        }

        echo $buffer;
        echo $after_widget;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['text'] = $new_instance['text'];
        return $instance;
    }

    function form($instance) {
    ?>
        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>">
                Title:
                <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($instance['title']); ?>" />
            </label>


            <label for="<?php echo $this->get_field_id('text'); ?>">
                Introduction:
                <textarea class="widefat" rows="10" cols="20" id="<?php echo $this->get_field_id('text'); ?>" name="<?php echo $this->get_field_name('text'); ?>"><?php echo esc_html($instance['text']); ?></textarea>
            </label>
            The subscription form is created according the subscription panel configurations and appended at the end of the introduction text. If you
            want to place the form in the middle of introduction text above, use the {subscription_form} tag.<br />
            You can even create a full customized subscription on introduction text, it will be detected and the standard form not inserted.
            Just add a &lt;form&gt; tag with wanted newsletter fields (see documentation on custom form building).
        </p>
    <?php
    }

}

add_action('widgets_init', create_function('', 'return register_widget("NewsletterWidget");'));

?>
