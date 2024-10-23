<?php function blogdata_slider_default_section() {
global $post;
$blogdata_url = blogdata_get_freatured_image_url($post->ID, 'blogdata-slider-full');
$slider_meta_enable = get_theme_mod('slider_meta_enable','true');
$slider_overlay_enable = get_theme_mod('slider_overlay_enable','true'); ?>
<div class="swiper-slide">
  <div class="bs-slide bs-blog-post three lg back-img bshre" style="background-image: url('<?php echo esc_url($blogdata_url); ?>');">
    <a class="link-div" href="<?php the_permalink(); ?>"> </a>
    <?php if ($slider_overlay_enable != false){ ?>
    <div class="inner">
      <?php if($slider_meta_enable == true) { ?><?php blogdata_post_categories(); ?> <?php } ?>
      <h4 class="title"> <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
      <?php if($slider_meta_enable == true) { blogdata_post_meta(); } ?>
    </div>
    <?php } ?>
  </div> 
</div>
<?php }