<?php
/**
 * An async process implementation meant to test if the environment is compatible with it.
 *
 * @since TBD
 */

class Tribe__Process__Tester extends Tribe__Process__Handler {

	/**
	 * The name of the transient this class will set in its async task.
	 *
	 * @var
	 */
	protected $transient_name = 'tribe_supports_async_process';

	/**
	 * Handles the process immediately, not in an async manner.
	 *
	 * @since 4.7.12
	 *
	 * @param array|null $data_source If not provided the method will read the handler data from the
	 *                                request array.
	 *
	 * @return mixed
	 */
	public function sync_handle( array $data_source = null ) {
		/*
		 * The purpose of this class is exactly to make sure async processing works
		 * so it will do nothing if running in synchronous mode.
		 */
		return null;
	}

	/**
	 * Returns the name of the transient this class will set as part of its test.
	 *
	 * @since TBD
	 *
	 * @return string The set transient name.
	 */
	public function get_canary_transient() {
		return $this->transient_name;
	}

	/**
	 * Call the dispatch method, in its vanilla form, as the base class would.
	 *
	 * Since the purpose of this class is to test for async process support
	 * we do not want any option or env var to make this work in any other
	 * way but the async one.
	 *
	 * @since TBD
	 *
	 * @return mixed
	 */
	public function dispatch() {
		$url  = add_query_arg( $this->get_query_args(), $this->get_query_url() );
		$args = $this->get_post_args();

		return wp_remote_post( esc_url_raw( $url ), $args );
	}

	/**
	 * An override of the method implemented by the base Tribe Handler
	 * class to make sure the processing is done in async mode.
	 *
	 * This is the same code as the base WP_Background_Process class.
	 *
	 * @since TBD
	 */
	public function maybe_handle() {
		// Don't lock up other requests while processing
		session_write_close();

		check_ajax_referer( $this->identifier, 'nonce' );

		$this->handle();

		wp_die();
	}

	/**
	 * The task this class will perform is just setting a transient.
	 * The transient existence will be used as a canary to detect if
	 * background processing is supported.
	 *
	 * @since TBD
	 */
	protected function handle() {
		error_log( 'Handling' );
		set_transient( $this->transient_name, 1, HOUR_IN_SECONDS );
	}

	/**
	 * Returns this handler action identifier.
	 *
	 * @since TBD
	 *
	 * @return string This handler action identifier.
	 */
	public static function action() {
		return 'async_process_support_test';
	}
}