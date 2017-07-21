<?php
/*
Plugin Name: Property Listings
Plugin URI: http://edkatzman.com/
Description: Declares a plugin that will create a custom post type displaying property listings.
Version: 1.0
Author: Ed Katzman
Author URI: http://edkatzman.com/
License: GPLv2
*/


/* Set up custom post property_listings */
/*
function pl_create_property_listing() {
    register_post_type( 'property_listings',
        array(
            'labels' => array(
                'name' => 'Properties',
                'singular_name' => 'Property',
                'add_new' => 'Add New',
                'add_new_item' => 'Add New Property',
                'edit' => 'Edit',
                'edit_item' => 'Edit Property',
                'new_item' => 'New Property',
                'view' => 'View',
                'view_item' => 'View Property',
                'search_items' => 'Search Properties',
                'not_found' => 'No Properties found',
                'not_found_in_trash' => 'No Properties found in Trash',
                'parent' => 'Parent Property',
                'parent_item_colon' => 'Parent Properties:'
            ),
 
            'public' => true,
            'menu_position' => 15,
            'supports' => array( 'title', 'editor', 'comments', 'thumbnail' ),
            'taxonomies' => array( 'property_listings_categories', 'property_listings_tags'),
            'menu_icon' => 'dashicons-networking',
            'has_archive' => true
        )
    );
}

add_action( 'init', 'pl_create_property_listing' );
*/

// Register Custom Post Type
function pl_create_property_listings() {

	$labels = array(
		'name'                  => _x( 'Property Listings', 'Post Type General Name', 'pl_property_listings' ),
		'singular_name'         => _x( 'Property Listing', 'Post Type Singular Name', 'pl_property_listings' ),
		'menu_name'             => __( 'Property Listings', 'pl_property_listings' ),
		'name_admin_bar'        => __( 'Property Listing', 'pl_property_listings' ),
		'archives'              => __( 'Property Archives', 'pl_property_listings' ),
		'attributes'            => __( 'Property Attributes', 'pl_property_listings' ),
		'parent_item_colon'     => __( 'Parent Property:', 'pl_property_listings' ),
		'all_items'             => __( 'All Properties', 'pl_property_listings' ),
		'add_new_item'          => __( 'Add New Property', 'pl_property_listings' ),
		'add_new'               => __( 'Add New', 'pl_property_listings' ),
		'new_item'              => __( 'New Property', 'pl_property_listings' ),
		'edit_item'             => __( 'Edit Property', 'pl_property_listings' ),
		'update_item'           => __( 'Update Property', 'pl_property_listings' ),
		'view_item'             => __( 'View Property', 'pl_property_listings' ),
		'view_items'            => __( 'View Properties', 'pl_property_listings' ),
		'search_items'          => __( 'Search Property', 'pl_property_listings' ),
		'not_found'             => __( 'Not found', 'pl_property_listings' ),
		'not_found_in_trash'    => __( 'Not found in Trash', 'pl_property_listings' ),
		'featured_image'        => __( 'Featured Image', 'pl_property_listings' ),
		'set_featured_image'    => __( 'Set featured image', 'pl_property_listings' ),
		'remove_featured_image' => __( 'Remove featured image', 'pl_property_listings' ),
		'use_featured_image'    => __( 'Use as featured image', 'pl_property_listings' ),
		'insert_into_item'      => __( 'Insert into Property', 'pl_property_listings' ),
		'uploaded_to_this_item' => __( 'Uploaded to this Property', 'pl_property_listings' ),
		'items_list'            => __( 'Properties list', 'pl_property_listings' ),
		'items_list_navigation' => __( 'Properties list navigation', 'pl_property_listings' ),
		'filter_items_list'     => __( 'Filter Properties list', 'pl_property_listings' ),
	);
	$rewrite = array(
		'slug'                  => 'property',
		'with_front'            => true,
		'pages'                 => true,
		'feeds'                 => true,
	);
	$args = array(
		'label'                 => __( 'Property Listing', 'pl_property_listings' ),
		'description'           => __( 'Real estate property listings', 'pl_property_listings' ),
		'labels'                => $labels,
		'supports'              => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'post-formats', ),
		'taxonomies'            => array( 'pl_property_listings_categories', 'pl_property_listings_tags' ),
		'hierarchical'          => false,
		'public'                => true,
		'show_ui'               => true,
		'show_in_menu'          => true,
		'menu_position'         => 20,
		'menu_icon'             => 'dashicons-networking',
		'show_in_admin_bar'     => true,
		'show_in_nav_menus'     => true,
		'can_export'            => true,
		'has_archive'           => true,		
		'exclude_from_search'   => false,
		'publicly_queryable'    => true,
		'rewrite'               => $rewrite,
		'capability_type'       => 'page',
	);
	register_post_type( 'pl_property_listings', $args );

}
add_action( 'init', 'pl_create_property_listings', 0 );

