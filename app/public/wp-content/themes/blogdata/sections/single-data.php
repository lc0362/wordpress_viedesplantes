<?php
$single_meta_orders = get_theme_mod(
    'single_post_meta',
    array(
        'author',
        'date',
        'comments',
        'tags',
    )
);
$enable_category = blogdata_get_option('blogdata_single_post_category');
$enable_meta = blogdata_get_option('blogdata_single_post_meta');
$enable_image = blogdata_get_option('blogdata_single_post_image');
$enable_admin = blogdata_get_option('blogdata_enable_single_admin');
$enable_related = blogdata_get_option('blogdata_enable_single_related');
$enable_comments = blogdata_get_option('blogdata_enable_single_comments');
if(have_posts()) {
    while(have_posts()) { the_post(); ?>
        <div class="bs-blog-post single"> 
            <div class="bs-header">
                <?php
                    if ($enable_category == true ) { 
                        blogdata_post_categories(); 
                    } ?>
                        <h1 class="title" title="<?php the_title_attribute();?>">
                            <?php the_title(); ?>
                        </h1> 
                    <?php

                    if ($enable_meta == true) { ?>
                        <div class="bs-info-author-block">
                            <div class="bs-blog-meta mb-0">
                                <?php foreach($single_meta_orders as $key=> $single_meta_order) {

                                    if ($single_meta_order == 'author') {
                            
                                        do_action('blogdata_action_single_small_admin');
                                    }
                            
                                    if ($single_meta_order == 'date') {
                            
                                        blogdata_date_content();
                                    }
                        
                                    if ($single_meta_order == 'comments') {
                            
                                        blogdata_post_comments();
                                    }

                                    if ($single_meta_order == 'tags') {
                            
                                        blogdata_post_item_tag();
                                    }
                                } ?>
                            </div>
                        </div>
                    <?php }

                    if ($enable_image == true) {
                        do_action('blogdata_action_single_featured_image'); 
                    } ?>
            </div>
            <article class="small single">
                <?php the_content();
                    blogdata_edit_link();
                    blogdata_social_share_post($post); ?>
                    <div class="clearfix mb-3"></div>
                    <?php
                    do_action('blogdata_action_single_next_prev_links');
                    
                    wp_link_pages(array(
                        'before' => '<div class="single-nav-links">',
                        'after' => '</div>',
                    ));
                ?>
            </article>
        </div>
    <?php }

    if ($enable_admin == true) {
        get_template_part('sections/single','author');
    }

    if ($enable_related == true) {
        get_template_part('sections/single','related');
    }

    if ($enable_comments == true) {
        if (comments_open() || get_comments_number()) :
            comments_template();
        endif; 
    }
}