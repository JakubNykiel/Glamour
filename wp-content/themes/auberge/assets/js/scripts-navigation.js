/**
 * Accessible navigation
 *
 * @link  http://a11yproject.com/
 * @link  https://codeable.io/community/wordpress-accessibility-creating-accessible-dropdown-menus/
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.4.8
 * @version  2.0
 *
 * Contents:
 *
 * 10) Accessibility
 * 20) Mobile navigation
 */





jQuery( function() {





	/**
	 * Cache
	 */

		var $glamourSiteNavigation   = jQuery( document.getElementById( 'site-navigation' ) ),
		    $glamourSiteMenuPrimary  = jQuery( document.getElementById( 'menu-primary' ) ),
		    $glamourMenuToggleButton = jQuery( document.getElementById( 'menu-toggle' ) );





	/**
	 * 10) Accessibility
	 */

		/**
		 * Adding ARIA attributes
		 */

			$glamourSiteNavigation
				.find( 'li' )
					.attr( 'role', 'menuitem' );

			$glamourSiteNavigation
				.find( '.menu-item-has-children' )
					.attr( 'aria-haspopup', 'true' );

			$glamourSiteNavigation
				.find( '.sub-menu' )
					.attr( 'role', 'menu' );



		/**
		 * Setting `.focus` class for menu parent
		 */

			$glamourSiteNavigation
				.on( 'focus.aria mouseenter.aria', '.menu-item-has-children', function ( e ) {

					// Processing

						jQuery( e.currentTarget )
							.addClass( 'focus' );

				} );

			$glamourSiteNavigation
				.on( 'blur.aria mouseleave.aria', '.menu-item-has-children', function ( e ) {

					// Processing

						jQuery( e.currentTarget )
							.removeClass( 'focus' );

				} );



		/**
		 * Touch-enabled
		 */

			$glamourSiteNavigation
				.on( 'touchstart click', '.menu-item-has-children > a .expander', function( e ) {

					// Helper variables

						var $this = jQuery( this ).parent().parent(); // Get the LI element


					// Processing

						e.preventDefault();

						$this
							.toggleClass( 'focus' )
							.siblings()
								.removeClass( 'focus' );

				} );



		/**
		 * Menu navigation with arrow keys
		 */

			$glamourSiteNavigation
				.on( 'keydown', 'a', function( e ) {

					// Helper variables

						var $this = jQuery( this );


					// Processing

						if ( e.which === 37 ) {

							// Left key

								e.preventDefault();

								$this
									.parent()
									.prev()
										.children( 'a' )
											.focus();

						} else if ( e.which === 39 ) {

							// Right key

								e.preventDefault();

								$this
									.parent()
									.next()
										.children( 'a' )
											.focus();

						} else if ( e.which === 40 ) {

							// Down key

								e.preventDefault();

								if ( $this.next().length ) {

									$this
										.next()
											.find( 'li:first-child a' )
											.first()
												.focus();

								} else {

									$this
										.parent()
										.next()
											.children( 'a' )
												.focus();

								}

						} else if ( e.which === 38 ) {

							// Up key

								e.preventDefault();

								if ( $this.parent().prev().length ) {

									$this
										.parent()
										.prev()
											.children( 'a' )
												.focus();

								} else {

									$this
										.parents( 'ul' )
										.first()
										.prev( 'a' )
											.focus();

								}

						}

				} );





	/**
	 * 20) Mobile navigation
	 */

		/**
		 * Mobile navigation
		 */

			/**
			 * Mobile menu actions
			 */
			function glamourMobileMenuActions() {

				// Processing

					if ( ! $glamourSiteNavigation.hasClass( 'active' ) ) {

						$glamourSiteMenuPrimary
							.attr( 'aria-hidden', 'true' );

						$glamourMenuToggleButton
							.attr( 'aria-expanded', 'false' );

					}

					$glamourSiteNavigation
						.on( 'keydown', function( e ) {

							// Processing

								if ( e.which === 27 ) {

									// ESC key

										e.preventDefault();

										$glamourSiteNavigation
											.removeClass( 'active' );

										$glamourSiteMenuPrimary
											.attr( 'aria-hidden', 'true' );

										$glamourMenuToggleButton
											.focus();

								}

						} );

			} // /glamourMobileMenuActions

			// Default mobile menu setup

				if ( 880 >= window.innerWidth ) {

					$glamourSiteNavigation
						.removeClass( 'active' );

					glamourMobileMenuActions();

				}

			// Clicking the menu toggle button

				$glamourMenuToggleButton
					.on( 'click', function( e ) {

						// Processing

							e.preventDefault();

							$glamourSiteNavigation
								.toggleClass( 'active' );

							if ( $glamourSiteNavigation.hasClass( 'active' ) ) {

								$glamourSiteMenuPrimary
									.attr( 'aria-hidden', 'false' );

								$glamourMenuToggleButton
									.attr( 'aria-expanded', 'true' );

								jQuery( 'html, body' )
									.stop()
									.animate( { scrollTop : '0px' }, 0 );

							} else {

								$glamourSiteMenuPrimary
									.attr( 'aria-hidden', 'true' );

								$glamourMenuToggleButton
									.attr( 'aria-expanded', 'false' );

							}

					} );

			// Refocus to menu toggle button once the end of the menu is reached

				$glamourSiteNavigation
					.on( 'focus.aria', '.menu-toggle-skip-link', function( e ) {

						// Processing

							$glamourMenuToggleButton
								.focus();

					} );

			// Disable mobile navigation on wider screens

				jQuery( window )
					.on( 'resize orientationchange', function( e ) {

						// Processing

							if ( 880 < window.innerWidth ) {

								// On desktops

								$glamourSiteNavigation
									.removeClass( 'active' );

								$glamourSiteMenuPrimary
									.attr( 'aria-hidden', 'false' );

								$glamourMenuToggleButton
									.attr( 'aria-expanded', 'true' );

							} else {

								// On mobiles

								glamourMobileMenuActions();

							}

					} );





} );
