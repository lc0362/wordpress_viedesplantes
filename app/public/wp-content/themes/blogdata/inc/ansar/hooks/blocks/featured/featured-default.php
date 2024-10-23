<?php $slider_meta_enable = get_theme_mod('slider_meta_enable','true');
    // Trending
    $select_trending_news_category = blogdata_get_option('select_trending_news_category');
    // Editor
    $select_editor_news_category = blogdata_get_option('select_editor_news_category');
    $editor_off = 0;
    
    $featured_editor_posts = blogdata_get_posts( 2, $select_editor_news_category);    
    $featured_trending_posts = blogdata_get_posts( 1, $select_trending_news_category);
?>
<div class="col-lg-5 col-md-6">
    <div class="multi-post-widget mb-0 mt-3 mt-lg-0">
        <div class="inner_columns one">
        
            <?php 
            if ($featured_trending_posts->have_posts()) : 
                while ($featured_trending_posts->have_posts()) : $featured_trending_posts->the_post();

                global $post;
                $blogdata_url = blogdata_get_freatured_image_url($post->ID, 'blogdata-slider-full');
                ?>
            <div class="bs-blog-post three bsm back-img bshre trending-post post-1 mb-0" <?php if (!empty($blogdata_url)): ?>
                    style="background-image: url('<?php echo esc_url($blogdata_url); ?>');"
                    <?php endif; ?>>
                <a class="link-div" href="<?php the_permalink(); ?>"> </a>
                <div class="inner">
                <?php if($slider_meta_enable == true) { ?><?php blogdata_post_categories(); ?> <?php } ?>
                <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                <?php if($slider_meta_enable == true) { blogdata_post_meta(); } ?>
                </div>
            </div>
        <?php endwhile;
                endif;
            wp_reset_postdata(); ?>
            <?php
            if ($featured_editor_posts->have_posts()) :
            while ($featured_editor_posts->have_posts()) : $featured_editor_posts->the_post();
            if($editor_off >= 2){
                break;
            }
            global $post;
            $blogdata_url = blogdata_get_freatured_image_url($post->ID, 'blogdata-slider-full');
            ?>
                <div class="bs-blog-post three bsm back-img bshre mb-0" <?php if (!empty($blogdata_url)): ?>
                    style="background-image: url('<?php echo esc_url($blogdata_url); ?>');"
                    <?php endif; ?>>
                <a class="link-div" href="<?php the_permalink(); ?>"> </a>
                <div class="inner">
                    <?php if($slider_meta_enable == true) { ?><?php blogdata_post_categories(); ?> <?php } ?>
                    <h4 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
                    <?php if($slider_meta_enable == true) { blogdata_post_meta(); } ?>
                </div>
                </div>

            <?php endwhile;
                endif;
                wp_reset_postdata(); 
        ?>
        </div>
    </div>
</div>