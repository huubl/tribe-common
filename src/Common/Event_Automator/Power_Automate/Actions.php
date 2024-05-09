<?php
/**
 * Class to manage Power Automate actions.
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate
 */

namespace TEC\Common\Event_Automator\Power_Automate;

/**
 * Class Actions
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Power_Automate
 */
class Actions {

	/**
	 * The name of the action used to add a Power Automate connection.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $add_connection = 'tec-automator-power-automate-add-connection';

	/**
	 * The name of the action used to create a Power Automate access token.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $create_access = 'tec-automator-power-automate-create-access-token';

	/**
	 * The name of the action used to delete a Power Automate connection.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $delete_connection = 'tec-automator-power-automate-delete-connection';

	/**
	 * The name of the action used to clear a Power Automate endpoint queue.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $clear_action = 'tec-automator-power-automate-clear-endpoint-queue';

	/**
	 * The name of the action used to disable a Power Automate endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $disable_action = 'tec-automator-power-automate-disable-endpoint';

	/**
	 * The name of the action used to enable a Power Automate endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @var string
	 */
	public static $enable_action = 'tec-automator-power-automate-enable-endpoint';

}
