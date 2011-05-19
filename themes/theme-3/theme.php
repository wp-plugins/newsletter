<?php
// If you want to translate or customize this theme, just copy the file inside the folder
// wp-content/plugins/newsletter-pro-custom/themes
// (create it if it does not exist) and the a new theme called "theme-1" will appear
// on autocompose menu. You can rename the file if you want.

$posts = new WP_Query();
$posts->query(array('showposts' => 10, 'post_status' => 'publish'));
?>

<br />

<table cellspacing="0" align="center" border="0" style="max-width:600px; width:600px; background-color: #eee;" cellpadding="0" width="600px">
    <!-- Header -->
    <tr style="background: #455560; background-image: url(<?php echo plugins_url('header.jpg', __FILE__); ?>); height:80px;width:600px;" cellspacing="0" border="0" align="center" cellpadding="0" width="600" height="80">
        <td height="80" width="600" style="color: #fff; font-size: 30px; font-family: Arial;" align="center" valign="middle">
            <?php echo get_option('blogname'); ?>
        </td>
    </tr>
    <tr style="background: #d0d0d0; height:20px;width:600px;">
        <td valign="top" height="20" width="600" bgcolor="#ffffff" align="center" style="font-family: Arial; font-size: 12px">
            <?php echo get_option('blogdescription'); ?>
        </td>
    </tr>
    <tr>
        <td>
            <table cellspacing="0" border="0" style="max-width:600px; width:600px; background-color: #eee;font-family:helvetica,arial,sans-serif;color:#555;font-size:13px;line-height:15px;" align="center" cellpadding="20" width="600px">
                <tr>
                    <td>
                        <table cellpadding="0" cellspacing="0" border="0" bordercolor="" width="100%" bgcolor="#ffffff">
                            <?php
                            while ($posts->have_posts()) {
                                $posts->the_post();
                                $image = nt_post_image(get_the_ID());
                            ?>
                                <tr>
                                    <td style="font-family: Arial; font-size: 12px">
                                        <?php if ($image != null) { ?>
                                            <img src="<?php echo $image; ?>" alt="picture" align="left" width="100" height="100" style="margin-right: 10px"/>
                                        <?php } ?>
                                        <a href="<?php echo get_permalink(); ?>" style="color: #000; text-decoration: none"><b><?php the_title(); ?></b></a><br />

                                        <?php the_excerpt(); ?>
                                    </td>
                                </tr>
                            <?php
                                }
                            ?>
                            </table>

                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td bgcolor="#ffffff" style="font-family: Arial; font-size: 12px">

                This email was sent to <b>{email}</b> because you opted in on <?php echo get_option('blogname'); ?> website.
            <br />

            <a href="{profile_url}">Manage Subscriptions</a> |

            <a href="{unsubscription_url}">Unsubscribe</a>
        </td>
    </tr>
</table>
