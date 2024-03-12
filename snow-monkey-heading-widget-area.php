<?php
/**
 * Plugin name: Snow Monkey Heading Widget Area
 * Description: A plugin that adds a widget area to be displayed above the first heading of posts.
 * Version: 2.2.0
 * Tested up to: 6.5
 * Requires at least: 6.1
 * Requires PHP: 7.4
 * Requires Snow Monkey: 19.0.0
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

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'plugins_loaded', array( $this, '_bootstrap' ) );
	}

	/**
	 * Bootstrap.
	 */
	public function _bootstrap() {
		load_plugin_textdomain( 'snow-monkey-heading-widget-area', false, basename( __DIR__ ) . '/languages' );

		add_action( 'init', array( $this, '_activate_autoupdate' ) );

		$theme = wp_get_theme( get_template() );
		if ( 'snow-monkey' !== $theme->template && 'snow-monkey/resources' !== $theme->template ) {
			add_action(
				'admin_notices',
				function() {
					?>
					<div class="notice notice-warning is-dismissible">
						<p>
							<?php esc_html_e( '[Snow Monkey Heading Widget Area] Needs the Snow Monkey.', 'snow-monkey-heading-widget-area' ); ?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		$data = get_file_data(
			__FILE__,
			array(
				'RequiresSnowMonkey' => 'Requires Snow Monkey',
			)
		);

		if (
			isset( $data['RequiresSnowMonkey'] ) &&
			version_compare( $theme->get( 'Version' ), $data['RequiresSnowMonkey'], '<' )
		) {
			add_action(
				'admin_notices',
				function() use ( $data ) {
					?>
					<div class="notice notice-warning is-dismissible">
						<p>
							<?php
							echo esc_html(
								sprintf(
									// translators: %1$s: version
									__(
										'[Snow Monkey Heading Widget Area] Needs the Snow Monkey %1$s or more.',
										'snow-monkey-heading-widget-area'
									),
									'v' . $data['RequiresSnowMonkey']
								)
							);
							?>
						</p>
					</div>
					<?php
				}
			);
			return;
		}

		add_action( 'widgets_init', array( $this, '_widgets_init' ) );
		add_action( 'snow_monkey_after_entry_content', array( $this, '_display_widget_area' ) );
		add_action( 'wp_enqueue_scripts', array( $this, '_wp_enqueue_scripts' ), 9 );
	}

	/**
	 * Add widget area.
	 */
	public function _widgets_init() {
		register_sidebar(
			array(
				'name'          => __( '1st heading widget area', 'snow-monkey-heading-widget-area' ),
				'description'   => __( 'These widgets are displayed above the first heading of posts.', 'snow-monkey-heading-widget-area' ),
				'id'            => $this->sidebar_id,
				'before_widget' => '<div id="%1$s" class="c-widget %2$s">',
				'after_widget'  => '</div>',
				'before_title'  => '<h3 class="c-widget__title">',
				'after_title'   => '</h3>',
			)
		);
	}

	/**
	 * Display widget area.
	 */
	public function _display_widget_area() {
		$post_types = apply_filters( 'snow_monkey_heading_widget_area_allow_post_types', array( 'post' ) );

		if ( ! is_singular( $post_types ) ) {
			return;
		}

		if ( ! $this->_has_sidebar() ) {
			return;
		}

		$bootstrap = new WP_Plugin_View_Controller\Bootstrap(
			array(
				'prefix' => 'snow_monkey_heading_widget_area_',
				'path'   => SNOW_MONKEY_HEADING_WIDGET_AREA_PATH . '/templates/',
			)
		);

		$bootstrap->render( 'heading-widget-area', null, array( 'sidebar_id' => $this->sidebar_id ) );
	}

	/**
	 * Enqueue assets.
	 */
	public function _wp_enqueue_scripts() {
		if ( ! $this->_has_sidebar() ) {
			return;
		}

		wp_enqueue_script(
			'snow-monkey-heading-widget-area',
			SNOW_MONKEY_HEADING_WIDGET_AREA_URL . '/dist/js/app.js',
			array(),
			filemtime( SNOW_MONKEY_HEADING_WIDGET_AREA_PATH . '/dist/js/app.js' ),
			true
		);

		wp_enqueue_style(
			'snow-monkey-heading-widget-area',
			SNOW_MONKEY_HEADING_WIDGET_AREA_URL . '/dist/css/app.css',
			array( \Framework\Helper::get_main_style_handle() ),
			filemtime( SNOW_MONKEY_HEADING_WIDGET_AREA_PATH . '/dist/css/app.css' )
		);
	}

	/**
	 * Activate auto update using GitHub.
	 */
	public function _activate_autoupdate() {
		new Updater(
			plugin_basename( __FILE__ ),
			'inc2734',
			'snow-monkey-heading-widget-area',
			array(
				'homepage' => 'https://snow-monkey.2inc.org',
			)
		);
	}

	/**
	 * Return true when had sidebar.
	 *
	 * @return boolean
	 */
	private function _has_sidebar() {
		return is_active_sidebar( $this->sidebar_id ) && is_registered_sidebar( $this->sidebar_id );
	}
}

require_once( __DIR__ . '/vendor/autoload.php' );
new Bootstrap();
