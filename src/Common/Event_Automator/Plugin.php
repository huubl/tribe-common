<?php
/**
 * The main Event Automator plugin service provider: it bootstraps the plugin code.
 *
 * @since   1.0.0
 *
 * @package TEC\Common\Event_Automator
 */

namespace TEC\Common\Event_Automator;

use TEC\Common\Event_Automator\Hooks as Hooks_Provider;
use TEC\Common\Event_Automator\Service_Providers\Context_Provider;
use TEC\Common\Event_Automator\Zapier\Zapier_Provider;
use TEC\Common\Event_Automator\Power_Automate\Power_Automate_Provider;
use Tribe__Autoloader;

/**
 * Class Plugin
 *
 * @since   1.0.0
 *
 * @package TEC\Common\Event_Automator
 */
class Plugin {
	/**
	 * Stores the version for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public const VERSION = '1.7.0';

	/**
	 * Stores the base slug for the plugin.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const SLUG = 'event-automator';

	/**
	 * Stores the base slug for the extension.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	const FILE = EVENT_AUTOMATOR_FILE;

	/**
	 * The slug that will be used to identify HTTP requests the plugin should handle.
	 *
	 * @since 1.0.0
	 *
	 * @var string
	 */
	public static $request_slug = 'event_automator_request';


	/**
	 * @since 1.0.0
	 *
	 * @var string Plugin Directory.
	 */
	public $plugin_dir;

	/**
	 * @since 1.0.0
	 *
	 * @var string Plugin path.
	 */
	public $plugin_path;

	/**
	 * @since 1.0.0
	 *
	 * @var string Plugin URL.
	 */
	public $plugin_url;

	/**
	 * Allows this class to be used as a singleton.
	 *
	 * Note this specifically doesn't have a typing, just a type hinting via Docblocks, it helps
	 * avoid problems with deprecation since this is loaded so early.
	 *
	 * @since 1.5.0
	 *
	 * @var \Tribe__Container
	 */
	protected $container;

	/**
	 * Sets the container for the class.
	 *
	 * Note this specifically doesn't have a typing for the container, just a type hinting via Docblocks, it helps
	 * avoid problems with deprecation since this is loaded so early.
	 *
	 * @since 1.5.0
	 *
	 * @param ?\Tribe__Container $container The container to use, if any. If not provided, the global container will be used.
	 *
	 */
	public function set_container( $container = null ): void {
		$this->container = $container ?: tribe();
	}

	/**
	 * Boots the plugin class and registers it as a singleton.
	 *
	 * Note this specifically doesn't have a typing for the container, just a type hinting via Docblocks, it helps
	 * avoid problems with deprecation since this is loaded so early.
	 *
	 * @since 1.5.0
	 *
	 * @param ?\Tribe__Container $container The container to use, if any. If not provided, the global container will be used.
	 */
	public static function boot( $container = null ): void {
		$plugin = new static();
		$plugin->register_autoloader();
		$plugin->set_container( $container );
		$plugin->container->singleton( static::class, $plugin );

		$plugin->register();
	}

	/**
	 * Setup the Extension's properties.
	 *
	 * This always executes even if the required plugins are not present.
	 */
	public function register() {
		tec_automator_load_text_domain();
		// Set up the plugin provider properties.
		$this->plugin_path = trailingslashit( dirname( static::FILE ) );
		$this->plugin_dir  = trailingslashit( basename( $this->plugin_path ) );
		$this->plugin_url  = plugins_url( $this->plugin_dir, $this->plugin_path );

		// Register this provider as the main one and use a bunch of aliases.
		$this->container->singleton( static::class, $this );
		$this->container->singleton( 'event-automator', $this );
		$this->container->singleton( 'event-automator.plugin', $this );

		if ( ! $this->check_plugin_dependencies() ) {
			// If the plugin dependency manifest is not met, then bail and stop here.
			return;
		}

		$this->register_autoloader();

		$this->container->register( Hooks_Provider::class );
		$this->container->register( PUE::class );
		$this->container->register( Context_Provider::class );
		$this->container->register( Zapier_Provider::class );
		$this->container->register( Power_Automate_Provider::class );
	}

	/**
	 * Register the Tribe Autoloader in Events Automator.
	 *
	 * @since 1.2.0
	 */
	protected function register_autoloader() {
		// Load Composer autoload file only if we've not included this file already.
		require_once dirname( EVENT_AUTOMATOR_FILE ) . '/vendor/autoload.php';

		$autoloader = Tribe__Autoloader::instance();

		// For namespaced classes.
		$autoloader->register_prefix(
			'\\TEC\\Event_Automator\\',
			$this->plugin_path . '/src/Event_Automator',
			'event-automator'
		);
	}

	/**
	 * Checks whether the plugin dependency manifest is satisfied or not.
	 *
	 * @since 1.0.0
	 *
	 * @return bool Whether the plugin dependency manifest is satisfied or not.
	 */
	protected function check_plugin_dependencies(): bool {
		$this->register_plugin_dependencies();

		if ( ! tribe_check_plugin( static::class ) ) {
			return false;
		}

		return true;
	}

	/**
	 * Registers the plugin and dependency manifest among those managed by Event Automator.
	 *
	 * @since 1.0.0
	 */
	protected function register_plugin_dependencies() {
		$plugin_register = new Plugin_Register();
		$plugin_register->register_plugin();

		$this->container->singleton( Plugin_Register::class, $plugin_register );
		$this->container->singleton( 'event-automator.plugin_register', $plugin_register );
	}
}
