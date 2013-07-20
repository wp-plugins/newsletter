<div id="tabs">
    <ul>
        <li><a href="#tab-general">General</a></li>
        <li><a href="#tab-posts">Posts</a></li>
        <li><a href="#tab-social">Social</a></li>
    </ul>

    <div id="tab-general">
        Base color
        <?php $controls->color('theme_color'); ?> (format #RRGGBB)
        <br><br>
        Banner/Title
        <?php $controls->wp_editor('theme_banner'); ?>
        <div class="hints">
            Create a content with an image (500 pixel wide) that will be your newsletter banner and that will replace the 
            title with your blog name.
        </div>
    </div>
    <div id="tab-posts">
        <?php $controls->checkbox('theme_posts', 'Add latest posts'); ?>
        <br>
        <?php $controls->checkbox('theme_thumbnails', 'Add post thumbnails'); ?>
        <br>
        <?php $controls->checkbox('theme_excerpts', 'Add post excerpts'); ?>
        <br><br>
        Categories
        <?php $controls->categories_group('theme_categories'); ?>
        <br><br>
        Tags
        <?php $controls->text('theme_tags', 30); ?> (comma separated)
        <br><br>
        Max posts
        <?php $controls->text('theme_max_posts', 5); ?>   
        <br><br>
        Post types to include
        <br> 
        <?php $controls->post_types('theme_post_types'); ?>
        <div class="hints">Leave all uncheck for a default behavior.</div>
    </div>
    <div id="tab-social">
        <table class="form-table">
            <tr>
                <th>Facebook</th>
                <td><?php $controls->text_url('theme_facebook',30); ?></td>
            </tr>
            <tr>
                <th>Twitter</th>
                <td><?php $controls->text_url('theme_twitter',30); ?></td>
            </tr>
            <tr>
                <th>Pinterest</th>
                <td><?php $controls->text_url('theme_pinterest',30); ?></td>
            </tr>
            <tr>
                <th>Google+</th>
                <td><?php $controls->text_url('theme_googleplus',30); ?></td>
            </tr>
            <tr>
                <th>LinkedIn</th>
                <td><?php $controls->text_url('theme_linkedin',30); ?></td>
            </tr>
        </table>

    </div>
</div>