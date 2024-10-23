<?php function blogdata_header_default_section() {
  do_action('blogdata_action_header_image_section'); ?>
<!--header-->
<header class="bs-default">
  <div class="clearfix"></div>
  <!-- Main Menu Area-->
  <?php $sticky_header = get_theme_mod('sticky_header_toggle', true ) == true ? ' sticky-header' : ''; ?>
  <div class="bs-menu-full<?php echo esc_attr($sticky_header); ?>">
    <div class="inner">
      <div class="container">
        <div class="main d-flex align-center">
          <!-- logo Area-->
          <?php do_action('blogdata_action_header_logo_section'); ?>
          <!-- /logo Area-->
          <!-- Main Menu Area-->
          <?php do_action('blogdata_action_header_menu_section'); ?>
          <!-- /Main Menu Area-->
          <!-- Right Area-->
          <?php do_action('blogdata_action_header_right_menu_section'); ?>
          <!-- Right-->
        </div><!-- /main-->
      </div><!-- /container-->
    </div><!-- /inner-->
  </div><!-- /Main Menu Area-->
</header>
<?php }