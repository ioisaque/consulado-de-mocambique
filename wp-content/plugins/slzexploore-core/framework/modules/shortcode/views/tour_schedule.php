<?php
$def = array(
	'title'       => '',
	'icon'        => '',
	'subtitle'    => '',
	'description' => '',
	'image'       => '',
	'img_item'    => '',
	);
$custom_css = '';
$block_uniq = 'block_' . Slzexploore_Core::make_id();
$idx = 0;

/**
 * Slick Carousel Data
 */
$data_slick      = array(
	'slidesToShow'   => 1,
	'slidesToScroll' => 1,
	'dots'           => ! empty( $atts['show_dots'] ),
	'arrows'         => ! empty( $atts['show_arrows'] ),
	'autoplay'       => ! empty( $atts['slide_autoplay'] ),
	'infinite'       => ! empty( $atts['slide_infinite'] ),
	'speed'          => ! empty( $atts['slide_speed'] ) ? intval( $atts['slide_speed'] ) : 300,
	'fade'           => ! empty( $atts['slide_animation'] ),
);
$data_slick_json = json_encode( $data_slick );
?>
<div class="slz-shortcode tour-schedule overview-block clearfix <?php echo esc_attr($atts['extra_class'])?>">
	<?php if( !empty( $atts['block_title'] ) ):?>
	<h3 class="title-style-3"><?php echo esc_attr($atts['block_title'])?></h3>
	<?php endif;?>

	<div class="timeline-container">
		<div class="timeline">
			<?php
			foreach($atts['tour_schedule'] as $item ):
				if( empty( $item ) ) continue;
				$idx ++;
				$block_item = 'timeline-'.$idx;
				$item = array_merge($def, $item);
				extract($item);
				$class = '';

				// Display Image
				$img_item = '';
				if ( empty( $image ) ) { // case no image
					$class      = 'w-full';
					$custom_css .= '.' . $block_uniq . ' .' . $block_item . ' .timeline-custom-col.image-col::before{width:0px;}' . "\n";
				} else { // case have image
					$images = explode( ',', $image );
					foreach ( $images as $attach_id ) {
						$attach_id = intval( $attach_id );
						$thumbnail = wp_get_attachment_image_src( $attach_id, 'full' );
						if ( $thumbnail ) {
							$img_item .= sprintf( '<div class="timeline-image-block items"><img src="%s" alt=""></div>', esc_url( $thumbnail[0] ) );
						}
					}
					$img_item = sprintf( '<div class="timeline-custom-col image-col" data-slick="%1$s">%2$s</div>', esc_attr( $data_slick_json ) , wp_kses_post( $img_item ) );
				}
			?>
			<div class="timeline-block <?php echo $block_item;?>">
				<div class="timeline-title">
					<span><?php echo esc_html( $title );?></span>
				</div>
				<div class="timeline-content medium-margin-top">
					<div class="row">
						<div class="timeline-point"><i class="fa fa-circle-o"></i></div>
						<div class="timeline-custom-col content-col <?php echo esc_attr($class)?>">
							<div class="timeline-location-block">
								<p class="location-name">
									<?php echo esc_html( $subtitle )?>
									<?php if( !empty($icon)):?>
									<i class="<?php echo esc_attr( $icon );?> icon-marker"></i>
									<?php endif;?>
								</p>
								<div class="description">
									<?php echo wp_kses_post($description);?>
								</div>
							</div>
						</div>
						<?php echo ( $img_item );?>
					</div>
				</div>
			</div>
			<?php endforeach;?>
		</div>
	</div>
</div>
<?php
if( $custom_css ){
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}?>