<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site will use a
 * different template.
 *
 * @package bootstrapwp
 */

get_header(); ?>

<?php

$bg_img = dwp_option('service-banner-img', false, 'url');
$bg_url = '';

if($bg_img != ''){
	$bg_url = 'style="background-image: url(' . $bg_img . ')";';
} 
?>

<div class="pagewrap" <?php echo $bg_url; ?>>
	<header>
	<?php
		if(dwp_option('hp-banner-title', 'Header Text') != ''){
			$banner_text = dwp_option('service-banner-title', 'Header Text');
			echo '<h1 class="entry-title">' . $banner_text . '</h1>';
		}
	?>
	</header> 
</div><!-- /pagewrap -->



<div class="container">
	<div class="row">
		<div id="primary" class="col-lg-12">
			<main id="main" class="site-main" role="main">
			<?php 
				if(dwp_option('page-title-text') != ''){
					$title_text = dwp_option('page-title-text');
					echo '<h3 class="entry-title">' . $title_text . '</h3>';
				}
				if(dwp_option('page-intro-copy') != ''){
					$intro_copy = dwp_option('page-intro-copy');
					echo '<p>' . $intro_copy . '</p>';
				}
				if(dwp_option('editor-text') != ''){
					$editor_text = dwp_option('editor-text');
					echo '<p>' . $editor_text . '</p>';
				}
			?>

				<?php 
				// the query
				$the_query = new WP_Query( array('post_type' => 'service') ); ?>

				<?php if ( $the_query->have_posts() ) : ?>

					<div class="row">
						<div class="services">

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

			</main><!-- #main -->
		</div><!-- #primary -->
	</div> <!-- .row -->
</div> <!-- .container -->


<?php get_footer(); ?>
