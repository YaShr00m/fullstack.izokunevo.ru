<?php
function enqueue_ajax() {

	// Enqueue javascript on the frontend.
	wp_enqueue_script(
		'add_form',
		get_stylesheet_directory_uri() . '/js/add_real_estate.js',
		array( 'jquery' )
	);

	wp_localize_script(
		'add_form',
		'ajax_obj',
		array(
			'ajaxurl' => admin_url( 'admin-ajax.php' ),
			'nonce' => wp_create_nonce( 'nonce' )
		)
	);

}

add_action( 'wp_enqueue_scripts', 'enqueue_ajax' );

include ('add_real_estate.php');
?>