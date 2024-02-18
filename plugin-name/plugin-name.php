<?php
/**
 * Plugin Name: {PLUGIN_NAME}
 * Plugin URI: 
 * Description: {PLUGIN_DESCRIPTION}
 * Version: 1.0.0
 * Author: Code Atlantic
 * Author URI: https://code-atlantic.com/?utm_campaign=plugin-info&utm_source=php-file-header&utm_medium=plugin-ui&utm_content=author-uri
 * Donate link: https://code-atlantic.com/donate/?utm_campaign=donations&utm_source=php-file-header&utm_medium=plugin-ui&utm_content=donate-link
 * Text Domain: {PLUGIN_TEXT_DOMAIN}
 *
 * Minimum PHP: {MIN_PHP_VERSION}
 * Minimum WP: {MIN_WP_VERSION}
 *
 * @package    {PLUGIN_NAME}}
 * @author     Code Atlantic
 * @copyright  Copyright (c) 2023, Code Atlantic LLC.
 * 
 * IMPORTANT! Ensure that you make the following adjustments
 * before releasing your extension:
 *
 * - Replace all instances of plugin-name with the name of your plugin.
 *   By WordPress coding standards, the folder name, plugin file name,
 *   and text domain should all match. For the purposes of standardization,
 *   the folder name, plugin file name, and text domain are all the
 *   lowercase form of the actual plugin name, replacing spaces with
 *   hyphens.
 *
 * - Replace all instances of Plugin_Name with the name of your plugin.
 *   For the purposes of standardization, the camel case form of the plugin
 *   name, replacing spaces with underscores, is used to define classes
 *   in your extension.
 *
 * - Replace all instances of PLUGINNAME with the name of your plugin.
 *   For the purposes of standardization, the uppercase form of the plugin
 *   name, removing spaces, is used to define plugin constants.
 *
 * - Replace all instances of Plugin Name with the actual name of your
 *   plugin. This really doesn't need to be anywhere other than in the
 *   EDD Licensing call in the hooks method.
 *
 * - Find all instances of @todo in the plugin and update the relevant
 *   areas as necessary.
 *
 * - All functions that are not class methods MUST be prefixed with the
 *   plugin name, replacing spaces with underscores. NOT PREFIXING YOUR
 *   FUNCTIONS CAN CAUSE PLUGIN CONFLICTS!
 */

namespace {PLUGIN_NAMESPACE};

defined( 'ABSPATH' ) || exit;

/**
 * Define plugin's global configuration.
 *
 * @return array<string,mixed>
 */
function get_plugin_config() {
	return [
		'name'          => \__( '{PLUGIN_NAME}', '{PLUGIN_TEXT_DOMAIN}' ),
		'slug'          => '{PLUGIN_SLUG}',
		'version'       => '1.0.0',
		'option_prefix' => '{PLUGIN_PREFIX}',
		'text_domain'   => '{PLUGIN_TEXT_DOMAIN}',
		'edd_id'		=> {EDD_ID},
		'fullname'      => \__( '{PLUGIN_NAME}', '{PLUGIN_TEXT_DOMAIN}' ),
		'min_php_ver'   => '{MIN_PHP_VERSION}',
		'min_wp_ver'    => '{MIN_WP_VERSION}',
		'file'          => __FILE__,
		'url'           => \plugin_dir_url( __FILE__ ),
		'path'          => \realpath( \plugin_dir_path( __FILE__ ) ) . \DIRECTORY_SEPARATOR,
	];
}

/**
 * Get config or config property.
 *
 * @param string|null $key Key of config item to return.
 *
 * @return mixed
 */
function config( $key = null ) {
	$config = get_plugin_config();

	if ( ! isset( $key ) ) {
		return $config;
	}

	return isset( $config[ $key ] ) ? $config[ $key ] : false;
}

/**
 * Register autoloader.
 */
require_once __DIR__ . '/vendor-prefixed/code-atlantic/wp-autoloader/src/Autoloader.php';

if ( ! \{PLUGIN_NAMESPACE}\Vendor\CodeAtlantic\Autoloader\Autoloader::init( config( 'name' ), config( 'path' ) ) ) {
	return;
}

/**
 * Check plugin prerequisites.
 *
 * @return bool
 */
function check_prerequisites() {

	// 1.a Check Prerequisites.
	$prerequisites = new \{PLUGIN_NAMESPACE}\Vendor\CodeAtlantic\PrerequisiteChecks\Prerequisites(
		[
			[
				// a. PHP Min Version.
				'type'    => 'php',
				'version' => config( 'min_php_ver' ),
			],
			// a. PHP Min Version.
			[
				'type'    => 'wp',
				'version' => config( 'min_wp_ver' ),
			],
			[
				'type'            => 'plugin',
				'slug'            => 'popup-maker',
				'name'            => __( 'Popup Maker', 'popup-maker' ),
				'version'         => '1.18.0',
				'check_installed' => true,
			],
		],
		config()
	);

	/**
	 * 1.b If there are missing requirements, render error messaging and return.
	 */
	if ( $prerequisites->check() === false ) {
		$prerequisites->setup_notices();

		return false;
	}


	return true;
}

add_action(
	'plugins_loaded',
	function () {
		if ( check_prerequisites() ) {
			plugin_instance();
		}
	},
	// Core plugin loads at 11, Pro loads at 12 & addons load at 13.
	13
);

/**
 * Initiates and/or retrieves an encapsulated container for the plugin.
 *
 * This kicks it all off, loads functions and initiates the plugins main class.
 *
 * @return \{PLUGIN_NAMESPACE}\Plugin\Core
 */
function plugin_instance() {
	static $plugin;

	if ( ! $plugin instanceof \{PLUGIN_NAMESPACE}}\Plugin\Core ) {
		require_once __DIR__ . '/inc/functions.php';
		$plugin = new Plugin\Core( get_plugin_config() );
	}

	return $plugin;
}

/**
 * Easy access to all plugin services from the container.
 *
 * @see \{PLUGIN_NAMESPACE}\plugin_instance
 *
 * @param string|null $service_or_config Key of service or config to fetch.
 * @return \{PLUGIN_NAMESPACE}\Plugin\Core|mixed
 */
function plugin( $service_or_config = null ) {
	if ( ! isset( $service_or_config ) ) {
		return plugin_instance();
	}

	return plugin_instance()->get( $service_or_config );
}

\register_activation_hook( __FILE__, '\{PLUGIN_NAMESPACE}\Plugin\Install::activate_plugin' );
\register_deactivation_hook( __FILE__, '\{PLUGIN_NAMESPACE}\Plugin\Install::deactivate_plugin' );
\register_uninstall_hook( __FILE__, '\{PLUGIN_NAMESPACE}\Plugin\Install::uninstall_plugin' );
