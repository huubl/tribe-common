<?php

// Don't load directly
if ( ! defined( 'ABSPATH' ) ) {
	die( '-1' );
}

/**
 * Class with a few helpers for the Administration Pages
 */
class Tribe__Admin__Helpers {
	/**
	 * Static Singleton Factory Method
	 *
	 * @since      4.0.1
	 * @deprecated 4.5
	 *
	 * @return Tribe__Admin__Helpers
	 */
	public static function instance() {
		return tribe( 'admin.helpers' );
	}

	/**
	 * Matcher for Admin Pages related to Post Types
	 *
	 * @since  4.0.1
	 *
	 * @param  string|array|null $id What will be checked to see if we return true or false
	 *
	 * @return boolean
	 */
	public function is_post_type_screen( $post_type = null ) {
		global $current_screen;

		// Not in the admin we don't even care
		if ( ! is_admin() ) {
			return false;
		}

		// Doing AJAX? bail.
		if ( Tribe__Main::instance()->doing_ajax() ) {
			return false;
		}

		// Avoid Notices by checking the object type of WP_Screen
		if ( ! ( $current_screen instanceof WP_Screen ) ) {
			return false;
		}

		$defaults = apply_filters( 'tribe_is_post_type_screen_post_types', Tribe__Main::get_post_types() );

		// Match any Post Type from Tribe
		if ( is_null( $post_type ) && in_array( $current_screen->post_type, $defaults ) ) {
			return true;
		}

		// Match any of the post_types set
		if ( ! is_scalar( $post_type ) && in_array( $current_screen->post_type, (array) $post_type ) ) {
			return true;
		}

		// Match a specific Post Type
		if ( $current_screen->post_type === $post_type ) {
			return true;
		}

		return false;
	}

	/**
	 * Matcher for administration pages that are from Tribe the easier way
	 *
	 * @since  4.0.1
	 *
	 * @param  string|array|null $id What will be checked to see if we return true or false
	 *
	 * @return boolean
	 */
	public function is_screen( $id = null ) {
		global $current_screen;

		// Not in the admin we don't even care
		if ( ! is_admin() ) {
			return false;
		}

		// Doing AJAX? bail.
		if ( Tribe__Main::instance()->doing_ajax() ) {
			return false;
		}

		// Avoid Notices by checking the object type of WP_Screen
		if ( ! ( $current_screen instanceof WP_Screen ) ) {
			return false;
		}

		// Match any screen from Tribe
		if ( is_null( $id ) && false !== strpos( $current_screen->id, 'tribe' ) ) {
			return true;
		}

		// Match any of the pages set
		if ( ! is_scalar( $id ) && in_array( $current_screen->id, (array) $id ) ) {
			return true;
		}

		// Match a specific page
		if ( $current_screen->id === $id ) {
			return true;
		}

		// Match any post type page in the supported post types
		$defaults = apply_filters( 'tribe_is_post_type_screen_post_types', Tribe__Main::get_post_types() );
		if ( ! in_array( $current_screen->post_type, $defaults ) ) {
			return false;
		}

		return false;
	}

	/**
	 * Matcher for administration pages action
	 *
	 * @since  4.0.1
	 *
	 * @param  string|array|null $action What will be checked to see if we return true or false
	 *
	 * @return boolean
	 */
	public function is_action( $action = null ) {
		global $current_screen;

		// Not in the admin we don't even care
		if ( ! is_admin() ) {
			return false;
		}

		// Doing AJAX? bail.
		if ( Tribe__Main::instance()->doing_ajax() ) {
			return false;
		}

		// Avoid Notices by checking the object type of WP_Screen
		if ( ! ( $current_screen instanceof WP_Screen ) ) {
			return false;
		}

		// Match any of the actions passed
		if ( ! is_scalar( $action ) && in_array( $current_screen->action, (array) $action ) ) {
			return true;
		}

		// Match a specific page action
		if ( $current_screen->action === $action ) {
			return true;
		}

		return false;
	}

	/**
	 * Matcher for administration pages action
	 *
	 * @since  TBD
	 *
	 * @param  string|array|null $action What will be checked to see if we return true or false
	 *
	 * @return boolean
	 */
	public function is_base( $base = null ) {
		global $current_screen;

		// Not in the admin we don't even care
		if ( ! is_admin() ) {
			return false;
		}

		// Doing AJAX? bail.
		if ( Tribe__Main::instance()->doing_ajax() ) {
			return false;
		}

		// Avoid Notices by checking the object type of WP_Screen
		if ( ! ( $current_screen instanceof WP_Screen ) ) {
			return false;
		}

		// Match any of the bases for the screen
		if ( ! is_scalar( $base ) && in_array( $current_screen->base, (array) $base ) ) {
			return true;
		}

		// Match a specific base for the screen
		if ( $current_screen->base === $base ) {
			return true;
		}

		return false;
	}
}
