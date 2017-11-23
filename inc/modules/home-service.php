<?php
/**
 * File used for homepage service module
 *
 * @package WordPress
 */
?>

<!-- ==== GREYWRAP ==== -->
<div class="greywrap">
	<div class="container">
	<div class="row">	
	<?php 

	if(dwp_option('home-number-services') != ''){
		$number_services = dwp_option('home-number-services');
	} else {
		$number_services = 3;
	}
	
	$the_query = new WP_Query( array('post_type' => 'service', 'posts_per_page' => $number_services ) ); ?>

	<?php if ( $the_query->have_posts() ) : ?>


			<div class="home-services">

			<!-- the loop -->
			<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

            <?php get_template_part( 'content', 'service' ); ?>

			<?php endwhile; ?>
			<!-- end of the loop -->

		</div> <!-- #service-items -->

		</div> <!-- .row -->
		

		<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>			

</div>
</div><!-- greywrap -->