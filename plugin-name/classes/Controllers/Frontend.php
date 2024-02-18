<?php
/**
 * Frontend general setup.
 *
 * @copyright (c) 2024, Code Atlantic LLC.
 * @package {PLUGIN_NAMESPACE}
 */

namespace {PLUGIN_NAMESPACE}\Controllers;

use {PLUGIN_NAMESPACE}\Base\Controller;

// use {PLUGIN_NAMESPACE}\Controllers\Frontend\Blocks;

defined( 'ABSPATH' ) || exit;

/**
 * Class Frontend
 */
class Frontend extends Controller {

	/**
	 * Initialize Hooks & Filters
	 */
	public function init() {
		$this->container->register_controllers([
			// 'Frontend\Blocks' => new Blocks( $this->container ),
		]);

		$this->hooks();
	}

	/**
	 * Register general frontend hooks.
	 *
	 * @return void
	 */
	public function hooks() {
		// Add frontend hooks and filters.
	}
}
