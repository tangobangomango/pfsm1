<?php
/*
Plugin Name: Real Pull Quotes
Plugin URI: http://edkatzman.com/real-pull-quotes
Description: Add "real" magazine-style pull quotes that leave original text in place
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

if ( ! function_exists( 'tdg_real_pull_quotes_setup' ) ):
/**
 * Sets up plugin defaults and registers support for various WordPress features.
 *
 *
 * @since V 1.0
 */
function tdg_real_pull_quotes_setup() {
 
    /**
     * Custom template tags for this plugin.
     */
    require( plugin_dir_path( __FILE__ ) . 'real-pull-quotes-settings.php' );
 
     /**
     * Custom template tags for this plugin.
     */
    require( plugin_dir_path( __FILE__ ) . 'inc/template-tags.php' );

    /**
     * Custom functions that act independently of the plugin templates
     */
    require( plugin_dir_path( __FILE__ ) .'inc/tweaks.php' );
 
    /**
     * Make theme available for translation
     * Translations can be filed in the /languages/ directory
     */
    load_plugin_textdomain( 'tdg_real_pull_quotes', false, plugin_dir_path( __FILE__ ) . '/languages' );
}
endif; // end tdg_real_pull_quotes_setup

add_action( 'init', 'tdg_real_pull_quotes_setup' );

/* Enqueue plugin stylesheet and javascript*/

function tdg_real_pull_quotes_load_plugin_files() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'real-pull-quotes', $plugin_url . 'css/real-pull-quotes.css' );
    wp_enqueue_script( 'real-pull-quotes-js', $plugin_url . 'js/real-pull-quotes.js', array( 'jquery' ), '1.0.0' );
}
add_action( 'wp_enqueue_scripts', 'tdg_real_pull_quotes_load_plugin_files' );

/*
 *
 * MAIN SHORTCODE FUNCTION
 *
 */

function tdg_real_pull_quotes( $atts, $content = null ) {

	/* Set up available atributes and defaults */
	$attributes = shortcode_atts(array(
									'alignment' => 'right', //Align pull quote left, right, or full width
									'position'  => 'below', //Position above or below text place in text
									'border'    => 'side',  //Border at side or top-bottom or none
									'color'     => null,    //Color of border in HEX #000000
									'size'      => null,    //Size of text in pixels
									'class'     => null,    //Class(es) to add to containing div
									'before'	=> null,	//Text to add in pull quote before text e.g. ellipses when using less than a full sentence
									'after'		=> null,	//Text to add after pull quote
									), $atts, 'real-pull-quotes' );

	/* Set proper css class for type of alignment in content block: left, right, full */
	$alignment = '';
	if  ( $attributes['alignment'] == 'left' ) {
		$alignment = ' tdg-real-pull-quotes-align-left'; 
	} elseif  ($attributes['alignment'] == 'full' ) {
		$alignment = ' tdg-real-pull-quotes-align-full'; 
	} else $alignment = ' tdg-real-pull-quotes-align-right';
	

	/* Return html string to create pull quote wiyh selected attributes */
	return $content . '<span class="tdg-real-pull-quote' . $alignment . '">' . $attributes['before'] . do_shortcode( $content ) . $attributes['after'] . '</span>'; // changed div to span to eliminate space if placed next to same paragraph

}

add_shortcode( 'realpullquote', 'tdg_real_pull_quotes');

/*
 *
 * ADD EDITOR SHORTCODE BUTTON
 *
 */
//IMPORTANT: Button name 'tdg' needs to be same in javascript file
function tdg_add_button( $plugin_array ) {
	$plugin_url = plugin_dir_url( __FILE__ );
    $plugin_array['tdg'] = $plugin_url .'js/real-pull-quotes-editor.js';
	return $plugin_array;
}
function wptuts_add_buttons( $plugin_array ) {
	$plugin_url = plugin_dir_url( __FILE__ );
    $plugin_array['wptuts'] = $plugin_url .'js/real-pull-quotes-editor.js'; //get_template_directory_uri() . '/wptuts-editor-buttons/wptuts-plugin.js';
    return $plugin_array;
}

function tdg_register_button( $buttons ) {   
    array_push( $buttons, 'myquotes' );
    return $buttons;
}
function wptuts_register_buttons( $buttons ) {
    array_push( $buttons, 'dropcap', 'showrecent' ); // dropcap', 'recentposts
    return $buttons;
}


function tdg_button() {
    add_filter( "mce_external_plugins", "tdg_add_button" );
    add_filter( 'mce_buttons', 'tdg_register_button' );
}
function wptuts_buttons() {
    add_filter( "mce_external_plugins", "wptuts_add_buttons" );
    add_filter( 'mce_buttons', 'wptuts_register_buttons' );
}

add_action( 'init', 'tdg_button' );

add_action( 'init', 'wptuts_buttons' );


