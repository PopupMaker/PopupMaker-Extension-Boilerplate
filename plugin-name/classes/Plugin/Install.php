<?php
/**
 * Plugin installer.
 *
 * @author    Code Atlantic
 * @package   {PLUGIN_NAMESPACE}
 * @copyright (c) 2024, Code Atlantic LLC.
 */

namespace {PLUGIN_NAMESPACE}\Plugin;

use {PLUGIN_NAMESPACE}\Vendor\CodeAtlantic\InstallRoutine\Installer;

defined( 'ABSPATH' ) || exit;

/**
 * Class Install
 */
class Install extends Installer {

	/**
	 * Option prefix.
	 *
	 * @var string
	 */
	const OPTION_PREFIX = '{PLUGIN_PREFIX}_';

	/**
	 * Activate on single site.
	 *
	 * @return void
	 */
	public static function activate_site() {
		// Add a temporary option that will fire a hookable action on next load.
		set_transient( '_' . self::OPTION_PREFIX . 'installed', true, HOUR_IN_SECONDS );
	}

	/**
	 * Deactivate on single site.
	 *
	 * @return void
	 */
	public static function deactivate_site() {
	}

	/**
	 * Uninstall single site.
	 *
	 * @return void
	 */
	public static function uninstall_site() {
	}
}
