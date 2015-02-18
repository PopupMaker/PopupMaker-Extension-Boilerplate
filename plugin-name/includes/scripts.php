<?php
/**
 * Scripts
 *
 * @package     PopMake\PluginName\Scripts
 * @since       1.0.0
 */


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;


/**
 * Load admin scripts
 *
 * @since       1.0.0
 * @global      array $popmake_settings_page The slug for the Popup Maker settings page
 * @global      string $post_type The type of post that we are editing
 * @return      void
 */
function popmake_plugin_name_admin_scripts( $hook ) {
    global $popmake_settings_page, $post_type;

    // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    /**
     * @todo		This block loads styles or scripts explicitly on the
     *				Popup Maker settings page.
     */
    if( $hook == $popmake_settings_page ) {
        wp_enqueue_script( 'popmake_plugin_name_admin_js', POPMAKE_PLUGIN_NAME_URL . '/assets/js/admin' . $suffix . '.js', array( 'jquery', 'popup-maker-admin' ) );
        wp_enqueue_style( 'popmake_plugin_name_admin_css', POPMAKE_PLUGIN_NAME_URL . '/assets/css/admin' . $suffix . '.css' );
    }
}
add_action( 'admin_enqueue_scripts', 'popmake_plugin_name_admin_scripts', 100 );


/**
 * Load frontend scripts
 *
 * @since       1.0.0
 * @return      void
 */
function popmake_plugin_name_scripts( $hook ) {
    // Use minified libraries if SCRIPT_DEBUG is turned off
	$suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';

    wp_enqueue_script( 'popmake_plugin_name_js', POPMAKE_PLUGIN_NAME_URL . '/assets/js/scripts' . $suffix . '.js', array( 'jquery', 'popup-maker-site' ) );
    wp_enqueue_style( 'popmake_plugin_name_css', POPMAKE_PLUGIN_NAME_URL . '/assets/css/styles' . $suffix . '.css' );
}
add_action( 'wp_enqueue_scripts', 'popmake_plugin_name_scripts' );
