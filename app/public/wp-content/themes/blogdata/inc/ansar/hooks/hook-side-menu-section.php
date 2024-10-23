<?php
if (!function_exists('blogdata_side_menu_section')) :
/**
 *  Header
 *
 * @since Blogdata
 *
 */
function blogdata_side_menu_section() { ?>
  <aside class="bs-offcanvas end" bs-data-targeted="true">
    <div class="bs-offcanvas-close">
      <a href="#" class="bs-offcanvas-btn-close" bs-data-removable="true">
        <span></span>
        <span></span>
      </a>
    </div>
    <div class="bs-offcanvas-inner">
      <?php if( is_active_sidebar('menu-sidebar-content')){
        get_template_part('sidebar','menu');
      } else { ?>
      
      <div class="bs-card-box empty-sidebar">
        <div class="bs-widget-title one">
          <h2 class='title'><?php echo esc_html( 'Header Toggle Sidebar', 'blogdata' ); ?></h3>
        </div>
        <p class='empty-sidebar-widget-text'>
          <?php echo esc_html( 'This is an example widget to show how the Header Toggle Sidebar looks by default. You can add custom widgets from the', 'blogdata' ); ?>
          <a href='<?php echo esc_url( admin_url( 'widgets.php' ) ); ?>' title='<?php esc_attr_e('widgets','blogdata'); ?>'>
            <?php echo esc_html( 'widgets', 'blogdata' ); ?>
          </a>
          <?php echo esc_html( 'in the admin.', 'blogdata' ); ?>
        </p>
      </div>
      <?php } ?>
    </div>
  </aside>
  <?php 
}
endif;
add_action('blogdata_action_side_menu_section', 'blogdata_side_menu_section', 5);