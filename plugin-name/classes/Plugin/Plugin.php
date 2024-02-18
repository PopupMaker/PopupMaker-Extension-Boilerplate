<?php
/**
 * Main plugin.
 *
 * @author    Code Atlantic
 * @package   {PLUGIN_NAMESPACE}
 * @copyright (c) 2024, Code Atlantic LLC.
 */

namespace {PLUGIN_NAMESPACE}\Plugin;

defined( 'ABSPATH' ) || exit;

/**
 * Class Plugin
 *
 * @package {PLUGIN_NAMESPACE}\Plugin
 */
class Plugin {

	/**
	 * Exposed container.
	 *
	 * @var Container
	 */
	public $container;

	/**
	 * Array of controllers.
	 *
	 * Useful to unhook actions/filters from global space.
	 *
	 * @var Container
	 */
	public $controllers;

	/**
	 * Initiate the plugin.
	 *
	 * @param array<string,string|bool> $config Configuration variables passed from main plugin file.
	 */
	public function __construct( $config ) {
		$this->container   = new Container( $config );
		$this->controllers = new Container();

		$this->register_extension();

		$this->register_services();
		$this->define_paths();
		$this->initiate_controllers();

		$this->check_version();

		add_action( 'init', [ $this, 'load_textdomain' ] );
	}

	public function register_extension() {
		$edd_id = $this->get( 'edd_id' );

		// Handle licensing
		if ( $edd_id && $edd_id > 0 && class_exists( 'PUM_Extension_License' ) ) {
			new PUM_Extension_License(
				$this->get( 'file' ),
				$this->get( 'name' ),
				$this->get( 'version' ),
				'Popup Maker',
				null,
				null,
				$edd_id
			);
		}

		add_filter( 'pum_enabled_extensions', [ $this, 'enabled_extensions' ] );
	}

	/**
	 * Register the extension is enabled.
	 *
	 * @param array $enabled_extensions
	 *
	 * @return array
	 */
	public function enabled_extensions( $enabled_extensions = [] ) {
		$enabled_extensions[ $this->get( 'slug' ) ] = $this->extension_class_name;

		return $enabled_extensions;
	}

	/**
	 * Update & track version info.
	 *
	 * @return void
	 */
	protected function check_version() {
		$version    = $this->get( 'version' );
		$option_key = "{$this->get( 'option_prefix' )}_version";

		$current_data = \get_option( $option_key, false );

		$data = wp_parse_args(
			false !== $current_data ? $current_data : [],
			[
				'version'         => $version,
				'upgraded_from'   => null,
				'initial_version' => $version,
				'installed_on'    => gmdate( 'Y-m-d H:i:s' ),
			]
		);

		// Process old version data storage.
		if ( false === $current_data ) {
			$data = $this->process_version_data_migration( $data );
		}

		if ( version_compare( $data['version'], (string) $version, '<' ) ) {
			// Allow processing of small core upgrades.

			/**
			 * Fires when the plugin version is updated.
			 *
			 * Note: Old version is still available in options.
			 *
			 * @param string $version The new version.
			 */
			do_action( "{$this->get( 'option_prefix' )}/update_version", $data['version'] );

			// Save Upgraded From option.
			$data['upgraded_from'] = $data['version'];
			$data['version']       = $version;
		}

		if ( $current_data !== $data ) {
			\update_option( $option_key, $data );
		}
	}

	/**
	 * Process old version data.
	 *
	 * @param array<string,string|null> $data Array of data.
	 * @return array<string,string|null>
	 */
	protected function process_version_data_migration( $data ) {
		// Check if old settings exist.
		$has_old_data = false;

		if ( false !== $has_old_data ) {
			$data = [
				// @todo Update this to the last known version prior to the one implementing this mechanism.
				'version'         => '{PLUGIN_VERSION}',
				'upgraded_from'   => null,
				'initial_version' => '{PLUGIN_VERSION}',
			];
		}

		if ( empty( $data['initial_version'] ) ) {
			$oldest_known = $data['version'];

			if ( $data['upgraded_from'] && version_compare( $data['upgraded_from'], $oldest_known, '<' ) ) {
				$oldest_known = $data['upgraded_from'];
			}

			$data['initial_version'] = $oldest_known;
		}

		return $data;
	}

