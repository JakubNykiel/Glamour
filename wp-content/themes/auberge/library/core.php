<?php
/**
 * A set of core functions.
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 *
 * Contents:
 *
 *   1) Required files
 *  10) Theme upgrade action
 *  20) Branding
 *  30) Post/page
 *  40) CSS functions
 * 100) Helpers
 */





/**
 * 1) Required files
 */

	// Theme Hook Alliance support

		require_once( trailingslashit( dirname( __FILE__ ) ) . 'hooks.php' );

	// Customizer generator

		require_once( trailingslashit( dirname( __FILE__ ) ) . 'customize.php' );

	// Admin required files

		if ( is_admin() ) {
			require_once( trailingslashit( dirname( __FILE__ ) ) . 'admin.php' );
		}





/**
 * 10) Theme upgrade action
 */

	/**
	 * Do action on theme version change
	 *
	 * @since    1.0
	 * @version  2.0
	 */
	if ( ! function_exists( 'wm_theme_upgrade' ) ) {
		function wm_theme_upgrade() {

			// Helper variables

				$current_theme_version = get_transient( 'glamour-version' );
				$new_theme_version     = wp_get_theme( get_template() )->get( 'Version' );


			// Processing

				if (
						empty( $current_theme_version )
						|| $new_theme_version != $current_theme_version
					) {

					do_action( 'wmhook_theme_upgrade', $current_theme_version, $new_theme_version );

					set_transient( 'glamour-version', $new_theme_version );

				}

		}
	} // /wm_theme_upgrade

	add_action( 'init', 'wm_theme_upgrade' );





/**
 * 20) Branding
 */

	/**
	 * Logo
	 *
	 * Accessibility rules applied.
	 *
	 * @link  http://blog.rrwd.nl/2014/11/21/html5-headings-in-wordpress-lets-fight/
	 *
	 * @since    1.0
	 * @version  2.0
	 */
	if ( ! function_exists( 'wm_logo' ) ) {
		function wm_logo( $container_class = 'site-branding' ) {

			// Helper variables

				$output = array();

				// @todo Remove `wp_title` with WordPress 4.6
				$document_title = ( 0 > version_compare( $GLOBALS['wp_version'], '4.4' ) ) ? ( wp_title( '|', false, 'right' ) ) : ( wp_get_document_title() ); // Since WordPress 4.4

				$custom_logo = get_theme_mod( 'custom_logo' ); // Since WordPress 4.5

				// If we don't get WordPress 4.5+ custom logo, try Jetpack Site Logo

					if ( empty( $custom_logo ) && function_exists( 'jetpack_get_site_logo' ) ) {
						$custom_logo = get_option( 'site_logo', array() );
						$custom_logo = ( isset( $custom_logo['id'] ) && $custom_logo['id'] ) ? ( absint( $custom_logo['id'] ) ) : ( false );
					}

				$blog_info = apply_filters( 'wmhook_wm_logo_blog_info', array(
						'name'        => trim( get_bloginfo( 'name' ) ),
						'description' => trim( get_bloginfo( 'description' ) ),
					), $container_class );

				$args = apply_filters( 'wmhook_wm_logo_args', array(
						'logo_image' => ( ! empty( $custom_logo ) ) ? ( $custom_logo ) : ( false ),
						'logo_type'  => 'text',
						'title_att'  => ( $blog_info['description'] ) ? ( $blog_info['name'] . ' | ' . $blog_info['description'] ) : ( $blog_info['name'] ),
						'url'        => home_url( '/' ),
						'container'  => $container_class,
					) );


			// Processing

				// Logo image

					if ( $args['logo_image'] ) {

						$img_id = ( is_numeric( $args['logo_image'] ) ) ? ( absint( $args['logo_image'] ) ) : ( wm_get_image_id_from_url( $args['logo_image'] ) );

						if ( $img_id ) {

							$atts = (array) apply_filters( 'wmhook_wm_logo_image_atts', array(
									'alt'   => esc_attr( sprintf( _x( '%s logo', 'Site logo image "alt" HTML attribute text.', 'glamour' ), $blog_info['name'] ) ),
									'title' => esc_attr( $args['title_att'] ),
									'class' => '',
								) );

							$args['logo_image'] = wp_get_attachment_image( absint( $img_id ), 'full', false, $atts );

						}

						$args['logo_type'] = 'img';

					}

					$args['logo_image'] = apply_filters( 'wmhook_wm_logo_logo_image', $args['logo_image'] );

				// Logo HTML

					$logo_class = apply_filters( 'wmhook_wm_logo_class', 'site-title logo type-' . $args['logo_type'], $args );

					if ( $args['container'] ) {
						$output[1] = '<div class="' . esc_attr( trim( $args['container'] ) ) . '">';
					}

						if ( is_front_page() ) {
							$output[10] = '<h1 id="site-title" class="' . esc_attr( $logo_class ) . '">';
						} else {
							$output[10] = '<h2 class="screen-reader-text">' . $document_title . '</h2>'; // To provide BODY heading on subpages
							$output[15] = '<a id="site-title" class="' . esc_attr( $logo_class ) . '" href="' . esc_url( $args['url'] ) . '" title="' . esc_attr( $args['title_att'] ) . '" rel="home">';
						}

							if ( 'text' === $args['logo_type'] ) {
								$output[30] = '<span class="text-logo">' . $blog_info['name'] . '</span>';
							} else {
								$output[30] = $args['logo_image'] . '<span class="screen-reader-text">' . $blog_info['name'] . '</span>';
							}

						if ( is_front_page() ) {
							$output[40] = '</h1>';
						} else {
							$output[40] = '</a>';
						}

							if ( $blog_info['description'] ) {
								$output[50] = '<div class="site-description">' . $blog_info['description'] . '</div>';
							}

					if ( $args['container'] ) {
						$output[100] = '</div>';
					}

					// Filter output array

						$output = (array) apply_filters( 'wmhook_wm_logo_output', $output, $args );

						ksort( $output );


			// Output

				echo implode( '', $output );

		}
	} // /wm_logo

	add_action( 'tha_header_top', 'wm_logo', 110, 0 );





