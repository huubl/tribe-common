<?php
/**
 * The Zapier Canceled Event Triggers.
 *
 * @since   1.2.0
 * @package TEC\Common\Event_Automator\Zapier\Triggers;
 */

namespace TEC\Common\Event_Automator\Zapier\Triggers;

use TEC\Common\Event_Automator\Zapier\Trigger_Queue\Abstract_Trigger_Queue;
use Tribe__Events__Main as TEC;
use Tribe__Utils__Array as Arr;

/**
 * Class Canceled_Events
 *
 * @since   1.2.0
 *
 * @package TEC\Common\Event_Automator\Zapier\Triggers
 */
class Canceled_Events extends Abstract_Trigger_Queue {

	/**
	 * @inheritdoc
	 */
	protected static $queue_name = 'canceled_events';

	/**
	 * @inheritdoc
	 */
	protected function validate_for_trigger( $post_id, $data ) {
		$status = Arr::get( $data, 'status', false );
		if ( empty( $status ) ) {
			return false;
		}

		if ( $status !== 'canceled' ) {
			return false;
		}

		$post_status = get_post_status( $post_id );
		if ( $post_status !== 'publish' ) {
			return false;
		}

		return true;
	}
}
