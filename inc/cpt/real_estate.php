<?php

// Create CPT for real estate
add_action('init', 'create_cpt_real_estate', 0);
function create_cpt_real_estate () {
	register_post_type('real_estate', array(
		'label'               => 'Недвижимость',
		'labels'              => array(
			'name'               => 'Недвижимость',
			'singular_name'      => 'Объект недвижимости',
			'menu_name'          => 'Недвижимость',
			'add_new_item'       => 'Добавить объект',
			'add_new'            => 'Добавить объект'
		),
		'supports'              => array('title', 'editor', 'thumbnail', 'author', 'excerpt'),
		// для работы gutenberg
		'show_in_rest' => true,
		'public'                => true,
		'menu_position'         => 8,
		'menu_icon'             => 'dashicons-admin-home'
	));
}

?>