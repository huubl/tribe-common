<?php
/**
 * Handles In-App Notifications setup and actions.
 *
 * @since   TBD
 *
 * @package TEC\Common\Notifications
 */

namespace TEC\Common\Notifications;

use Tribe__Main;
use TEC\Common\Admin\Conditional_Content\Dismissible_Trait;

/**
 * Class Notifications
 *
 * @since   TBD

 * @package TEC\Common\Notifications
 */
final class Notifications {
	use Dismissible_Trait;

	/**
	 * The slugs for plugins that support In-App Notifications.
	 *
	 * @since TBD
	 *
	 * @var array
	 */
	private $plugin_slugs = [
		'the-events-calendar',
		'event-tickets',
	];

	/**
	 * The Notifications API URL.
	 *
	 * @since TBD
	 *
	 * @var string
	 */
	private $api_url = '';

	/**
	 * Notification slug for dismissible content.
	 *
	 * @var string
	 */
	protected string $slug = '';

	/**
	 * Boot the in-App Notifications
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function boot(): void {
		// TODO - Get the organization, brand, and product from... where?
		$this->api_url = 'https://ian.stellarwp.com/feed/organization/brand/product.json';

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once In-App Notifications is initiated.
		 *
		 * @since TBD
		 *
		 * @param self $ian The In-App Notifications instance.
		 */
		do_action( 'tec_common_ian_preload', $this );
	}

	/**
	 * Initializes the plugins and triggers the "loaded" action.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function init(): void {
		$this->register_plugins();

		/**
		 * Filter the base parent slugs for IAN.
		 *
		 * @since TBD
		 *
		 * @param array<string> $plugin_slugs The slugs for plugins that support IAN.
		 */
		$this->plugin_slugs = apply_filters( 'tec_common_ian_plugin_slugs', $this->plugin_slugs );

		/**
		 * Allow plugins to hook in and add themselves,
		 * running their own actions once IAN is initiated.
		 *
		 * @since TBD
		 *
		 * @param self $ian The IAN instance.
		 */
		do_action( 'tec_common_ian_loaded', $this );
	}

	/**
	 * Register the Admin assets for the In-App Notifications.
	 *
	 * @since  TBD
	 *
	 * @return void
	 */
	public function register_assets(): void {
		tribe_assets(
			Tribe__Main::instance(),
			[
				[ 'ian-client-css', 'ian-client.css' ],
				[ 'ian-client-js', 'ian-client.js', [ 'jquery' ] ],
			],
			'admin_enqueue_scripts',
			[
				'conditionals' => [ $this, 'is_ian_page' ],
				'in_footer'    => false,
				'localize'     => [
					'name' => 'commonIan',
					'data' => [
						'ajax_url' => admin_url( 'admin-ajax.php' ),
						'nonce'    => wp_create_nonce( 'common_ian_nonce' ),
						'dismiss'  => esc_html__( 'Dismiss', 'tribe-common' ),
					],
				],
			]
		);
	}

	/**
	 * Define which pages will show the notification icon.
	 *
	 * @since TBD
	 *
	 * @return bool
	 */
	public function is_ian_page() {
		$screen = get_current_screen();

		$allowed = [ 'tribe_events', 'edit-tribe_events', 'tribe_events_page_tec-events-settings' ];

		/**
		 * Filter the allowed pages for the Notifications icon.
		 *
		 * @since TBD
		 *
		 * @param array<string> $allowed The allowed pages for the Notifications icon.
		 */
		$allowed = apply_filters( 'tec_common_ian_allowed_pages', $allowed );

		if ( in_array( $screen->id, $allowed, true ) ) {
			return true;
		}

		return false;
	}

	/**
	 * Register the plugins that are hooked into `tec_ian_slugs`.
	 * This keeps all TEC plugins in sync and only requires one notifications sidebar.
	 *
	 * @since TBD
	 *
	 * @param bool|null $opted Whether to opt in or out. If null, will calculate based on existing status.
	 *
	 * @return void
	 */
	public function register_plugins( $opted = null ) {
		// Let's reduce the amount this triggers.
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		global $pagenow;

		// Only run on the plugins page, or when we're manually setting an opt-in!
		if ( $pagenow !== 'plugins.php' && is_null( $opted ) ) {
			return;
		}

		$tec_slugs = $this->plugin_slugs;

		// We've got no plugins?
		if ( empty( $tec_slugs ) ) {
			return;
		}

		// Check for cached slugs.
		$cached_slugs = tribe( 'cache' )['tec_ian_slugs'] ?? null;

		// We have already run and the slug list hasn't changed since then. Or we are manually running.
		if ( is_null( $opted ) && ! empty( $cached_slugs ) && $cached_slugs == $tec_slugs ) {
			return;
		}

		// No cached slugs, or the list has changed, or we're running manually - so (re)set the cached value.
		tribe( 'cache' )['tec_ian_slugs'] = $tec_slugs;
	}

	/**
	 * Show our notification icon.
	 *
	 * @since TBD
	 *
	 * @param string $slug The plugin slug for IAN.
	 *
	 * @return void
	 */
	public function show_icon( $slug ): void {
		if ( ! in_array( $slug, $this->plugin_slugs, true ) || ! $this->is_ian_page() ) {
			return;
		}

		/**
		 * Filter allowing disabling of the Notifications icon by returning false.
		 *
		 * @since TBD
		 *
		 * @param bool $show Whether to show the icon or not.
		 */
		$show = (bool) apply_filters( 'tec_common_ian_show_icon', true, $slug );

		if ( ! $show ) {
			return;
		}

		$template = new Template();
		$template->render_icon( [ 'slug' => $slug ], true );
	}

	/**
	 * Optin to IAN notifications.
	 *
	 * @since TBD
	 */
	public function opt_in() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( wp_verify_nonce( $nonce, 'common_ian_nonce' ) ) {

			tribe_update_option( 'ian-client-opt-in', 1 );

			wp_send_json_success( 'IAN opt-in successful', 200 );
		} else {
			wp_send_json_error( 'Invalid nonce', 403 );
		}
	}

	/**
	 * Get the IAN notifications.
	 *
	 * @since TBD
	 */
	public function get_feed() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( wp_verify_nonce( $nonce, 'common_ian_nonce' ) ) {

			// TODO: The call to Laravel. GET or POST? Do we need to send any data? Auth?
			// $response = wp_remote_request(
			// 	$this->ian_server,
			// 	[
			// 		'method'    => 'POST',
			// 		'headers'   => [ 'Content-Type' => 'application/json; charset=utf-8' ],
			// 		'timeout'   => 15, // phpcs:ignore WordPressVIPMinimum.Performance.RemoteRequestTimeout.timeout_timeout
			// 		'sslverify' => false, // we trust our server.
			// 		'body'      => wp_json_encode(
			// 			[
			// 				'param1' => '',
			// 				'param2' => '',
			// 				'token'  => '??',
			// 			]
			// 		),
			// 	]
			// );

			// if ( ! is_wp_error( $response ) && wp_remote_retrieve_response_code( $response ) === 200 ) {
			// 	$body = json_decode( wp_remote_retrieve_body( $response ), true );
			// } else {
			// 	wp_send_json_error( wp_remote_retrieve_response_message( $response ), wp_remote_retrieve_response_code( $response ) );
			// }

			// $notifications = Conditionals::filter_feed( $body['notifications'] );

			// $this->slug = $slug;
			// $this->has_user_dismissed( get_current_user_id() )

			// If we want to reset all dismissible content for testing.
			// delete_user_meta( get_current_user_id(), 'tec-dismissible-content' );
			// or just one
			// $this->undismiss( get_current_user_id() )

			// TODO: Below is an example notifications array. Send the real one.
			wp_send_json_success(
				[
					[
						'id'          => '101',
						'type'        => 'update',
						'slug'        => 'tec-update-664',
						'title'       => 'The Events Calendar 6.6.4 Update',
						'content'     => '<p>The latest update of The Events Calendar adds an option to allow for duplicate Venue creation, updates custom table query logic to avoid DB error, and logic that displays the “REST API blocked” banner to prevent false positives.</p>',
						'actions'     => [
							[
								'text'   => 'See Details',
								'link'   => 'https://evnt.is/1ai-',
								'target' => '_blank',
							],
							[
								'text'   => 'Update Now',
								'link'   => '/wp-admin/update-core.php',
								'target' => '_self',
							],
						],
						'dismissible' => true,
					],
					[
						'id'          => '102',
						'type'        => 'notice',
						'slug'        => 'event-tickets-upsell',
						'title'       => 'Sell Tickets & Collect RSVPs with Event Tickets',
						'content'     => '<p>Sell tickets, collect RSVPs and manage attendees for free.</p>',
						'actions'     => [
							[
								'text'   => 'Learn More',
								'link'   => 'https://evnt.is/1aj1',
								'target' => '_blank',
							],
						],
						'dismissible' => true,
					],
					[
						'id'          => '103',
						'type'        => 'warning',
						'slug'        => 'fbar-upgrade-556',
						'title'       => 'Filter Bar 5.5.6 Security Update',
						'content'     => '<p>Get the latest version of Filter Bar for important security updates.</p>',
						'actions'     => [
							[
								'text'   => 'Update',
								'link'   => '/wp-admin/plugins.php?plugin_status=upgrade',
								'target' => '_self',
							],
						],
						'dismissible' => false,
					],
				],
				200
			);
		} else {
			wp_send_json_error( 'Invalid nonce', 403 );
		}
	}

	/**
	 * AJAX handler for dismissing IAN notifications.
	 *
	 * @since TBD
	 *
	 * @return void
	 */
	public function handle_dismiss(): void {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( wp_verify_nonce( $nonce, 'common_ian_nonce' ) ) {

			$slug = isset( $_POST['slug'] ) ? sanitize_key( $_POST['slug'] ) : '';

			if ( empty( $slug ) ) {
				wp_send_json_error( 'Invalid slug', 403 );
			}

			$this->slug = $slug;

			wp_send_json_success( $this->dismiss(), 200 );
		} else {
			wp_send_json_error( 'Invalid nonce', 403 );
		}
	}
}
