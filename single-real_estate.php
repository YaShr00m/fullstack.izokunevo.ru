<?php
get_header ();

$price = get_field ('price');
$whole_area = get_field ('area');
$live_area = get_field ('live_area');
$address = get_field ('address');
@$city_id = get_post_meta (get_the_ID(), '_city') [0];

if ($live_area === $whole_area) $live_area = '';
$terms = wp_get_post_terms($post->ID, 'real_estate_types',  array("fields" => "names"));


?>

<div class="estate">
	<div class="container bootstrap snippets bootdey">
		<div class="row">
			<div class="col-md-12">
				<div class="row">
					<div class="col-md-7">
						<div class="product-gallery">
							<div class="primary-image">
								<a href="#" class="theater" rel="group" hidefocus="true">
									<img src="<?=get_the_post_thumbnail_url(get_the_ID(), 'post-item-image')?>" class="img-responsive" alt="<?=get_the_title ()?>">
								</a>
							</div>
							<div class="thumbnail-images">
								<?php foreach (get_field ('photos') as $photo) : ?>
									<a href="#" class="theater" rel="group" hidefocus="true">
										<img src="<?=$photo ['thumbnail_image_url']?>" data-full-image-url="<?=wp_get_attachment_image_src ($photo ['id'], 'post-item-image') [0]?>" alt="">
									</a>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
					<div class="col-md-5">
						<div class="product-info">
							<h3><?=get_the_title()?> <span title="Общая и жилая площадь"><?=$whole_area?><?php echo empty($live_area) ? "" : '/'.$live_area; ?> м²</span></h3>
							<div class="wp-block property list no-border">
								<div class="wp-block-content clearfix">
									<small><i class="fa fa-clock-o"></i> <?=get_the_date( 'd.m.Y' );?></small>
                                    <small><i class="fa fa-house"></i> <?=$terms [0]?></small>
									<div class="mb-3">
										<span class="price"><?=number_format($price, 0, '.', ' ');?> ₽</span>
									</div>
									<div class="estate-address mb-3">
										<i class="fa fa-map-marker"></i> <?=$address?>
									</div>
									<?=the_content (); ?>
									<?php
									// посчитаем кол-во объектов в городе
									$objects_num = get_city_objects_num ($city_id);
									if ($objects_num) echo '<a href="'.get_permalink ($city_id).'">Ещё недвижимость в городе '.get_the_title($city_id).' ('.$objects_num.')</a>';
									?>
									<a href=""></a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
    $ = jQuery;
    $('.theater img').click (function () {
        $('.primary-image a img').attr ('src', $(this).data ('full-image-url'));
    });
</script>
<?php
get_footer ();
?>
