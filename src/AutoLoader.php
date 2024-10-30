<?php

namespace JarrydAndTheJackles\Elementor_Random_Image;

final class AutoLoader {
	/**
	 * @var self|null
	 */
	private static ?self $_instance = null;
	private array $loaded_classes = [];

	public function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );
	}

	public static function instance(): ?AutoLoader {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function autoload( string $class ): void {
		// Remove classes that are not part of the "JjRandomArticle" namespace.
		if ( ! $this->is_class_valid( $class ) || $this->is_class_loaded( $class ) ) {
			return;
		}

		// Get the parts of the FQCN
		$parts = explode( '\\', $class );
		// Remove the base namespace.
		array_shift( $parts );
		array_shift( $parts );
		// Build the relative file path.
		$path = implode( DIRECTORY_SEPARATOR, $parts );
		// Wrap the file path with base directory and file extension.
		$path = __DIR__ . DIRECTORY_SEPARATOR . $path . '.php';

		if ( $this->include_file( $path ) ) {
			$this->add_loaded_class( $class );
		}
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	private function is_class_valid( string $class ): bool {
		return ! ( strlen( $class ) < 17 || strpos( $class, 'JarrydAndTheJackles\\Elementor_Random_Image\\' ) !== 0 );
	}

	/**
	 * @param string $class
	 *
	 * @return bool
	 */
	private function is_class_loaded( string $class ): bool {
		return $this->loaded_classes[ $this->get_class_hash( $class ) ] ?? false;
	}

	/**
	 * @param string $class
	 */
	private function add_loaded_class( string $class ): void {
		$this->loaded_classes[ $this->get_class_hash( $class ) ] = true;
	}

	/**
	 * @param string $class
	 *
	 * @return string
	 */
	private function get_class_hash( string $class ): string {
		return sha1( $class );
	}

	/**
	 * @param string $path
	 *
	 * @return bool
	 */
	private function include_file( string $path ): bool {
		return ( static function ( string $path ): bool {
			$real_path = realpath( $path );

			if ( ! file_exists( $real_path ) ) {
				return false;
			}

			/** @noinspection PhpIncludeInspection */
			require_once $real_path;

			return true;
		} )( $path );
	}
}