/* Set up post interaction messages */

function pl_messages( $messages ) {
	$post = get_post();

	$messages['pl_property_listings'] = array(
		0  => '',
		1  => 'Property updated.',
		2  => 'Custom field updated.',
		3  => 'Custom field deleted.',
		4  => 'Property updated.',
		5  => isset( $_GET['revision'] ) ? sprintf( 'Property restored to revision from %s',wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
		6  => 'Property published.',
		7  => 'Property saved.',
		8  => 'Property submitted.',
		9  => sprintf(
			'Property scheduled for: <strong>%1$s</strong>.',
			date_i18n( 'M j, Y @ G:i', strtotime( $post->post_date ) )
		),
		10 => 'Property draft updated.'
	);

	return $messages;
}
add_filter( 'post_updated_messages', 'pl_messages' );

/* Set up custom categories and tags */
/*
function pl_property_listings_taxonomies() {  
    
	// Add property categories as taxonomy 
    register_taxonomy(  
        'property_listings_categories',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'property_listings',        //post type name
        array(  
            'hierarchical' => true,  
            'label' => 'Property category',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'property-category', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the category base before 
            )
        )  
    );  

        // Add property tags as taxonomy 
        register_taxonomy(  
        'property_listings_tags',  //The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces). 
        'property_listings',        //post type name
        array(  
            'hierarchical' => false,  
            'label' => 'Property tag',  //Display name
            'query_var' => true,
            'rewrite' => array(
                'slug' => 'property-tag', // This controls the base slug that will display before each term
                'with_front' => false // Don't display the tag base before 
            )
        )  
    ); 

        
}  
add_action( 'init', 'pl_property_listings_taxonomies');
*/

// Register Custom Taxonomy
function pl_custom_categories() {

	$labels = array(
		'name'                       => _x( 'Property Categories', 'Taxonomy General Name', 'pl_property_listings' ),
		'singular_name'              => _x( 'Property Category', 'Taxonomy Singular Name', 'pl_property_listings' ),
		'menu_name'                  => __( 'Property Categories', 'pl_property_listings' ),
		'all_items'                  => __( 'All Property Categories', 'pl_property_listings' ),
		'parent_item'                => __( 'Parent Property Category', 'pl_property_listings' ),
		'parent_item_colon'          => __( 'Parent Property Category:', 'pl_property_listings' ),
		'new_item_name'              => __( 'New Property Category Name', 'pl_property_listings' ),
		'add_new_item'               => __( 'Add New Property Category', 'pl_property_listings' ),
		'edit_item'                  => __( 'Edit Property Category', 'pl_property_listings' ),
		'update_item'                => __( 'Update Property Category', 'pl_property_listings' ),
		'view_item'                  => __( 'View Property Category', 'pl_property_listings' ),
		'separate_items_with_commas' => __( 'Separate Property Categories with commas', 'pl_property_listings' ),
		'add_or_remove_items'        => __( 'Add or remove Property Category', 'pl_property_listings' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'pl_property_listings' ),
		'popular_items'              => __( 'Popular Property Categories', 'pl_property_listings' ),
		'search_items'               => __( 'Search Property Categories', 'pl_property_listings' ),
		'not_found'                  => __( 'Not Found', 'pl_property_listings' ),
		'no_terms'                   => __( 'No Properties', 'pl_property_listings' ),
		'items_list'                 => __( 'Property Category list', 'pl_property_listings' ),
		'items_list_navigation'      => __( 'Property Category list navigation', 'pl_property_listings' ),
	);
	$rewrite = array(
		'slug'                       => 'property-category',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => true,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => false,
		'rewrite'                    => $rewrite,
	);
	register_taxonomy( 'pl_property_listings_categories', array( 'pl_property_listings' ), $args );

}
add_action( 'init', 'pl_custom_categories', 0 );

// Register Custom Taxonomy
function pl_custom_tags() {

	$labels = array(
		'name'                       => _x( 'Property Tags', 'Taxonomy General Name', 'pl_property_listings' ),
		'singular_name'              => _x( 'Property Tag', 'Taxonomy Singular Name', 'pl_property_listings' ),
		'menu_name'                  => __( 'Property Tags', 'pl_property_listings' ),
		'all_items'                  => __( 'All Property Tags', 'pl_property_listings' ),
		'parent_item'                => __( 'Parent Property Tag', 'pl_property_listings' ),
		'parent_item_colon'          => __( 'Parent Property Tag:', 'pl_property_listings' ),
		'new_item_name'              => __( 'New Property Tag Name', 'pl_property_listings' ),
		'add_new_item'               => __( 'Add New Property Tag', 'pl_property_listings' ),
		'edit_item'                  => __( 'Edit Property Tag', 'pl_property_listings' ),
		'update_item'                => __( 'Update Property Tag', 'pl_property_listings' ),
		'view_item'                  => __( 'View Property Tag', 'pl_property_listings' ),
		'separate_items_with_commas' => __( 'Separate Property Tags with commas', 'pl_property_listings' ),
		'add_or_remove_items'        => __( 'Add or remove Property Tag', 'pl_property_listings' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'pl_property_listings' ),
		'popular_items'              => __( 'Popular Property Tags', 'pl_property_listings' ),
		'search_items'               => __( 'Search Property Tags', 'pl_property_listings' ),
		'not_found'                  => __( 'Not Found', 'pl_property_listings' ),
		'no_terms'                   => __( 'No Property Tags', 'pl_property_listings' ),
		'items_list'                 => __( 'Property Tag list', 'pl_property_listings' ),
		'items_list_navigation'      => __( 'Property Tag list navigation', 'pl_property_listings' ),
	);
	$rewrite = array(
		'slug'                       => 'property-tag',
		'with_front'                 => true,
		'hierarchical'               => false,
	);
	
	$args = array(
		'labels'                     => $labels,
		'hierarchical'               => false,
		'public'                     => true,
		'show_ui'                    => true,
		'show_admin_column'          => true,
		'show_in_nav_menus'          => true,
		'show_tagcloud'              => true,
		'rewrite'                    => $rewrite,
		
	);
	register_taxonomy( 'pl_property_listings_tags', array( 'pl_property_listings' ), $args );

}
add_action( 'init', 'pl_custom_tags', 0 );

function pl_include_template_function( $template_path ) {
    if ( get_post_type() == 'pl_property_listings' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-property_listings.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-property_listings.php';
            }
        }
    }
    return $template_path;
}

