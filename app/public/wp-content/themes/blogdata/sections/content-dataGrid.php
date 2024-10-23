<?php
    if(isset($args['visibility'])){ $visibility = $args['visibility']; }else{ $visibility = ''; }
    $blogdata_archive_page_layout = esc_attr(blogdata_get_option('blogdata_archive_page_layout',)); 
    global $post; ?>

    <div id="post-<?php the_ID(); ?>" <?php if($blogdata_archive_page_layout == "grid-fullwidth") { echo post_class('c '.$visibility); } else { echo post_class(' '.$visibility); } ?>>
    <!-- bs-posts-sec bs-posts-modul-6 -->
        <div class="bs-blog-post grid-card"> 
            <?php 
                $url = blogdata_get_freatured_image_url($post->ID, 'blogdata-medium');
                blogdata_post_image_display_type($post); 
            blogdata_post_title_content(); ?>
        </div>
    </div>
    <?php 