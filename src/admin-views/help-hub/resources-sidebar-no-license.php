<?php
/**
 * The template that displays the resources sidebar.
 *
 * @var Tribe__Main $main      The main common object.
 * @var bool $is_opted_in      Whether the user has opted in to telemetry.
 * @var bool $is_license_valid Whether the user has any valid licenses.
 */

$stars_icon_url = tribe_resource_url( 'images/icons/stars.svg', false, null, $main );
$chat_icon_url  = tribe_resource_url( 'images/icons/chat-bubble.svg', false, null, $main );

?>

<div class="tec-settings-form__sidebar tec-help-resources__sidebar">
	<div class="tec-settings__sidebar-inner">
		<div class="tec-settings__sidebar-icon-wrap">
			<div>
				<img class="tec-settings-infobox-logo"
					src="<?php echo esc_url( $stars_icon_url ); ?>"
					alt="AI Chatbot logo"
				>
			</div>
			<div class="tec-settings__sidebar-icon-wrap-content">
					<h2>
						<?php echo esc_html_x( 'Our AI Chatbot is here to help you', 'Help page resources sidebar header', 'tribe-common' ); ?>
					</h2>
					<p>
						<?php
						echo esc_html_x(
							'You have questions? The TEC Chatbot has the answers.',
							'Call to action to use The Events Calendar Help Chatbot.',
							'tribe-common'
						);
						?>
					</p>
					<p>
						<a data-tab-target="tec-help-tab" href="javascript:void(0)">
							<?php
							echo esc_html_x(
								'Talk to TEC Chatbot',
								'Link to the Help Chatbot',
								'tribe-common'
							);
							?>
						</a>
					</p>

			</div>
		</div>
	</div>

	<?php $this->template( 'help-hub/shared-live-support' ); ?>
</div>
