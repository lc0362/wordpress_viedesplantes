<?php
if (!function_exists('blogdata_header_logo_section')) :
/**
 *  Header
 *
 * @since blogdata
 *
 */
function blogdata_header_logo_section() { ?>
<!-- logo-->
<div class="logo">
  <div class="site-logo">
      <?php if(get_theme_mod('custom_logo') !== ""){ the_custom_logo(); } ?>
  </div>
  <?php 
    if (display_header_text()) { ?>
    <div class="site-branding-text">
    <?php } else { ?>
    <div class="site-branding-text d-none">
    <?php } if (is_front_page() || is_home()) { ?>
    <h1 class="site-title"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></h1>
    <?php } else { ?>
    <p class="site-title"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php echo esc_html(get_bloginfo( 'name' )); ?></a></p>
    <?php } ?>
    <p class="site-description"><?php  echo esc_html(get_bloginfo( 'description' )); ?></p>
  </div>
</div><!-- /logo-->
<?php  }
endif;
add_action('blogdata_action_header_logo_section', 'blogdata_header_logo_section', 4);

// Offcanvas Menu
if (!function_exists('blogdata_sidebar_menu_function')):
  function blogdata_sidebar_menu_function() { 
    $blogdata_menu_sidebar  = get_theme_mod('blogdata_menu_sidebar','true'); 
    if($blogdata_menu_sidebar == true){ ?>
    <!-- Off Canvas -->
      <div class="hedaer-offcanvas d-none d-lg-block">
        <button class="offcanvas-trigger" bs-data-clickable-end="true">
          <i class="fa-solid fa-bars-staggered"></i>
        </button>
      </div>
    <!-- /Off Canvas -->
    <?php } ?>

  <?php }
endif;
add_action('blogdata_action_sidebar_menu_function', 'blogdata_sidebar_menu_function', 5);

// Dar/Light Switch
if (!function_exists('blogdata_header_dark_switch_section')) :

  function blogdata_header_dark_switch_section() {  
    $blogdata_lite_dark_switcher = get_theme_mod('blogdata_lite_dark_switcher','true');
    if($blogdata_lite_dark_switcher == true){ 
      if ( isset( $_COOKIE["blogdata-site-mode-cookie"] ) ) {
        $blogdata_skin_mode = $_COOKIE["blogdata-site-mode-cookie"];
    } else {
        $blogdata_skin_mode = get_theme_mod( 'blogdata_skin_mode', 'defaultcolor' );
    } ?>
      <label class="switch d-none d-lg-inline-block" for="switch">
        <input type="checkbox" name="theme" id="switch" class="<?php echo esc_attr( $blogdata_skin_mode ); ?>" data-skin-mode="<?php echo esc_attr( $blogdata_skin_mode ); ?>">
        <span class="slider"></span>
      </label>
    <?php } 
  }
endif;
add_action('blogdata_action_header_dark_switch_section', 'blogdata_header_dark_switch_section', 5);

// Search
if (!function_exists('blogdata_header_search_section')) :

  function blogdata_header_search_section() { 
    $blogdata_menu_search  = get_theme_mod('blogdata_menu_search','true');
    if($blogdata_menu_search == true) { ?>
      <!-- search-->
      <a class="msearch" href="#" bs-search-clickable="true">
        <i class="fa-solid fa-magnifying-glass"></i>
      </a>
      <!-- /search-->
    <?php } 
  }
endif;
add_action('blogdata_action_header_search_section', 'blogdata_header_search_section', 5);

// Subscriber Button
if (!function_exists('blogdata_header_subs_section')) :

  function blogdata_header_subs_section() {  
    $subsc_link = get_theme_mod('blogdata_subsc_link', '#'); 
    $blogdata_menu_subscriber  = get_theme_mod('blogdata_menu_subscriber','true');
    $subsc_icon  = get_theme_mod('subsc_icon_layout','play');
    $subsc_open_in_new  = get_theme_mod('subsc_open_in_new','true');
    $subs_title  = get_theme_mod('subs_news_title','Subscribe');
    if($blogdata_menu_subscriber == true) { ?> 
    <a href="<?php echo esc_attr($subsc_link) ?>" class="subscribe-btn btn d-none d-lg-flex align-center" <?php if($subsc_open_in_new == true){ echo ' target="_blank"'; } ?>>
      <i class="fas fa-<?php echo esc_html($subsc_icon) ?>"></i> <?php if(!empty($subs_title)){ echo '<span>'.$subs_title.'</span>';  } ?>
    </a>
    <?php }
  }
endif;
add_action('blogdata_action_header_subs_section', 'blogdata_header_subs_section', 5);

// Woo Cart

if (!function_exists('blogdata_header_woo_cart_section')) :

  function blogdata_header_woo_cart_section() {  
    $enable_cart  = get_theme_mod('blogdata_cart_enable',1);
    if($enable_cart == 1){ ?>
    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="bs-cart d-none d-lg-flex">
      
        <span class='bs-cart-total'>
        <?php echo WC()->cart->get_cart_subtotal(); ?>
        </span>
        <span class="bs-cart-icon">
        <i class="fa-solid fa-cart-arrow-down"></i>
        </span>
        <span class='bs-cart-count'>
          <?php echo WC()->cart->get_cart_contents_count(); ?>
        </span>
    </a>
    <?php  
    } 
  } 
endif;
add_action('blogdata_action_header_cart_section', 'blogdata_header_woo_cart_section', 5);