	/**
	 * Internationalization.
	 *
	 * @return void
	 */
	public function load_textdomain() {
		load_plugin_textdomain( $this->container['text_domain'], false, $this->get_path( 'languages' ) );
	}

	/**
	 * Add default services to our Container.
	 *
	 * @return void
	 */
	public function register_services() {
		/**
		 * Self reference for deep DI lookup.
		 */
		$this->container['plugin'] = $this;

		/**
		 * Attach our container to the global.
		 */
		$GLOBALS[ $this->get( 'option_prefix' ) ] = $this->container;
	}

	/**
	 * Update & track version info.
	 *
	 * @return array<string,\{PLUGIN_NAMESPACE}\Base\Controller>
	 */
	protected function registered_controllers() {
		return [
			// Controllers.
			'Admin'    => new \(){PLUGIN_NAMESPACE}\Controllers\Admin( $this ),
			'Assets'   => new \(){PLUGIN_NAMESPACE}\Controllers\Assets( $this ),
			'Frontend' => new \(){PLUGIN_NAMESPACE}\Controllers\Frontend( $this ),
		];
	}

	/**
	 * Initiate internal components.
	 *
	 * @return void
	 */
	protected function initiate_controllers() {
		$this->register_controllers( $this->registered_controllers() );
	}

	/**
	 * Register controllers.
	 *
	 * @param array<string,Controller> $controllers Array of controllers.
	 * @return void
	 */
	public function register_controllers( $controllers = [] ) {
		foreach ( $controllers as $name => $controller ) {
			if ( $controller instanceof Controller ) {
				$controller->init();
				$this->controllers->set( $name, $controller );
			}
		}
	}

	/**
	 * Get a controller.
	 *
	 * @param string $name Controller name.
	 *
	 * @return Controller|null
	 */
	public function get_controller( $name ) {
		$controller = $this->controllers->get( $name );

		if ( $controller instanceof Controller ) {
			return $controller;
		}

		return null;
	}

	/**
	 * Initiate internal paths.
	 *
	 * @return void
	 */
	protected function define_paths() {
		/**
		 * Attach utility functions.
		 */
		$this->container['get_path'] = [ $this, 'get_path' ];
		$this->container['get_url']  = [ $this, 'get_url' ];

		// Define paths.
		$this->container['dist_path'] = $this->get_path( 'dist' ) . '/';
	}

	/**
	 * Utility method to get a path.
	 *
	 * @param string $path Subpath to return.
	 * @return string
	 */
	public function get_path( $path ) {
		return $this->container['path'] . $path;
	}

	/**
	 * Utility method to get a url.
	 *
	 * @param string $path Sub url to return.
	 * @return string
	 */
	public function get_url( $path = '' ) {
		return $this->container['url'] . $path;
	}

	/**
	 * Get item from container
	 *
	 * @param string $id Key for the item.
	 *
	 * @return mixed Current value of the item.
	 */
	public function get( $id ) {
		// 1. Check if the item exists in the container.
		if ( $this->container->offsetExists( $id ) ) {
			return $this->container->get( $id );
		}

		// 2. Check if the item exists in the controllers container.
		if ( $this->controllers->offsetExists( $id ) ) {
			return $this->controllers->get( $id );
		}

		// 3. Check if the item exists in the global space.
		if ( get_called_class() !== __CLASS__ ) {
			// If this is an addon, check if the service exists in the core plugin.
			// Get core plugin container and see if the service exists there.
			$plugin_service = \{PLUGIN_NAMESPACE}\plugin( $id );

			if ( $plugin_service ) {
				return $plugin_service;
			}
		}

		// 5. Return null, item not found.
		return null;
	}

	/**
	 * Set item in container
	 *
	 * @param string $id Key for the item.
	 * @param mixed  $value Value to set.
	 *
	 * @return void
	 */
	public function set( $id, $value ) {
		$this->container->set( $id, $value );
	}
}
