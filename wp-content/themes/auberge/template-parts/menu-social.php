<?php
/**
 * Social links menu template
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 */





// Helper variables

	$glamour_social_menu_id = rand( 10, 99 );



if ( has_nav_menu( 'social' ) ) :

	?>

	<nav class="social-links" role="navigation" aria-labelledby="social-links-label-<?php echo absint( $glamour_social_menu_id ); ?>">

		<h2 class="screen-reader-text" id="social-links-label-<?php echo absint( $glamour_social_menu_id ); ?>"><?php esc_html_e( 'Social Menu', 'glamour' ); ?></h2>

		<?php

		wp_nav_menu( array(
				'theme_location' => 'social',
				'container'      => false,
				'menu_class'     => 'social-links-items',
				'depth'          => 1,
				'link_before'    => '<span class="screen-reader-text">',
				'link_after'     => '</span>',
				'fallback_cb'    => false,
			) );

		?>

	</nav>

	<?php

endif;
