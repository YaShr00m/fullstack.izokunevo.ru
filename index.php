<?php
acf_form_head();
get_header ();
?>
	<!--Section: Объекты недвижимости-->
	<div class="container">
		<?php
		$query = new WP_Query(array(
			'post_type' => 'real_estate',
			'post_status' => 'publish',
			'posts_per_page' => 6,
			'orderby'   => array(
				'date' =>'DESC'
			)
		));

		if ($query->have_posts()) : echo '
    <h2>Новые объекты недвижимости</h2>
    <section class="mt-3"><div class="row gx-lg-5">';
			while ($query->have_posts()) :
				$query->the_post();
				$objectID = get_the_ID();
				$price = get_field ('price', $objectID);
				$whole_area = get_field ('area', $objectID);
				$live_area = get_field ('live_area', $objectID);
				$floor = get_field ('floor', $objectID);
				if ($live_area === $whole_area) $live_area = '';
				$term = wp_get_post_terms($post->ID, 'real_estate_types',  array("fields" => "names")) [0];
				?>
				<div class="col-6 col-sm-6 col-lg-4 col-md-6 mb-4 post_item">
					<div>
						<div class="bg-image hover-overlay shadow-1-strong ripple rounded-5 mb-2"
						     data-mdb-ripple-color="light">
							<div class="post_item_price"><?=number_format($price, 0, '.', ' ');?> ₽</div>
							<a href="<?=get_permalink()?>">
								<img src="<?=get_the_post_thumbnail_url($objectID, 'post-item-thumb')?>" class="img-fluid" />
							</a>
							<div class="post_item_info">
								<div class="row">
									<div class="col-6">
										<i class="fa fa-house"></i> <?=$term?> <span title="Общая и жилая площадь"><?=$whole_area?><?php echo empty($live_area) ? "" : '/'.$live_area; ?> м²</span>
									</div>
									<div class="col-6 text-end">
										<span title="Этаж"><?php echo empty($floor) ? "" : '<i class="fa fa-stairs"></i> '.$floor.' этаж'; ?></span>
									</div>
								</div>
							</div>
						</div>
						<div class="row mb-3">
							<div class="col-7">
								<a href="<?=get_permalink()?>" title="<?=get_the_title(); ?>" class="post_item_link">
									<?=get_the_title(); ?>
								</a>
							</div>

							<div class="col-5 text-end item_city"><span>
                            <?php
                            @$city_id = get_post_meta (get_the_ID(), '_city') [0];
                            if (!$city_id) $city = '<i>не указан</i>'; else @$city = get_the_title($city_id);
                            echo '<a href="'.get_permalink($city_id).'"> <i class="fa fa-map-marker" aria-hidden="true"></i>'.$city.'</a>';
                            ?></span>
							</div>
						</div>
					</div>
				</div>
			<?php
			endwhile;
			// end if query have posts
			echo '</div></section>';
		endif;
		?>
		<!--Section: // Объекты недвижимости-->

		<!--Section: Города-->
		<?php
		$query = new WP_Query(array(
			'post_type' => 'city',
			'post_status' => 'publish',
			'posts_per_page' => -1,
			'orderby'   => array(
				'date' =>'DESC'
			)
		));

		if ($query->have_posts()) : echo '
    <h2>Города</h2>
    <section class="mt-5"><div class="row gx-lg-5">';
			while ($query->have_posts()) :
				$query->the_post();
				?>
				<div class="col-lg-2 col-4 col-md-3 mb-4 mb-lg-3 post_item post_cities">
					<div>
						<div class="bg-image hover-overlay shadow-1-strong ripple rounded-5 mb-2"
						     data-mdb-ripple-color="light">
							<a href="<?=get_permalink()?>">
								<i><?=get_city_objects_num (get_the_ID())?></i>
								<img src="<?=get_the_post_thumbnail_url(get_the_id (), 'thumbnail')?>" class="img-fluid" />
								<span><?=get_the_title(); ?></span>
							</a>
						</div>
					</div>
				</div>
			<?php
			endwhile;
			// end if query have posts
			echo '</div></section>';
		endif;
		?>
		<!--Section: // города -->

		<section class="mt-5">
			<div class="row gx-lg-5">
				<h2>Разместите вашу недвижимость на сайте</h2>
				<?php if (!is_user_logged_in()) : ?>
					<form class="form" id="form_signup">
						<div id="form_error" class="alert alert-danger d-none"></div>
						<div class="form-group mb-2">
							<label for="email">Ваш адрес электронной почты:</label>
							<input type="email" class="form-control mt-3 mb-1" id="email" aria-describedby="emailHelp" placeholder="example@example.ru">
							<small id="emailHelp" class="form-text text-muted d-block mb-3">Он нужен для того, чтобы в дальнейшем вы смогли редактировать ваше объявление</small>
							<button type="button" class="btn btn-success" id="add_real_estate" onclick="show_add_real_estate_form()">Добавить объявление</button>
						</div>
					</form>
					<div id="form_result">
					</div>
                <div id="show_form_real_estate_add">
                    <?php
                    else:
                        get_currentuserinfo();
                        $email = (string) $current_user->user_email;
                        echo '<p>Вы добавляете объявление от имени аккаунта '.$email.'</p>';
                        show_form_real_estate_add ();
                    endif;
                    ?>
                </div>
                <div id="show_form_real_estate_result">
                </div>
			</div>
		</section>

		<!-- END CONTAINER-->
	</div>
<?php
get_footer ();
?>