<?php
/**
 * Functions which enhance the theme by hooking into WordPress
 *
 * @package BlogData
 */

/**
 * Adds custom classes to the array of body classes.
 *
 * @param array $classes Classes for the body element.
 *
 * @return array
 */
function blogdata_body_classes($classes)
{
    // Adds a class of hfeed to non-singular pages.
    if (!is_singular()) {
        $classes[] = 'hfeed';
    }

    $global_site_mode_setting = blogdata_get_option('global_site_mode_setting');
    $classes[] = $global_site_mode_setting;

    $single_post_featured_image_view = blogdata_get_option('single_post_featured_image_view');
    if ($single_post_featured_image_view == 'full') {
        $classes[] = 'ta-single-full-header';
    }

    $global_hide_post_date_author_in_list = blogdata_get_option('global_hide_post_date_author_in_list');
    if ($global_hide_post_date_author_in_list == true) {
        $classes[] = 'ta-hide-date-author-in-list';
    }

    global $post;

    $global_alignment = blogdata_get_option('blogdata_archive_page_layout');
    $page_layout = $global_alignment;
    $disable_class = '';
    $frontpage_content_status = blogdata_get_option('frontpage_content_status');
    if (1 != $frontpage_content_status) {
        $disable_class = 'disable-default-home-content';
    }

    // Check if single.
    if ($post && is_singular()) {
        $post_options = get_post_meta($post->ID, 'blogdata-meta-content-alignment', true);
        if (!empty($post_options)) {
            $page_layout = $post_options;
        } else {
            $page_layout = $global_alignment;
        }
    }
    
    if ( isset( $_COOKIE["blogdata-site-mode-cookie"] ) ) {
        $classes[] = $_COOKIE["blogdata-site-mode-cookie"];
    } else {
    	$classes[] = get_theme_mod( 'blogdata_skin_mode', 'defaultcolor' );
    }

    return $classes;
}

add_filter('body_class', 'blogdata_body_classes');

if (!function_exists('blogdata_target_element')) {
    function blogdata_target_element($type, $type_id, $info_text = '') {
        if($info_text == ''){
            $info_text = 'Click to edit this '.$type;
        }
        if ( is_customize_preview() && stripos($_SERVER['REQUEST_URI'], 'customize_messenger_channel') !== false && stripos($_SERVER['REQUEST_URI'], 'customize_changeset_uuid') !== false ) { ?>
            <div class="blogdata-customizer-edit-icon" type="<?php echo esc_attr($type); ?>" type_id="<?php echo esc_attr($type_id); ?>">
                <button class="blogdata-customizer-target-btn" title="<?php echo esc_attr($info_text); ?>">
                    <span class="dashicons dashicons-edit"></span>
                </button>
            </div> 
        <?php 
        }
    }
}


/**
 * Add a pingback url auto-discovery header for singularly identifiable articles.
 */
function blogdata_pingback_header()
{
    if (is_singular() && pings_open()) {
        echo '<link rel="pingback" href="', esc_url(get_bloginfo('pingback_url')), '">';
    }
}

add_action('wp_head', 'blogdata_pingback_header');


/**
 * Returns posts.
 *
 * @since blogdata 1.0.0
 */
if (!function_exists('blogdata_get_posts')):
    function blogdata_get_posts($number_of_posts, $category = '0', $search = '', $post_type = 'post') 
    {   
        

        $ins_args = array(
            'post_type' => $post_type,
            'posts_per_page' => ($number_of_posts),
            'post_status' => 'publish',
            'orderby' => 'date',
            'order' => 'DESC',
            'ignore_sticky_posts' => true
        );
        if($search !== ''){
            $ins_args['s']  = $search;
        }
    
        $category = isset($category) ? $category : '0';
        if (absint($category) > 0) {
            $ins_args['cat'] = absint($category);
        }

        $all_posts = new WP_Query($ins_args);

        return $all_posts;
    }

endif;

if (!function_exists('blogdata_all_posts_title')):
    function blogdata_all_posts_title()
    {
        $args = array(
            'post_type'   => 'post',
            'post_status' => 'publish',
            'posts_per_page' => -1,  // Retrieve all posts
        );
            
        $query = new WP_Query($args);
        $all_select_post = [];
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $all_select_post[get_the_title()] = get_the_title();
            }
            wp_reset_postdata();
        } else {
            echo "No posts found.";
        }
        
        return $all_select_post;
    }
endif;

/**
 * Returns no image url.
 *
 * @since  blogdata 1.0.0
 */
if (!function_exists('blogdata_post_format')):
    function blogdata_post_format($post_id)
    {
        $post_format = get_post_format($post_id);
        switch ($post_format) {
            case "image":
                $post_format = "<div class='af-post-format em-post-format'></div>";
                break;
            case "video":
                $post_format = "<div class='af-post-format em-post-format'></div>";

                break;
            case "gallery":
                $post_format = "<div class='af-post-format em-post-format'></div>";
                break;
            default:
                $post_format = "";
        }

        echo $post_format;
    }

endif;


if (!function_exists('blogdata_get_block')) :
    /**
     *
     * @param null
     *
     * @return null
     *
     * @since blogdata 1.0.0
     *
     */
    function blogdata_get_block($block = 'grid', $section = 'post')
    {

        get_template_part('inc/ansar/hooks/blocks/block-' . $section, $block);

    }
endif;

/* Display Breadcrumbs */
if (!function_exists('blogdata_excerpt_length')) :

    /**
     * Simple excerpt length.
     *
     * @since 1.0.0
     */

    function blogdata_excerpt_length($length) {

        if (is_admin()) {
            return $length;
        }

        return 15;
    }

endif;
add_filter('excerpt_length', 'blogdata_excerpt_length', 999);


/* Display Breadcrumbs */
if (!function_exists('blogdata_excerpt_more')) :

    /**
     * Simple excerpt more.
     *
     * @since 1.0.0
     */
    function blogdata_excerpt_more($more)
    {
        return '...';
    }

endif;

add_filter('excerpt_more', 'blogdata_excerpt_more');

/**
 * @param $post_id
 * @param string $size
 *
 * @return mixed|string
 */
function blogdata_get_freatured_image_url($post_id, $size = 'blogdata-featured') {
    if (has_post_thumbnail($post_id)) {
        $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($post_id), $size);
        $url = $thumb !== false ? '' . $thumb[0] . '' : '""';
    } else {
        $url = '';
    }

    return $url;
}

if (!function_exists('blogdata_audio_format')):
    function blogdata_audio_format() { ?>
        <div class="audio">
            <?php global $post;
            $audio = array('post_parent' => $post->ID, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'audio' );
            foreach ( $audio as $attachment_id => $attachment ) : ?>
                <div class="player">
                    <audio controls>
                        <source src="<?php echo wp_get_attachment_url( $attachment_id, 'full' ); ?>" type="audio/mpeg">
                    </audio>
                </div>
            <?php endforeach; ?>
        </div>
    <?php } 
endif;


if (!function_exists('blogdata_edit_link')) :

    function blogdata_edit_link($view = 'default')
    {
        global $post;
        edit_post_link(
            sprintf(
                wp_kses(
                /* translators: %s: Name of current post. Only visible to screen readers */
                    __('Edit <span class="screen-reader-text">%s</span>', 'blogdata'),
                    array(
                        'span' => array(
                            'class' => array(),
                        ),
                    )
                ),
                get_the_title()
            ),
            '<span class="edit-link">',
            '</span>'
        );
    } 
