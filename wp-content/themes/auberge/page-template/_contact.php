<?php
/**
 * Custom page template
 *
 * Template Name: Kontakt page
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


		?>
		<header class="entry-header">
						<h1 class="entry-title">
							<?php echo get_the_title(get_the_ID()) ?>
						</h1>
		</header>
		<div class="contact-box">
			<div class="posts">
				<article class="entry left-box">
					<header class="entry-header">
						<h2 class="entry-title">
							Oświęcim
						</h2>
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2562.5579230394856!2d19.21850931561075!3d50.03837897942067!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x471695713aacb979%3A0x77acd0d574a5bbb2!2sStudio+Urody+Glamour!5e0!3m2!1spl!2spl!4v1485519436368" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
						<div class="content-contact">
							poniedziałek-piątek: 9 - 19 <br>
							sobota: 9 - 14 <br>
							Rynek Główny 18 <br>
							32-600, Oświęcim <br>
							tel: 693-360-661
						</div>
					</header>
				</article>
			</div>

			<div class="posts">
				<article class="entry right-box">
					<header class="entry-header">
						<h2 class="entry-title">
							Jaworzno
						</h2>
						<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2553.6503726095816!2d19.265670215617153!3d50.20506587944229!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x4716c2fac864c489%3A0x39bcca7e433cf7de!2sStudio+Urody+Glamour!5e0!3m2!1spl!2spl!4v1485520193988" width="400" height="300" frameborder="0" style="border:0" allowfullscreen></iframe>
						<div class="content-contact">
							poniedziałek-piątek: 9 - 19 <br>
							sobota: 9 - 14 <br>
							Grunwaldzka 83 <br>
							43-600, Jaworzno <br>
							tel: 725-360-661
						</div>
					</header>
				</article>
			</div>

			<div class="posts">

					<?php get_template_part( 'template-parts/content', 'page' ); ?>

			</div>
		</div>


	<?php endwhile;

get_footer();
