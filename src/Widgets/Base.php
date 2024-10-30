<?php

namespace JarrydAndTheJackles\Elementor_Random_Image\Widgets;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Exception;

/**
 * Base Widget.
 *
 * @since 1.4.0
 */
abstract class Base extends Widget_Base {
	/**
	 * @var bool
	 */
	protected $_has_template_content = false;

	/**
	 * JjRandomArticle\Widgets\Base constructor.
	 *
	 * @param array $data
	 * @param null $args
	 *
	 * @throws Exception
	 */
	final public function __construct( $data = [], $args = [] ) {
		parent::__construct( $data, $args );

		// Register our stylesheets and scripts
		$this->register_styles();
		$this->register_scripts();

		// Add the stylesheets and scripts we are dependant on.
		$this->add_style_dependence();
		$this->add_script_dependence();
	}

	/**
	 * Get widget name.
	 *
	 * Retrieve Random_Article widget name.
	 *
	 * @return string Widget name.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_name() {
		return jarrydandthejackles_elementor_random_image()->get_plugin_name();
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve Random_Article widget icon.
	 *
	 * @return string Widget icon.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_icon() {
		return 'eicon-image-box';
	}

	public function get_categories() {
		return array_merge( parent::get_categories(), [ 'jarrydandthejackles' ] );
	}

	/**
	 * Used to register all our required stylesheets.
	 * e.g.
	 *<code>
	 * wp_register_style(
	 *     'bootstrap',
	 *     plugins_url( 'assets/css/bootstrap.css', JJ_RANDOM_ARTICLE_PATH ),
	 *     null,
	 *     '3.3.6'
	 * );
	 *</code>
	 *
	 * @return void
	 */
	protected function register_styles() {
	}

	/**
	 * Used to register all our required scripts.
	 * e.g.
	 *<code>
	 * wp_register_style(
	 *     'bootstrap',
	 *     plugins_url( 'assets/css/bootstrap.min.js', JJ_RANDOM_ARTICLE_PATH ),
	 *     null,
	 *     '3.3.6'
	 * );
	 *</code>
	 *
	 * @return void
	 */
	protected function register_scripts() {
	}

	/**
	 * Used to add our required stylesheet handlers.
	 * e.g.
	 * <code>
	 * $this->add_style_depends( 'bootstrap' );
	 * </code>
	 * @return void
	 */
	protected function add_style_dependence() {
	}

	/**
	 * Used to add our required script handlers.
	 * e.g.
	 * <code>
	 * $this->add_script_depends( 'bootstrap' );
	 * </code>
	 *
	 * @return void
	 */
	protected function add_script_dependence() {
	}

	/**
	 * Register all our controls. This method calls better organised methods that should be used instead.
	 *
	 * @return void
	 */
	protected function register_controls() {
		$this->register_content_controls();
		$this->register_styles_controls();
		$this->register_advanced_controls();
		$this->register_custom_controls();

		if ( $this->is_debugging() ) {
			$this->register_debugging_controls();
		}
	}

	/**
	 * Used to register our controls for the content tab.
	 *
	 * @return void
	 */
	protected function register_content_controls() {
	}

	/**
	 * Used to register our controls for the styles tab.
	 *
	 * @return void
	 */
	protected function register_styles_controls() {
	}

	/**
	 * Used to register our controls for the advanced tab.
	 *
	 * @return void
	 */
	protected function register_advanced_controls() {
	}

	/**
	 * Used to register our custom controls. These can exist in any tab.
	 *
	 * @return void
	 */
	protected function register_custom_controls() {
	}

	/**
	 * Used to register our controls to display debugging information.
	 *
	 * @return void
	 */
	protected function register_debugging_controls() {
		// Debugging info
		$this->start_controls_section( 'debug', [
			'label' => __( 'Debug', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'   => Controls_Manager::TAB_ADVANCED,
		] );

		$this->add_control( 'info', [
			'label' => __( 'Data Dump', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'  => Controls_Manager::RAW_HTML,
			'raw'   => '<pre>' . $this->debug_html() . '</pre>',
		] );

		$this->end_controls_section();
	}

	/**
	 * Returns the output for html rendering of debugging information.
	 *
	 * @return string
	 */
	protected function debug_html() {
		ob_start();
		var_dump( $this->debug_info() );

		return (string) ob_get_clean();
	}

	/**
	 * Return the information to be used for debugging.
	 *
	 * @return array
	 */
	protected function debug_info() {
		return $this->get_settings_for_display();
	}

	/**
	 * Used to determine if we are in debugging mode or not.
	 *
	 * @return bool
	 */
	protected function is_debugging() {
		return false;
//		return WP_DEBUG;
	}
}
