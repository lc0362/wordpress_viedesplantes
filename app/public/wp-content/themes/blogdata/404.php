<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package BlogData
 */
get_header();?>
<!--container--> 
  <div id="content" class="404-class container content">
    <!--container-->
    <div class="container">
      <?php do_action('blogdata_breadcrumb_content'); ?>
    </div>
    <!--row-->
    <div class="row">
      <div class="col-lg-12 text-center bs-section"> 
        <!--mg-error-404-->
          <div class="bs-error-404">
            <h1 class="title">
              <?php esc_html_e('4','blogdata'); ?><i class="fa-solid fa-face-sad-tear"></i><?php esc_html_e('4','blogdata'); ?>
            </h1>
            <h4 class="subtitle"><?php echo esc_html(get_theme_mod('blogdata_404_title', 'Oops! Page not found')); ?></h4>
            <p class="description"><?php echo esc_html(get_theme_mod('blogdata_404_desc','We are sorry, but the page you are looking for does not exist.')); ?></p>
            <a href="<?php echo esc_url(home_url());?>" onClick="history.back();" class="btn btn-theme">
              <?php echo esc_html(get_theme_mod('blogdata_404_btn_title','Go Back')); ?>
            </a>
          </div>
        <!--/mg-error-404--> 
      </div>
      <!--/col-lg-12--> 
    </div>
    <!--/row--> 
  </div>
<!--/container-->
<?php get_footer();