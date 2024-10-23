<?php
if (!function_exists('blogdata_header_right_menu_section')) :
/**
 *  Header
 *
 * @since blogdata
 *
 */
function blogdata_header_right_menu_section() { ?>
<div class="right-nav">
<?php 
  do_action('blogdata_action_sidebar_menu_function'); 
  do_action('blogdata_action_header_dark_switch_section'); 
  do_action('blogdata_action_header_search_section'); 
  if( class_exists( 'WooCommerce' ) ) { do_action('blogdata_action_header_cart_section'); }
  do_action('blogdata_action_header_subs_section'); 
?>
</div>
<?php }
endif;
add_action('blogdata_action_header_right_menu_section', 'blogdata_header_right_menu_section', 6);