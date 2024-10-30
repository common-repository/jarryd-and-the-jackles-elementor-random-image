<?php

namespace JarrydAndTheJackles\Elementor_Random_Image\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;
use Exception;
use JsonException;

/**
 * Elementor Random_Article Widget.
 *
 * Elementor widget that inserts an embeddable content into the page, from any given URL.
 *
 * @since 1.0.0
 */
class WordsearchCreator extends Base {
	public function get_icon() {
		return 'eicon-gallery-grid';
	}

	public function get_title() {
		return __( 'Wordsearch Creator', jarrydandthejackles_elementor_random_image()->get_textdomain() );
	}

	public function get_name() {
		return parent::get_name() . '-wordsearch-creator-widget';
	}

	protected function register_scripts() {
		return wp_register_script(
			       'jarrydandthejackles-wordsearch',
			       plugins_url( 'assets/js/wordsearch.bundle.js', jarrydandthejackles_elementor_random_image()->get_plugin_file() ),
			       [],
			       '0.1.2',
		       ) && wp_register_script(
			       jarrydandthejackles_elementor_random_image()->get_textdomain() . 'wordsearch-creator',
			       null,
			       [ 'jarrydandthejackles-wordsearch' ],
			       jarrydandthejackles_elementor_random_image()->get_version(),
			       true
		       );
	}

	protected function add_script_dependence() {
		$this->add_script_depends( jarrydandthejackles_elementor_random_image()->get_textdomain() . 'wordsearch-creator' );
	}

