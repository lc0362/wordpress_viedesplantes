<?php

class blogdata_featured_latest_news extends WP_Widget {

	function __construct() {
		parent::__construct(
			'blogdata_featured_latest_news', //Base ID
			__('AR: Recent Post', 'blogdata'), //Name
			array( 'description' => __( 'Display your recent posts on your website', 'blogdata' ), ) // Args
		);
	}

	public function widget( $args, $instance ) {
		
		$instance['title'] = (isset($instance['title'])?$instance['title']:'');
		$instance['number_of_posts'] = (isset($instance['number_of_posts'])?$instance['number_of_posts']:'4');
		$instance['tumb_size'] = (isset($instance['tumb_size'])?$instance['tumb_size']:'');
		$instance['image_show']=(isset($instance['image_show'])?$instance['image_show']:true);

		echo $args['before_widget'];
		
		if($instance['title']) ?>
	
		<div class="col-lg-12">
		<div class="bs-recent-blog-post<?php if (!empty($instance['title'])) { echo ' wd-back'; } ?>">
			<?php if (!empty($instance['title'])){ ?>
            <!-- bs-sec-title -->
            <div class="bs-widget-title one">
				<h4 class="title"><?php echo esc_html($instance['title']) ?></h4>
			</div> 
            <!-- // bs-sec-title -->
				<?php  }
			$loop = new WP_Query(array( 'post_type' => 'post','ignore_sticky_posts' => 1, 'showposts' => $instance['number_of_posts'] ));
			if( $loop->have_posts() ) : 
			while ( $loop->have_posts() ) : $loop->the_post();?>
			<div class="small-post">
				<?php if($instance['image_show']==true){ if(has_post_thumbnail()){?>
				<div class="img-small-post back-img hlgr right">
					<a href="<?php the_permalink(); ?>" class="post-thumbnail"> <?php $defalt_arg =array('class' => "img-fluid" ); the_post_thumbnail($instance['tumb_size'], $defalt_arg); ?>
					</a>
				</div>
				<?php } } ?>
				<div class="small-post-content">
					<div class="bs-blog-meta">
						<?php blogdata_author_content(); blogdata_date_content(); ?>
					</div>
					<h5 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h5>
				</div>
			</div>
		<?php endwhile; 
			endif; ?>
		</div>	
		</div>
		<?php
			
		echo $args['after_widget']; 	
	}

	public function form( $instance ) {

		$instance['title'] = (isset($instance['title'])?$instance['title']:'Recent Post');
		$instance['number_of_posts'] = (isset($instance['number_of_posts'])?$instance['number_of_posts']:'4');
		
		$instance['tumb_size'] = (isset($instance['tumb_size'])?$instance['tumb_size']:'');
		$instance['image_show']=(isset($instance['image_show'])?$instance['image_show']:true);

		?>
		
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title','blogdata' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $instance['title'] ); ?>" />
		</p>
				
		<p>
		<label for="<?php echo $this->get_field_id( 'number_of_posts' ); ?>"><?php _e( 'Number of posts to show','blogdata' ); ?></label> 
		<input size="3" maxlength="2"id="<?php echo $this->get_field_id( 'number_of_posts' ); ?>" name="<?php echo $this->get_field_name( 'number_of_posts' ); ?>" type="text" value="<?php echo esc_attr( $instance['number_of_posts'] ); ?>" />
		</p>	
		<p>
		<label for="<?php echo $this->get_field_id( 'tumb_size' ); ?>"><?php _e( 'Featured post image size','blogdata' ); ?></label><br/> 
		<select id="<?php echo $this->get_field_id( 'tumb_size' ); ?>" name="<?php echo $this->get_field_name( 'tumb_size' ); ?>">
			<option value>-- <?php _e('Select post image size','blogdata'); ?> --</option>
			<option value="thumbnail" <?php echo ($instance['tumb_size']=='thumbnail'?'selected':''); ?>><?php _e('Thumbnail','blogdata'); ?></option>
			<option value="full" <?php echo ($instance['tumb_size']=='full'?'selected':''); ?>><?php _e('Full','blogdata'); ?></option>
		</select>
	</p>
	
	<p>
		<label for="<?php echo $this->get_field_id( 'image_show' ); ?>"><?php _e( 'Enable feature image','blogdata' ); ?></label> 
		<input type="checkbox" class="widefat" id="<?php echo $this->get_field_id( 'image_show' ); ?>" name="<?php echo $this->get_field_name( 'image_show' ); ?>" <?php if($instance['image_show']==true) echo 'checked'; ?> >
	</p>
		
	<?php 
	}

	public function update( $new_instance, $old_instance ) {
		
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? $new_instance['title'] : '';
		$instance['number_of_posts'] = ( ! empty( $new_instance['number_of_posts'] ) ) ? strip_tags( $new_instance['number_of_posts'] ) : '';
		
		$instance['tumb_size'] = ( ! empty( $new_instance['tumb_size'] ) ) ? strip_tags( $new_instance['tumb_size'] ) : '';
		$instance['image_show'] = ( ! empty( $new_instance['image_show'] ) ) ? $new_instance['image_show'] : '';
		
		return $instance;
	}

} // class
?>