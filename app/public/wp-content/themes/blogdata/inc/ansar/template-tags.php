<?php
/**
 * Custom template tags for this theme
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package BlogData
 */

if (!function_exists('blogdata_get_option')):
    /**
     * Get theme option.
     *
     * @since 1.0.0
     *
     * @param string $key Option key.
     * @return mixed Option value.
     */
    function blogdata_get_option($key) {
    
        if (empty($key)) {
            return;
        }
    
        $value = '';
    
        $default       = blogdata_get_default_theme_options();
        $default_value = null;
    
        if (is_array($default) && isset($default[$key])) {
            $default_value = $default[$key];
        }
    
        if (null !== $default_value) {
            $value = get_theme_mod($key, $default_value);
        } else {
            $value = get_theme_mod($key);
        }
    
        return $value;
    }
endif;

if (!function_exists('blogdata_post_categories')) :
    function blogdata_post_categories($separator = '&nbsp')
    {
        if ( 'post' === get_post_type() ) {
            $categories = wp_get_post_categories(get_the_ID());
            if(!empty($categories)){
                ?>
                <div class="bs-blog-category one">
                    <?php
                    foreach($categories as $c){
                        $style = '';
                        $cat = get_category( $c );
                        // $color = get_term_meta($cat->term_id, 'category_color', true);
                        $color = get_theme_mod('category_' .absint($cat->term_id). '_color' , '');
                        if($color){
                            $style = "--cat-color:".esc_attr($color);
                        }
                        ?>
                        <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>" style="<?php echo esc_attr($style);?>" id="<?php echo 'category_' .absint($cat->term_id). '_color'; ?>" >
                            <?php echo esc_html($cat->cat_name);?>
                        </a>
                    <?php } ?>
                </div>
                <?php
            }
        }
    }
endif;

// Post Read More Fuction
if (!function_exists('blogdata_post_read_more')) :
    function blogdata_post_read_more()
    {
        $blogdata_readmore_excerpt=get_theme_mod('blogdata_blog_content','excerpt'); 
        if($blogdata_readmore_excerpt=="excerpt") { ?>
            <a href="<?php the_permalink();?>" class="more-link">
                <?php echo esc_html('Read More'); ?>
            </a>
        <?php } 
        
    }
endif;

/*Save Date Formate*/
if ( ! function_exists( 'blogdata_date_content' ) ) :
    function blogdata_date_content($date_format = 'default-date') { ?>
        <?php if($date_format == 'default-date'){ ?>
            <span class="bs-blog-date">
                <a href="<?php echo esc_url(get_month_link(esc_html(get_post_time('Y')),esc_html(get_post_time('m')))); ?>"><time datetime=""><?php echo get_the_date('M'); ?> <?php echo get_the_date('j,'); ?> <?php echo get_the_date('Y'); ?></time></a>
            </span>
        <?php } else{ ?>
            <span class="bs-blog-date">
                <a href="<?php echo esc_url(get_month_link(esc_html(get_post_time('Y')),esc_html(get_post_time('m')))); ?>"><time datetime=""><?php echo esc_html(get_the_date()); ?></time></a>
            </span>
        <?php } ?>
    <?php }
endif;

/*Save Author Content*/
if ( ! function_exists( 'blogdata_author_content' ) ) :
    function blogdata_author_content() { ?>
        <span class="bs-author">
            <a class="auth" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"> <?php the_author(); ?> </a>
        </span>
    <?php }
endif;

/*Number Of Comments*/
if ( ! function_exists( 'blogdata_post_comments' ) ) :
    function blogdata_post_comments() { ?>
       <span class="comments-link"> 
            <a href="<?php the_permalink(); ?>">
                <?php echo get_comments_number(); ?> <?php esc_html_e( get_comments_number() <= 1 ? __('Comment', 'blogdata') : __('Comments', 'blogdata')); ?>
            </a> 
        </span>
    <?php }
endif;

//Previous / next page navigation
if ( ! function_exists( 'blogdata_post_pagination' ) ) :
    function blogdata_post_pagination() {
    $grid_layout = get_theme_mod('blog_post_layout','grid-layout') == 'grid-layout' ? ' mt-5 mb-4 mb-lg-0' : '';
    $pagingtype = get_theme_mod('blogdata_post_blog_pagination','number');
    if($pagingtype == 'number') { ?>
        <div class="blogdata-pagination d-flex-center<?php echo esc_attr($grid_layout)?>">
            <?php blogdata_target_element('control', 'blogdata_post_blog_pagination', 'Click To Edit Pagination.');
                $left = is_rtl() ? 'right': 'left';
                $right = is_rtl() ? 'left': 'right';
                the_posts_pagination( array(
                    'prev_text'          => '<i class="fas fa-angle-'.$left.'"></i>',
                    'next_text'          => '<i class="fas fa-angle-'.$right.'"></i>',
                ) ); 
            ?> 
        </div>
    <?php } elseif($pagingtype == 'next_prev') { ?>
        <div class="blogdata-pagination navigation d-flex-center<?php echo esc_attr($grid_layout)?>"> 
            <?php blogdata_target_element('control', 'blogdata_post_blog_pagination', 'Click To Edit Pagination.'); ?>
            <div class="navigation pagination next-prev">
                <?php posts_nav_link();?>
            </div>
        </div> 
    <?php }
    }
