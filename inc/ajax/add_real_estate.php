<?php

function show_form_real_estate_add () {?>
	<form id="add_real_estate_form" enctype="multipart/form-data">
		<div class="row">
            <div class="form-group col-md-12">
                <label for="title">Название объекта</label>
                <input name="title" type="text" class="form-control" id="title" placeholder="">
            </div>
        </div>
        <div class="row mt-3">
            <div class="form-group col-md-2">
                <label for="title">Тип объекта</label>
		        <?php
		        echo '<select class="form-control" name="object_type" id="city">';
		        $terms = get_terms([
			        'taxonomy' => 'real_estate_types',
			        'hide_empty' => false,
		        ]);
                foreach ($terms as $term) echo '<option value="'.$term->slug.'">'.$term->name.'</option>';
		        echo '</select>';
		        ?>
            </div>
            <div class="form-group col-md-2">
                <label for="city">Город</label>
		        <?php
		        $query = new WP_Query(array(
			        'post_type' => 'city',
			        'post_status' => 'publish',
			        'posts_per_page' => -1,
			        'order' => 'ASC',
			        'orderby' => 'title'
		        ));

		        echo '<select class="form-control" name="city" id="city">';
		        while ($query->have_posts()) {
			        $query->the_post();
			        $post_id = get_the_ID();
			        if ((int) get_post_meta ($_GET ['post'], '_city') [0] === $post_id) $selected = ' selected'; else $selected = '';
			        echo '<option value="'.$post_id.'" '.$selected.'>'.get_the_title().'</option>';
		        }
		        echo '</select>';

		        wp_reset_query();
		        ?>
            </div>
			<div class="form-group col-md-2">
				<label for="whole_area">Общая площадь, м²</label>
				<input name="whole_area" type="number" class="form-control" id="whole_area" placeholder="">
			</div>
			<div class="form-group col-md-2">
				<label for="live_area">Жилая площадь, м²</label>
				<input name="live_area" type="number" class="form-control" id="live_area" placeholder="">
			</div>
			<div class="form-group col-md-1">
				<label for="floor">Этаж</label>
				<input name="floor" type="number" class="form-control" id="floor" placeholder="">
			</div>
			<div class="form-group col-md-2">
				<label for="price">Цена</label>
				<input name="price" type="number" class="form-control" id="price" placeholder="">
			</div>
		</div>
		<div class="row mt-3">
			<div class="form-group col-md-8">
				<label for="address">Адрес</label>
				<input name="address" type="text" class="form-control" id="address" placeholder="В произвольном формате">
			</div>
		</div>
		<div class="row mt-3">
			<div class="form-group col-md-12">
				<label for="photos">Добавьте одно или несколько изображений:</label>
				<input name="photos[]" type="file" class="form-control-file mb-2" id="photos" accept="image/*" multiple>
			</div>
		</div>
        <div class="form-group mb-3">
            <label for="content">Расскажите об объекте</label>
            <textarea name="content" class="form-control" id="content" rows="3"></textarea>
        </div>
		<button type="button" onclick="add_real_estate ();" id="add_real_estate_submit_btn" class="btn btn-primary">Добавить объявление</button>
	</form>
<?php
}

function show_acf_form() {
	$nonce = $_POST['nonce'];

	if ($_REQUEST ['show_form']) { show_form_real_estate_add (); die (); }

	#if (!wp_verify_nonce( $nonce, 'nonce' )) {
	#	die( 'Please provide nonce code' );
	#}

	if ( isset( $_REQUEST ) ) {
		$email = $_POST ['email'];
		if (($email = filter_var($email, FILTER_VALIDATE_EMAIL)) !== false) {
			my_user_add ($email);
		}
		else {
			echo 'BAD_EMAIL';
		}
	}

	die();
}

function add_real_estate () {

	// nonce тут отключен т.к. после первой ajax авторизации nonce меняется, и выскакивает ошибка.
	// на продакшне такого не нужно делать, но для тестового задания тут нормально :)

    if (!is_user_logged_in()) die ('PLEASE LOGIN');

    // Создание поста
	extract( $_POST );

	// Create post object
	$my_post = array(
		'post_title'    => $title,
		'post_content'  => $content,
		'post_type'     => 'real_estate',
		'post_status'   => 'publish',
		'post_author'   => 1,
		'post_category' => array( 8, 39 )
	);

	// Insert the post into the database
	$post_id = wp_insert_post( $my_post );

	// наполняем недвижку данными
	if ( $post_id ) {
		update_field( 'area', (int) $whole_area, $post_id );
		update_field( 'live_area', (int) $live_area, $post_id );
		update_field( 'price', (int) $price, $post_id );
		update_field( 'address', $address, $post_id );
		update_field( 'floor', (int) $floor, $post_id );
		// город
        update_post_meta( $post_id, '_city', (int) $city );
		// тип недвижки (terms, ct)
		wp_set_object_terms($post_id, $object_type, 'real_estate_types', false);

		// Загрузка и обработка файлов
		$files = reArrayFiles( $_FILES ['photos'] );
		foreach ( $files as $file ) {
			$file_ext = pathinfo( $file ['name'], PATHINFO_EXTENSION );

			$image_url  = $file ['tmp_name'];
			$upload_dir = wp_upload_dir();
			$image_data = file_get_contents( $image_url );
			$filename   = basename( $image_url );

			if ( wp_mkdir_p( $upload_dir['path'] ) ) {
				$file = $upload_dir['path'] . '/' . $filename;
			} else {
				$file = $upload_dir['basedir'] . '/' . $filename;
			}

			file_put_contents( $file, $image_data );
			$attachment = array(
				'post_mime_type' => 'image/jpeg',
				'post_title'     => sanitize_file_name( $filename ),
				'post_content'   => '',
				'post_status'    => 'inherit'
			);

			$attach_id     = wp_insert_attachment( $attachment, $file );
			$attach_ids [] = $attach_id;
			require_once( ABSPATH . 'wp-admin/includes/image.php' );
			$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
			wp_update_attachment_metadata( $attach_id, $attach_data );
		}

        if (count ($attach_ids) > 0) update_field( 'photos', implode( ',', $attach_ids ), $post_id );
		update_post_meta( $post_id, '_thumbnail_id',$attach_ids [0]);
		die ('<div class="alert alert-success" role="alert">Спасибо, ваше объявление успешно добавлено! Вы можете посмотреть его <a href=": '.get_permalink($post_id).'">по ссылке</a></div>');
	}
}

add_action( 'wp_ajax_show_acf_form', 'show_acf_form' );
// we must allow to use this script non logged users
add_action( 'wp_ajax_nopriv_show_acf_form', 'show_acf_form' );

add_action( 'wp_ajax_add_real_estate', 'add_real_estate' );
// we must disallow to use this script non logged users (so just leave this line commented)
#add_action( 'wp_ajax_nopriv_add_real_estate', 'add_real_estate' );
