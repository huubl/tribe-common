<?php
/**
 * Help Hub Factory.
 *
 * Responsible for creating and returning a configured instance of the Help Hub based on the specified type.
 * This class provides a standardized way to instantiate the Help Hub with the correct data configuration.
 *
 * @since   TBD
 * @package TEC\Common\Help_Hub
 */

namespace TEC\Common\Help_Hub;

use TEC\Common\Help_Hub\Resource_Data\ET_Hub_Resource_Data;
use TEC\Common\Help_Hub\Resource_Data\TEC_Hub_Resource_Data;
use InvalidArgumentException;

/**
 * Class Help_Hub_Factory
 *
 * Factory class to instantiate Help Hub instances with specific data configurations.
 *
 * @since   TBD
 *
 * @package TEC\Common\Help_Hub
 */
class Help_Hub_Factory {

	/**
	 * Creates a new Help Hub instance configured with the appropriate data.
	 *
	 * This method initializes a new `Hub` instance and applies the relevant data configuration
	 * based on the provided `$type`. Throws an exception for unrecognized types.
	 *
	 * @since TBD
	 *
	 * @param string $type The type of data configuration needed for the Help Hub.
	 *                     Accepts 'tec_events' or 'event_tickets'.
	 *
	 * @return Hub Configured instance of Help Hub.
	 * @throws InvalidArgumentException If an unknown type is provided.
	 */
	public static function create( string $type ): Hub {
		$help_hub = new Hub();

		if ( ! defined( 'DOCSBOT_SUPPORT_KEY' ) ) {
			// @todo Define the DOCSBOT support key.
			define( 'DOCSBOT_SUPPORT_KEY', '' );
		}
		if ( ! defined( 'ZENDESK_CHAT_KEY' ) ) {
			define( 'ZENDESK_CHAT_KEY', '' );
		}

		switch ( $type ) {
			case 'tec_events':
				$help_hub->setup( new TEC_Hub_Resource_Data() );
				break;

			case 'event_tickets':
				$help_hub->setup( new ET_Hub_Resource_Data() );
				break;

			default:
				throw new InvalidArgumentException( "Unknown HelpHub type: {$type}" );
		}

		return $help_hub;
	}
}
