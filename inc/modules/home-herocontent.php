<?php
/**
 * File used for homepage hero content module
 *
 * @package WordPress
 */
?>

<?php 
$bg_img = dwp_option('home-banner-img', false, 'url');
$bg_url = '';

if($bg_img != ''){
	$bg_url = 'style="background-image: url(' . $bg_img . ')";';
} 
?>

<div class="pagewrap" <?php echo $bg_url; ?>>
	<header>
	<?php
		if(dwp_option('home-hero-title') != ''){
			$banner_text = dwp_option('home-hero-title');
			echo '<h1 class="entry-title">' . $banner_text . '</h1>';
		}
	?>
	</header> 
</div><!-- /pagewrap -->