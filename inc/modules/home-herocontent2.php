<?php
/**
 * File used for homepage service module
 *
 * @package WordPress
 */
?>

<!-- ==== ABOUT ==== -->
		<div class="container wrap-section">
			<div class="row">
				<?php
				if(dwp_option('heading-two-col') != ''){
					$header_text = dwp_option('heading-two-col');
					echo '<h1 class="centered">' . $header_text . '</h1>';
				}
				?>
				<hr>
				
				<div class="col-lg-6">
				<?php
				if(dwp_option('left-text') != ''){
					$left_text = dwp_option('left-text');
					echo '<p>' . $left_text . '</p>';
				}
				?>
				</div><!-- col-lg-6 -->
				
				<div class="col-lg-6">
				<?php
				if(dwp_option('right-text') != ''){
					$right_text = dwp_option('right-text');
					echo '<p>' . $right_text . '</p>';
				}
				?>
				</div><!-- col-lg-6 -->
			</div><!-- row -->
		</div><!-- container -->
		


