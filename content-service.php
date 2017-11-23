<?php
/**
 * The template used for displaying portfolio items
 *
 * @package bootstrapwp
 */
?>

<!-- SERVICE ITEM -->
<div class="col-lg-4 callout">
	<?php 
	$icon = '';
	if (rwmb_meta('dwp_service_icon') != '') {
		$icon = rwmb_meta('dwp_service_icon'); 
	}?>
	<i class="fa fa-<?php echo $icon ?>"></i>
	<?php the_title('<h2>','</h2>'); ?>
	<?php the_content('<p>','</p>'); ?>
</div><!-- /col -->

<?php $icon = rwmb_meta('dwp_service_icon'); ?>