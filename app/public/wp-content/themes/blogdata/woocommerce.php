<?php
/**
 * The template for displaying all WooCommerce pages.
 *
 * @package BlogData
 */
get_header(); ?>
<!--==================== ti breadcrumb section ====================-->

<!-- #main -->
<main id="content" class="woocommerce-class content">
	<div class="container">
		<?php do_action('blogdata_action_archive_page_title'); ?>
		<div class="row">
			<div class="col-lg-12">
				<?php woocommerce_content(); ?>
			</div>
		</div><!-- .container -->
	</div>	
</main><!-- #main -->
<?php get_footer(); ?>