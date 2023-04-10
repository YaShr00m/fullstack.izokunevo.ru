<?php
get_header ();
$city_name = get_the_title();
$city_id = get_the_ID();
?>
<div class="container">
	<div class="row">
		<h1 class="mb-4"><?=$city_name?></h1>
		<div class="col-6">
			<img src="<?=get_the_post_thumbnail_url(get_the_ID(), 'post-item-image')?>" />
		</div>
		<div class="col-6">
			<?php the_content (); ?>
		</div>
	</div>
</div>

<div class="container">
<?php

$query = new WP_Query(
	array(
		'post_type'  => 'real_estate',
		'meta_query' => array(
			array(
				'key' => '_city',
				'value' => $city_id,
				'compare' => '='
			)
		)
	)
);

if ($query->have_posts()) : echo '
    <h2 class="mt-5">Недвижимость в городе '.$city_name.'</h2>
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


<?php
get_footer ();
?>