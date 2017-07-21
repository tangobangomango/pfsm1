<?php
/*
Plugin Name: Dashicons + Custom Post Types
Plugin URI: http://halgatewood.com/dashicons-cpt
Description: Easily select which dashicons you want to use with your custom post types.
Author: Hal Gatewood
Author URI: http://www.halgatewood.com
Version: 1.0.2

	FILTERS:
		dashicons_cpt_types: Allows you to change what types of post types to show

*/

require_once( plugin_dir_path( __FILE__ ) . "/dashicons.php" );


// LOAD ALL THE GOODS
function dash_cpt_loaded()
{
	add_action( 'admin_head', 'dash_cpt_css' );
	add_action(	'admin_menu', 'dash_cpt_admin_menu' );
	add_action( 'admin_enqueue_scripts', 'dash_cpt_admin_js');
	add_action( 'wp_ajax_dashicon-cpt-save', 'dash_cpt_save_icon_selection' );
	add_action( 'wp_ajax_dashicon-cpt-remove', 'dash_cpt_remove_icon_selection' );
}
add_action( 'plugins_loaded', 'dash_cpt_loaded' );


// ADD THE JAVASCRIPT FOR THE SAVING OF THE SELECTION
function dash_cpt_admin_js() 
{
	wp_enqueue_script( 'dash-cpt-js', plugins_url( 'dashicons-cpt/js/dashicons-cpt.js' , dirname(__FILE__) ), array('jquery') );
}


// CREATE THE SETTINGS PAGE
function dash_cpt_admin_menu()
{
	add_options_page( 'Dashicons + CPT', 'Dashicons + CPT', 'manage_options', 'dashicons_cpt', 'dash_cpt_page' );
}


// CSS FOR OUR SETTINGS PAGE
function dash_cpt_css()
{
	global $_wp_admin_css_colors;

	// GET CURRENT THEME
	$color_scheme 					= get_user_option( 'admin_color' );
	$current_admin_color_theme 		= $_wp_admin_css_colors[ $color_scheme ];
	
	$current_icons 		= get_option('dashicons-cpt');

	echo '
		<style>
			.dashicons-cpt-wrap h2 { padding-bottom: 20px; margin-bottom: 20px; border-bottom: solid 1px #fff; }
			.dashicons-set { padding: 10px; margin-top: 5px; }
			.dashicons-set .dashicons { cursor: pointer; cursor: hand; width: 24px; height: 24px; font-size: 24px; padding: 2px; transition: all 0.25s;  }
			.dashicons-set .dashicons:hover { color: ' . $current_admin_color_theme->colors[3] . '; }
			.dashicons-set .dashicons.selected { background: #efefef; color: ' . $current_admin_color_theme->colors[2] . '; }
	';
	
	foreach( $current_icons as $pt => $current_icon )
	{
		echo ' #adminmenu #menu-posts-' . $pt . ' div.wp-menu-image:before { content: "\\' . $current_icon . '"; } ';
	}
		
	echo '
		</style>
	';
}


// SETTINGS PAGE, INCLUDE dashicons-cpt-page.php AS THE TEMPLATE
// LOAD UP SOME VARIABLES
function dash_cpt_page()
{
	$post_types 	= get_post_types( apply_filters( 'dashicons_cpt_types', array( '_builtin' => false )), 'objects' );
	$dashicons 		= dash_cpt_icons();
	
	$current_icons 		= get_option('dashicons-cpt');
	
	require_once( plugin_dir_path( __FILE__ ) . "/dashicons-cpt-page.php" );
}


// AJAX REMOVE OF AN ICON

function dash_cpt_remove_icon_selection()
{
	$current_icons = get_option('dashicons-cpt');
	unset( $current_icons[ $_POST['post_type'] ] );
	update_option( 'dashicons-cpt', $current_icons );
}


// AJAX SAVE OF THE ICONS
function dash_cpt_save_icon_selection()
{
	$new_save 			= array();
	$current_icons 		= get_option('dashicons-cpt');
	$post_types 		= get_post_types( apply_filters( 'dashicons_cpt_types', array( '_builtin' => false )), 'objects' );
	
	// RUN THROUGH TO CLEAN UP
	foreach( $post_types as $post_type )
	{
		if( isset($current_icons[ $post_type->name ]) AND $current_icons[ $post_type->name ] != "")
		{
			$new_save[ $post_type->name ] = $current_icons[ $post_type->name ];
		}
	}

	$post_type 			= addslashes($_POST['post_type']);
	$before_content 	= addslashes($_POST['before_content']);
	
	$new_save['' . $post_type . ''] = $before_content;

	update_option( 'dashicons-cpt', $new_save );
}