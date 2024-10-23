<?php
/**
 * The template for displaying the content.
 * @package BlogData
 */
?>

<div id="list" <?php post_class('align_cls d-grid'); ?>>
    <?php while(have_posts()){ the_post();
        get_template_part('sections/content','data'); 
    }
    blogdata_post_pagination(); ?>
</div>
<?php