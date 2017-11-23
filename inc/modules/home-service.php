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
	
	$the_query = new WP_Query( array('post_type' => 'service', 'posts_per_page' => $number_services, $counter = 0 ) ); ?>

	<?php if ( $the_query->have_posts() ) : ?>

		<!-- the loop -->
		<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

        <?php get_template_part( 'content', 'service' ); ?>
              <?php $counter++;
			          if ($counter % 3 == 0) {
			          echo '</div><div class="row">';
			         } ?>

		<?php endwhile; ?>
		<!-- end of the loop -->

	</div> <!-- .row -->

	<?php wp_reset_postdata(); ?>

	<?php else : ?>
		<p><?php _e( 'Sorry, no posts matched your criteria.' ); ?></p>
	<?php endif; ?>			

</div>
</div><!-- greywrap -->


