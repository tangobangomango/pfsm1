<?php
/*
Plugin Name: Tabbed Settings Example
Plugin URI: http://edkatzman.com/real-pull-quotes
Description: Dummy plugin to work on settings page example
Version: 1.0
Author: Ed Katzman
Author URI: http://edkatzman.com/
License: GPLv2
*/

/*
 *
 * SETUP
 *
 */

if ( ! function_exists( 'tdg_tabbed_settings_example_setup' ) ):
/**
 * Sets up plugin defaults and registers support for various WordPress features.
 *
 *
 * @since V 1.0
 */
function tdg_tabbed_settings_example_setup() {
 
    /**
     * Custom template tags for this plugin.
     */
    require( plugin_dir_path( __FILE__ ) . 'tabbed-settings-example-settings.php' );
 

}
endif; // end tdg_tabbed_settings_example_setup

add_action( 'init', 'tdg_tabbed_settings_example_setup' );