add_filter( 'template_include', 'pl_include_template_function', 1 );

if ( ! function_exists( 'pl_property_listings_setup' ) ):
/**
 * Sets up plugin defaults and registers support for various WordPress features.
 *
 *
 * @since V 1.0
 */
function pl_property_listings_setup() {
 
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
     * If you're building a theme based on Shape, use a find and replace
     * to change 'shape' to the name of your theme in all the template files
     */
    load_plugin_textdomain( 'pl_property_listings', false, plugin_dir_path( __FILE__ ) . '/languages' );
 
 
 
    
}
endif; // shape_setup
add_action( 'init', 'pl_property_listings_setup' );

function pl_load_plugin_css() {
    $plugin_url = plugin_dir_url( __FILE__ );

    wp_enqueue_style( 'property-listings', $plugin_url . 'css/property-listings.css' );
}
add_action( 'wp_enqueue_scripts', 'pl_load_plugin_css' );



function pl_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'CPT Sidebar', 'twentysixteen' ),
		'id'            => 'sidebar-pl',
		'description'   => __( 'Add widgets here to appear in your CPT sidebar.', 'twentysixteen' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );

	
}
add_action( 'widgets_init', 'pl_widgets_init' );


function pl_enqueue_google_maps() {
	wp_enqueue_script( 
	  'google-maps', 
	  '//maps.googleapis.com/maps/api/js?key=AIzaSyC2gQ9Td4bbyH-HTOd-1c3IO1RD3dvsavk&callback=initMap', 
	  array(), 
	  '1.0', 
	  true 
	);
}
add_action( 'wp_enqueue_scripts', 'pl_enqueue_google_maps' );

function pl_register_maps_script() {
    $plugin_url = plugin_dir_url( __FILE__ );
    global $post;
    $pl_maps_lat = 43.1640;
    $pl_maps_long = 13.3574;
    $pl_address = get_field('town') . ' ' . get_field('province') . ' Italy';
    //$pl_label = get_field('english_translation');
    $pl_label = get_the_title();

    if( $pl_address && $pl_label ) {
        wp_enqueue_script('pl-location-script', $plugin_url . 'js/pl-google-map2.js');
        wp_localize_script('pl-location-script', 'my_location', array(
            'lat' => $pl_maps_lat,
            'long' => $pl_maps_long,
            'address' => $pl_address ,
            'label' => $pl_label
            )
        );
    }
}
add_action( 'wp_enqueue_scripts', 'pl_register_maps_script' );