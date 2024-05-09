<?php
/**
 * The REST Endpoint Interface.
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Integrations\REST\V1\Interfaces
 */

namespace TEC\Common\Event_Automator\Integrations\REST\V1\Interfaces;

/**
 * REST_Endpoint_Interface
 *
 * @since 1.4.0
 *
 * @package TEC\Common\Event_Automator\Integration\REST\V1\Interfaces
 */
interface REST_Endpoint_Interface {

	/**
	 * Gets the Endpoint path for this route.
	 *
	 * @since 1.4.0
	 *
	 * @return string
	 */
	public function get_endpoint_path();

	/**
	 * Get the endpoint type.
	 *
	 * @since 1.4.0
	 *
	 * @return string The endpoint type.
	 */
	public function get_endpoint_type();

	/**
	 * Get the endpoint id.
	 *
	 * @since 1.4.0
	 *
	 * @return string The endpoint details id with prefix and endpoint combined.
	 */
	public function get_id();

	/**
	 * Get the endpoint option id.
	 *
	 * @since 1.4.0
	 *
	 * @return string The endpoint details id with prefix and endpoint combined.
	 */
	public function get_option_id();

	/**
	 * Adds the endpoint to the endpoint dashboard fitler.
	 *
	 * @since 1.4.0
	 */
	public function add_to_dashboard();

	/**
	 * Add the endpoint details to the endpoint array for the dashboard.
	 *
	 * @since 1.4.0
	 *
	 * @param array<string,array> $endpoints An array of the integration endpoints to display.
	 *
	 * @return array<string,array> An array of the integration endpoints to display with current endpoint added.
	 */
	public function add_endpoint_details( $endpoints );

	/**
	 * Get details for the current endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @return array<string,array> An array of the details for an endpoint.
	 */
	public function get_endpoint_details();

	/**
	 * Get the endpoint saved details ( last access and enabled ).
	 *
	 * @since 1.4.0
	 *
	 * @return array<string,array> An array of saved details for an endpoint.
	 */
	public function get_saved_details();

	/**
	 * Set the endpoint details ( last access and enabled ).
	 *
	 * @since 1.4.0
	 *
	 * @param array<string|integer> $details An array of saved details for an endpoint ( last access and enabled ).
	 *
	 * @return bool
	 */
	public function set_endpoint_details( array $details );

	/**
	 * Updates the last access valid access of an endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @param string $app_name The optional app name used with this API key pair.
	 */
	public function set_endpoint_last_access( $app_name = '' );

	/**
	 * Clears last access of an endpoint.
	 *
	 * @since 1.4.0
	 */
	public function clear_endpoint_last_access();

	/**
	 * Disables or enables the endpoint.
	 *
	 * @since 1.4.0
	 *
	 * @param bool $enabled The enabled to change the endpoint too.
	 */
	public function set_endpoint_enabled( bool $enabled );

	/**
	 * Add a custom post id to a trigger queue.
	 *
	 * @since 1.4.0
	 *
	 * @param integer            $post_id A WordPress custom post id.
	 * @param array<mixed|mixed> $data    An array of data specific to the trigger and used for validation.
	 */
	public function add_to_queue( $post_id, $data );

	/**
	 * Get the endpoint dependents.
	 *
	 * @since 1.4.0
	 *
	 * @return array<string> The endpoint dependents array.
	 */
	public function get_dependents();
}