endif;

add_filter( 'woocommerce_show_page_title', 'blogdata_hide_shop_page_title' );

function blogdata_hide_shop_page_title( $title ) {
    if ( is_shop() ) $title = false;
    return $title;
}

function blogdata_social_share_post($post) {

    $blogdata_blog_post_icon_enable = esc_attr(get_theme_mod('blogdata_blog_post_icon_enable','true'));
    if($blogdata_blog_post_icon_enable == true) {
    $post_link  = esc_url( get_the_permalink() );
    $post_title = get_the_title();

    $facebook_url = add_query_arg(
        array(
            'url' => $post_link,
        ),
        'https://www.facebook.com/sharer.php'
    );

    $twitter_url = add_query_arg(
        array(
            'url'  => $post_link,
            'text' => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) ),
        ),
        'http://twitter.com/share'
    );

    $email_title = str_replace( '&', '%26', $post_title );

    $email_url = add_query_arg(
        array(
            'subject' => wp_strip_all_tags( $email_title ),
            'body'    => $post_link,
        ),
        'mailto:'
    ); 

    $linkedin_url = add_query_arg(
        array(
            'url'  => $post_link,
            'title' => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) )
        ),
        'https://www.linkedin.com/sharing/share-offsite/?url'
    );

    $pinterest_url = add_query_arg(
        array( 
            'url'  => $post_link,
            'title' => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) )
        ),
        'http://pinterest.com/pin/create/link/?url='
    );

    $reddit_url = add_query_arg(
        array(
            'url' => $post_link,
            'title' => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) )
        ),
        'https://www.reddit.com/submit'
    );

    $telegram_url = add_query_arg(
        array(
            'url' => $post_link,
            'title' => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) )
        ),
        'https://t.me/share/url?url='
    );

    $whatsapp_url = add_query_arg(
        array(
            'text' => $post_link,
            'title' => rawurlencode( html_entity_decode( wp_strip_all_tags( $post_title ), ENT_COMPAT, 'UTF-8' ) )
        ),
        'https://api.whatsapp.com/send?text='
    ); ?>
    <script>
        function pinIt()
        {
            var e = document.createElement('script');
            e.setAttribute('type','text/javascript');
            e.setAttribute('charset','UTF-8');
            e.setAttribute('src','https://assets.pinterest.com/js/pinmarklet.js?r='+Math.random()*99999999);
            document.body.appendChild(e);
        }
    </script>
    <div class="post-share">
        <div class="post-share-icons cf">                
            <a class="facebook" href="<?php echo esc_url("$facebook_url"); ?>" class="link " target="_blank" >
                <i class="fab fa-facebook"></i>
            </a>
            <a class="twitter" href="<?php echo esc_url("$twitter_url"); ?>" class="link " target="_blank">
                <i class="fa-brands fa-x-twitter"></i>
            </a>
            <a class="envelope" href="<?php echo esc_url("$email_url"); ?>" class="link " target="_blank" >
                <i class="fas fa-envelope-open"></i>
            </a>
            <a class="linkedin" href="<?php echo esc_url("$linkedin_url"); ?>" class="link " target="_blank" >
                <i class="fab fa-linkedin"></i>
            </a>
            <a href="javascript:pinIt();" class="pinterest">
                <i class="fab fa-pinterest"></i>
            </a>
            <a class="telegram" href="<?php echo esc_url("$telegram_url"); ?>" target="_blank" >
                <i class="fab fa-telegram"></i>
            </a>
            <a class="whatsapp" href="<?php echo esc_url("$whatsapp_url"); ?>" target="_blank" >
                <i class="fab fa-whatsapp"></i>
            </a>
            <a class="reddit" href="<?php echo esc_url("$reddit_url"); ?>" target="_blank" >
                <i class="fab fa-reddit"></i>
            </a>
            <a class="print-r" href="javascript:window.print()"> <i class="fas fa-print"></i></a>
        </div>
    </div>
<?php } }

function blogdata_post_image_display_type($post) {
    $post_image_type = get_theme_mod('post_image_type','blogdata_post_img_hei');
    $url = blogdata_get_freatured_image_url($post->ID, 'blogdata-medium');
    if ( $post_image_type == 'blogdata_post_img_hei' ) {
        if($url) { ?>
            <div class="bs-blog-thumb lg back-img" style="background-image: url('<?php echo esc_url($url); ?>');">
                <a href="<?php the_permalink(); ?>" class="link-div"></a>
            </div> 
        <?php } 
    }
    elseif ( $post_image_type == 'blogdata_post_img_acc' )  {
        if(has_post_thumbnail()) { ?>
            <div class="bs-post-thumb img">
                <?php echo '<a href="'.esc_url(get_the_permalink()).'">';
                    the_post_thumbnail( '', array( 'class'=>'img-responsive' ) );
                    echo '</a>'; ?> 
            </div> 
        <?php } 
    } 
}

