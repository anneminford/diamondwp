<?php
/**
 * @package WordPress
 * @subpackage DiamondWP
 * Template Name: Homepage
 */
?>

<?php get_header(); ?>

<!-- <div class="container"> -->
	<?php
	// Loop through homepage modules and get their corresponding files
	// See your theme's includes folder for editing these modules
    global $dwp_options;
    $homepage_modules = $dwp_options['homepage-layout']['enabled'];
    if ($homepage_modules):
		// Loop through each module
    	foreach ($homepage_modules as $key=>$value) :

			$value = preg_replace('/\s*/', '', $value); // remove white spaces
			$value = strtolower($value); // lowercase
    		get_template_part('inc/modules/home', $value); // get correct file for each module
   		endforeach;
	endif; ?>
<!-- </div> -->

<?php get_footer(); ?>