endif;


/*Save Category fields*/
if(!function_exists('blogdata_save_category_fields')):
    function blogdata_save_category_fields($term_id) {
        if ( isset( $_POST['category_color'] ) && ! empty( $_POST['category_color']) ) {
            update_term_meta( $term_id, 'category_color', sanitize_hex_color( $_POST['category_color'] ) );
        }else{
            delete_term_meta( $term_id, 'category_color' );
        }
    }
endif;
add_action( 'created_category', 'blogdata_save_category_fields' , 10, 3 );
add_action( 'edited_category', 'blogdata_save_category_fields' , 10, 3 );


if (!function_exists('blogdata_post_item_tag')) :
    function blogdata_post_item_tag() { 
        $tag_list = get_the_tag_list();
        $tags = get_the_tags();
        if($tag_list){ ?>
            <span class="blogdata-tags tag-links">
                <?php foreach ($tags as $tag) {
                    $tag_link = get_tag_link($tag->term_id);
                    echo '#<a href="' . esc_url($tag_link) . '">' . esc_html($tag->name) . '</a> ';
                } ?>
           </span>
        <?php }
    }
endif;

if (!function_exists('blogdata_post_thumbnail_image')) :
    function blogdata_post_thumbnail_image()
    {
        if(has_post_thumbnail()) { ?>
            <div  class="bs-blog-thumb lg back-img">
                <?php echo '<a  href="'.esc_url(get_the_permalink()).'">'; the_post_thumbnail( '', array( 'class'=>'img-fluid' ) ); echo '</a>'; ?>
            </div>
        <?php } 
    }
endif;

if (!function_exists('blogdata_post_meta')) :

    function blogdata_post_meta() {
        
        $blogdata_meta_orders = get_theme_mod(
            'blogdata_blog_post_meta',
            array(
                'author',
                'date',
            )
        ); ?>
        <div class="bs-blog-meta">
            <?php
            foreach($blogdata_meta_orders as $key=> $blogdata_meta_order) {

                if ($blogdata_meta_order == 'author') {
                    blogdata_author_content();
                }
        
                if ($blogdata_meta_order == 'date') {
                    blogdata_date_content();
                }
    
                if ($blogdata_meta_order == 'comments') {
                    blogdata_post_comments();
                }

            }
            blogdata_edit_link(); ?>
        </div>
        <?php
    }
endif; 

if (!function_exists('blogdata_post_title_content')) :
    function blogdata_post_title_content() {

        echo '<article class="small col">';
        $blogdata_blog_post_category = blogdata_get_option('blogdata_blog_post_category');
        if ($blogdata_blog_post_category == true) {
            blogdata_post_categories();
        } ?>
        <h4 class="entry-title title"><a href="<?php the_permalink(); ?>"><?php the_title();?></a></h4><?php
        $blogdata_enable_post_meta = blogdata_get_option('blogdata_enable_post_meta');
        if ($blogdata_enable_post_meta == true) {
            blogdata_post_meta();
        }
        blogdata_posted_content(); wp_link_pages( ); 
        echo '</article>'; 
    }
    
endif;


add_action('admin_head', 'blogdata_custom_width_css');

function blogdata_custom_width_css() {
  echo '<style>
    .column-remove{display:none;}
  </style>';
}

if (!function_exists('get_archive_title')) :
        
    function get_archive_title($title)
    {
        if (class_exists('WooCommerce')) {
            if (is_shop()) {
                $title = 'Shop';
            } elseif (is_product_category()) {
                $title = single_term_title('', false);
            } elseif (is_product_tag()) {
                $title = single_term_title('', false);
            }
        }

        if (is_category()) {
            $title = single_cat_title('', false);
        } elseif (is_tag()) {
            $title = single_tag_title('', false);
        } elseif (is_author()) {
            $title = get_the_author();
        } elseif (is_year()) {
            $title = get_the_date('Y');
        } elseif (is_month()) {
            $title = get_the_date('F Y');
        } elseif (is_day()) {
            $title = get_the_date('F j, Y');
        } elseif (is_post_type_archive()) {
            $title = post_type_archive_title('', false);
        } elseif (is_single()) {
            $title = '';
        } else {
            $title = get_the_title();
        }
        
        return $title;
    }

endif;
add_filter('get_the_archive_title', 'get_archive_title');

