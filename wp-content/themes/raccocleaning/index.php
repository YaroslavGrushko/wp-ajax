<?php
/*
 * The main template file
 * 
 * @package YVG
 */

 get_header();
 ?>
<main class="container">
	<?php 
		if( have_posts() ):

			// content
			while( have_posts() ): the_post();
				the_excerpt();
			endwhile;

			// pagination
			the_posts_pagination( array(
				'prev_text'		=> esc_html__('Previous', 'yvg'),
				'next_text'		=> esc_html__('Next', 'yvg'),
			));
		else:
	?>
	<p><?php esc_html_e('No content.', 'yvgraccocleaning'); ?></p>
	<?php endif; ?>
</main>
<?php get_footer(); ?>