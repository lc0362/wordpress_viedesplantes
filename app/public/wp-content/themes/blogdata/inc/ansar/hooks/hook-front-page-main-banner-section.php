<?php
if (!function_exists('blogdata_front_page_banner_section')) :
  /**
   *
   * @since blogdata
   *
   */
  function blogdata_front_page_banner_section() {
    if (is_front_page() || is_home()) {
              
      get_template_part('inc/ansar/hooks/blocks/block','banner-list');
      get_template_part('inc/ansar/hooks/blocks/featured/featured','default');

    }
  }
endif;
add_action('blogdata_action_front_page_main_section_1', 'blogdata_front_page_banner_section', 40); 