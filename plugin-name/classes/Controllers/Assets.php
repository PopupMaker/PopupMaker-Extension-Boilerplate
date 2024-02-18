<?php
/**
 * Plugin assets controller.
 *
 * @package {PLUGIN_NAMESPACE}\Admin
 * @copyright (c) 2023 Code Atlantic LLC.
 */

namespace {PLUGIN_NAMESPACE}\Controllers;

use {PLUGIN_NAMESPACE}\Base\Controller;

defined( 'ABSPATH' ) || exit;

/**
 * Admin assets controller.
 *
 * @package {PLUGIN_NAMESPACE}\Admin
 */
class Assets extends Controller {

	/**
	 * Initialize the assets controller.
	 */
	public function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ], 0 );
		add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts' ], 0 );
		add_action( 'wp_print_scripts', [ $this, 'autoload_styles_for_scripts' ], 0 );
		add_action( 'admin_print_scripts', [ $this, 'autoload_styles_for_scripts' ], 0 );
	}

	/**
	 * Get list of plugin packages.
	 *
	 * @return array<string,array<string,mixed>>
	 */
	public function get_packages() {
		$packages = [
			'admin'  => [
				'handle'   => '{PLUGIN_SLUG}-admin',
				'styles'   => true,
				/**
				 * Use this to pass variables to the script.
				 *
				 * 'varsName' => 'pluginNameAdmin',
				 * 'vars'     => [
				 * 	   'adminUrl'  => admin_url( 'admin-ajax.php' ),
				 *     'wpVersion' => $wp_version,
				 * ],
				 */
				'deps' => [],
			],
			'frontend'  => [
				'handle'   => '{PLUGIN_SLUG}-frontend',
				'styles'   => true,
				/**
				 * Use this to pass variables to the script.
				 *
				 * 'varsName' => 'pluginNameFrontend',
				 * 'vars'     => [
				 *     'wpVersion' => $wp_version,
				 * ],
				 */
				'deps' => [],
			],
		];

		return $packages;
	}

	/**
	 * Register all package scripts & styles.
	 *
	 * @return void
	 */
	public function register_scripts() {
		$packages = $this->get_packages();

		// Register front end block styles.
		wp_register_style( '{PLUGIN_SLUG}-block-styles', $this->container->get_url( 'dist/style-block-editor.css' ), [], $this->container->get( 'version' ) );

		foreach ( $packages as $package => $package_data ) {
			$handle = $package_data['handle'];
			$meta   = $this->get_asset_meta( $package );

			$js_deps = isset( $package_data['deps'] ) ? $package_data['deps'] : [];

			wp_register_script( $handle, $this->container->get_url( "dist/$package.js" ), array_merge( $meta['dependencies'], $js_deps ), $meta['version'], true );

			if ( isset( $package_data['styles'] ) && $package_data['styles'] ) {
				wp_register_style( $handle, $this->container->get_url( "dist/$package.css" ), [], $meta['version'] );
			}

			if ( isset( $package_data['varsName'] ) && ! empty( $package_data['vars'] ) ) {
				$localized_vars = apply_filters( "{PLUGIN_PREFIX}/{$package}_localized_vars", $package_data['vars'] );
				wp_localize_script( $handle, $package_data['varsName'], $localized_vars );
			}

			/**
			 * May be extended to wp_set_script_translations( 'my-handle', 'my-domain',
			 * plugin_dir_path( MY_PLUGIN ) . 'languages' ) ). For details see
			 * https://make.wordpress.org/core/2018/11/09/new-javascript-i18n-support-in-wordpress/
			 */
			wp_set_script_translations( $handle, '{PLUGIN_TEXTDOMAIN}' );
		}
	}

	/**
	 * Auto load styles if scripts are enqueued.
	 *
	 * @return void
	 */
	public function autoload_styles_for_scripts() {
		$packages = $this->get_packages();

		foreach ( $packages as $package => $package_data ) {
			if ( wp_script_is( $package_data['handle'], 'enqueued' ) ) {
				if ( isset( $package_data['styles'] ) && $package_data['styles'] ) {
					wp_enqueue_style( $package_data['handle'] );
				}
			}
		}
	}

	/**
	 * Get asset meta from generated files.
	 *
	 * @param string $package Package name.
	 * @return array{dependencies:string[],version:string}
	 */
	public function get_asset_meta( $package ) {
		$meta_path = $this->container->get_path( "dist/$package.asset.php" );
		return file_exists( $meta_path ) ? require $meta_path : [
			'dependencies' => [],
			'version'      => $this->container->get( 'version' ),
		];
	}
}
