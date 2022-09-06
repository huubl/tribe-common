<?php

namespace Tribe;

/**
 * Class Context_LocationsTest
 *
 * @since   TBD
 *
 * @package Tribe
 */
class Context_LocationsTest extends \Codeception\TestCase\WPTestCase {
	/**
	 * @test
	 */
	public function is_main_query_should_not_error_on_wp_query_null() {
		$GLOBALS['wp_query'] = null;

		$context = new \Tribe__Context();
		$context->dangerously_repopulate_locations();

		$this->assertFalse( $context->is( 'is_main_query' ), 'When WP_Query is `null` it should be false.' );
	}

	/**
	 * @test
	 */
	public function is_main_query_should_not_error_on_wp_query_is_array() {
		$GLOBALS['wp_query'] = [ 'test' ];

		$context = new \Tribe__Context();
		$context->dangerously_repopulate_locations();

		$this->assertFalse( $context->get( 'is_main_query' ), 'When WP_Query is `array` it should be false.' );
	}

	/**
	 * @test
	 */
	public function is_main_query_should_not_error_on_wp_query_not_WP_Query_instance() {
		$GLOBALS['wp_query'] = (object) [];

		$context = new \Tribe__Context();
		$context->dangerously_repopulate_locations();

		$this->assertFalse( $context->get( 'is_main_query' ), 'When WP_Query is `stdObj` it should be false.' );
	}

	/**
	 * @test
	 */
	public function is_main_query_should_return_true_when_both_global_instances_are_the_same() {
		$GLOBALS['wp_query'] = new \WP_Query();
		$GLOBALS['wp_the_query'] = $GLOBALS['wp_query'];

		$context = new \Tribe__Context();
		$context->dangerously_repopulate_locations();

		$this->assertTrue( $context->get( 'is_main_query' ), 'When global $wp_query and $wp_the_query are the same it returns true.' );
	}
}