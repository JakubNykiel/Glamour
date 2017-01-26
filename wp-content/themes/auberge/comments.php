<?php
/**
 * Comments list template
 *
 * @package    glamour
 * @copyright  WebMan Design, Oliver Juhas
 *
 * @since    1.0
 * @version  2.0
 */





/**
 * Return early without loading comments if:
 * - the current post is protected by a password and the visitor has not yet entered the password
 * - the page is a front page
 * - we are not on single post page
 * - comments are closed or we have have no comments to display (even if the comments are closed now, there could be some old ones)
 * - post type doesn't support comments
 */
if (
		post_password_required()
		|| ( is_page() && is_front_page() )
		|| ! ( is_page( get_the_ID() ) || is_single( get_the_ID() ) )
		|| ! ( comments_open() || have_comments() )
		|| ! post_type_supports( get_post_type(), 'comments' )
	) {
	return;
}





do_action( 'tha_comments_before' );

?>

<?php

do_action( 'tha_comments_after' );
