<?php
/**
 * Handles Context manipulation.
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Common\Event_Automator\Service_Providers
 */

namespace TEC\Common\Event_Automator\Service_Providers;

use TEC\Common\Event_Automator\Plugin;
use TEC\Common\Contracts\Service_Provider;
use Tribe__Context;

/**
 * Class Context_Provider
 *
 * @since TBD Migrated to Common from Event Automator
 *
 * @package TEC\Common\Event_Automator\Service_Providers
 */
class Context_Provider extends Service_Provider {

	/**
	 * Binds and sets up implementations.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 */
	public function register() {
		add_filter( 'tribe_context_locations', [ $this, 'filter_context_locations' ] );
	}

	/**
	 * Filters the context locations to add the ones used by Event Automator.
	 *
	 * @since TBD Migrated to Common from Event Automator
	 *
	 * @param array $locations The array of context locations.
	 *
	 *@return array<string,mixed> The modified context locations.
	 */
	public function filter_context_locations( array $locations = [] ) {
		$locations = array_merge(
			$locations,
			[
				'tec_automator_admin_request' => [
					'read' => [
						Tribe__Context::REQUEST_VAR => [ Plugin::$request_slug, 'state' ],
					],
				],
			]
		);

		return $locations;
	}
}