/* Typography Fonts */
if (!function_exists('blogdata_typo_fonts')) {

    function blogdata_typo_fonts() {
        return array('ABeeZee' => 'ABeeZee', 'Abel' => 'Abel', 'Abril Fatface' => 'Abril Fatface', 'Aclonica' => 'Aclonica', 'Acme' => 'Acme', 'Actor' => 'Actor', 'Adamina' => 'Adamina', 'Advent Pro' => 'Advent Pro', 'Aguafina Script' => 'Aguafina Script', 'Akronim' => 'Akronim', 'Aladin' => 'Aladin', 'Aldrich' => 'Aldrich', 'Alef' => 'Alef', 'Alegreya' => 'Alegreya', 'Alegreya SC' => 'Alegreya SC', 'Alegreya Sans' => 'Alegreya Sans', 'Alegreya Sans SC' => 'Alegreya Sans SC', 'Alex Brush' => 'Alex Brush', 'Alfa Slab One' => 'Alfa Slab One', 'Alice' => 'Alice', 'Alike' => 'Alike', 'Alike Angular' => 'Alike Angular', 'Allan' => 'Allan', 'Allerta' => 'Allerta', 'Allerta Stencil' => 'Allerta Stencil', 'Allura' => 'Allura', 'Almendra' => 'Almendra', 'Almendra Display' => 'Almendra Display', 'Almendra SC' => 'Almendra SC', 'Amarante' => 'Amarante', 'Amaranth' => 'Amaranth', 'Amatic SC' => 'Amatic SC', 'Amatica SC' => 'Amatica SC', 'Amethysta' => 'Amethysta', 'Amiko' => 'Amiko', 'Amiri' => 'Amiri', 'Amita' => 'Amita', 'Anaheim' => 'Anaheim', 'Andada' => 'Andada', 'Andika' => 'Andika', 'Angkor' => 'Angkor', 'Annie Use Your Telescope' => 'Annie Use Your Telescope', 'Anonymous Pro' => 'Anonymous Pro', 'Antic' => 'Antic', 'Antic Didone' => 'Antic Didone', 'Antic Slab' => 'Antic Slab', 'Anton' => 'Anton', 'Arapey' => 'Arapey', 'Arbutus' => 'Arbutus', 'Arbutus Slab' => 'Arbutus Slab', 'Architects Daughter' => 'Architects Daughter', 'Archivo Black' => 'Archivo Black', 'Archivo Narrow' => 'Archivo Narrow', 'Aref Ruqaa' => 'Aref Ruqaa', 'Arima Madurai' => 'Arima Madurai', 'Arimo' => 'Arimo', 'Arizonia' => 'Arizonia', 'Armata' => 'Armata', 'Artifika' => 'Artifika', 'Arvo' => 'Arvo', 'Arya' => 'Arya', 'Asap' => 'Asap', 'Asar' => 'Asar', 'Asset' => 'Asset', 'Assistant' => 'Assistant', 'Astloch' => 'Astloch', 'Asul' => 'Asul', 'Athiti' => 'Athiti', 'Atma' => 'Atma', 'Atomic Age' => 'Atomic Age', 'Aubrey' => 'Aubrey', 'Audiowide' => 'Audiowide', 'Autour One' => 'Autour One', 'Average' => 'Average', 'Average Sans' => 'Average Sans', 'Averia Gruesa Libre' => 'Averia Gruesa Libre', 'Averia Libre' => 'Averia Libre', 'Averia Sans Libre' => 'Averia Sans Libre', 'Averia Serif Libre' => 'Averia Serif Libre', 'Bad Script' => 'Bad Script', 'Baloo' => 'Baloo', 'Baloo Bhai' => 'Baloo Bhai', 'Baloo Da' => 'Baloo Da', 'Baloo Thambi' => 'Baloo Thambi', 'Balthazar' => 'Balthazar', 'Bangers' => 'Bangers', 'Basic' => 'Basic', 'Battambang' => 'Battambang', 'Baumans' => 'Baumans', 'Bayon' => 'Bayon', 'Belgrano' => 'Belgrano', 'Belleza' => 'Belleza', 'BenchNine' => 'BenchNine', 'Bentham' => 'Bentham', 'Berkshire Swash' => 'Berkshire Swash', 'Bevan' => 'Bevan', 'Bigelow Rules' => 'Bigelow Rules', 'Bigshot One' => 'Bigshot One', 'Bilbo' => 'Bilbo', 'Bilbo Swash Caps' => 'Bilbo Swash Caps', 'BioRhyme' => 'BioRhyme', 'BioRhyme Expanded' => 'BioRhyme Expanded', 'Biryani' => 'Biryani', 'Bitter' => 'Bitter', 'Black Ops One' => 'Black Ops One', 'Bokor' => 'Bokor', 'Bonbon' => 'Bonbon', 'Boogaloo' => 'Boogaloo', 'Bowlby One' => 'Bowlby One', 'Bowlby One SC' => 'Bowlby One SC', 'Brawler' => 'Brawler', 'Bree Serif' => 'Bree Serif', 'Bubblegum Sans' => 'Bubblegum Sans', 'Bubbler One' => 'Bubbler One', 'Buda' => 'Buda', 'Buenard' => 'Buenard', 'Bungee' => 'Bungee', 'Bungee Hairline' => 'Bungee Hairline', 'Bungee Inline' => 'Bungee Inline', 'Bungee Outline' => 'Bungee Outline', 'Bungee Shade' => 'Bungee Shade', 'Butcherman' => 'Butcherman', 'Butterfly Kids' => 'Butterfly Kids', 'Cabin' => 'Cabin', 'Cabin Condensed' => 'Cabin Condensed', 'Cabin Sketch' => 'Cabin Sketch', 'Caesar Dressing' => 'Caesar Dressing', 'Cagliostro' => 'Cagliostro', 'Cairo' => 'Cairo', 'Calligraffitti' => 'Calligraffitti', 'Cambay' => 'Cambay', 'Cambo' => 'Cambo', 'Candal' => 'Candal', 'Cantarell' => 'Cantarell', 'Cantata One' => 'Cantata One', 'Cantora One' => 'Cantora One', 'Capriola' => 'Capriola', 'Cardo' => 'Cardo', 'Carme' => 'Carme', 'Carrois Gothic' => 'Carrois Gothic', 'Carrois Gothic SC' => 'Carrois Gothic SC', 'Carter One' => 'Carter One', 'Catamaran' => 'Catamaran', 'Caudex' => 'Caudex', 'Caveat' => 'Caveat', 'Caveat Brush' => 'Caveat Brush', 'Cedarville Cursive' => 'Cedarville Cursive', 'Ceviche One' => 'Ceviche One', 'Changa' => 'Changa', 'Changa One' => 'Changa One', 'Chango' => 'Chango', 'Chathura' => 'Chathura', 'Chau Philomene One' => 'Chau Philomene One', 'Chela One' => 'Chela One', 'Chelsea Market' => 'Chelsea Market', 'Chenla' => 'Chenla', 'Cherry Cream Soda' => 'Cherry Cream Soda', 'Cherry Swash' => 'Cherry Swash', 'Chewy' => 'Chewy', 'Chicle' => 'Chicle', 'Chivo' => 'Chivo', 'Chonburi' => 'Chonburi', 'Cinzel' => 'Cinzel', 'Cinzel Decorative' => 'Cinzel Decorative', 'Clicker Script' => 'Clicker Script', 'Coda' => 'Coda', 'Coda Caption' => 'Coda Caption', 'Codystar' => 'Codystar', 'Coiny' => 'Coiny', 'Combo' => 'Combo', 'Comfortaa' => 'Comfortaa', 'Coming Soon' => 'Coming Soon', 'Concert One' => 'Concert One', 'Condiment' => 'Condiment', 'Content' => 'Content', 'Contrail One' => 'Contrail One', 'Convergence' => 'Convergence', 'Cookie' => 'Cookie', 'Copse' => 'Copse', 'Corben' => 'Corben', 'Cormorant' => 'Cormorant', 'Cormorant Garamond' => 'Cormorant Garamond', 'Cormorant Infant' => 'Cormorant Infant', 'Cormorant SC' => 'Cormorant SC', 'Cormorant Unicase' => 'Cormorant Unicase', 'Cormorant Upright' => 'Cormorant Upright', 'Courgette' => 'Courgette', 'Cousine' => 'Cousine', 'Coustard' => 'Coustard', 'Covered By Your Grace' => 'Covered By Your Grace', 'Crafty Girls' => 'Crafty Girls', 'Creepster' => 'Creepster', 'Crete Round' => 'Crete Round', 'Crimson Text' => 'Crimson Text', 'Croissant One' => 'Croissant One', 'Crushed' => 'Crushed', 'Cuprum' => 'Cuprum', 'Cutive' => 'Cutive', 'Cutive Mono' => 'Cutive Mono', 'Damion' => 'Damion', 'Dancing Script' => 'Dancing Script', 'Dangrek' => 'Dangrek', 'David Libre' => 'David Libre', 'Dawning of a New Day' => 'Dawning of a New Day', 'Days One' => 'Days One', 'Dekko' => 'Dekko', 'Delius' => 'Delius', 'Delius Swash Caps' => 'Delius Swash Caps', 'Delius Unicase' => 'Delius Unicase', 'Della Respira' => 'Della Respira', 'Denk One' => 'Denk One', 'Devonshire' => 'Devonshire', 'Dhurjati' => 'Dhurjati', 'Didact Gothic' => 'Didact Gothic', 'Diplomata' => 'Diplomata', 'Diplomata SC' => 'Diplomata SC', 'Domine' => 'Domine', 'Donegal One' => 'Donegal One', 'Doppio One' => 'Doppio One', 'Dorsa' => 'Dorsa', 'Dosis' => 'Dosis', 'Dr Sugiyama' => 'Dr Sugiyama', 'Droid Sans' => 'Droid Sans', 'Droid Sans Mono' => 'Droid Sans Mono', 'Droid Serif' => 'Droid Serif', 'Duru Sans' => 'Duru Sans', 'Dynalight' => 'Dynalight', 'EB Garamond' => 'EB Garamond', 'Eagle Lake' => 'Eagle Lake', 'Eater' => 'Eater', 'Economica' => 'Economica', 'Eczar' => 'Eczar', 'Ek Mukta' => 'Ek Mukta', 'El Messiri' => 'El Messiri', 'Electrolize' => 'Electrolize', 'Elsie' => 'Elsie', 'Elsie Swash Caps' => 'Elsie Swash Caps', 'Emblema One' => 'Emblema One', 'Emilys Candy' => 'Emilys Candy', 'Engagement' => 'Engagement', 'Englebert' => 'Englebert', 'Enriqueta' => 'Enriqueta', 'Erica One' => 'Erica One', 'Esteban' => 'Esteban', 'Euphoria Script' => 'Euphoria Script', 'Ewert' => 'Ewert', 'Exo' => 'Exo', 'Exo 2' => 'Exo 2', 'Expletus Sans' => 'Expletus Sans', 'Fanwood Text' => 'Fanwood Text', 'Farsan' => 'Farsan', 'Fascinate' => 'Fascinate', 'Fascinate Inline' => 'Fascinate Inline', 'Faster One' => 'Faster One', 'Fasthand' => 'Fasthand', 'Fauna One' => 'Fauna One', 'Federant' => 'Federant', 'Federo' => 'Federo', 'Felipa' => 'Felipa', 'Fenix' => 'Fenix', 'Finger Paint' => 'Finger Paint', 'Fira Mono' => 'Fira Mono', 'Fira Sans' => 'Fira Sans', 'Fjalla One' => 'Fjalla One', 'Fjord One' => 'Fjord One', 'Flamenco' => 'Flamenco', 'Flavors' => 'Flavors', 'Fondamento' => 'Fondamento', 'Fontdiner Swanky' => 'Fontdiner Swanky', 'Forum' => 'Forum', 'Francois One' => 'Francois One', 'Frank Ruhl Libre' => 'Frank Ruhl Libre', 'Freckle Face' => 'Freckle Face', 'Fredericka the Great' => 'Fredericka the Great', 'Fredoka One' => 'Fredoka One', 'Freehand' => 'Freehand', 'Fresca' => 'Fresca', 'Frijole' => 'Frijole', 'Fruktur' => 'Fruktur', 'Fugaz One' => 'Fugaz One', 'GFS Didot' => 'GFS Didot', 'GFS Neohellenic' => 'GFS Neohellenic', 'Gabriela' => 'Gabriela', 'Gafata' => 'Gafata', 'Galada' => 'Galada', 'Galdeano' => 'Galdeano', 'Galindo' => 'Galindo', 'Gentium Basic' => 'Gentium Basic', 'Gentium Book Basic' => 'Gentium Book Basic', 'Geo' => 'Geo', 'Geostar' => 'Geostar', 'Geostar Fill' => 'Geostar Fill', 'Germania One' => 'Germania One', 'Gidugu' => 'Gidugu', 'Gilda Display' => 'Gilda Display', 'Give You Glory' => 'Give You Glory', 'Glass Antiqua' => 'Glass Antiqua', 'Glegoo' => 'Glegoo', 'Gloria Hallelujah' => 'Gloria Hallelujah', 'Goblin One' => 'Goblin One', 'Gochi Hand' => 'Gochi Hand', 'Gorditas' => 'Gorditas', 'Goudy Bookletter 1911' => 'Goudy Bookletter 1911', 'Graduate' => 'Graduate', 'Grand Hotel' => 'Grand Hotel', 'Gravitas One' => 'Gravitas One', 'Great Vibes' => 'Great Vibes', 'Griffy' => 'Griffy', 'Gruppo' => 'Gruppo', 'Gudea' => 'Gudea', 'Gurajada' => 'Gurajada', 'Habibi' => 'Habibi', 'Halant' => 'Halant', 'Hammersmith One' => 'Hammersmith One', 'Hanalei' => 'Hanalei', 'Hanalei Fill' => 'Hanalei Fill', 'Handlee' => 'Handlee', 'Hanuman' => 'Hanuman', 'Happy Monkey' => 'Happy Monkey', 'Harmattan' => 'Harmattan', 'Headland One' => 'Headland One', 'Heebo' => 'Heebo', 'Henny Penny' => 'Henny Penny', 'Herr Von Muellerhoff' => 'Herr Von Muellerhoff', 'Hind' => 'Hind', 'Hind Guntur' => 'Hind Guntur', 'Hind Madurai' => 'Hind Madurai', 'Hind Siliguri' => 'Hind Siliguri', 'Hind Vadodara' => 'Hind Vadodara', 'Holtwood One SC' => 'Holtwood One SC', 'Homemade Apple' => 'Homemade Apple', 'Homenaje' => 'Homenaje', 'IM Fell DW Pica' => 'IM Fell DW Pica', 'IM Fell DW Pica SC' => 'IM Fell DW Pica SC', 'IM Fell Double Pica' => 'IM Fell Double Pica', 'IM Fell Double Pica SC' => 'IM Fell Double Pica SC', 'IM Fell English' => 'IM Fell English', 'IM Fell English SC' => 'IM Fell English SC', 'IM Fell French Canon' => 'IM Fell French Canon', 'IM Fell French Canon SC' => 'IM Fell French Canon SC', 'IM Fell Great Primer' => 'IM Fell Great Primer', 'IM Fell Great Primer SC' => 'IM Fell Great Primer SC', 'Iceberg' => 'Iceberg', 'Iceland' => 'Iceland', 'Imprima' => 'Imprima', 'Inconsolata' => 'Inconsolata', 'Inder' => 'Inder', 'Indie Flower' => 'Indie Flower', 'Inika' => 'Inika', 'Inknut Antiqua' => 'Inknut Antiqua', 'Inter' => 'Inter', 'Irish Grover' => 'Irish Grover', 'Istok Web' => 'Istok Web', 'Italiana' => 'Italiana', 'Italianno' => 'Italianno', 'Itim' => 'Itim', 'Jacques Francois' => 'Jacques Francois', 'Jacques Francois Shadow' => 'Jacques Francois Shadow', 'Jaldi' => 'Jaldi', 'Jim Nightshade' => 'Jim Nightshade', 'Jockey One' => 'Jockey One', 'Jolly Lodger' => 'Jolly Lodger', 'Jomhuria' => 'Jomhuria', 'Josefin Sans' => 'Josefin Sans', 'Josefin Slab' => 'Josefin Slab', 'Joti One' => 'Joti One', 'Judson' => 'Judson', 'Julee' => 'Julee', 'Julius Sans One' => 'Julius Sans One', 'Junge' => 'Junge', 'Jura' => 'Jura', 'Just Another Hand' => 'Just Another Hand', 'Just Me Again Down Here' => 'Just Me Again Down Here', 'Kadwa' => 'Kadwa', 'Kalam' => 'Kalam', 'Kameron' => 'Kameron', 'Kanit' => 'Kanit', 'Kantumruy' => 'Kantumruy', 'Karla' => 'Karla', 'Karma' => 'Karma', 'Katibeh' => 'Katibeh', 'Kaushan Script' => 'Kaushan Script', 'Kavivanar' => 'Kavivanar', 'Kavoon' => 'Kavoon', 'Kdam Thmor' => 'Kdam Thmor', 'Keania One' => 'Keania One', 'Kelly Slab' => 'Kelly Slab', 'Kenia' => 'Kenia', 'Khand' => 'Khand', 'Khmer' => 'Khmer', 'Khula' => 'Khula', 'Kite One' => 'Kite One', 'Knewave' => 'Knewave', 'Kotta One' => 'Kotta One', 'Koulen' => 'Koulen', 'Kranky' => 'Kranky', 'Kreon' => 'Kreon', 'Kristi' => 'Kristi', 'Krona One' => 'Krona One', 'Kumar One' => 'Kumar One', 'Kumar One Outline' => 'Kumar One Outline', 'Kurale' => 'Kurale', 'La Belle Aurore' => 'La Belle Aurore', 'Laila' => 'Laila', 'Lakki Reddy' => 'Lakki Reddy', 'Lalezar' => 'Lalezar', 'Lancelot' => 'Lancelot', 'Lateef' => 'Lateef', 'Lato' => 'Lato', 'League Script' => 'League Script', 'Leckerli One' => 'Leckerli One', 'Ledger' => 'Ledger', 'Lekton' => 'Lekton', 'Lemon' => 'Lemon', 'Lemonada' => 'Lemonada', 'Libre Baskerville' => 'Libre Baskerville', 'Libre Franklin' => 'Libre Franklin', 'Life Savers' => 'Life Savers', 'Lilita One' => 'Lilita One', 'Lily Script One' => 'Lily Script One', 'Limelight' => 'Limelight', 'Linden Hill' => 'Linden Hill', 'Lobster' => 'Lobster', 'Lobster Two' => 'Lobster Two', 'Londrina Outline' => 'Londrina Outline', 'Londrina Shadow' => 'Londrina Shadow', 'Londrina Sketch' => 'Londrina Sketch', 'Londrina Solid' => 'Londrina Solid', 'Lora' => 'Lora', 'Love Ya Like A Sister' => 'Love Ya Like A Sister', 'Loved by the King' => 'Loved by the King', 'Lovers Quarrel' => 'Lovers Quarrel', 'Luckiest Guy' => 'Luckiest Guy', 'Lusitana' => 'Lusitana', 'Lustria' => 'Lustria', 'Macondo' => 'Macondo', 'Macondo Swash Caps' => 'Macondo Swash Caps', 'Mada' => 'Mada', 'Magra' => 'Magra', 'Maiden Orange' => 'Maiden Orange', 'Maitree' => 'Maitree', 'Mako' => 'Mako', 'Mallanna' => 'Mallanna', 'Mandali' => 'Mandali', 'Marcellus' => 'Marcellus', 'Marcellus SC' => 'Marcellus SC', 'Marck Script' => 'Marck Script', 'Margarine' => 'Margarine', 'Marko One' => 'Marko One', 'Marmelad' => 'Marmelad', 'Martel' => 'Martel', 'Martel Sans' => 'Martel Sans', 'Marvel' => 'Marvel', 'Mate' => 'Mate', 'Mate SC' => 'Mate SC', 'Maven Pro' => 'Maven Pro', 'McLaren' => 'McLaren', 'Meddon' => 'Meddon', 'MedievalSharp' => 'MedievalSharp', 'Medula One' => 'Medula One', 'Meera Inimai' => 'Meera Inimai', 'Megrim' => 'Megrim', 'Meie Script' => 'Meie Script', 'Merienda' => 'Merienda', 'Merienda One' => 'Merienda One', 'Merriweather' => 'Merriweather', 'Merriweather Sans' => 'Merriweather Sans', 'Metal' => 'Metal', 'Metal Mania' => 'Metal Mania', 'Metamorphous', 'Metrophobic' => 'Metrophobic', 'Michroma' => 'Michroma', 'Milonga' => 'Milonga', 'Miltonian' => 'Miltonian', 'Miltonian Tattoo' => 'Miltonian Tattoo', 'Miniver' => 'Miniver', 'Miriam Libre' => 'Miriam Libre', 'Mirza' => 'Mirza', 'Miss Fajardose' => 'Miss Fajardose', 'Mitr' => 'Mitr', 'Modak' => 'Modak', 'Modern Antiqua' => 'Modern Antiqua', 'Mogra' => 'Mogra', 'Molengo' => 'Molengo', 'Molle' => 'Molle', 'Monda' => 'Monda', 'Monofett' => 'Monofett', 'Monoton' => 'Monoton', 'Monsieur La Doulaise' => 'Monsieur La Doulaise', 'Montaga' => 'Montaga', 'Montez' => 'Montez', 'Montserrat' => 'Montserrat', 'Montserrat Alternates' => 'Montserrat Alternates', 'Montserrat Subrayada' => 'Montserrat Subrayada', 'Moul' => 'Moul', 'Moulpali' => 'Moulpali', 'Mountains of Christmas' => 'Mountains of Christmas', 'Mouse Memoirs' => 'Mouse Memoirs', 'Mr Bedfort' => 'Mr Bedfort', 'Mr Dafoe' => 'Mr Dafoe', 'Mr De Haviland' => 'Mr De Haviland', 'Mrs Saint Delafield' => 'Mrs Saint Delafield', 'Mrs Sheppards' => 'Mrs Sheppards', 'Mukta Vaani' => 'Mukta Vaani', 'Muli' => 'Muli', 'Mystery Quest' => 'Mystery Quest', 'NTR' => 'NTR', 'Neucha' => 'Neucha', 'Neuton' => 'Neuton', 'New Rocker' => 'New Rocker', 'News Cycle' => 'News Cycle', 'Niconne' => 'Niconne', 'Nixie One' => 'Nixie One', 'Nobile' => 'Nobile', 'Nokora' => 'Nokora', 'Norican' => 'Norican', 'Nosifer' => 'Nosifer', 'Nothing You Could Do' => 'Nothing You Could Do', 'Noticia Text' => 'Noticia Text', 'Noto Sans' => 'Noto Sans', 'Noto Serif' => 'Noto Serif', 'Nova Cut' => 'Nova Cut', 'Nova Flat' => 'Nova Flat', 'Nova Mono' => 'Nova Mono', 'Nova Oval' => 'Nova Oval', 'Nova Round' => 'Nova Round', 'Nova Script' => 'Nova Script', 'Nova Slim' => 'Nova Slim', 'Nova Square' => 'Nova Square', 'Numans' => 'Numans', 'Nunito' => 'Nunito', 'Odor Mean Chey' => 'Odor Mean Chey', 'Offside' => 'Offside', 'Old Standard TT' => 'Old Standard TT', 'Oldenburg' => 'Oldenburg', 'Oleo Script' => 'Oleo Script', 'Oleo Script Swash Caps' => 'Oleo Script Swash Caps', 'Open Sans' => 'Open Sans', 'Open Sans Condensed' => 'Open Sans Condensed', 'Oranienbaum' => 'Oranienbaum', 'Orbitron' => 'Orbitron', 'Oregano' => 'Oregano', 'Orienta' => 'Orienta', 'Original Surfer' => 'Original Surfer', 'Oswald' => 'Oswald', 'Over the Rainbow' => 'Over the Rainbow', 'Overlock' => 'Overlock', 'Overlock SC' => 'Overlock SC', 'Ovo' => 'Ovo', 'Oxygen' => 'Oxygen', 'Oxygen Mono' => 'Oxygen Mono', 'PT Mono' => 'PT Mono', 'PT Sans' => 'PT Sans', 'PT Sans Caption' => 'PT Sans Caption', 'PT Sans Narrow' => 'PT Sans Narrow', 'PT Serif' => 'PT Serif', 'PT Serif Caption' => 'PT Serif Caption', 'Pacifico' => 'Pacifico', 'Palanquin' => 'Palanquin', 'Palanquin Dark' => 'Palanquin Dark', 'Paprika' => 'Paprika', 'Parisienne' => 'Parisienne', 'Passero One' => 'Passero One', 'Passion One' => 'Passion One', 'Pathway Gothic One' => 'Pathway Gothic One', 'Patrick Hand' => 'Patrick Hand', 'Patrick Hand SC' => 'Patrick Hand SC', 'Pattaya' => 'Pattaya', 'Patua One' => 'Patua One', 'Pavanam' => 'Pavanam', 'Paytone One' => 'Paytone One', 'Peddana' => 'Peddana', 'Peralta' => 'Peralta', 'Permanent Marker' => 'Permanent Marker', 'Petit Formal Script' => 'Petit Formal Script', 'Petrona' => 'Petrona', 'Philosopher' => 'Philosopher', 'Piedra' => 'Piedra', 'Pinyon Script' => 'Pinyon Script', 'Pirata One' => 'Pirata One', 'Plaster' => 'Plaster', 'Play' => 'Play', 'Playball' => 'Playball', 'Playfair Display' => 'Playfair Display', 'Playfair Display SC' => 'Playfair Display SC', 'Podkova' => 'Podkova', 'Poiret One' => 'Poiret One', 'Poller One' => 'Poller One', 'Poly' => 'Poly', 'Pompiere' => 'Pompiere', 'Pontano Sans' => 'Pontano Sans', 'Poppins' => 'Poppins', 'Port Lligat Sans' => 'Port Lligat Sans', 'Port Lligat Slab' => 'Port Lligat Slab', 'Pragati Narrow' => 'Pragati Narrow', 'Prata' => 'Prata', 'Preahvihear' => 'Preahvihear', 'Press Start 2P' => 'Press Start 2P', 'Pridi' => 'Pridi', 'Princess Sofia' => 'Princess Sofia', 'Prociono' => 'Prociono', 'Prompt' => 'Prompt', 'Prosto One' => 'Prosto One', 'Proza Libre' => 'Proza Libre', 'Puritan' => 'Puritan', 'Purple Purse' => 'Purple Purse', 'Quando' => 'Quando', 'Quantico' => 'Quantico', 'Quattrocento' => 'Quattrocento', 'Quattrocento Sans' => 'Quattrocento Sans', 'Questrial' => 'Questrial', 'Quicksand' => 'Quicksand', 'Quintessential' => 'Quintessential', 'Qwigley' => 'Qwigley', 'Racing Sans One' => 'Racing Sans One', 'Radley' => 'Radley', 'Rajdhani' => 'Rajdhani', 'Rakkas' => 'Rakkas', 'Raleway' => 'Raleway', 'Raleway Dots' => 'Raleway Dots', 'Ramabhadra' => 'Ramabhadra', 'Ramaraja' => 'Ramaraja', 'Rambla' => 'Rambla', 'Rammetto One' => 'Rammetto One', 'Ranchers' => 'Ranchers', 'Rancho' => 'Rancho', 'Ranga' => 'Ranga', 'Rasa' => 'Rasa', 'Rationale' => 'Rationale', 'Redressed' => 'Redressed', 'Reem Kufi' => 'Reem Kufi', 'Reenie Beanie' => 'Reenie Beanie', 'Revalia' => 'Revalia', 'Rhodium Libre' => 'Rhodium Libre', 'Ribeye' => 'Ribeye', 'Ribeye Marrow' => 'Ribeye Marrow', 'Righteous' => 'Righteous', 'Risque' => 'Risque', 'Roboto' => 'Roboto', 'Roboto Condensed' => 'Roboto Condensed', 'Roboto Mono' => 'Roboto Mono', 'Roboto Slab' => 'Roboto Slab', 'Rochester' => 'Rochester', 'Rock Salt' => 'Rock Salt', 'Rokkitt' => 'Rokkitt', 'Romanesco' => 'Romanesco', 'Ropa Sans' => 'Ropa Sans', 'Rosario' => 'Rosario', 'Rosarivo' => 'Rosarivo', 'Rouge Script' => 'Rouge Script', 'Rozha One' => 'Rozha One', 'Rubik' => 'Rubik', 'Rubik Mono One' => 'Rubik Mono One', 'Rubik One' => 'Rubik One', 'Ruda' => 'Ruda', 'Rufina' => 'Rufina', 'Ruge Boogie' => 'Ruge Boogie', 'Ruluko' => 'Ruluko', 'Rum Raisin' => 'Rum Raisin', 'Ruslan Display' => 'Ruslan Display', 'Russo One => Russo One', 'Ruthie' => 'Ruthie', 'Rye' => 'Rye', 'Sacramento' => 'Sacramento', 'Sahitya' => 'Sahitya', 'Sail' => 'Sail', 'Salsa' => 'Salsa', 'Sanchez' => 'Sanchez', 'Sancreek' => 'Sancreek', 'Sansita One' => 'Sansita One', 'Sarala' => 'Sarala', 'Sarina' => 'Sarina', 'Sarpanch' => 'Sarpanch', 'Satisfy' => 'Satisfy', 'Scada' => 'Scada', 'Scheherazade' => 'Scheherazade', 'Schoolbell' => 'Schoolbell', 'Scope One' => 'Scope One', 'Seaweed Script' => 'Seaweed Script', 'Secular One' => 'Secular One', 'Sevillana' => 'Sevillana', 'Seymour One' => 'Seymour One', 'Shadows Into Light' => 'Shadows Into Light', 'Shadows Into Light Two' => 'Shadows Into Light Two', 'Shanti' => 'Shanti', 'Share' => 'Share', 'Share Tech' => 'Share Tech', 'Share Tech Mono' => 'Share Tech Mono', 'Shojumaru' => 'Shojumaru', 'Short Stack' => 'Short Stack', 'Shrikhand' => 'Shrikhand', 'Siemreap' => 'Siemreap', 'Sigmar One' => 'Sigmar One', 'Signika' => 'Signika', 'Signika Negative' => 'Signika Negative', 'Simonetta' => 'Simonetta', 'Sintony' => 'Sintony', 'Sirin Stencil' => 'Sirin Stencil', 'Six Caps' => 'Six Caps', 'Skranji' => 'Skranji', 'Slabo 13px' => 'Slabo 13px', 'Slabo 27px' => 'Slabo 27px', 'Slackey' => 'Slackey', 'Smokum' => 'Smokum', 'Smythe' => 'Smythe', 'Sniglet' => 'Sniglet', 'Snippet' => 'Snippet', 'Snowburst One' => 'Snowburst One', 'Sofadi One' => 'Sofadi One', 'Sofia' => 'Sofia', 'Sonsie One' => 'Sonsie One', 'Sorts Mill Goudy' => 'Sorts Mill Goudy', 'Source Code Pro' => 'Source Code Pro', 'Source Sans Pro' => 'Source Sans Pro', 'Source Serif Pro' => 'Source Serif Pro', 'Space Mono' => 'Space Mono', 'Special Elite' => 'Special Elite', 'Spicy Rice' => 'Spicy Rice', 'Spinnaker' => 'Spinnaker', 'Spirax' => 'Spirax', 'Squada One' => 'Squada One', 'Sree Krushnadevaraya' => 'Sree Krushnadevaraya', 'Sriracha' => 'Sriracha', 'Stalemate' => 'Stalemate', 'Stalinist One' => 'Stalinist One', 'Stardos Stencil' => 'Stardos Stencil', 'Stint Ultra Condensed' => 'Stint Ultra Condensed', 'Stint Ultra Expanded' => 'Stint Ultra Expanded', 'Stoke' => 'Stoke', 'Strait' => 'Strait', 'Sue Ellen Francisco' => 'Sue Ellen Francisco', 'Suez One' => 'Suez One', 'Sumana' => 'Sumana', 'Sunshiney' => 'Sunshiney', 'Supermercado One' => 'Supermercado One', 'Sura' => 'Sura', 'Suranna' => 'Suranna', 'Suravaram' => 'Suravaram', 'Suwannaphum' => 'Suwannaphum', 'Swanky and Moo Moo' => 'Swanky and Moo Moo', 'Syncopate' => 'Syncopate', 'Tangerine' => 'Tangerine', 'Taprom' => 'Taprom', 'Tauri' => 'Tauri', 'Taviraj' => 'Taviraj', 'Teko' => 'Teko', 'Telex' => 'Telex', 'Tenali Ramakrishna' => 'Tenali Ramakrishna', 'Tenor Sans' => 'Tenor Sans', 'Text Me One' => 'Text Me One', 'The Girl Next Door' => 'The Girl Next Door', 'Tienne' => 'Tienne', 'Tillana' => 'Tillana', 'Timmana' => 'Timmana', 'Tinos' => 'Tinos', 'Titan One' => 'Titan One', 'Titillium Web' => 'Titillium Web', 'Trade Winds' => 'Trade Winds', 'Trirong' => 'Trirong', 'Trocchi' => 'Trocchi', 'Trochut' => 'Trochut', 'Trykker' => 'Trykker', 'Tulpen One' => 'Tulpen One', 'Ubuntu' => 'Ubuntu', 'Ubuntu Condensed' => 'Ubuntu Condensed', 'Ubuntu Mono' => 'Ubuntu Mono', 'Ultra' => 'Ultra', 'Uncial Antiqua' => 'Uncial Antiqua', 'Underdog' => 'Underdog', 'Unica One' => 'Unica One', 'UnifrakturCook' => 'UnifrakturCook', 'UnifrakturMaguntia' => 'UnifrakturMaguntia', 'Unkempt' => 'Unkempt', 'Unlock' => 'Unlock', 'Unna' => 'Unna', 'VT323' => 'VT323', 'Vampiro One' => 'Vampiro One', 'Varela' => 'Varela', 'Varela Round' => 'Varela Round', 'Vast Shadow' => 'Vast Shadow', 'Vesper Libre' => 'Vesper Libre', 'Vibur' => 'Vibur', 'Vidaloka' => 'Vidaloka', 'Viga' => 'Viga', 'Voces' => 'Voces', 'Volkhov' => 'Volkhov', 'Vollkorn' => 'Vollkorn', 'Voltaire' => 'Voltaire', 'Waiting for the Sunrise' => 'Waiting for the Sunrise', 'Wallpoet' => 'Wallpoet', 'Walter Turncoat' => 'Walter Turncoat', 'Warnes' => 'Warnes', 'Wellfleet' => 'Wellfleet', 'Wendy One' => 'Wendy One', 'Wire One' => 'Wire One', 'Work Sans' => 'Work Sans', 'Yanone Kaffeesatz' => 'Yanone Kaffeesatz', 'Yantramanav' => 'Yantramanav', 'Yatra One' => 'Yatra One', 'Yellowtail' => 'Yellowtail', 'Yeseva One' => 'Yeseva One', 'Yesteryear' => 'Yesteryear', 'Yrsa' => 'Yrsa', 'Zeyada' => 'Zeyada');
    }

}

