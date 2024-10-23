<?php $author_by = isset($args['author_by']) ? $args['author_by'] : ''; ?>
<div class="bs-info-author-block py-4 px-3 mb-4 flex-column justify-center text-center">
  <a class="bs-author-pic" href="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php echo get_avatar( get_the_author_meta( 'ID') , 150); ?></a>
  <div class="flex-grow-1">
    <h4 class="title"><?php echo esc_html('By','blogdata'); ?> <a href ="<?php echo esc_url(get_author_posts_url( get_the_author_meta( 'ID' ) ));?>"><?php the_author(); ?></a></h4>
    <p><?php the_author_meta( 'description' ); ?></p>
  </div>
</div>