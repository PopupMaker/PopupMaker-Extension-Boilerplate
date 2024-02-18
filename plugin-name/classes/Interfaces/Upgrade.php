<?php
/**
 * Plugin upgrade.
 *
 * @copyright (c) 2024, Code Atlantic LLC.
 *
 * @package {PLUGIN_NAMESPACE}
 */

namespace {PLUGIN_NAMESPACE}\Interfaces;

defined( 'ABSPATH' ) || exit;

/**
 * Localized controller class.
 */
interface Upgrade {

	/**
	 * Return label for this upgrade.
	 *
	 * @return string
	 */
	public function label();

	/**
	 * Return full description for this upgrade.
	 *
	 * @return string
	 */
	public function description();

	/**
	 * Check if this upgrade is required.
	 *
	 * @return bool
	 */
	public function is_required();

	/**
	 * Check if prerequisites are met.
	 *
	 * @return bool
	 */
	public function prerequisites_met();

	/**
	 * Run the upgrade.
	 *
	 * @return void|\WP_Error|false
	 */
	public function run();
}
