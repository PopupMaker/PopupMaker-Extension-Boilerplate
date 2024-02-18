<?php
/**
 * Plugin controller.
 *
 * @copyright (c) 2021, Code Atlantic LLC.
 *
 * @package {PLUGIN_NAMESPACE}
 */

namespace {PLUGIN_NAMESPACE}\Base;

defined( 'ABSPATH' ) || exit;

/**
 * Localized container class.
 */
abstract class Controller implements \{PLUGIN_NAMESPACE}\Interfaces\Controller {

	/**
	 * Plugin Container.
	 *
	 * @var \{PLUGIN_NAMESPACE}\Plugin\Plugin
	 */
	public $container;

	/**
	 * Initialize based on dependency injection principles.
	 *
	 * @param \{PLUGIN_NAMESPACE}\Plugin\Plugin $container Plugin container.
	 * @return void
	 */
	public function __construct( $container ) {
		$this->container = $container;
	}
}
