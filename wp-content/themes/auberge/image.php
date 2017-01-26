<?php
/**
 * Image attachment template
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 */





get_header();

	while ( have_posts() ) : the_post();

		get_template_part( 'template-parts/content', 'attachment-image' );

	endwhile;

get_footer();
