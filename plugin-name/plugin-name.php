<?php
/**
 * Plugin Name:     @todo
 * Plugin URI:      @todo
 * Description:     @todo
 * Version:         1.0.0
 * Author:          @todo
 * Author URI:      @todo
 * Text Domain:     plugin-name
 *
 * @package         PopMake\PluginName
 * @author          @todo
 * @copyright       Copyright (c) @todo
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


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if( !class_exists( 'PopMake_Plugin_Name' ) ) {

    /**
     * Main PopMake_Plugin_Name class
     *
     * @since       1.0.0
     */
    class PopMake_Plugin_Name {

        /**
         * @var         PopMake_Plugin_Name $instance The one true PopMake_Plugin_Name
         * @since       1.0.0
         */
        private static $instance;


        /**
         * Get active instance
         *
         * @access      public
         * @since       1.0.0
         * @return      object self::$instance The one true PopMake_Plugin_Name
         */
        public static function instance() {
            if( !self::$instance ) {
                self::$instance = new PopMake_Plugin_Name();
                self::$instance->setup_constants();
                self::$instance->includes();
                self::$instance->load_textdomain();
                self::$instance->hooks();
            }

            return self::$instance;
        }


        /**
         * Setup plugin constants
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function setup_constants() {
            // Plugin version
            define( 'POPMAKE_PLUGIN_NAME_VER', '1.0.0' );

            // Plugin path
            define( 'POPMAKE_PLUGIN_NAME_DIR', plugin_dir_path( __FILE__ ) );

            // Plugin URL
            define( 'POPMAKE_PLUGIN_NAME_URL', plugin_dir_url( __FILE__ ) );
        }


        /**
         * Include necessary files
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         */
        private function includes() {
            // Include scripts
            require_once POPMAKE_PLUGIN_NAME_DIR . 'includes/scripts.php';
            require_once POPMAKE_PLUGIN_NAME_DIR . 'includes/functions.php';

            /**
             * @todo        The following files are not included in the boilerplate, but
             *              the referenced locations are listed for the purpose of ensuring
             *              path standardization in Popup Maker extensions. Uncomment any that are
             *              relevant to your extension, and remove the rest.
             */
            // require_once POPMAKE_PLUGIN_NAME_DIR . 'includes/shortcodes.php';
            // require_once POPMAKE_PLUGIN_NAME_DIR . 'includes/widgets.php';
        }


        /**
         * Run action and filter hooks
         *
         * @access      private
         * @since       1.0.0
         * @return      void
         *
         * @todo        The hooks listed in this section are a guideline, and
         *              may or may not be relevant to your particular extension.
         *              Please remove any unnecessary lines, and refer to the
         *              WordPress codex and Popup Maker documentation for additional
         *              information on the included hooks.
         *
         *              This method should be used to add any filters or actions
         *              that are necessary to the core of your extension only.
         *              Hooks that are relevant to meta boxes, widgets and
         *              the like can be placed in their respective files.
         *
         *              IMPORTANT! If you are releasing your extension as a
         *              commercial extension in the Popup Maker store, DO NOT remove
         *              the license check!
         */
        private function hooks() {
            // Register settings
            add_filter( 'popmake_settings_extensions', array( $this, 'settings' ), 1 );

            // Handle licensing
            // @todo        Replace the Plugin Name and Your Name with your data
            if( class_exists( 'PopMake_License' ) ) {
                $license = new PopMake_License( __FILE__, 'Plugin Name', POPMAKE_PLUGIN_NAME_VER, 'Your Name' );
            }
        }


        /**
         * Internationalization
         *
         * @access      public
         * @since       1.0.0
         * @return      void
         */
        public function load_textdomain() {
            // Set filter for language directory
            $lang_dir = POPMAKE_PLUGIN_NAME_DIR . '/languages/';
            $lang_dir = apply_filters( 'popmake_plugin_name_languages_directory', $lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'popmake-plugin-name' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'popmake-plugin-name', $locale );

            // Setup paths to current locale file
            $mofile_local   = $lang_dir . $mofile;
            $mofile_global  = WP_LANG_DIR . '/popmake-plugin-name/' . $mofile;

            if( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/popmake-plugin-name/ folder
                load_textdomain( 'popmake-plugin-name', $mofile_global );
            } elseif( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/popmake-plugin-name/languages/ folder
                load_textdomain( 'popmake-plugin-name', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'popmake-plugin-name', false, $lang_dir );
            }
        }


        /**
         * Add settings
         *
         * @access      public
         * @since       1.0.0
         * @param       array $settings The existing Popup Maker settings array
         * @return      array The modified Popup Maker settings array
         */
        public function settings( $settings ) {
            $new_settings = array(
                array(
                    'id'    => 'popmake_plugin_name_settings',
                    'name'  => '<strong>' . __( 'Plugin Name Settings', 'popmake-plugin-name' ) . '</strong>',
                    'desc'  => __( 'Configure Plugin Name Settings', 'popmake-plugin-name' ),
                    'type'  => 'header',
                )
            );

            return array_merge( $settings, $new_settings );
        }
    }
} // End if class_exists check


/**
 * The main function responsible for returning the one true PopMake_Plugin_Name
 * instance to functions everywhere
 *
 * @since       1.0.0
 * @return      PopMake_Plugin_Name The one true PopMake_Plugin_Name
 *
 * @todo        Inclusion of the activation code below isn't mandatory, but
 *              can prevent any number of errors, including fatal errors, in
 *              situations where your extension is activated but Popup Maker is not
 *              present.
 */
function popmake_plugin_name_load() {
    if( ! class_exists( 'Popup_Maker' ) ) {
        if( ! class_exists( 'PopMake_Extension_Activation' ) ) {
            require_once 'includes/class.extension-activation.php';
        }

        $activation = new PopMake_Extension_Activation( plugin_dir_path( __FILE__ ), basename( __FILE__ ) );
        $activation = $activation->run();
    } else {
        PopMake_Plugin_Name::instance();
    }
}
add_action( 'plugins_loaded', 'popmake_plugin_name_load' );


/**
 * The activation hook is called outside of the singleton because WordPress doesn't
 * register the call from within the class, since we are preferring the plugins_loaded
 * hook for compatibility, we also can't reference a function inside the plugin class
 * for the activation function. If you need an activation function, put it here.
 *
 * @since       1.0.0
 * @return      void
 */
function popmake_plugin_name_activation() {
    /* Activation functions here */
}
register_activation_hook( __FILE__, 'popmake_plugin_name_activation' );
