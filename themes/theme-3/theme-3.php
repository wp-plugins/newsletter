<?php
$posts = new WP_Query();
$posts->query(array('showposts'=>10, 'post_status'=>'publish'));

$texts['notice'] = 'You received this email because you are subscribed to ' . get_option('blogname');

$texts['header'] = '';

$empty_image = get_option('blogurl') . '/wp-content/plugins/newsletter/themes/with-picture/empty.gif';

@include(dirname(__FILE__) . '/' . WPLANG . '.php');
?>

            <table align="center" width="600" cellpadding="5" cellspacing="0" style="border-width:2px; border-style:solid; border-color:#aaa;" >
                <!--<tr><td width="600" colspan="2"><div style="margin:10px;"><small><?php echo $texts['notice']; ?></small></div></td></tr>-->
                <tr>
                    <td width="600" colspan="2">
                        <span style="font-size: 38px; color:silver"><?php echo get_option('blogname'); ?></span>
                    </td>
                </tr>
                <tr>
                    <td width="600" colspan="2">
                        <p>Hi {name},<br />
                    here the last news from my blog!</p>
                    </td>
                </tr>
                <tr>
                    <td width="150" valign="top" align="left" style="border-right: 1px solid #ccc">
                        
                        <strong>Categories</strong><br />
                        <?php echo wp_list_categories(array('title_li'=>'', 'echo'=>0, 'style'=>'none')); ?>
                        
                        <br /><br />
                        
                        <strong>Tags</strong><br />
                            <?php echo wp_tag_cloud(array('title_li'=>'', 'echo'=>0, 'smallest'=>9, 'largest'=>'14', 'unit'=>'px')); ?>

                        <br /><br />
                        
                        
                        <strong>Pages</strong><br />
                            <?php wp_page_menu(array('link_after'=>'<br />')); ?>
                    </td>
                    <td width="450" valign="top" align="left">


                        <table cellspacing="10">
                        <?php
                        while ($posts->have_posts())
                        {
                            $posts->the_post();
                        ?>
                            <tr>
                                <!--
                                <td valign="top">
                                    <a href="<?php echo get_permalink(); ?>"><img src="<?php echo nt_post_image($post->ID, 'thumbnail', $empty_image); ?>" width="100"/></a>
                                </td>
                                -->
                                <td valign="top" align="left">
                                    <a href="<?php echo get_permalink(); ?>"><strong><?php the_title(); ?></strong></a><br />
                                        <?php echo the_excerpt(); ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </table>

                    </td>
                </tr>
                <tr>
                    <td colspan="2" height="40" bgcolor="#e9eee8">
                            <small>
                                If you want to unsubscribe <a href="{unsubscription_url}">unsubscribe click here</a>.
                                <br />
                                &copy; <?php echo get_option('blogname'); ?>
                            </small>
                    </td>
                </tr>
            </table>



