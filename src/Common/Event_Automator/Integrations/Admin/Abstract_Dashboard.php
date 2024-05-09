<?php
/**
 * Class to manage Endpoint Dashboard.
 *
 * @since   1.4.0
 *
 * @package TEC\Common\Event_Automator\Integrations\Admin
 */

namespace TEC\Common\Event_Automator\Integrations\Admin;

use TEC\Common\Event_Automator\Integrations\Dashboard;
use TEC\Common\Event_Automator\Integrations\Endpoints_Manager;
use TEC\Common\Event_Automator\Integrations\Template_Modifications;
use TEC\Common\Event_Automator\Integrations\Url;

/**
 * Class Abstract_Dashboard
 *
 * @since   1.4.0
 *
 * @package TEC\Common\Event_Automator\Integrations\Admin
 */
class Abstract_Dashboard {

	/**
	 * The prefix, in the context of tribe options, of each setting for this extension.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $option_prefix = '';

	/**
	 * The internal id of the integration.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $api_id = '';

	/**
	 * An instance of the Integration Endpoints_Manager.
	 *
	 * @since 1.4.0
	 *
	 * @var Endpoints_Manager
	 */
	protected $manager;

	/**
	 * An instance of the Template_Modifications.
	 *
	 * @since 1.4.0
	 *
	 * @var Template_Modifications
	 */
	protected $template_modifications;

	/**
	 * The Integration URL handler instance.
	 *
	 * @since 1.4.0
	 *
	 * @var Url
	 */
	protected $url;

	/**
	 * Get the integration endpoint dashboard fields to display on the Integrations tab.
	 *
	 * @since 1.4.0
	 *
	 * @param array<string,array> $fields The current fields.
	 *
	 * @return array<string,array> The fields, as updated by the settings.
	 */
	public function add_fields( array $fields = [] ) {
		$api_id = static::$api_id;

		$wrapper_classes = tribe_get_classes( [
			'tec-automator-dashboard'                       => true,
			'tec-events-settings-' . $api_id . '-dashboard' => true,
		] );

		$dashboard_fields = [
			static::$option_prefix . 'wrapper_open'  => [
				'type' => 'html',
				'html' => '<div id="tribe-settings-' . $api_id . '-application" class="' . implode( ' ', $wrapper_classes ) . '">'
			],
			static::$option_prefix . 'header'        => [
				'type' => 'html',
				'html' => $this->get_intro_text(),
			],
			static::$option_prefix . 'endpoints'     => [
				'type' => 'html',
				'html' => $this->get_dashboard(),
			],
			static::$option_prefix . 'wrapper_close' => [
				'type' => 'html',
				'html' => '<div class="clear"></div></div>',
			],
		];

		$fields = array_merge( $fields, $dashboard_fields );

		/**
		 * Filters the integration endpoint dashboard shown to the user in the Events > Settings > Integrations tab.
		 *
		 * @since 1.4.0
		 *
		 * @param array<string,array> $fields A map of the API fields that will be printed on the page.
		 * @param Dashboard           $this   A Dashboard instance.
		 */
		$fields = apply_filters( "tec_event_automator_{$api_id}_dashboard_fields", $fields, $this );

		return $fields;
	}

	/**
	 * Provides the introductory text to the integration endpoint dashboard.
	 *
	 * @since 1.4.0
	 *
	 * @return string The introductory text for the integration endpoint dashboard.
	 */
	protected function get_intro_text() {
		return $this->template_modifications->get_dashboard_intro_text();
	}

	/**
	 * Get the integration endpoints.
	 *
	 * @since 1.4.0
	 *
	 * @return array<string,array> An array of the integration endpoints.
	 */
	public function get_endpoints() {
		$api_id = static::$api_id;

		/**
		 * Filters the integration endpoints for the dashboard.
		 *
		 * @since 1.4.0
		 *
		 * @param array<string,array> An array of the integration endpoints.
		 */
		$endpoints = apply_filters( "tec_event_automator_{$api_id}_endpoints", [] );

		$sorted_endpoints = wp_list_sort(
			$endpoints,
			[
			  'id'  => 'ASC',
			]
		);

		return $sorted_endpoints;
	}

	/**
	 * Get the integration endpoints dashboard template.
	 *
	 * @since 1.4.0
	 *
	 * @return string HTML for the dashboard.
	 */
	public function get_dashboard() {
		$endpoints = $this->get_endpoints();

		return $this->template_modifications->get_dashboard( $endpoints, $this->manager, $this->url );
	}
}
