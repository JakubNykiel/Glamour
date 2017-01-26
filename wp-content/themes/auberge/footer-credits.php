<?php
/**
 * Theme credits
 *
 * It is completely optional, but if you like this WordPress theme,
 * I would appreciate it if you keep the credit link in the footer.
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    2.0
 * @version  2.0
 */





/**
 * Helper variables
 */

	$custom_credits = (string) apply_filters( 'wmhook_wm_credits_output', '' );



/**
 * Requirements check
 */

	if ( '-' === $custom_credits ) {
		return;
	}



?>

<div class="site-footer-area footer-area-site-info">
	<div class="site-info-container">

		<div class="site-info" role="contentinfo">
			<?php if ( empty( $custom_credits ) ) : ?>

				&copy; <?php echo date( 'Y' ); ?> <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
				
				<span class="sep"> | </span>
				<a href="#top" id="back-to-top" class="back-to-top"><?php esc_html_e( 'Do góry &uarr;', 'glamour' ); ?></a>

			<?php else : ?>

				<?php

				// This is already validated/sanitized when stored in customizer (with wp_kses_post())

					echo $custom_credits;

				?>

			<?php endif; ?>
		</div>

		<?php get_template_part( 'template-parts/menu', 'social' ); ?>

	</div>
</div>
