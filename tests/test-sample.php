<?php
/**
 * Class SampleTest
 *
 * @package snow-monkey-heading-widget-area
 */

/**
 * Sample test case.
 */
class SampleTest extends WP_UnitTestCase {

	/**
	 * A single example test.
	 */
	function test_sample() {
		$updater = new \Inc2734\WP_GitHub_Plugin_Updater\Bootstrap(
			plugin_basename( __FILE__ ),
			'inc2734',
			'snow-monkey-heading-widget-area'
		);

		$this->assertTrue( is_a( $updater, '\Inc2734\WP_GitHub_Plugin_Updater\Bootstrap' ) );
	}
}
