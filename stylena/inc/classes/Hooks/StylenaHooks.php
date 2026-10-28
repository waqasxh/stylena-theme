<?php

/**
 * Hooks modification on Bootstrap Basic 4 template.
 *
 * @package Stylena
 */


namespace Stylena\Hooks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\Stylena\Hooks\StylenaHooks' ) ) {
	/**
	 * This class will be hook into WordPress and make changes to the theme.<br>
	 * If you want to hook to enable or add feature to the theme, please use \Stylena\Stylena() class.
	 */
	class StylenaHooks {



		/**
		 * Add actions or filters that will be hook into WordPress and make changes to this theme.
		 *
		 * To use, just code as follows:
		 *
		 * $StylenaHooks = new \Stylena\Hooks\StylenaHooks();
		 * $StylenaHooks->add_actions_filters();
		 *
		 * That's it.
		 */
		public function add_actions_filters() {
			// Change title separator from - to the configured in functions.php file.
			\add_filter( 'document_title_separator', array( $this, 'modify_title_separator' ), 10, 1 );
			// Modift excerpt more text. (default is [...].).
			\add_filter( 'excerpt_more', array( $this, 'modify_excerpt_more' ), 10, 1 );
			// Modify pagination page link.
			\add_filter( 'wp_link_pages_link', array( $this, 'pagination_page_link' ), 10, 2 );
			// Modify comment reply link class.
			\add_filter( 'comment_reply_link', array( $this, 'modify_comment_reply_link_class' ), 10, 1 );
			// Modify comment navigation link attributes.
			\add_filter( 'previous_comments_link_attributes', array( $this, 'modify_comment_nav_link_previous' ), 10, 1 );
			\add_filter( 'next_comments_link_attributes', array( $this, 'modify_comment_nav_link_next' ), 10, 1 );
			// Modify previous/next image link.
			\add_filter( 'previous_image_link', array( $this, 'modify_previous_next_image_link' ) );
			\add_filter( 'next_image_link', array( $this, 'modify_previous_next_image_link' ) );
		} // add_actions_filters


		/**
		 * Modify comment navigation link (older comments, newer comments link).
		 *
		 * @param string $attributes
		 * @param string $nav
		 * @return string
		 */
		protected function modify_comment_nav_link( $attributes, $nav ) {
			$attributes = 'class="btn btn-light';
			if ( 'next' === $nav ) {
				$attributes .= ' float-right';
			} else {
				$attributes .= ' float-left';
			}
			$attributes .= '"';

			return $attributes;
		} // modify_comment_nav_link


		/**
		 * Modify comment navigation link
		 *
		 * @param string $attributes
		 * @return string
		 */
		public function modify_comment_nav_link_next( $attributes ) {
			return $this->modify_comment_nav_link( $attributes, 'next' );
		} // modify_comment_nav_link_next


		/**
		 * Modify comment navigation link
		 *
		 * @param string $attributes
		 * @return string
		 */
		public function modify_comment_nav_link_previous( $attributes ) {
			return $this->modify_comment_nav_link( $attributes, 'previous' );
		} // modify_comment_nav_link_previous


		/**
		 * Modify comment reply link class.
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 * @param string $class_name The comment reply link class.
		 * @return string Return modified class.
		 */
		public function modify_comment_reply_link_class( $class_name ) {
			$class_name = str_ireplace( 'comment-reply-link', 'comment-reply-link btn btn-light btn-sm', $class_name );
			$class_name = str_ireplace( 'comment-reply-login', 'comment-reply-login btn btn-light btn-sm', $class_name );

			return $class_name;
		} // modify_comment_reply_link_class


		/**
		 * Modify excerpt more text.
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 * @param string $more Default more text is [...].
		 * @return string Return the new more text.
		 */
		public function modify_excerpt_more( $more ) {
			return ' &hellip;';
		} // modify_excerpt_more


		/**
		 * Modify Previous/Next image link.
		 *
		 * @link http://wordpress.stackexchange.com/questions/77296/adding-class-to-next-prev-image-link-in-attachment-php Reference.
		 * @param string $link
		 * @return string
		 */
		public function modify_previous_next_image_link( $link ) {
			return str_replace( '<a ', '<a class="btn btn-outline-secondary" ', $link );
		} // modify_previous_next_image_link


		/**
		 * Modify title separator.
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 * @global string $stylena_title_separator
		 * @return string Return the new title separator.
		 */
		public function modify_title_separator() {
			global $stylena_title_separator;

			return $stylena_title_separator;
		} // modify_title_separator


		/**
		 * Modify pagination page link
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 * @param string  $link
		 * @param integer $i
		 */
		public function pagination_page_link( $link, $i = 0 ) {
			if ( stripos( $link, '<a' ) === false ) {
				// if not found `<a>` link.
				return '<li class="page-item active"><a class="page-link" href="#">' . $link . '</a></li>' . PHP_EOL;
			} else {
				// if found `<a>` link.
				if ( stripos( $link, 'class=' ) !== false ) {
					// if found `class=".."` attribute.
					$pattern = 'class\s*=\s*[\'"](?<classValue>[\w \-_]+)[\'"]';
					$replace = 'class="$1 page-link"';
					$link    = preg_replace( '/' . $pattern . '/', $replace, $link );
					unset( $pattern, $replace );
				} else {
					// if not found `class=".."` attribute.
					$link = str_ireplace( '<a', '<a class="page-link"', $link );
				}
				// always wrap pagination with `<li>` element.
				return '<li class="page-item">' . $link . '</li>' . PHP_EOL;
			}
		} // pagination_page_link
	}
}