if ( ! function_exists( 'blogdata_header_color' ) ) :

    function blogdata_header_color() { ?>
        <style type="text/css">
            <?php if ( ! display_header_text() ) : ?>

                .site-title,
                .site-description {
                    position: absolute;
                    clip: rect(1px, 1px, 1px, 1px);
                }

            <?php else :
            endif; ?>
        </style>
        <?php
    }
endif;


function blogdata_comment_count() { 
    $comments_count = wp_count_comments();
    $message =   $comments_count->approved;
    
    return $message; 
} 
 
add_action('wpb_total_comments','blogdata_comment_count');

//SCROLL TO TOP //
if ( ! function_exists( 'blogdata_scrolltoup' ) ) :
    function blogdata_scrolltoup() {
        $scrollup_layout = get_theme_mod('scrollup_layout','fa-arrow-up');
        $scrollup_enable = get_theme_mod('blogdata_scrollup_enable',true); 
        if($scrollup_enable == true) { ?>
            <div class="blogdata_stt">
                <a href="#" class="bs_upscr">
                    <i class="fa-solid <?php echo esc_attr($scrollup_layout)?>"></i>
                </a>
            </div>
    <?php } }
endif; 

function blogdata_dropcap() {
    $blogdata_drop_caps_enable = get_theme_mod('blogdata_drop_caps_enable','false');
    if($blogdata_drop_caps_enable == 'true'){ ?>
        <style>
            .bs-blog-post p:nth-of-type(1)::first-letter {
                font-size: 60px;
                font-weight: 800;
                margin-right: 10px;
                font-family: 'Vollkorn', serif;
                line-height: 1; 
                float: left;
            }
        </style>
    <?php } else { ?>
        <style>
            .bs-blog-post p:nth-of-type(1)::first-letter {
                display: none;
            }
        </style>
    <?php } 
} add_action('wp_head','blogdata_dropcap'); 

