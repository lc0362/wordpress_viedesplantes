<?php
/**
 * The template for displaying search results pages.
 *
 * @package BlogData
 */
get_header(); ?>
<!--==================== main content section ====================-->
<main id="content" class="search-class content">
    <!--container-->
    <div class="container">
    <!--row-->
        <div class="row">
            <!--==================== breadcrumb section ====================-->
            <?php do_action('blogdata_breadcrumb_content'); ?>
            <div class="col-lg-<?php echo ( !is_active_sidebar( 'sidebar-1' ) ? '12' :'8' ); ?>">
                <h2>
                    <?php /* translators: %s: search term */
                        printf( esc_html__( 'Search Results for: %s','blogdata'), '<span>' . esc_html( get_search_query() ) . '</span>' ); ?>
                </h2>
                <div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <?php if ( have_posts() ) : /* Start the Loop */
                        while ( have_posts() ) : the_post(); ?>
                        <div class="bs-blog-post list-blog">
                            <?php blogdata_post_image_display_type($post); ?>
                            <article class="small">
                                <?php blogdata_post_categories(); ?>
                                <h4 class="entry-title title"><a href="<?php the_permalink();?>"><?php the_title();?></a></h4>
                                <?php blogdata_post_meta(); ?> 
                                <p><?php echo wp_trim_words( get_the_excerpt(), 20 ); ?></p>
                            </article> 
                        </div> 
                    <?php endwhile; else :?> 
                    <h2><?php esc_html_e( "Nothing Found", 'blogdata' ); ?></h2>
                    <div class="">
                        <p>
                            <?php esc_html_e( "Sorry, but nothing matched your search criteria. Please try again with some different keywords.", 'blogdata' ); ?>
                        </p>
                        <?php get_search_form(); ?>
                    </div><!-- .blog_con_mn -->
                <?php endif; ?>
                </div>  
            </div>
            <aside class="col-lg-4 sidebar-right">
                <?php get_sidebar();?>
            </aside>
        </div><!--/row-->
    </div><!--/container-->
</div>
<?php
get_footer();