<?php
/**
 * Class to manage Zapier Endpoints.
 *
 * @since   1.4.0
 *
 * @package TEC\Common\Event_Automator\Zapier\Admin
 */

namespace TEC\Common\Event_Automator\Zapier\Admin;

use TEC\Common\Event_Automator\Integrations\Admin\Abstract_Endpoints_Manager;
use TEC\Common\Event_Automator\Zapier\Actions;
use TEC\Common\Event_Automator\Zapier\Template_Modifications;

/**
 * Class Endpoints_Manager
 *
 * @since   1.4.0
 *
 * @package TEC\Common\Event_Automator\Zapier\Admin
 */
class Endpoints_Manager extends Abstract_Endpoints_Manager {

	/**
	 * @inheritdoc
	 */
	public static $api_name = 'Zapier';

	/**
	 * @inheritdoc
	 */
	public static $api_id = 'zapier';

	/**
	 * Endpoints_Manager constructor.
	 *
	 * @since 1.4.0
	 *
	 * @param Actions                $actions An instance of the Actions name handler.
	 * @param Template_Modifications $actions An instance of the Template_Modifications.
	 */
	public function __construct( Actions $actions, Template_Modifications $template_modifications ) {
		$this->actions                = $actions;
		$this->template_modifications = $template_modifications;
		$api_id                       = static::$api_id;

		/**
		 * Filters the endpoints for the dashboard.
		 *
		 * @since 1.4.0
		 *
		 * @param array<string,array> An array of endpoints.
		 */
		$this->endpoints = apply_filters( "tec_event_automator_{$api_id}_endpoints", [] );
	}
}
