<?php

function create_ct_real_estate_type () {
	$labels = array(
		'name'              => 'Типы объектов',
		'singular_name'     => 'Тип объекта',
		'search_items'      =>'Поиск по объектам',
		'all_items'         =>'Все типы недвижимости',
		'parent_item'       =>'Родительский объект',
		'parent_item_colon' =>'Родительский объект:',
		'edit_item'         =>'Редактировать объект',
		'update_item'       =>'Обновить объект',
		'add_new_item'      =>'Добавить новый объект',
		'new_item_name'     =>'Имя нового объекта',
		'menu_name'         =>'Типы объектов'
	);
	$args   = array(
		'hierarchical'      => true,
		'show_in_rest' => true,
		'labels'            => $labels,
		'show_ui'           => true,
		'show_admin_column' => true,
		'query_var'         => true,
		'rewrite'           => [ 'slug' => 'types' ]
	);
	register_taxonomy ( 'real_estate_types', [ 'real_estate' ], $args );
}
add_action( 'init', 'create_ct_real_estate_type', 0);
?>