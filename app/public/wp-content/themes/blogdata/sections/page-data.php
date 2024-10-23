<?php if( class_exists('woocommerce') && (is_account_page() || is_cart() || is_checkout())) { ?>
<div class="col-lg-12">
    <div class="bs-card-box wd-back">
    <?php if (have_posts()) {  
        while (have_posts()) : the_post();
            the_content();
        endwhile; 
    } 

} else { 
    $blogdata_page_layout = get_theme_mod('blogdata_page_layout','page-align-content-right');

    if($blogdata_page_layout == "page-align-content-left") { ?> 
            <!--col-lg-4-->
                <aside class="col-lg-4 sidebar-left">
                    <?php get_sidebar();?>
                </aside>
            <!--/col-lg-4--> 
    <?php } ?>
        
    <div class="<?php echo esc_attr(($blogdata_page_layout == "page-full-width-content") ? 'col-lg-12' : 'col-lg-8 content-right'); ?>">
        <div class="bs-card-box wd-back">
            <?php if( have_posts()) {
                the_post();  
                the_post_thumbnail( '', array( 'class'=>'img-responsive img-fluid' ) );
                the_content();
            } 
            while ( have_posts() ) : the_post();
                // Include the page
                the_content();
                comments_template( '', true ); // show comments 
                wp_link_pages(array(
                    'before' => '<div class="link btn-theme">' . esc_html__('Pages:', 'blogdata'),
                    'after' => '</div>',
                ));
            endwhile; 
            blogdata_edit_link(); ?>	
        </div>
    </div> <?php  

    if($blogdata_page_layout == "page-align-content-right") { ?> 
        <!--col-lg-4-->
            <aside class="col-lg-4 sidebar-right">
                <?php get_sidebar(); ?>
            </aside>
        <!--/col-lg-4--> 
    <?php }
}