/**
 * 30) Post/page
 */

	/**
	 * Table of contents from <!--nextpage--> tag
	 *
	 * Will create a table of content in multipage post from
	 * the first H2 heading in each post part.
	 * Appends the output at the top and bottom of post content.
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  string $content
	 */
	if ( ! function_exists( 'wm_nextpage_table_of_contents' ) ) {
		function wm_nextpage_table_of_contents( $content ) {

			// Helper variables

				global $page, $numpages, $multipage, $post;

				// Requirements check

					if (
							! $multipage
							|| ! is_singular()
						) {
						return $content;
					}

				$title_text = apply_filters( 'wmhook_wm_nextpage_table_of_contents_title_text', sprintf( esc_html_x( '"%s" table of contents', '%s: post title.', 'glamour' ), the_title_attribute( 'echo=0' ) ) );
				$title      = apply_filters( 'wmhook_wm_nextpage_table_of_contents_title', '<h2 class="screen-reader-text">' . $title_text . '</h2>' );

				$args = apply_filters( 'wmhook_wm_nextpage_table_of_contents_atts', array(
						'disable_first' => true, // First part to have a title of the post (part title won't be parsed)?
						'links'         => array(), // The output HTML links
						'post_content'  => ( isset( $post->post_content ) ) ? ( $post->post_content ) : ( '' ), // Get the whole post content
						'tag'           => 'h2', // HTML heading tag to parse as a post part title
					) );

				// Post part counter

					$i = 0;


			// Processing

				$args['post_content'] = explode( '<!--nextpage-->', (string) $args['post_content'] );

				// Get post parts titles

					foreach ( $args['post_content'] as $part ) {

						// Current post part number

							$i++;

						// Get title for post part

							if ( $args['disable_first'] && 1 === $i ) {

								$part_title = get_the_title();

							} else {

								preg_match( '/<' . tag_escape( $args['tag'] ) . '(.*?)>(.*?)<\/' . tag_escape( $args['tag'] ) . '>/', $part, $matches );

								if ( ! isset( $matches[2] ) || ! $matches[2] ) {
									$part_title = sprintf( esc_html__( 'Page %s', 'glamour' ), number_format_i18n( $i ) );
								} else {
									$part_title = $matches[2];
								}

							}

						// Set post part class

							if ( $page === $i ) {
								$class = ' class="current"';
							} elseif ( $page > $i ) {
								$class = ' class="passed"';
							} else {
								$class = '';
							}

						// Post part item output

							$args['links'][$i] = (string) apply_filters( 'wmhook_wm_nextpage_table_of_contents_part', '<li' . $class . '>' . _wp_link_page( $i ) . $part_title . '</a></li>', $i, $part_title, $class, $args );

					} // /foreach

				// Add table of contents into the post/page content

					$args['links'] = implode( '', $args['links'] );

					$links = apply_filters( 'wmhook_wm_nextpage_table_of_contents_links', array(
							// Display table of contents before the post content only in first post part
								'before' => ( 1 === $page ) ? ( '<div class="post-table-of-contents top" title="' . esc_attr( strip_tags( $title_text ) ) . '">' . $title . '<ol>' . $args['links'] . '</ol></div>' ) : ( '' ),
							// Display table of cotnnets after the post cotnent on each post part
								'after'  => '<div class="post-table-of-contents bottom" title="' . esc_attr( strip_tags( $title_text ) ) . '">' . $title . '<ol>' . $args['links'] . '</ol></div>',
						), $args );

					$content = $links['before'] . $content . $links['after'];


			// Output

				return $content;

		}
	} // /wm_nextpage_table_of_contents

	add_filter( 'the_content', 'wm_nextpage_table_of_contents', 10 );



		/**
		 * Parted post navigation
		 *
		 * Shim for passing the Theme Check review.
		 * Using table of contents generator instead.
		 *
		 * @since    1.0
		 * @version  2.0
		 */
		if ( ! function_exists( 'wm_link_pages_shim' ) ) {
			function wm_link_pages_shim() {

				// Processing

					wp_link_pages();

			}
		} // /wm_link_pages_shim



	/**
	 * Post meta info
	 *
	 * hAtom microformats compatible. @link http://goo.gl/LHi4Dy
	 * Supports WP ULike plugin. @link https://wordpress.org/plugins/wp-ulike/
	 * Supports ZillaLikes plugin. @link http://www.themezilla.com/plugins/zillalikes/
	 * Supports Post Views Count plugin. @link https://wordpress.org/plugins/baw-post-views-count/
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  array $args
	 */
	if ( ! function_exists( 'wm_post_meta' ) ) {
		function wm_post_meta( $args = array() ) {

			// Helper variables

				$output = '';

				$args = wp_parse_args( $args, apply_filters( 'wmhook_wm_post_meta_defaults', array(
						'class'       => 'entry-meta',
						'container'   => 'div',
						'date_format' => null,
						'html'        => '<span class="{class}"{attributes}>{description}{content}</span> ',
						'html_custom' => array(), // Example: array( 'date' => 'CUSTOM_HTML_WITH_{class}_{attributes}_{description}_AND_{content}_HERE' )
						'meta'        => array(), // Example: array( 'date', 'author', 'category', 'comments', 'permalink' )
						'post_id'     => null,
					) ) );
				$args = apply_filters( 'wmhook_wm_post_meta_args', $args );

				$args['meta'] = array_filter( (array) $args['meta'] );

				if ( $args['post_id'] ) {
					$args['post_id'] = absint( $args['post_id'] );
				}


			// Requirements check

				if ( empty( $args['meta'] ) ) {
					return;
				}


			// Processing

				foreach ( $args['meta'] as $meta ) {

						$helper = '';

						$replacements  = (array) apply_filters( 'wmhook_wm_post_meta_replacements', array(), $meta, $args );
						$output_single = apply_filters( 'wmhook_wm_post_meta', '', $meta, $args );
						$output       .= $output_single;

					// Predefined metas

						switch ( $meta ) {

							case 'category':

								if (
										apply_filters( 'wmhook_wm_post_meta_enable_' . $meta, true, $args )
										&& wm_is_categorized_blog()
										&& ( $helper = get_the_category_list( ', ', '', $args['post_id'] ) )
									) {
									$replacements = array(
											'{attributes}'  => '',
											'{class}'       => esc_attr( 'cat-links entry-meta-element' ),
											'{description}' => '<span class="entry-meta-description">' . esc_html_x( 'Categorized in:', 'Post meta info description: categories list.', 'glamour' ) . ' </span>',
											'{content}'     => $helper,
										);
								}

							break;
							
							case 'likes':

								if ( apply_filters( 'wmhook_wm_post_meta_enable_' . $meta, true, $args ) ) {

									if ( function_exists( 'wp_ulike' ) ) {
									// WP ULike first

										$replacements = array(
												'{attributes}'  => '',
												'{class}'       => esc_attr( 'entry-likes entry-meta-element' ),
												'{description}' => '',
												'{content}'     => wp_ulike( 'put' ),
											);

									} elseif ( function_exists( 'zilla_likes' ) ) {
									// ZillaLikes after

										global $zilla_likes;

										$replacements = array(
												'{attributes}'  => '',
												'{class}'       => esc_attr( 'entry-likes entry-meta-element' ),
												'{description}' => '',
												'{content}'     => $zilla_likes->do_likes(),
											);

									}

								}

							break;
							case 'permalink':

								if ( apply_filters( 'wmhook_wm_post_meta_enable_' . $meta, true, $args ) ) {
									$the_title_attribute_args = array( 'echo' => false );
									if ( $args['post_id'] ) {
										$the_title_attribute_args['post'] = $args['post_id'];
									}

									$replacements = array(
											'{attributes}'  => ( function_exists( 'wm_schema_org' ) ) ? ( wm_schema_org( 'url' ) ) : ( '' ),
											'{class}'       => esc_attr( 'entry-permalink entry-meta-element' ),
											'{description}' => '<span class="entry-meta-description">' . esc_html_x( 'Bookmark link:', 'Post meta info description: post bookmark link.', 'glamour' ) . ' </span>',
											'{content}'     => '<a href="' . esc_url( get_permalink( $args['post_id'] ) ) . '" title="' . esc_attr( sprintf( esc_html__( 'Permalink to "%s"', 'glamour' ), the_title_attribute( $the_title_attribute_args ) ) ) . '" rel="bookmark"><span>' . get_the_title( $args['post_id'] ) . '</span></a>',
										);
								}

							break;
							case 'tags':

								if (
										apply_filters( 'wmhook_wm_post_meta_enable_' . $meta, true, $args )
										&& ( $helper = get_the_tag_list( '', ' ', '', $args['post_id'] ) )
									) {
									$replacements = array(
											'{attributes}'  => ( function_exists( 'wm_schema_org' ) ) ? ( wm_schema_org( 'keywords' ) ) : ( '' ),
											'{class}'       => esc_attr( 'tags-links entry-meta-element' ),
											'{description}' => '<span class="entry-meta-description">' . esc_html_x( 'Tagged as:', 'Post meta info description: tags list.', 'glamour' ) . ' </span>',
											'{content}'     => $helper,
										);
								}

							break;
							case 'views':

								if (
										apply_filters( 'wmhook_wm_post_meta_enable_' . $meta, true, $args )
										&& function_exists( 'bawpvc_views_sc' )
										&& ( $helper = bawpvc_views_sc( array() ) )
									) {
									$replacements = array(
											'{attributes}'  => ' title="' . esc_attr__( 'Views count', 'glamour' ) . '"',
											'{class}'       => esc_attr( 'entry-views entry-meta-element' ),
											'{description}' => '',
											'{content}'     => wp_strip_all_tags( $helper ),
										);
								}

							break;

							default:
							break;

						} // /switch

						// Single meta output

							$replacements = (array) apply_filters( 'wmhook_wm_post_meta_replacements_' . $meta, $replacements, $args );

							if (
									empty( $output_single )
									&& ! empty( $replacements )
								) {

								if ( isset( $args['html_custom'][ $meta ] ) ) {
									$output .= strtr( $args['html_custom'][ $meta ], (array) $replacements );
								} else {
									$output .= strtr( $args['html'], (array) $replacements );
								}

							}

				} // /foreach

				if ( $output ) {
					$output = '<' . tag_escape( $args['container'] ) . ' class="' . esc_attr( $args['class'] ) . '">' . $output . '</' . tag_escape( $args['container'] ) . '>';
				}


			// Output

				return $output;

		}
	} // /wm_post_meta



	/**
	 * Paginated heading suffix
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  string $tag           Wrapper tag
	 * @param  string $singular_only Display only on singular posts of specific type
	 */
	if ( ! function_exists( 'wm_paginated_suffix' ) ) {
		function wm_paginated_suffix( $tag = '', $singular_only = false ) {

			// Requirements check

				if (
						$singular_only
						&& ! is_singular( $singular_only )
					) {
					return;
				}


			// Helper variables

				global $page, $paged;

				$output = '';

				if ( ! isset( $paged ) ) {
					$paged = 0;
				}
				if ( ! isset( $page ) ) {
					$page = 0;
				}

				$paged = max( $page, $paged );

				$tag = trim( $tag );
				if ( $tag ) {
					$tag = array( '<' . tag_escape( $tag ) . '>', '</' . tag_escape( $tag ) . '>' );
				} else {
					$tag = array( '', '' );
				}


			// Processing

				if ( 1 < $paged ) {
					$output = ' ' . $tag[0] . sprintf( esc_html_x( '(page %s)', 'Paginated content title suffix, %s: page number.', 'glamour' ), number_format_i18n( $paged ) ) . $tag[1];
				}


			// Output

				return apply_filters( 'wmhook_wm_paginated_suffix_output', $output );

		}
	} // /wm_paginated_suffix



	/**
	 * Checks for <!--more--> tag in post content
	 *
	 * @since    1.0
	 * @version  1.0
	 *
	 * @param  obj/absint $post
	 */
	if ( ! function_exists( 'wm_has_more_tag' ) ) {
		function wm_has_more_tag( $post = null ) {

			// Helper variables

				if ( empty( $post ) ) {
					global $post;
				} elseif ( is_numeric( $post ) ) {
					$post = get_post( absint( $post ) );
				}


			// Requirements check

				if (
						! is_object( $post )
						|| ! isset( $post->post_content )
					) {
					return;
				}


			// Output

				return strpos( $post->post_content, '<!--more-->' );

		}
	} // /wm_has_more_tag





