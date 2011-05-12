<?php
// If you want to translate or customize this theme, just copy the file inside the folder
// wp-content/plugins/newsletter-pro-custom/themes
// (create it if it does not exist) and the a new theme called "theme-1" will appear
// on autocompose menu. You can rename the file if you want.

$posts = new WP_Query();
$posts->query(array('showposts'=>10, 'post_status'=>'publish'));
?>
<table bgcolor="#c0c0c0" width="100%" cellpadding="20" cellspacing="0" border="0">
    <tr>
        <td align="center">
            <table width="500" bgcolor="#ffffff" align="center" cellspacing="10" cellpadding="0" style="border: 1px solid #666;">
          <tr>
              <td style="font-size: 30px">
                  <i><?php echo get_option('blogname'); ?></i>
              </td>
          </tr>
          <tr>
              <td style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 12px; color: #999">
                  <br />NEWSLETTER<br /><br />
              </td>
          </tr>
          <tr>
              <td style="font-size: 14px; color: #666">
                  <p>Dear {name}, here an update from <?php echo get_option('blogname'); ?>.</p>
              </td>
          </tr>
<?php
while ($posts->have_posts())
{
    $posts->the_post();
    $image = nt_post_image(get_the_ID());
?>
          <tr>
              <td style="font-size: 14px; color: #666">
                  <p><a href="<?php echo get_permalink(); ?>" style="font-size: 16px; color: #000; text-decoration: none"><?php the_title(); ?></a></p>

                  <?php the_excerpt(); ?>
              </td>
          </tr>
<?php
}
?>
          <tr>
              <td style="border-top: 1px solid #eee; border-bottom: 1px solid #eee; font-size: 12px; color: #999">
                  You received this email because you subscribed for it as {email}. If you'd like, you can <a href="{unsubscription_url}">unsubscribe</a>.
              </td>
          </tr>
      </table>
        </td>
    </tr>
</table>