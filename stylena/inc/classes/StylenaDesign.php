<?php

/**
 * The Bootstrap Basic 4 design functions (template tags).
 *
 * @package Stylena
 */


namespace Stylena;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\Stylena\StylenaDesign' ) ) {
	/**
	 * This class works on template tags or design functions. Such as link to categories or tags from the post page, post on date/time, post by ..., comment fields, and more.
	 */
	class StylenaDesign {



		/**
		 * Return or display attachment.
		 *
		 * @param bool $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return attachment.
		 */
		public function attachment( $use_return = false ) {
			$post                = get_post();
			$metadata            = wp_get_attachment_metadata();
			$attachment_size     = apply_filters( 'bootstrap_basic4_attachment_size', array( 1140, 1140 ) ); // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Public legacy filter kept for backwards compatibility.
			$next_attachment_url = wp_get_attachment_url();

			/**
			 * Grab the IDs of all the image attachments in a gallery so we can get the
			 * URL of the next adjacent image in a gallery, or the first image (if
			 * we're looking at the last image in a gallery), or, in a gallery of one,
			 * just the link to that image file.
			 */
			$attachment_ids = get_posts(
				array(
					'post_parent' => $post->post_parent,
					'fields'      => 'ids',
					'numberposts' => -1,
					'post_status' => 'inherit',
					'post_type'   => 'attachment',
					// 'post_mime_type' => 'image',
					'order'       => 'ASC',
					'orderby'     => 'menu_order ID',
				)
			);

			// If there is more than 1 attachment in a gallery...
			if ( count( $attachment_ids ) > 1 ) {
				foreach ( $attachment_ids as $attachment_id ) {
					if ( $attachment_id === $post->ID ) {
						$next_id = current( $attachment_ids );
						break;
					}
				}
				unset( $attachment_id );
				if ( isset( $next_id ) ) {
					// get the URL of the next image attachment...
					$next_attachment_url = get_attachment_link( $next_id );
				} else {
					// or get the URL of the first image attachment.
					$next_attachment_url = get_attachment_link( array_shift( $attachment_ids ) );
				}
				unset( $next_id );
			}

			$output = '';
			$type   = get_post_mime_type( $post->ID );
			switch ( strtolower( $type ) ) {
				case 'audio/mp3':
				case 'audio/mpeg':
				case 'audio/mpeg3':
				case 'audio/mpg':
				case 'audio/wav':
				case 'audio/wave':
				case 'audio/webm':
				case 'audio/x-wav':
					$output = do_shortcode( '[audio ' . $metadata['fileformat'] . '="' . wp_get_attachment_url( $post->ID ) . '"][/audio]' );
					break;
				case 'image/bmp':
				case 'image/gif':
				case 'image/jpeg':
				case 'image/pjpeg':
				case 'image/png':
				case 'image/x-png':
					$output = sprintf(
						'<a href="%1$s" title="%2$s" rel="attachment">%3$s</a>',
						esc_url( $next_attachment_url ),
						the_title_attribute( array( 'echo' => false ) ),
						wp_get_attachment_image( $post->ID, $attachment_size, false, array( 'class' => 'img-fluid aligncenter' ) )
					);
					break;
				case 'video/mp4':
				case 'video/mpeg':
				case 'video/quicktime':
				case 'video/webm':
				case 'video/x-ms-wmv':
				case 'video/x-msvideo':
					$output = do_shortcode( '[video width="' . $metadata['width'] . '" height="' . $metadata['height'] . '" ' . $metadata['fileformat'] . '="' . wp_get_attachment_url( $post->ID ) . '"][/video]' );
					break;
				default:
					$output = '<div class="card"><div class="card-body"><i class="fas fa-download"></i> ' . wp_get_attachment_link() . '</div></div>';
					break;
			} // endswitch;
			unset( $type );

			unset( $attachment_ids, $attachment_size, $metadata, $next_attachment_url, $post );
			if ( true === $use_return ) {
				return $output;
			} else {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
				unset( $output );
			}
		} // attachment


		/**
		 * Return or display categories list.
		 *
		 * @param string $categories_list The categories html.
		 * @param bool   $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return categories list.
		 */
		public function categories_list( $categories_list, $use_return = false ) {
			$output = sprintf( '<span class="categories-icon fas fa-th-list" title="' . __( 'Posted in', 'stylena' ) . '"></span> %1$s', $categories_list );

			if ( true === $use_return ) {
				return $output;
			} else {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
			}
		} // categories_list


		/**
		 * Display comments link.
		 */
		public function comments_link() {
			$comment_icon  = '<i class="comment-icon fas fa-comment"></i> <small class="comment-total">%d</small>';
			$comments_icon = '<i class="comment-icon fas fa-comments"></i> <small class="comment-total">%s</small>';
			comments_popup_link( sprintf( $comment_icon, '' ), sprintf( $comment_icon, '1' ), sprintf( $comments_icon, '%' ), 'btn btn-light btn-sm' );
		} // comments_link


		/**
		 * Return or display continue reading message.
		 *
		 * @param bool $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return continue reading message.
		 */
		public function continue_reading( $use_return = false ) {
			$output = __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'stylena' );

			if ( true === $use_return ) {
				return $output;
			} else {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
			}
		} // continue_reading


		/**
		 * Display the comments
		 *
		 * @param object $comment
		 * @param array  $args
		 * @param int    $depth
		 */
		public function display_comments( $comment, $args, $depth ) {
			if ( 'pingback' === $comment->comment_type || 'trackback' === $comment->comment_type ) {
				echo '<li id="comment-';
				comment_ID();
				echo '" ';
				comment_class( 'comment-type-pt' );
				echo '>';
				echo '<div class="comment-body media">';
				echo '<div class="media-body">';
				esc_html_e( 'Pingback:', 'stylena' );
				comment_author_link();
				edit_comment_link( __( 'Edit', 'stylena' ), '<span class="edit-link">', '</span>' );
				echo '</div>';
				echo '</div>';
			} else {
				echo '<li id="comment-';
				comment_ID();
				echo '" ';
				comment_class( empty( $args['has_children'] ) ? '' : 'parent' );
				echo '>';

				echo '<article id="div-comment-';
				comment_ID();
				echo '" class="comment-body media">';

				// footer.
				echo '<footer class="comment-meta media-left">';
				if ( 0 !== intval( $args['avatar_size'] ) ) {
					echo get_avatar( $comment, $args['avatar_size'] );
				}
				echo '</footer><!-- .comment-meta -->' . PHP_EOL;
				// end footer.

				// comment content.
				echo '    <div class="comment-content media-body">' . PHP_EOL;
				echo '        <div class="comment-author vcard">' . PHP_EOL;
				echo '            <div class="comment-metadata">' . PHP_EOL;

				// date-time.
				echo '                <a href="';
				echo esc_url( get_comment_link( $comment->comment_ID ) );
				echo '">';
				echo '<time datetime="';
				comment_time( 'c' );
				echo '">';
				/* translators: %1$s: Comment date, %2$s: Comment time. */
				printf( esc_html_x( '%1$s at %2$s', '1: date, 2: time', 'stylena' ), esc_html( get_comment_date() ), esc_html( get_comment_time() ) );
				echo '</time>';
				echo '</a>';
				// end date-time.

				echo ' ';

				edit_comment_link( '<span class="far fa-edit "></span>' . __( 'Edit', 'stylena' ), '<span class="edit-link">', '</span>' );
				echo PHP_EOL;

				echo '            </div><!-- .comment-metadata -->' . PHP_EOL;

				// if comment was not approved.
				if ( '0' === strval( $comment->comment_approved ) ) {
					echo '<div class="comment-awaiting-moderation text-warning"> <span class="fas fa-info-circle"></span> ';
					esc_html_e( 'Your comment is awaiting moderation.', 'stylena' );
					echo '</div>' . PHP_EOL;
				} //endif;

				// comment author says.
				echo '            ';
				/* translators: $s: Comment author name with link. */
				printf( wp_kses_post( __( '%s <span class="says">says:</span>', 'stylena' ) ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) );
				echo PHP_EOL;
				echo '        </div><!-- .comment-author -->' . PHP_EOL;

				// comment content body.
				comment_text();
				// end comment content body.

				// reply link.
				comment_reply_link(
					array_merge(
						$args,
						array(
							'add_below'  => 'div-comment',
							'depth'      => $depth,
							'max_depth'  => $args['max_depth'],
							'reply_text' => '<span class="fas fa-reply"></span> ' . __( 'Reply', 'stylena' ),
							'login_text' => '<span class="fas fa-reply"></span> ' . __( 'Log in to Reply', 'stylena' ),
						)
					)
				);
				// end reply link.
				echo PHP_EOL;
				echo '    </div><!-- .comment-content -->' . PHP_EOL;
				// end comment content.

				echo '</article><!-- .comment-body -->' . PHP_EOL;
			} //endif;
		} // display_comments


		/**
		 * Return or display edit post link.
		 *
		 * @param bool $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return edit post link.
		 */
		public function edit_post_link( $use_return = false ) {
			$edit_post_link = get_edit_post_link();
			if ( ! empty( $edit_post_link ) ) {
				$edit_btn = '<a class="post-edit-link btn btn-light btn-sm" href="' . $edit_post_link . '" title="' . __( 'Edit', 'stylena' ) . '" role="button"><i class="edit-post-icon far fa-edit" title="' . __( 'Edit', 'stylena' ) . '"></i></a>';
				unset( $edit_post_link );

				if ( true === $use_return ) {
					return $edit_btn;
				} else {
					echo $edit_btn; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
				}
			}
			unset( $edit_btn, $edit_post_link );
		} // edit_post_link


		/**
		 * Return or display pagination.
		 *
		 * @global \WP_Query $wp_query WordPress query class.
		 * @param string $pagination_align_class The pagination css class.
		 * @param bool   $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return pagination html.
		 */
		public function pagination( $pagination_align_class = 'justify-content-center', $use_return = false ) {
			$output = apply_filters( 'bootstrap_basic4_pagination', '' ); // allow plugin hooks to override pagination. // phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedHooknameFound -- Public legacy filter kept for backwards compatibility.
			if ( '' !== $output ) {
				if ( true === $use_return ) {
					return $use_return;
				} else {
					echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
					unset( $output );
					return '';
				}
			}

			global $wp_query;
			$big              = 999999999;
			$pagination_array = paginate_links(
				array(
					'base'      => str_replace( $big, '%#%', get_pagenum_link( $big ) ),
					'format'    => '/page/%#%',
					'current'   => max( 1, get_query_var( 'paged' ) ),
					'total'     => $wp_query->max_num_pages,
					'prev_text' => '&laquo;',
					'next_text' => '&raquo;',
					'type'      => 'array',
				)
			);

			unset( $big );

			if ( is_array( $pagination_array ) && ! empty( $pagination_array ) ) {
				$output .= '<nav class="pagination-nav-container" aria-label="' . esc_attr__( 'Page navigation', 'stylena' ) . '">';
				$output .= '<ul class="pagination ' . $pagination_align_class . '">';
				foreach ( $pagination_array as $page ) {
					if ( ! is_scalar( $page ) ) {
						continue;
					}

					$output .= '<li';
					if ( strpos( $page, '<a' ) === false && strpos( $page, '&hellip;' ) === false ) {
						$output .= ' class="page-item active"';
					} else {
						$output .= ' class="page-item"';
					}
					$output .= '>';
					if ( strpos( $page, '<a' ) === false && strpos( $page, '&hellip;' ) === false ) {
						$output .= '<a class="page-link">' . $page . '</a>';
					} else {
						if ( strpos( $page, 'class=' ) === false ) {
							$page = str_ireplace( '<a', '<a class="page-link"', $page );
						} else {
							$page = str_ireplace( 'class="', 'class="page-link ', $page );
							$page = str_ireplace( 'class=\'', 'class=\'page-link ', $page );
						}
						$output .= $page;
					}
					$output .= '</li>';
				}
				$output .= '</ul>';
				$output .= '</nav>';
			}

			unset( $page, $pagination_array );
			if ( true === $use_return ) {
				return $output;
			} else {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
				unset( $output );
			}
		} // pagination


		/**
		 * Return or display post date/time and the author.
		 *
		 * @param bool $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return post date/time and the author.
		 */
		public function post_on( $use_return = false ) {
			$time_string = '<time class="entry-date published" datetime="%1$s">%2$s</time>';
			if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
				$time_string .= '<time class="updated" datetime="%3$s">%4$s</time>';
			}

			$time_string = sprintf(
				$time_string,
				esc_attr( get_the_date( 'c' ) ),
				esc_html( get_the_date() ),
				esc_attr( get_the_modified_date( 'c' ) ),
				esc_html( get_the_modified_date() )
			);

			$output = sprintf(
				/* translators: %1$s: Date/time with link, %2$s: Author name with link. */
				__( '<span class="posted-on">Posted on %1$s</span><span class="byline"> by %2$s</span>', 'stylena' ),
				sprintf(
					'<a href="%1$s" title="%2$s" rel="bookmark">%3$s</a>',
					esc_url( get_permalink() ),
					esc_attr( get_the_time() ),
					$time_string
				),
				sprintf(
					'<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
					esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
					/* translators: %s Author name. */
					esc_attr( sprintf( __( 'View all posts by %s', 'stylena' ), get_the_author() ) ),
					esc_html( get_the_author() )
				)
			);

			unset( $time_string );
			if ( true === $use_return ) {
				return $output;
			} else {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
			}
		} // post_on


		/**
		 * Return or display tags list
		 *
		 * @param string  $tags_list The tags list html.
		 * @param boolean $use_return If set to true it will use return the value, if set to false it will be display immediately.
		 * @return string Return tags list.
		 */
		public function tags_list( $tags_list, $use_return = false ) {
			$output = sprintf( '<span class="tags-icon fas fa-tags" title="' . __( 'Tagged', 'stylena' ) . '"></span> %1$s', $tags_list );

			if ( true === $use_return ) {
				return $output;
			} else {
				echo $output; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- markup assembled from escaped/translated parts.
			}
		} // tags_list
	}
}