if (!function_exists('blogdata_archive_page_title')) :
        
    function blogdata_archive_page_title($title) { ?>
        <div class="bs-card-box page-entry-title">
            <?php if(!empty(get_the_archive_title())){ ?>
            <h1 class="entry-title title mb-0"><?php echo get_the_archive_title();?></h1>
            <?php } do_action('blogdata_breadcrumb_content'); ?>
        </div>
        <?php
    }
endif;
add_action('blogdata_action_archive_page_title', 'blogdata_archive_page_title');

if ( ! function_exists( 'blogdata_posted_content' ) ) :
    function blogdata_posted_content() { 
        $blogdata_blog_content  = get_theme_mod('blogdata_blog_content','excerpt');

        if ( 'excerpt' == $blogdata_blog_content ){
            $blogdata_excerpt = blogdata_the_excerpt( absint(20 ) );
            if ( !empty( $blogdata_excerpt ) ) :                   
                echo wp_kses_post( wpautop( $blogdata_excerpt ) );
            endif; 
        } else{ 
            the_content( __('Read More','blogdata') );
        }
    }
endif;

if ( ! function_exists( 'blogdata_the_excerpt' ) ) :

    /**
     * Generate excerpt.
     *
     */
    function blogdata_the_excerpt( $length = 0, $post_obj = null ) {

        global $post;

        if ( is_null( $post_obj ) ) {
            $post_obj = $post;
        }

        $length = absint( $length );

        if ( 0 === $length ) {
            return;
        }

        $source_content = $post_obj->post_content;

        if ( ! empty( get_the_excerpt($post_obj) ) ) {
            $source_content = get_the_excerpt($post_obj);
        } 
        // Check if non-breaking space exists in the text with variations
        if (preg_match('/\s*(&nbsp;|\xA0)\s*/u', $source_content)) {
            // Remove non-breaking space and its variations from the text
            $source_content = preg_replace('/\s*(&nbsp;|\xA0)\s*/u', ' ', $source_content);
        }

        $source_content = preg_replace( '`\[[^\]]*\]`', '', $source_content );
        $trimmed_content = wp_trim_words( $source_content, $length, '&hellip;' );
        return $trimmed_content;

    }
endif;

if ( ! function_exists( 'blogdata_breadcrumb_trail' ) ) :
    /**
     * Theme default breadcrumb function.
     *
     * @since 1.0.0
     */
    function blogdata_breadcrumb_trail() {
        if ( ! function_exists( 'breadcrumb_trail' ) ) {
            // load class file
            require_once get_template_directory() . '/inc/ansar/breadcrumb-trail/breadcrumb-trail.php';
        }

        $breadcrumb_args = array(
            'container' => 'div',
            'show_browse' => false,
        );
        breadcrumb_trail( $breadcrumb_args );
    }
    add_action( 'blogdata_breadcrumb_trail_content', 'blogdata_breadcrumb_trail' );
endif;


if( ! function_exists( 'blogdata_breadcrumb' ) ) :
    /**
     *
     * @package blogdata
     */
    function blogdata_breadcrumb() {
    if ( is_front_page() || is_home() ) return;
        $breadcrumb_settings = get_theme_mod('breadcrumb_settings','true');
        if($breadcrumb_settings == true) {
            $blogdata_site_breadcrumb_type = get_theme_mod('blogdata_site_breadcrumb_type','default'); ?>
            <div class="bs-breadcrumb-section">
                <div class="overlay">
                    <div class="row">
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <?php if($blogdata_site_breadcrumb_type == 'yoast') {
                                    if( function_exists( 'yoast_breadcrumb' ) ) {
                                        yoast_breadcrumb();
                                    }
                                }
                                elseif($blogdata_site_breadcrumb_type == 'navxt')
                                {
                                    if( function_exists( 'bcn_display' ) ) {
                                        bcn_display();
                                    }
                                }
                                elseif($blogdata_site_breadcrumb_type == 'rankmath')
                                {
                                    if( function_exists( 'rank_math_the_breadcrumbs' ) ) {
                                        rank_math_the_breadcrumbs();
                                    }
                                }
                                else {
                                    do_action( 'blogdata_breadcrumb_trail_content' );
                                }
                                ?> 
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        <?php } 
    }
endif;
add_action( 'blogdata_breadcrumb_content', 'blogdata_breadcrumb' );

if( ! function_exists( 'blogdata_add_menu_description' ) ) :
    function blogdata_add_menu_description( $item_output, $item, $depth, $args ) {
        if($args->theme_location != 'primary') return $item_output;
        
        if ( !empty( $item->description ) ) {
            $item_output = str_replace( $args->link_after . '</a>', '<span class="menu-link-description">' . $item->description . '</span>' . $args->link_after . '</a>', $item_output );
        }
        return $item_output;
    }
    add_filter( 'walker_nav_menu_start_el', 'blogdata_add_menu_description', 10, 4 );
endif;