if ( ! function_exists( 'blogdata_search_popup' ) ) :
    function blogdata_search_popup() { ?>
        <div class="search-popup" bs-search-targeted="true">
            <div class="search-popup-content">
                <div class="search-popup-close">
                    <button type="button" class="btn-close" bs-dismiss-search="true"><i class="fa-solid fa-xmark"></i></button>
                </div>
                <div class="search-inner">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </div>
    <?php }
endif;
add_action('wp_footer','blogdata_search_popup');


if ( ! function_exists( 'blogdata_overlay_section' ) ) :
function blogdata_overlay_section() { ?>
    <div class="bs-overlay-section" bs-remove-overlay="true"></div>
<?php } 
endif;
add_action('wp_footer','blogdata_overlay_section');

if ( ! function_exists( 'blogdata_dimension' ) ) {
	
	function blogdata_dimension( $default_value, $output_value, $property ) {

		if ( $default_value === $output_value ) {
			return;
		}

        $output_dimension = '';

		$unit = isset( $output_value['unit'] ) ? $output_value['unit'] : ( isset( $default_value['unit'] ) ? $default_value['unit'] : 'px' );

		if ( isset( $output_value['top'] ) && ! empty( $output_value['top'] ) && ( $output_value['top'] !== $default_value['top'] ) ) {
			$output_dimension .= $property . '-top:' . $output_value['top'] . $unit . ';';
		}

		if ( isset( $output_value['top'] ) && ! empty( $output_value['top'] ) && ( $output_value['right'] !== $default_value['right'] ) ) {
			$output_dimension .= $property . '-right:' . $output_value['right'] . $unit . ';';
		}

		if ( isset( $output_value['bottom'] ) && ! empty( $output_value['bottom'] ) && ( $output_value['bottom'] !== $default_value['bottom'] ) ) {
			$output_dimension .= $property . '-bottom:' . $output_value['bottom'] . $unit . ';';
		}

		if ( isset( $output_value['left'] ) && ! empty( $output_value['left'] ) && ( $output_value['left'] !== $default_value['left'] ) ) {
			$output_dimension .= $property . '-left:' . $output_value['left'] . $unit . ';';
		}

		return $output_dimension;
	}
}

