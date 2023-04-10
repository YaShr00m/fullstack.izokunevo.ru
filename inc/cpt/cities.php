<?php

// Create CPT for cities
add_action('init', 'create_cpt_city', 0);
function create_cpt_city () {
	register_post_type('city', array(
		'label'               => 'Города',
		'labels'              => array(
			'name'               => 'Города',
			'singular_name'      => 'Город',
			'menu_name'          => 'Города',
			'add_new_item'       => 'Добавить город',
			'add_new'            => 'Добавить город'
		),
		'supports'              => array('title', 'editor', 'thumbnail', 'author', 'excerpt'),
		// для работы gutenberg
		'show_in_rest' => true,
		'public'                => true,
		'menu_position'         => 8,
		'menu_icon'             => 'dashicons-location'
	));
}

?>