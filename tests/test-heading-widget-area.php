<?php
/**
 * Regression tests for heading widgets.
 *
 * @package snow-monkey-heading-widget-area
 */

class HeadingWidgetAreaTest extends WP_UnitTestCase {

	private $sidebars;

	public function set_up() {
		parent::set_up();
		$this->sidebars = wp_get_sidebars_widgets();
		wp_register_sidebar_widget(
			'heading-widget-test',
			'Heading widget fixture',
			function () {
				echo '<p>Heading widget fixture</p>';
			}
		);
		wp_set_sidebars_widgets( array( 'heading-widget-area' => array( 'heading-widget-test' ) ) );
	}

	public function tear_down() {
		wp_unregister_sidebar_widget( 'heading-widget-test' );
		wp_set_sidebars_widgets( $this->sidebars );
		parent::tear_down();
	}

	private function render_widget_area() {
		ob_start();
		do_action( 'snow_monkey_after_entry_content' );
		return ob_get_clean();
	}

	public function test_sidebar_is_registered_with_snow_monkey() {
		$this->assertSame( 'snow-monkey', wp_get_theme()->get_template() );
		$this->assertTrue( is_registered_sidebar( 'heading-widget-area' ) );
	}

	public function test_single_post_renders_an_initially_hidden_widget() {
		$this->go_to( get_permalink( self::factory()->post->create() ) );
		$html = $this->render_widget_area();
		$this->assertStringContainsString( 'class="l-heading-widget-area"', $html );
		$this->assertStringContainsString( 'aria-hidden="true"', $html );
		$this->assertStringContainsString( 'Heading widget fixture', $html );
	}

	public function test_empty_sidebar_does_not_render() {
		$this->go_to( get_permalink( self::factory()->post->create() ) );
		wp_set_sidebars_widgets( array( 'heading-widget-area' => array() ) );
		$this->assertSame( '', $this->render_widget_area() );
	}

	public function test_pages_do_not_render_by_default() {
		$this->go_to( get_permalink( self::factory()->post->create( array( 'post_type' => 'page' ) ) ) );
		$this->assertSame( '', $this->render_widget_area() );
	}

	public function test_post_type_filter_can_enable_pages() {
		$this->go_to( get_permalink( self::factory()->post->create( array( 'post_type' => 'page' ) ) ) );
		$allow_pages = function ( $post_types ) {
			$post_types[] = 'page';
			return $post_types;
		};
		add_filter( 'snow_monkey_heading_widget_area_allow_post_types', $allow_pages );
		try {
			$this->assertStringContainsString( 'Heading widget fixture', $this->render_widget_area() );
		} finally {
			remove_filter( 'snow_monkey_heading_widget_area_allow_post_types', $allow_pages );
		}
	}
}
