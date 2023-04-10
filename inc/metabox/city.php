<?php
function city_meta_box() {

add_meta_box(
'city',
'Город',
'city_metabox_callback',
'real_estate',
'advanced'
);
}

add_action( 'add_meta_boxes', 'city_meta_box' );

function city_metabox_callback ( $post ) {
	wp_nonce_field( 'city_nonce', 'city_nonce' );
	$query = new WP_Query(array(
	'post_type' => 'city',
	'post_status' => 'publish',
	'posts_per_page' => -1,
	'order' => 'ASC',
	'orderby' => 'title'
	));

	echo '<select name="city" id="city">';
	while ($query->have_posts()) {
	$query->the_post();
	$post_id = get_the_ID();
	if ((int) get_post_meta ($_GET ['post'], '_city') [0] === $post_id) $selected = ' selected'; else $selected = '';
	echo '<option value="'.$post_id.'" '.$selected.'>'.get_the_title().'</option>';
	}
	echo '</select>';

wp_reset_query();
}


function save_city_metabox ( $post_id ) {

	// проверка наличия nonce
	if ( ! isset( $_POST['city_nonce'] ) ) {
		return;
	}

	// проверка nonce на валидность
	if ( ! wp_verify_nonce( $_POST['city_nonce'], 'city_nonce' ) ) {
		return;
	}

	// ничего не делаем если autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// проверяем права на редактирование
	if ( isset( $_POST['post_type'] ) && 'page' == $_POST['post_type'] ) {

		if ( ! current_user_can( 'edit_page', $post_id ) ) {
			return;
		}

	}
	else {
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
	}

	if (isset( $_POST['city'])) update_post_meta( $post_id, '_city', (int) $_POST['city'] );
}

add_action( 'save_post', 'save_city_metabox' );









?>