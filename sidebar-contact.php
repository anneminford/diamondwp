<?php
/**
 * The sidebar containing the contact sidebar widget area.
 *
 * @package bootstrapwp
 */

if ( ! is_active_sidebar( 'contact' ) ) {
	return;
}
?>

<div id="secondary" class="widget-area col-md-3 col-lg-3" role="complementary">
	<?php dynamic_sidebar( 'contact' ); ?>
</div><!-- #secondary -->

</div> <!-- .row -->
</div> <!-- .container -->