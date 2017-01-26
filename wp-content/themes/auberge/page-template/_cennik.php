<?php
/**
 * Custom page template
 *
 * Template Name: Cennik page
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

		get_template_part( 'template-parts/content', 'page' );
		?>

		<div class="posts">
			<article class="entry">  
				<header class="entry-header">
					<h2 class="entry-title">
						Oświęcim
					</h2>
				</header>
			</article>
		<div>

		<div class="posts">
			<article class="entry">  
				<header class="entry-header">
					<h2 class="entry-title">
						Jaworzno
					</h2>
				</header>
			</article>
		<div>


	<?php endwhile;

get_footer();