	protected function register_content_controls() {
		// register details section
		$this->start_controls_section( 'details', [
			'label' => __( 'Details', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'width', [
			'label'       => __( 'Width', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::NUMBER,
			'description' => __( 'The number of columns in the wordsearch', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'min'         => 0,
			'step'        => 1,
			'default'     => 8,
		] );

		$this->add_control( 'height', [
			'label'       => __( 'Height', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::NUMBER,
			'description' => __( 'The number of rows in the wordsearch', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'min'         => 0,
			'step'        => 1,
			'default'     => 8,
		] );

		$repeater = new Repeater();

		$repeater->add_control( 'text', [
			'label'       => __( 'Word', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'A word to be found', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'default'     => 'FIND',
		] );

		$repeater->add_control( 'color', [
			'label'       => __( 'Color', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::COLOR,
			'description' => __( 'The colour to display the word in', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'default'     => '#FF0D7B',
		] );

		$this->add_control( 'words', [
			'label'   => __( 'Words', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'    => Controls_Manager::REPEATER,
			'fields'  => $repeater->get_controls(),
			'default' => [
				[ 'text' => 'FIND', 'color' => '#FF0D7B' ],
				[ 'text' => 'ME', 'color' => '#FF0D7B' ],
			]
		] );

		$this->end_controls_section();

		// register directions section
		$this->start_controls_section( 'directions', [
			'label'       => __( 'Directions', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'         => Controls_Manager::TAB_CONTENT,
			'description' => __( 'The current active directions.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
		] );

		foreach ( $this->get_directions_control_data() as $direction ) {
			$this->add_control( $direction['id'], [
				'label'        => $direction['label'],
				'type'         => Controls_Manager::SWITCHER,
				'description'  => $direction['description'],
				'return_value' => 'yes',
				'default'      => $direction['default'],
			] );
		}

		$this->end_controls_section();
	}

	protected function register_styles_controls() {
		//register base styles section
		$this->start_controls_section( 'base_styles', [
			'label' => __( 'Base Styles', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'title_color', [
			'label'     => __( 'Title Color', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} #ws-score' => 'color: {{VALUE}}',
			],
			'default'   => '#FF0D7B',
		] );

		$this->end_controls_section();

		// register cell options section
		$this->start_controls_section( 'cell_options', [
			'label' => __( 'Cell Options', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'   => Controls_Manager::TAB_STYLE,
		] );

		$this->add_control( 'cell_width', [
			'label'       => __( 'Cell Width', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::NUMBER,
			'description' => __( 'The width of each cell.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'min'         => 0,
			'step'        => 1,
			'default'     => 25,
		] );

		$this->add_control( 'cell_height', [
			'label'       => __( 'Cell Height', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::NUMBER,
			'description' => __( 'The height of each cell.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'min'         => 0,
			'step'        => 1,
			'default'     => 25,
		] );

		$this->add_control( 'cell_font', [
			'label'       => __( 'Cell Font', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::FONT,
			'description' => __( 'The font of each cell.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'default'     => 'Calibri',
		] );

		$this->add_control( 'cell_font_size', [
			'label'       => __( 'Cell Font Size', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::NUMBER,
			'description' => __( 'The font size (in px) of each cell.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'min'         => 0,
			'step'        => 1,
			'default'     => 18,
		] );

		$this->add_control( 'cell_color', [
			'label'       => __( 'Cell Font Colour', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::COLOR,
			'description' => __( 'The font colour of each cell.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'default'     => '#333',
		] );

		$this->end_controls_section();
	}

	protected function register_advanced_controls() {
		// register extra section
		$this->start_controls_section( 'extra', [
			'label' => __( 'Extra', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'   => Controls_Manager::TAB_ADVANCED,
		] );

		$this->add_control( 'css_prefix', [
			'label'       => __( 'CSS selector prefix', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'        => Controls_Manager::TEXT,
			'description' => __( 'The prefix to use for selecting the chosen words. Used for prevent overriding another wordsearch.', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'default'     => 'ws-chosen-',
		] );

		$this->end_controls_section();
	}

	/**
	 * @return array[]
	 */
	protected function get_directions_control_data() {
		return [
			[
				'key'         => 'down',
				'id'          => 'direction_down',
				'label'       => __( 'Down', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Top to Bottom', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'left',
				'id'          => 'direction_left',
				'label'       => __( 'Left', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Right to Left', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'left_down',
				'id'          => 'direction_left_down',
				'label'       => __( 'Left Down', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Top Right to Bottom Left', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'left_up',
				'id'          => 'direction_left_up',
				'label'       => __( 'Left Up', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Bottom Right to Top Left', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'right',
				'id'          => 'direction_right',
				'label'       => __( 'Right', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Left to Right', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'right_down',
				'id'          => 'direction_right_down',
				'label'       => __( 'Right Down', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Top Left to Bottom Right', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'right_up',
				'id'          => 'direction_right_up',
				'label'       => __( 'Right Up', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Bottom Left to Top Right', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			],
			[
				'key'         => 'up',
				'id'          => 'direction_up',
				'label'       => __( 'Up', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'description' => __( 'Bottom to Top', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
				'default'     => 'yes',
			]
		];
	}

	/**
	 * @return string
	 */
	protected function get_parent_id() {
		try {
			$suffix = md5( random_bytes( 8 ) );
		} catch ( Exception $e ) {
			$suffix = str_shuffle( md5( $this->get_title() ) );
		}

		return $this->get_name() . '-wrapper-' . $suffix;
	}

	/**
	 * @throws JsonException
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$words = [];
		$css   = '';
		foreach ( $settings['words'] as $word ) {
			$words[] = $word['text'];
			$text    = strtolower( $word['text'] );
			$css     .= "#{$settings['css_prefix']}{$text}.ws-word-found { color: {$word['color']}; }";
			$css     .= ".ws-found.{$settings['css_prefix']}{$text} { background: {$word['color']}; }";
		}
		$words = json_encode( $words, JSON_THROW_ON_ERROR );

		$directions = [
			'inactive' => [],
		];
		foreach ( $this->get_directions_control_data() as $direction ) {
			if ( $settings[ $direction['id'] ] !== 'yes' ) {
				$directions['inactive'][] = $direction['key'];
			}
		}
		$directions = json_encode( $directions, JSON_THROW_ON_ERROR );

		$parent_id = $this->get_parent_id();
		$cell_font = "400 {$settings['cell_font_size']}px {$settings['cell_font']}";

		echo <<<EOF
<style>
{$css}
</style>
<div id="{$parent_id}"></div>
<script defer type="text/javascript">
(function() {
  var timeoutID,
      parent,
      creator;
  
  function createJJWordSearch() {
    parent = document.querySelector('#{$parent_id}');
    if (timeoutID) {
      clearTimeout(timeoutID);
    }
    
    if (parent === null || typeof JJWordSearch === 'undefined') {
      // simple wait to ensure script can run.
      timeoutID = setTimeout(createJJWordSearch, 250);
      return;
    }
    
    creator = new JJWordSearch.Creator({
      parentId: '{$parent_id}',
      width: Number.parseInt('{$settings['width']}'),
      height: Number.parseInt('{$settings['height']}'),
      words: JSON.parse('{$words}'),
      directions: JSON.parse('{$directions}'),
      cellOptions: {
        width: Number.parseInt('{$settings['cell_width']}'),
        height: Number.parseInt('{$settings['cell_height']}'),
        font: '{$cell_font}',
        style: '{$settings['cell_color']}'
      }
    });
    
    creator.create();
  }
  
  createJJWordSearch();
})();
</script>
EOF;
	}
}
