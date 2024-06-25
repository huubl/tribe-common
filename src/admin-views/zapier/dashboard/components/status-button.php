<?php
/**
 * View: Zapier Integration - Status Button to enable or disable endpoint.
 *
 * Override this template in your own theme by creating a file at:
 * [your-theme]/tribe/admin-views/zapier/dashboard/components/status
 * -button.php
 *
 * See more documentation about our views templating system.
 *
 * @since   1.4.0
 *
 * @version 1.4.0
 *
 * @link    http://evnt.is/1aiy
 *
 * @var array<string,array> $endpoint An array of the Zapier endpoint data.
 * @var Endpoints_Manager   $manager  The Endpoint Manager instance.
 * @var Url                 $url      The URLs handler for the integration.
 */

if ( $endpoint['missing_dependency'] ) {
	return;
}

$link  = $url->to_enable_endpoint_queue( $endpoint['id'] );
$label = _x( 'Enable', 'Enables a Zapier endpoint.', 'tribe-common' );
$confirmation = $manager->get_confirmation_to_enable_endpoint();
$type = 'enable';
if ( $endpoint['enabled'] ) {
	$link  = $url->to_disable_endpoint_queue( $endpoint['id'] );
	$label = _x( 'Disable', 'Disables a Zapier endpoint queue.', 'tribe-common' );
	$confirmation = $manager->get_confirmation_to_disable_endpoint( $endpoint['type'] );
	$type = 'disable';
}
?>
	<div class="tec-settings-connection-endpoint-dashboard-details-actions__<?php echo esc_html( $type ); ?>-wrap">
		<button
			class="tec-settings-connection-endpoint-dashboard-details-action__button tec-settings-connection-endpoint-dashboard-details-actions__<?php echo esc_html( $type ); ?> tec-common-zapier-details-actions__<?php echo esc_html( $type ); ?>"
			type="button"
			data-ajax-action-url="<?php echo $link; ?>"
			data-confirmation="<?php echo esc_html( $confirmation ); ?>"
		>
			<?php echo esc_html( $label ); ?>
		</button>
	</div>
<?php