<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Comments template.
 *
 * @package Stylena
 */


if ( post_password_required() ) {
	return;
}

$stylena_design = new \Stylena\StylenaDesign();
?>
<section id="comments" class="comments-area">
	<?php if ( have_comments() ) { ?>
		<h2 class="comments-title">
			<?php
			$stylena_comments_number = get_comments_number();
			if ( '1' === strval( $stylena_comments_number ) ) {
				/* translators: %s: The post title */
				printf( esc_html_x( 'One comment on &ldquo;%s&rdquo;', 'comments title', 'stylena' ), esc_html( get_the_title() ) );
			} else {
				printf(
					/* translators: %1$s: Number of comments, %2$s: Post title. */
					esc_html(
						_nx(
							'%1$s comment on &ldquo;%2$s&rdquo;',
							'%1$s comments on &ldquo;%2$s&rdquo;',
							$stylena_comments_number,
							'comments title',
							'stylena'
						)
					),
					esc_html( number_format_i18n( $stylena_comments_number ) ),
					'<span>' . esc_html( get_the_title() ) . '</span>'
				);
			}
			unset( $stylena_comments_number );
			?>
		</h2>

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through.
			?>
			<h3 class="screen-reader-text sr-only"><?php esc_html_e( 'Comment navigation', 'stylena' ); ?></h3>
			<nav id="comment-nav-above" class="comment-navigation clearfix" aria-label="<?php esc_attr_e( 'Comments', 'stylena' ); ?>"><ul class="list-unstyled m-0">
				<li class="nav-previous previous"><?php previous_comments_link( __( '&larr; Older Comments', 'stylena' ) ); ?></li>
				<li class="nav-next next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'stylena' ) ); ?></li>
			</ul></nav><!-- #comment-nav-above -->
		<?php } // check for comment navigation
		?>

		<ul class="list-unstyled media-list">
			<?php
			/**
			 * Loop through and list the comments. Tell wp_list_comments()
			 * to use $stylena_design->display_comments() to format the comments.
			 * If you want to override this in a child theme, then you can
			 * define display_comments() method and StylenaDesign class and that will be used instead.
			 * See display_comments() in inc/classes/StylenaDesign.php for more.
			 */
			wp_list_comments(
				array(
					'avatar_size' => '64',
					'callback'    => array( $stylena_design, 'display_comments' ),
				)
			);
			?>
		</ul><!-- .comment-list -->

		<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) { // are there comments to navigate through.
			?>
			<h3 class="screen-reader-text sr-only"><?php esc_html_e( 'Comment navigation', 'stylena' ); ?></h3>
			<nav id="comment-nav-below" class="comment-navigation comment-navigation-below clearfix" aria-label="<?php esc_attr_e( 'Comments', 'stylena' ); ?>"><ul class="list-unstyled m-0">
				<li class="nav-previous previous"><?php previous_comments_link( __( '&larr; Older Comments', 'stylena' ) ); ?></li>
				<li class="nav-next next"><?php next_comments_link( __( 'Newer Comments &rarr;', 'stylena' ) ); ?></li>
			</ul></nav><!-- #comment-nav-below -->
		<?php } // check for comment navigation
		?>

	<?php } // have_comments()
	?>

	<?php
	// If comments are closed and there are comments, let's leave a little note, shall we?
	if ( ! comments_open() && '0' !== strval( get_comments_number() ) && post_type_supports( get_post_type(), 'comments' ) ) { ?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'stylena' ); ?></p>
		<?php
	} //endif;
	?>

	<?php
	$stylena_req      = get_option( 'require_name_email' );
	$stylena_aria_req = ( $stylena_req ? " aria-required='true'" : '' );
	$stylena_html5    = true;

	// re-format comment allowed tags.
	$stylena_comment_allowedtags           = allowed_tags();
	$stylena_comment_allowedtags           = str_replace( array( "\r\n", "\r", "\n" ), '', $stylena_comment_allowedtags );
	$stylena_comment_allowedtags_array     = explode( '&gt; &lt;', $stylena_comment_allowedtags );
	$stylena_formatted_comment_allowedtags = '';
	foreach ( $stylena_comment_allowedtags_array as $stylena_item ) {
		$stylena_formatted_comment_allowedtags .= '<code>';

		if ( $stylena_comment_allowedtags_array[0] !== $stylena_item ) {
			$stylena_formatted_comment_allowedtags .= '&lt;';
		}

		$stylena_formatted_comment_allowedtags .= $stylena_item;

		if ( end( $stylena_comment_allowedtags_array ) !== $stylena_item ) {
			$stylena_formatted_comment_allowedtags .= '&gt;';
		}

		$stylena_formatted_comment_allowedtags .= '</code> ';
	}
	$stylena_comment_allowed_tags = $stylena_formatted_comment_allowedtags;
	unset( $stylena_comment_allowedtags, $stylena_comment_allowedtags_array, $stylena_formatted_comment_allowedtags );

	comment_form(
		array(
			'class_submit'        => 'btn btn-primary',
			'fields'              => array(
				'author' => '<div class="form-group row">' .
					'<label class="col-form-label col-md-2" for="author">' . __( 'Name', 'stylena' ) . ( $stylena_req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					'<div class="col-md-10">' .
					'<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $stylena_aria_req . ' class="form-control" />' .
					'</div>' .
					'</div>',
				'email'  => '<div class="form-group row">' .
					'<label class="col-form-label col-md-2" for="email">' . __( 'Email', 'stylena' ) . ( $stylena_req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
					'<div class="col-md-10">' .
					'<input id="email" name="email" ' . ( $stylena_html5 ? 'type="email"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_email'] ) . '" size="30"' . $stylena_aria_req . ' class="form-control" />' .
					'</div>' .
					'</div>',
				'url'    => '<div class="form-group row">' .
					'<label class="col-form-label col-md-2" for="url">' . __( 'Website', 'stylena' ) . '</label> ' .
					'<div class="col-md-10">' .
					'<input id="url" name="url" ' . ( $stylena_html5 ? 'type="url"' : 'type="text"' ) . ' value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" class="form-control" />' .
					'</div>' .
					'</div>',
			),
			'comment_field'       => '<div class="form-group row">' .
				'<label class="col-form-label col-md-2" for="comment">' . __( 'Comment', 'stylena' ) . '</label> ' .
				'<div class="col-md-10">' .
				'<textarea id="comment" name="comment" cols="45" rows="8" aria-required="true" class="form-control"></textarea>' .
				'</div>' .
				'</div>',
			'comment_notes_after' => '<p class="form-text text-muted">' .
				/* translators: %s: Comment allowed HTML tags. */
				sprintf(
					/* translators: 1: "HTML" abbreviation markup, 2: Comment allowed HTML tags. */
					esc_html__( 'You may use these %1$s tags and attributes: %2$s', 'stylena' ),
					'<abbr title="' . esc_attr__( 'HyperText Markup Language', 'stylena' ) . '">' . esc_html__( 'HTML', 'stylena' ) . '</abbr>',
					$stylena_comment_allowed_tags
				) .
				'</p>',
		)
	);

	unset( $stylena_comment_allowed_tags );
	?>
</section><!-- #comments -->
<?php unset( $stylena_design ); ?>