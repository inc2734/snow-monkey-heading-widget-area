<?php
/**
 * Plugin name: Snow Monkey Heading Widget Area
 * Description: A plugin that adds a widget area to be displayed above the first heading of posts.
 * Version: 1.2.0
 * Tested up to: 5.5
 * Requires at least: 5.5
 * Requires PHP: 5.6
 *
 * @package snow-monkey-heading-widget-area
 * @author inc2734
 * @license GPL-2.0+
 */

namespace Snow_Monkey\Plugin\SnowMonkeyHeadingWidgetArea;

use Inc2734\WP_Plugin_View_Controller;
use Inc2734\WP_GitHub_Plugin_Updater\Bootstrap as Updater;

define( 'SNOW_MONKEY_HEADING_WIDGET_AREA_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
define( 'SNOW_MONKEY_HEADING_WIDGET_AREA_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );

class Bootstrap {

	/**
	 * The ID of heading sidebar
	 *
	 * @var string
	 */
	protected $sidebar_id = 'heading-widget-area';

	public function __construct() {
		add_action( 'plugins_loaded', [ $this, '_bootstrap' ] );
	}

	public function _bootstrap() {
		load_plugin_textdomain( 'snow-monkey-heading-widget-area', false, basename( __DIR__ ) . '/languages' );

		add_action( 'init', [ $this, '_activate_autoupdate' ] );

		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			add_action( 'admin_notices', [ $this, '_admin_notice_no_snow_monkey' ] );
			return;
		}

		add_action( 'widgets_init', [ $this, '_widgets_init' ] );
		add_action( 'snow_monkey_after_entry_content', [ $this, '_display_widget_area' ] );
		add_action( 'wp_enqueue_scripts', [ $this, '_wp_enqueue_scripts' ], 9 );
	}

	/**
	 * Add widget area
	 *
	 * @return void
	 */
	public function _widgets_init() {
		register_sidebar(
			[
				'name'          => __( '1st heading widget area', 'snow-monkey-heading-widget-area' ),
				'description'   => __( 'These widgets are displayed above the first heading of posts.', 'snow-monkey-heading-widget-area' ),
				'id'            => $this->sidebar_id,
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="c-widget__title">',
				'after_title'   => '</h3>',
			]
		);
	}

	/**
	 * Display widget area
	 *
	 * @return void
	 */
	public function _display_widget_area() {
		$post_types = apply_filters( 'snow_monkey_heading_widget_area_allow_post_types', [ 'post' ] );

		if ( ! is_singular( $post_types ) ) {
			return;
		}

		if ( ! $this->_has_sidebar() ) {
			return;
		}

		$bootstrap = new WP_Plugin_View_Controller\Bootstrap(
			[
				'prefix' => 'snow_monkey_heading_widget_area_',
				'path'   => SNOW_MONKEY_HEADING_WIDGET_AREA_PATH . '/templates/',
			]
		);

		$bootstrap->render( 'heading-widget-area', null, [ 'sidebar_id' => $this->sidebar_id ] );
	}

	/**
	 * Enqueue assets
	 *
	 * @return void
	 */
	public function _wp_enqueue_scripts() {
		if ( ! $this->_has_sidebar() ) {
			return;
		}

		wp_enqueue_script(
			'snow-monkey-heading-widget-area',
			SNOW_MONKEY_HEADING_WIDGET_AREA_URL . '/dist/js/app.js',
			[],
			filemtime( SNOW_MONKEY_HEADING_WIDGET_AREA_PATH . '/dist/js/app.js' ),
			true
		);

		wp_enqueue_style(
			'snow-monkey-heading-widget-area',
			SNOW_MONKEY_HEADING_WIDGET_AREA_URL . '/dist/css/app.css',
			[ \Framework\Helper::get_main_style_handle() ],
			filemtime( SNOW_MONKEY_HEADING_WIDGET_AREA_PATH . '/dist/css/app.css' )
		);
	}

	/**
	 * Activate auto update using GitHub
	 *
	 * @return [void]
	 */
	public function _activate_autoupdate() {
		new Updater(
			plugin_basename( __FILE__ ),
			'inc2734',
			'snow-monkey-heading-widget-area',
			[
				'homepage' => 'https://snow-monkey.2inc.org',
			]
		);
	}

	/**
	 * Admin notice for no Snow Monkey
	 *
	 * @return void
	 */
	public function _admin_notice_no_snow_monkey() {
		?>
		<div class="notice notice-warning is-dismissible">
			<p>
				<?php esc_html_e( '[Snow Monkey Heading Widget Area] Needs the Snow Monkey.', 'snow-monkey-heading-widget-area' ); ?>
			</p>
		</div>
		<?php
	}

	/**
	 * Return true when had sidebar
	 *
	 * @return boolean
	 */
	private function _has_sidebar() {
		return is_active_sidebar( $this->sidebar_id ) && is_registered_sidebar( $this->sidebar_id );
	}
}

require_once( __DIR__ . '/vendor/autoload.php' );
new Bootstrap();
