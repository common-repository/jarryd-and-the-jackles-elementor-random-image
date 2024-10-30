<?php

use JarrydAndTheJackles\Elementor_Random_Image\Plugin;

/**
 * Jarryd And The Jackles - Elementor Random Image
 *
 * @link              https://jarrydandthejackles.com/
 * @since             1.0.0
 * @package           JarrydAndTheJackles/Elementor_Random_Image
 *
 * @wordpress-plugin
 * Plugin Name:       Jarryd And The Jackles - Elementor Random Image
 * Plugin URI:        https://wordpress.org/plugins/jarryd-and-the-jackles-elementor-random-image/
 * Description:       A random collection of elementor widgets to help make adding obscure functionality easy.
 * Version:           2.0.0
 * Requires at least: 5.0
 * Tested up to:      5.9
 * Requires PHP:      7.4
 * Author:            Jarryd And The Jackles
 * Author URI:        https://jarrydandthejackles.com/
 * License:           GPLv3
 * License URI:       https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       jarrydandthejackles-elementor-random-image
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Let's some constants we can work with.
 */
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_VERSION                   = '2.0.0';
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_MINIMUM_ELEMENTOR_VERSION = '3.0.0';
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_MINIMUM_PHP_VERSION       = '7.4';

// jarrydandthejackles_elementor_random_image
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_NAME       = 'jarrydandthejackles-elementor-random-image';
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_TEXTDOMAIN = 'jarrydandthejackles-elementor-random-image';

const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_PATH     = __FILE__;
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_DIR      = __DIR__;
const JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_I18N_DIR = JARRYDANDTHEJACKLES_ELEMENTOR_RANDOM_IMAGE_DIR . DIRECTORY_SEPARATOR . 'languages';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function jarrydandthejackles_elementor_random_image(): Plugin
{
	if ( ! class_exists( Plugin::class ) ) {
		require_once __DIR__ . '/src/Plugin.php';
	}

	return Plugin::instance();
}

jarrydandthejackles_elementor_random_image();
