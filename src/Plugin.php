<?php

namespace JarrydAndTheJackles\Elementor_Random_Image;

use Elementor\Controls_Manager;
use Elementor\Elements_Manager;
use Elementor\Plugin as ElementorPlugin;
use Elementor\Widgets_Manager;
use JarrydAndTheJackles\Elementor_Random_Image\Widgets\RandomArticle;
use JarrydAndTheJackles\Elementor_Random_Image\Widgets\WordsearchCreator;

/**
 * Main Elementor Test Extension Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class Plugin {

	/**
	 * Instance
	 *
	 * @since 1.0.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Plugin|null The single instance of the class.
	 */
	private static ?Plugin $_instance = null;
	/**
	 * @var AutoLoader|null
	 */
	private static ?AutoLoader $auto_loader = null;
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	public string $minimum_elementor_version;
	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	public string $minimum_php_version;
	/**
	 * @var string
	 */
	private string $plugin_name;
	/**
	 * @var string
	 */
	private string $textdomain;
	/**
	 * @var string
	 */
	private string $textdomain_directory;
	/**
	 * @var string
	 */
	private string $version;
	/**
	 * @var string
	 */
	private string $plugin_file;
	/**
	 * @var string
	 */
	private string $plugin_directory;

	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {
		$this->plugin_name               = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_NAME;
		$this->plugin_file               = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_PATH;
		$this->plugin_directory          = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_DIR;
		$this->textdomain                = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_TEXTDOMAIN;
		$this->textdomain_directory      = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_I18N_DIR;
		$this->version                   = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_VERSION;
		$this->minimum_php_version       = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_MINIMUM_PHP_VERSION;
		$this->minimum_elementor_version = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_MINIMUM_ELEMENTOR_VERSION;

		add_action( 'plugins_loaded', [ $this, 'on_plugins_loaded' ] );

		// Flush rewrite rules
		add_action( 'init', 'flush_rewrite_rules', 10 );

		// Set the autoloader for all classes.
		if ( ! self::$auto_loader ) {
			require_once __DIR__ . '/AutoLoader.php';
			self::$auto_loader = AutoLoader::instance();
		}
	}

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @return Plugin An instance of the class.
	 * @since 1.0.0
	 *
	 * @access public
	 * @static
	 *
	 */
	public static function instance(): Plugin {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	/**
	 * @return string
	 */
	public function get_plugin_name(): string {
		return $this->plugin_name;
	}

	/**
	 * @return string
	 */
	public function get_plugin_file(): string {
		return $this->plugin_file;
	}

	/**
	 * @return string
	 */
	public function get_plugin_directory(): string {
		return $this->plugin_directory;
	}

	/**
	 * @return string
	 */
	public function get_version(): string {
		return $this->version;
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function on_plugins_loaded(): void {
		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}
	}

	/**
	 * Compatibility Checks
	 *
	 * Checks if the installed version of Elementor meets the plugin's minimum requirement.
	 * Checks if the installed PHP version meets the plugin's minimum requirement.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function is_compatible(): bool {
		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );

			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, $this->get_minimum_elementor_version(), '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );

			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, $this->get_minimum_php_version(), '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );

			return false;
		}

		return true;
	}

	/**
	 * @return string
	 */
	public function get_minimum_elementor_version(): string {
		return $this->minimum_elementor_version;
	}

	/**
	 * @return string
	 */
	public function get_minimum_php_version(): string {
		return $this->minimum_php_version;
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function init(): void {
		$this->i18n();

		// Add Plugin actions
		add_action( 'elementor/widgets/register', [ $this, 'init_widgets' ] );
		add_action( 'elementor/elements/categories_registered', [ $this, 'init_categories' ] );
		add_action( 'elementor/controls/register', [ $this, 'init_controls' ] );
	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function i18n(): void {
		load_plugin_textdomain( $this->get_textdomain(), false, $this->get_textdomain_directory() . DIRECTORY_SEPARATOR );
	}

	/**
	 * @return string
	 */
	public function get_textdomain(): string {
		return $this->textdomain;
	}

	/**
	 * @return string
	 */
	public function get_textdomain_directory(): string {
		return $this->textdomain_directory;
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @param Widgets_Manager $widgets_manager Elementor widgets manager.
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function init_widgets( Widgets_Manager $widgets_manager ): void {
		// Register widget
		$widgets_manager->register( new RandomArticle() );
		$widgets_manager->register( new WordsearchCreator() );
	}

	/**
	 * Register widget category.
	 *
	 * @param Elements_Manager $elements_manager
	 *
	 * @access public
	 * @since 2.0.0
	 */
	public function init_categories( Elements_Manager $elements_manager ): void {
		$elements_manager->add_category( 'jarrydandthejackles', [
			'title' => __( 'Jarryd And The Jackles', 'jarrydandthejackles' ),
			'icon'  => 'fa fa-plug'
		] );
	}

	/**
	 * Init Controls
	 *
	 * Include controls files and register them
	 *
	 * @param Controls_Manager $controls_manager Elementor controls manager.
	 *
	 * @since 2.0.0
	 * @access public
	 */
	public function init_controls( Controls_Manager $controls_manager ): void {
		// Include Control files
//		require_once __DIR__ . '/class-jj-random-article-control.php' ;

		// Register control
//		Plugin::$instance->controls_manager->register_control( 'control-type-', new Test_Control() );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin(): void {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', $this->get_textdomain() ),
			'<strong>' . esc_html__( 'Elementor Random Image', $this->get_textdomain() ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', $this->get_textdomain() ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version(): void {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', $this->get_textdomain() ),
			'<strong>' . esc_html__( 'Elementor Random Image', $this->get_textdomain() ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', $this->get_textdomain() ) . '</strong>',
			$this->get_minimum_elementor_version()
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version(): void {
		if ( isset( $_GET['activate'] ) ) {
			unset( $_GET['activate'] );
		}

		$message = sprintf(
		/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', $this->get_textdomain() ),
			'<strong>' . esc_html__( 'Elementor Random Image', $this->get_textdomain() ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', $this->get_textdomain() ) . '</strong>',
			$this->get_minimum_php_version()
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
	}
}