if ( ! function_exists( 'blogdata_border_radius' ) ) {
	
	function blogdata_border_radius( $default_value, $output_value) {

		if ( $default_value === $output_value ) {
			return;
		}

        $output_dimension = '';

		$unit = isset( $output_value['unit'] ) ? $output_value['unit'] : ( isset( $default_value['unit'] ) ? $default_value['unit'] : 'px' );

		if ( isset( $output_value['top'] ) && ! empty( $output_value['top'] ) && ( $output_value['top'] !== $default_value['top'] ) ) {
			$output_dimension .= 'border-top-left-radius:' . $output_value['top'] . $unit . ';';
		}

		if ( isset( $output_value['top'] ) && ! empty( $output_value['top'] ) && ( $output_value['right'] !== $default_value['right'] ) ) {
			$output_dimension .= 'border-top-right-radius:' . $output_value['right'] . $unit . ';';
		}

		if ( isset( $output_value['bottom'] ) && ! empty( $output_value['bottom'] ) && ( $output_value['bottom'] !== $default_value['bottom'] ) ) {
			$output_dimension .= 'border-bottom-right-radius:' . $output_value['bottom'] . $unit . ';';
		}

		if ( isset( $output_value['left'] ) && ! empty( $output_value['left'] ) && ( $output_value['left'] !== $default_value['left'] ) ) {
			$output_dimension .= 'border-bottom-left-radius:' . $output_value['left'] . $unit . ';';
		}

		return $output_dimension;
	}
}

