<?php if (!function_exists('blogdata_header_type_section')) :
    /**
     *  Header
     *
     * @since blogdata
     *
     */
    function blogdata_header_type_section(){

        blogdata_header_default_section();
        do_action('blogdata_action_side_menu_section');
    }
endif;
add_action('blogdata_action_header_type_section', 'blogdata_header_type_section', 6);