<?php
  $blogdata_slider_category = blogdata_get_option('select_slider_news_category');
  $blogdata_number_of_post = 5;
  $blogdata_all_posts_main = blogdata_get_posts($blogdata_number_of_post, $blogdata_slider_category);
   ?>
  <!--row-->
  <div class="col-lg-7 col-md-6">
    <div class="mb-0">
      <div class="homemain bs swiper-container">
        <div class="swiper-wrapper">
          <?php
          if ($blogdata_all_posts_main->have_posts()) :
            while ($blogdata_all_posts_main->have_posts()) : $blogdata_all_posts_main->the_post(); 
              blogdata_slider_default_section();   
            endwhile;
          endif;
          wp_reset_postdata(); ?>

        </div>
        <!-- Add Arrows -->
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>

        <!-- <div class="swiper-pagination"></div> -->

      </div>
      <!--/swipper-->
    </div>
  </div>
  <!--/col-12-->