if (!function_exists('blogdata_box_shadow')):
    function blogdata_box_shadow($shadow_setting){
          $output_box_shadow = '';
    
            $output_box_shadow = sprintf(
                'box-shadow : %1$spx %2$spx %3$spx %4$spx %5$s',
                esc_html( $shadow_setting['horizontal'] ),
                esc_html( $shadow_setting['vertical'] ),
                esc_html( $shadow_setting['blur'] ),
                esc_html( $shadow_setting['spread'] ),
                esc_html( $shadow_setting['color'] )
            );

        return $output_box_shadow;
    }
endif;
/**
 * Takes Ranges Control JSON values, decodes and ouputs accordingly
 * @param  [string] $css_prop CSS Property to add
 * @param  [JSON Object] $obj_value Json Object Value
 * @param  [string] $ext css value extension, eg. px, in, pc
 * @return [string]
 */
function blogdata_media_range( $css_prop, $obj_value, $default, $media = 'desktop', $ext = '' ) {
    if( is_string( $obj_value ) && is_array( json_decode( $obj_value, true ) ) ){
        /* It means that we have media queries active */
        $json    = json_decode( $obj_value );
        $value   = '';
        if ( $media == 'desktop' && $json->desktop != $default ) {
            if ( is_array( $css_prop ) ) {
                $value   = $css_prop[0] . ': ' . esc_attr ( $json->desktop ) . $ext . ';';
                if ( count( $css_prop ) > 1 ) {
                    $value  .= $css_prop[1] . ': ' . esc_attr ( $json->desktop ) . $ext . ';';
                }
            } else {
                $value   = $css_prop . ': ' . esc_attr ( $json->desktop ) . $ext . ';';
            }
        }

        if ( $media == 'mobile' && $json->mobile != $default ) {
            if ( is_array( $css_prop ) ) {
                $value   = $css_prop[0] . ': ' . esc_attr ( $json->mobile ) . $ext . ';';
                if ( count( $css_prop ) > 1 ) {
                    $value  .= $css_prop[1] . ': ' . esc_attr ( $json->mobile ) . $ext . ';';
                }
            } else {
                $value   = $css_prop . ': ' . esc_attr ( $json->mobile ) . $ext . ';';
            }
        }

        if ( $media == 'tablet' && $json->tablet != $default ) {
            if ( is_array( $css_prop ) ) {
                $value   = $css_prop[0] . ': ' . esc_attr ( $json->tablet ) . $ext . ';';
                if ( count( $css_prop ) > 1 ) {
                    $value  .= $css_prop[1] . ': ' . esc_attr ( $json->tablet ) . $ext . ';';
                }
            } else {
                $value   = $css_prop . ': ' . esc_attr ( $json->tablet ) . $ext . ';';
            }
        }

        return $value;
    }

    return false;
}

