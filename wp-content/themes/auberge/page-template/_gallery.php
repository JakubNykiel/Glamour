<?php
/**
 * Custom page template
 *
 * Template Name: Gallery page
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 */

/* translators: Custom page template name. */
__( 'Gallery page', 'glamour' );





get_header();

	while ( have_posts() ) : the_post();
		get_template_part( 'template-parts/content', 'page' );

	endwhile;

get_footer();