<?php 
if (!function_exists('blogdata_main_content')) :
    function blogdata_main_content()
    { 
        $content_layout = (blogdata_get_option('blogdata_archive_page_layout'));
        $blog_post_layout = (get_theme_mod('blog_post_layout','grid-layout'));
      

        if($content_layout == "align-content-left") { ?>
            <!-- col-lg-4 -->
                <aside class="col-lg-4 sidebar-left">
                    <?php get_sidebar();?>
                </aside>
            <!-- / col-lg-4 -->
        <?php } elseif($content_layout == "sidebar-both") { ?>
            <!-- col-lg-3 -->
                <aside class="col-lg-3 sidebar-left">
                    <?php get_sidebar(); ?>
                </aside>
            <!-- / col-lg-3 -->
        <?php } ?>
        <div class="<?php
            echo esc_attr(($content_layout == "full-width-content")
                ? 'col-lg-12' :  'col-lg-8 content-right'); ?>"> <?php 
            if($blog_post_layout == 'grid-layout'){
                get_template_part('content','grid');
            } else { get_template_part('content',''); } ?>
        </div>

        <?php if($content_layout == "align-content-right") { ?>
            <!--col-lg-4-->
                <aside class="col-lg-4 sidebar-right">
                    <?php get_sidebar();?>
                </aside>
            <!--/col-lg-4-->
        <?php } elseif($content_layout == "sidebar-both") { ?>
            <!-- col-lg-3 -->
                <aside class="col-lg-3 sidebar-right">
                    <?php get_sidebar();?>
                </aside>
            <!-- / col-lg-3 -->
        <?php } 
    }
endif;
add_action('blogdata_action_main_content_layouts', 'blogdata_main_content', 40);

if (!function_exists('blogdata_single_main_content')) :
    function blogdata_single_main_content()
    { 
        $single_content_layout = (get_theme_mod('blogdata_single_page_layout','single-align-content-right'));

        if($single_content_layout == "single-align-content-left") { ?>
            <!-- col-lg-4 -->
                <aside class="col-lg-4 sidebar-left">
                    <?php get_sidebar(); ?>
                </aside>
            <!-- / col-lg-4 -->
        <?php } ?>
        
        <div class="<?php
            echo esc_attr(($single_content_layout == "single-full-width-content") ? 'col-lg-12' : 'col-lg-8 content-right'); ?>"> 
             <?php get_template_part('sections/single','data'); ?>
        </div>

        <?php if($single_content_layout == "single-align-content-right") { ?>
            <!--col-lg-4-->
                <aside class="col-lg-4 sidebar-right">
                    <?php get_sidebar();?>
                </aside>
            <!--/col-lg-4-->
        <?php } 
    }
endif;
add_action('blogdata_action_single_main_content_layouts', 'blogdata_single_main_content', 40);