/**
 * Takes Ranges Control applies to blogdata_media_range function and ouputs Full css with @media query
 * @param  [string] $css_prop CSS Property to add
 * @param  [string] $control control / settings
 * @param  [string] $ext css value extension, eg. px, in, pc
 * @return [string]
 */
function blogdata_customizer_value( $control, $css_selector, $css_prop, array $default, $ext = '' ) {
    if ( $control ) {
        $control        = get_theme_mod( $control );
        $return         = '';

        if( is_string( $control ) && is_array( json_decode( $control, true ) ) ){
            $desktop_val    = blogdata_media_range( $css_prop, $control,  $default[0], 'desktop', $ext );
            $tablet_val     = blogdata_media_range( $css_prop, $control, $default[1], 'tablet', $ext );
            $mobile_val     = blogdata_media_range( $css_prop, $control, $default[2], 'mobile', $ext );

            if ( !empty( $desktop_val ) ) {
                $return         = $css_selector . ' { ';
                $return        .= $desktop_val;
                $return        .= '} ';
            }

            if ( !empty( $tablet_val ) ) {
                $return        .= '@media (max-width:991px) {';
                $return        .= $css_selector . ' { ';
                $return        .= $tablet_val;
                $return        .= '} } ';
            }

            if ( !empty( $mobile_val ) ) {
                $return        .= '@media (max-width:575px) {';
                $return        .= $css_selector . ' { ';
                $return        .= $mobile_val;
                $return        .= '} } ';
            }
        } else {
            if ( !empty( $control ) && $control != $default[0] ) {
                $return        .= $css_selector . ' { ';
                $return        .= esc_attr( $control ) . $ext . ';';
                $return        .= ' } ';
            }
        }

        return $return;
    }

    return false;
}