/**
 * 40) CSS functions
 */

	// Escape inline CSS

		add_filter( 'wmhook_esc_css', 'wp_strip_all_tags' );
		add_filter( 'wmhook_esc_css', 'wm_fix_ssl_urls' );



	/**
	 * Outputs URL to the specific file
	 *
	 * This function looks for the file in the child theme first.
	 * If the file is not located in child theme, output the URL from parent theme.
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param   string $file_relative_path File to look for (insert the relative path within the theme folder)
	 *
	 * @return  string Actual URL to the file
	 */
	if ( ! function_exists( 'wm_get_stylesheet_directory_uri' ) ) {
		function wm_get_stylesheet_directory_uri( $file_relative_path ) {

			// Helper variables

				$output = '';

				$file_relative_path = trim( $file_relative_path );


			// Requirements chek

				if ( ! $file_relative_path ) {
					return;
				}


			// Processing

				if ( file_exists( trailingslashit( get_stylesheet_directory() ) . $file_relative_path ) ) {
					$output = trailingslashit( get_stylesheet_directory_uri() ) . $file_relative_path;
				} else {
					$output = trailingslashit( get_template_directory_uri() ) . $file_relative_path;
				}


			// Output

				return apply_filters( 'wmhook_wm_get_stylesheet_directory_uri_output', esc_url( $output ), $file_relative_path );

		}
	} // /wm_get_stylesheet_directory_uri





