<?php

//action to set up child theme stylesheet

add_action( 'wp_enqueue_scripts', 'enqueue_parent_styles' );
function enqueue_parent_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri().'/style.css' );

}

add_action('acf_input_admin_head', 'my_acf_admin_head');
function my_acf_admin_head() {
	?>
	<style type="text/css">
		.field_key-field_59497d6bcc71b {
			display: block;
			width: 50%;
		}
	</style>

	<?php
	

}