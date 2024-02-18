<?php
/**
 * Plugin container.
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
interface Controller {

	/**
	 * Handle hooks & filters or various other init tasks.
	 *
	 * @return void
	 */
	public function init();
}
