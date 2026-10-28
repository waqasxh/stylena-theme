<?php

/**
 * Hooks modification on WordPress original widgets.
 *
 * @package Stylena
 */


namespace Stylena\Hooks;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( '\Stylena\Hooks\WidgetHooks' ) ) {
	/**
	 * This class will hook into WordPress original widgets such as calendar widget.
	 */
	class WidgetHooks {



		/**
		 * Add actions or filters that will be hook into WordPress widgets.
		 *
		 * To use, just code as follows:
		 *
		 * $WidgetHooks = new \Stylena\Hooks\WidgetHooks();
		 * $WidgetHooks->add_actions_filters();
		 *
		 * That's it.
		 */
		public function add_actions_filters() {
			// Modify calendar to support Bootstrap 4 style.
			\add_filter( 'get_calendar', array( $this, 'modify_calendar_widget' ), 10, 1 );
		} // add_actions_filters


		/**
		 * Modify calendar widget.
		 *
		 * @access private Do not access this method directly. This is for hook callback not for direct call.
		 * @param string $calendar The WordPress original calendar widget.
		 */
		public function modify_calendar_widget( $calendar ) {
			$new_calendar = preg_replace( '#(<table*\s)(id="wp-calendar")#i', '$1 id="wp-calendar" class="table"', $calendar );
			$new_calendar = '<div class="table-responsive">' . $new_calendar . '</div>';
			return $new_calendar;
		} // modify_calendar_widget
	}
}
