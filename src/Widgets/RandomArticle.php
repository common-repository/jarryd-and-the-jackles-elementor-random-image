<?php

namespace JarrydAndTheJackles\Elementor_Random_Image\Widgets;

use Elementor\Control_Media;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Exception;

/**
 * Elementor RandomImage Widget.
 *
 * @since 1.0.0
 */
class RandomArticle extends Base {
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
		return jarrydandthejackles_elementor_random_image()->get_plugin_name(). '-random-image';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve Random_Article widget title.
	 *
	 * @return string Widget title.
	 * @since 1.0.0
	 * @access public
	 *
	 */
	public function get_title(): string {
		return __( 'Random Article', jarrydandthejackles_elementor_random_image()->get_textdomain() );
	}

	protected function register_content_controls(): void {
		$this->start_controls_section( 'section_image_carousel', [
			'label' => __( 'Image Selection', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'tab'   => Controls_Manager::TAB_CONTENT,
		] );

		$this->add_control( 'carousel', [
			'label'      => __( 'Add Images', jarrydandthejackles_elementor_random_image()->get_textdomain() ),
			'type'       => Controls_Manager::GALLERY,
			'default'    => [],
			'show_label' => false,
			'dynamic'    => [
				'active' => true,
			],
		] );

		$this->end_controls_section();
	}

	/**
	 * @throws Exception
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$images = array_values( $settings['carousel'] ?? [] );
		if ( ! $images ) {
			return;
		}
		$count = count( $images );
		$image = random_int( 0, ( $count - 1 ) );
		$image = $images[ $image ] ?? null;
		if ( ! $image ) {
			return;
		}

		$image_url = Group_Control_Image_Size::get_attachment_image_src( $image['id'], 'full', [ 'full_size' => 'full' ] );

		echo '<img class="' . $this->get_name() . '-image" src="' . esc_attr( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $image ) ) . '" />';
	}
}
