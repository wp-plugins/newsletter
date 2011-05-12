<?php

query_posts('showposts=' . nt_option('posts', 10) . '&post_status=publish');

$text_css = 'font-family: Lucida Sans Unicode, Lucida Grande, sans-serif; font-size: 11px;';
$title_css = 'font-family: Trebuchet MS, Helvetica, sans-serif; font-size: 20px; text-transform: uppercase;';

?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="#FFFFFF">
    <tr>
        <td align="left"><table width="600"  align="center" cellpadding="0" cellspacing="0" style="border-width:1px;border-style:solid;border-color:#cccccc;" >
                <tr>
                    <td bgcolor="#ffffff" height="10"></td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" style="padding-top:0;padding-bottom:0;padding-right:15px;padding-left:15px;"><font size="1" face="Lucida Sans Unicode, Lucida Grande, sans-serif" color="#999999">
                            <span style="font-size:11px;">You have received this email because you are subscribed to <?php echo get_option('blogname'); ?></span></font>
                    </td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff" height="10"></td>
                </tr>
                <tr>
                    <td height="3" bgcolor="#75af2d"></td>
                </tr>
                <tr>
                    <td height="30" bgcolor="#ffffff"></td>
                </tr>
                <tr>
                    <td bgcolor="#ffffff"><table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td width="25" valign="top"></td>
                                <td valign="top" width="262" height="48"><span style="font-size: 38px; color:silver"><?php echo get_option('blogname'); ?></span></td>
                                <td width="25" valign="top"></td>
                            </tr>
                            <tr>
                                <td width="25" height="20" valign="top"></td>
                                <td height="20" valign="top"></td>
                                <td width="25" height="20" valign="top"></td>
                            </tr>
                            <tr>
                                <td width="25" height="1" valign="top"></td>
                                <td height="1" valign="top" bgcolor="#e1e1e1"></td>
                                <td width="25" height="1" valign="top"></td>
                            </tr>
                            <tr>
                                <td width="25" height="20" valign="top"></td>
                                <td height="20" valign="top"></td>
                                <td width="25" height="20" valign="top"></td>
                            </tr>
                            <tr>
                                <td width="25" valign="top"></td>
                                <td valign="top">
                                    <p><strong>Hi {name},</strong></p>
                                    <p>Here a little bit of intgroduction text...</p>
                                    <p>A second line...</p>
                                    <p>Cheers, YOUR NAME</p>
                                </td>
                                <td width="25" valign="top"></td>
                            </tr>

                            <?php while (have_posts()) { the_post(); ?>

                            <tr>
                                <td width="25" height="20" valign="top"></td>
                                <td height="20" valign="top" style="border-bottom: 1px solid #e1e1e1"></td>
                                <td width="25" height="20" valign="top"></td>
                            </tr>
                            <!--
                            <tr>
                                <td width="25" height="1" valign="top"></td>
                                <td height="1" valign="top" bgcolor="#e1e1e1"></td>
                                <td width="25" height="1" valign="top"></td>
                            </tr>
                            -->
                            <tr>
                                <td width="25" height="20" valign="top"></td>
                                <td height="20" valign="top"></td>
                                <td width="25" height="20" valign="top"></td>
                            </tr>
                            <tr>
                                <td width="25" valign="top"></td>
                                <td valign="top" style="<?php echo $text_css; ?>">
                                    <h2 style="<?php echo $title_css; ?>"><?php the_title(); ?></h2>
                                        <?php $img = nt_post_image($post->ID, 'thumbnail'); ?>
                                        <?php if ($img != null) { ?>
                                    <p><img src="<?php echo $img; ?>" alt=""/></p>
                                        <?php } ?>
                                        <?php echo the_excerpt(); ?>
                                    <p><a href="<?php echo get_permalink(); ?>">Read more...</a></p>
                                </td>
                                <td width="25" valign="top"></td>
                            </tr>
                            <?php } ?>

                        </table></td>
                </tr>
                <tr>
                    <td height="25"></td>
                </tr>

                <tr>
                    <td height="40" bgcolor="#e9eee8" style="padding-top:10px;padding-bottom:10px;padding-right:15px;padding-left:15px;">
                        <font size="1" face="Lucida Sans Unicode, Lucida Grande, sans-serif" color="#403f41">
                            <span style="font-size:10px;" >
                                If you do not wish to receive further
                                email notifications like this one, please
                                <a href="{unsubscription_url}" style="text-decoration:none; color:#959595">unsubscribe</a>
                                <br />
                                &copy; 2009, <?php echo get_option('blogname'); ?>
                            </span>
                        </font>
                    </td>
                </tr>
            </table></td>
    </tr>
</table>


