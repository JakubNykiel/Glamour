<?php
/**
 * Custom page template
 *
 * Template Name: Fullwidth page
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 */

/* translators: Custom page template name. */
__( 'Fullwidth page', 'glamour' );





get_header();

	while ( have_posts() ) : the_post();
	echo do_shortcode("[metaslider id=111]");
		get_template_part( 'template-parts/content', 'page' );

	endwhile;

get_footer();