/**
 * 100) Helpers
 */

	/**
	 * Check WordPress version
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  float $version
	 */
	if ( ! function_exists( 'wm_check_wp_version' ) ) {
		function wm_check_wp_version( $version = 4.3 ) {

			// Output

				return apply_filters( 'wmhook_wm_check_wp_version_output', version_compare( $GLOBALS['wp_version'], $version, '>=' ), $version, $GLOBALS['wp_version'] );

		}
	} // /wm_check_wp_version



	/**
	 * Fixing URLs in `is_ssl()` returns TRUE
	 *
	 * @since    2.0
	 * @version  2.0
	 *
	 * @param  string $content
	 */
	if ( ! function_exists( 'wm_fix_ssl_urls' ) ) {
		function wm_fix_ssl_urls( $content ) {

			// Processing

				if ( is_ssl() ) {
					$content = str_ireplace( 'http:', 'https:', $content );
					$content = str_ireplace( 'xmlns="https:', 'xmlns="http:', $content );
					$content = str_ireplace( "xmlns='https:", "xmlns='http:", $content );
				}


			// Output

				return $content;

		}
	} // /wm_fix_ssl_urls



	/**
	 * Remove shortcodes from string
	 *
	 * This function keeps the text between shortcodes,
	 * unlike WordPress native strip_shortcodes() function.
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  string $content
	 */
	if ( ! function_exists( 'wm_remove_shortcodes' ) ) {
		function wm_remove_shortcodes( $content ) {

			// Output

				return preg_replace( '|\[(.+?)\]|s', '', $content );

		}
	} // /wm_remove_shortcodes

	add_filter( 'the_excerpt', 'wm_remove_shortcodes', 10 );



	/**
	 * HTML in widget titles
	 *
	 * Just replace the "<" and ">" in HTML tag with "[" and "]".
	 * Examples:
	 * "[em][/em]" will output "<em></em>"
	 * "[br /]" will output "<br />"
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  string $title
	 */
	if ( ! function_exists( 'wm_html_widget_title' ) ) {
		function wm_html_widget_title( $title ) {

			// Helper variables

				$replacements = array(
					'[' => '<',
					']' => '>',
				);

				$allowed_tags = array(
						'a'      => array( 'href' => array() ),
						'abbr'   => array(),
						'br'     => array(),
						'code'   => array(),
						'del'    => array(),
						'em'     => array(),
						'ins'    => array(),
						'mark'   => array(),
						'q'      => array(),
						's'      => array(),
						'small'  => array(),
						'span'   => array( 'class' => array() ),
						'strong' => array(),
						'sub'    => array(),
						'sup'    => array(),
					);


			// Output

				return wp_kses( strtr( $title, $replacements ), $allowed_tags );

		}
	} // /wm_html_widget_title

	remove_filter( 'widget_title', 'esc_html' );

	add_filter( 'widget_title', 'wm_html_widget_title' );
	add_filter( 'widget_text',  'do_shortcode'         );



	/**
	 * Accessibility skip links
	 *
	 * @since    2.0
	 * @version  2.0
	 *
	 * @param  string $id     Link target element ID.
	 * @param  string $text   Link text.
	 * @param  string $class  Additional link CSS classes.
	 */
	if ( ! function_exists( 'wm_link_skip_to' ) ) {
		function wm_link_skip_to( $id = 'content', $text = '', $class = '' ) {

			// Helper variables

				if ( empty( $text ) ) {
					$text = esc_html__( 'Skip to content', 'glamour' );
				}


			// Output

				return apply_filters( 'wmhook_wm_link_skip_to_output', '<a class="' . esc_attr( trim( 'skip-link screen-reader-text ' . $class ) ) . '" href="#' . esc_attr( trim( $id ) ) . '">' . esc_html( $text ) . '</a>' );

		}
	} // /wm_link_skip_to



	/**
	 * Get image ID from its URL
	 *
	 * @link   http://pippinsplugins.com/retrieve-attachment-id-from-image-url/
	 * @link   http://make.wordpress.org/core/2012/12/12/php-warning-missing-argument-2-for-wpdb-prepare/
	 *
	 * @since    1.0
	 * @version  2.0
	 *
	 * @param  string $url
	 */
	if ( ! function_exists( 'wm_get_image_id_from_url' ) ) {
		function wm_get_image_id_from_url( $url ) {

			// Helper variables

				global $wpdb;

				$output = null;

				$cache = array_filter( (array) get_transient( 'wm-image-ids' ) );


			// Return cached result if found and relevant

				if (
						! empty( $cache )
						&& isset( $cache[ $url ] )
						&& wp_get_attachment_url( absint( $cache[ $url ] ) )
						&& $url == wp_get_attachment_url( absint( $cache[ $url ] ) )
					) {

					return absint( apply_filters( 'wmhook_wm_get_image_id_from_url_output', $cache[ $url ] ) );

				}


			// Processing

				if (
						is_object( $wpdb )
						&& isset( $wpdb->posts )
					) {

					$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT id FROM $wpdb->posts WHERE guid='%s'", esc_url( $url ) ) );

					$output = ( isset( $attachment[0] ) ) ? ( $attachment[0] ) : ( null );

				}

				// Cache the new record

					$cache[ $url ] = $output;

					set_transient( 'wm-image-ids', array_filter( (array) $cache ) );


			// Output

				return absint( apply_filters( 'wmhook_wm_get_image_id_from_url_output', $output ) );

		}
	} // /wm_get_image_id_from_url



		/**
		 * Flush out the transients used in wm_get_image_id_from_url
		 *
		 * @since    1.0
		 * @version  1.0
		 */
		if ( ! function_exists( 'wm_image_ids_transient_flusher' ) ) {
			function wm_image_ids_transient_flusher() {

				// Processing

					delete_transient( 'wm-image-ids' );

			}
		} // /wm_image_ids_transient_flusher

		add_action( 'switch_theme', 'wm_image_ids_transient_flusher' );



	/**
	 * Returns true if a blog has more than 1 category
	 *
	 * @since    1.0
	 * @version  1.0
	 */
	if ( ! function_exists( 'wm_is_categorized_blog' ) ) {
		function wm_is_categorized_blog() {

			// Processing

				if ( false === ( $all_the_cool_cats = get_transient( 'wm-all-categories' ) ) ) {

					// Create an array of all the categories that are attached to posts

						$all_the_cool_cats = get_categories( array(
								'fields'     => 'ids',
								'hide_empty' => 1,
								'number'     => 2, //we only need to know if there is more than one category
							) );

					// Count the number of categories that are attached to the posts

						$all_the_cool_cats = count( $all_the_cool_cats );

					set_transient( 'wm-all-categories', $all_the_cool_cats );

				}


			// Output

				if ( $all_the_cool_cats > 1 ) {

					// This blog has more than 1 category

						return true;

				} else {

					// This blog has only 1 category

						return false;

				}

		}
	} // /wm_is_categorized_blog



		/**
		 * Flush out the transients used in wm_is_categorized_blog
		 */
		if ( ! function_exists( 'wm_all_categories_transient_flusher' ) ) {
			function wm_all_categories_transient_flusher() {

				// Requirements check

					if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
						return;
					}

				// Processing

					// Like, beat it. Dig?

						delete_transient( 'wm-all-categories' );

			}
		} // /wm_all_categories_transient_flusher

		add_action( 'edit_category', 'wm_all_categories_transient_flusher' );
		add_action( 'save_post',     'wm_all_categories_transient_flusher' );
