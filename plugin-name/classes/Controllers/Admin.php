<?php
/**
 * Admin controller.
 *
 * @copyright (c) 2022, Code Atlantic LLC.
 *
 * @package {PLUGIN_NAMESPACE}
 */

namespace {PLUGIN_NAMESPACE}\Controllers;

use {PLUGIN_NAMESPACE}\Base\Controller;
// use {PLUGIN_NAMESPACE}\Controllers\Admin\SettingsPage;

defined( 'ABSPATH' ) || exit;

/**
 * Admin controller class.
 *
 * @package {PLUGIN_NAMESPACE}
 */
class Admin extends Controller {

	/**
	 * Initialize admin controller.
	 *
	 * @return void
	 */
	public function init() {
		// Register sub controllers to keep things organized.
		$this->container->register_controllers( [
			// ex. 'Admin\Settings'       => new SettingsPage( $this->container ),
		] );

		$this->hooks();
	}

	/**
	 * Register general frontend hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		// Add admin hooks and filters.
	}
}
