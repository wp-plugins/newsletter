<?php
// This theme is used to show subscription messages to users along the various
// subscription and unsubscription steps.
// This theme is used to show feed by mail and follow up unsubscription messages too.
//
// The theme is used ONLY IF, on main configutation, you have NOT set a specific
// WordPress page to be used to show messages.
//
// The theme MUST contain the {message} place holder.

?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html40/strict.dtd">
<html>
    <head>
        <style type="text/css">
            body {
                font-family: sans-serif;
            }
        </style>
    </head>

    <body style="background-color: #ddd">
        <div style="margin: 40px auto 0 auto; width: 600px; border: 3px solid #666; background-color: #fff; padding: 20px">
            <h2><?php echo get_option('blogname'); ?></h2>
            {message}
        </div>
    </body>
</html>