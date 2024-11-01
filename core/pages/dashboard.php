<?php
/**
 * Custom about page
 */

?>
<div class="wrap about-wrap">

	<h1><?php _e( 'Team Members Blocks Plugin', 'tmb-string' ); ?></h1>

	<div class="about-text">
		<?php _e('Team Members Blocks is a clean and beautiful team members building plugin that can be esily customized and integrated into any theme.', 'tmb-string' ); ?>
	</div>

	<div class="changelog">
		<div class="feature-section images-stagger-right">
			 <div class="about-links">
				 <a href="http://themeflection.com/contact/"><i class="dashicons dashicons-sos"></i> <?php echo __('Support', 'tmb-string'); ?> </a>
         <a href="http://themeflection.com/docs/team-members/"><i class="dashicons dashicons-book"></i> <?php echo  __('Documentation', 'tmb-string'); ?></a>
         <a href="edit.php?post_type=tf_members&page=tf-addons"><i class="dashicons dashicons-archive"> </i><?php echo  __('Addons', 'tmb-string'); ?></a>
			</div>
			<h4><?php _e( 'Clean And Simple', 'tmb-string' ); ?></h4>
			<p><?php _e( 'Clean design and code, use our plugin to build beautiful members section in just few minutes!', 'tmb-string' ); ?></p>

			<h4><?php _e( 'Fields', 'tmb-string' ); ?></h4>
			<p><?php _e( 'Add photo, name, lastname, position, unlimited skills, about text and team member social networks', 'tmb-string' ); ?></p>

			<h4><?php _e( 'Customizable', 'tmb-string' ); ?></h4>
			<p><?php _e( 'You can change colors, and background of the TF Members section very easily from the available options.', 'tmb-string' ); ?></p>

			<h4 id="adn" style="color: green"><?php _e( 'Addons', 'tmb-string' ); ?></h4>
			<p>If you want even more controll and options, you can extend Team Members with available addons. </p>
			<a href="edit.php?post_type=tf_members&page=tf-addons" class="learn-more">Learn More</a>
		</div>
	</div>

</div>
