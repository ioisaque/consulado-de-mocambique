<?php 

/*--------------- Map---------------*/

$cluster_image = !(empty($cluster_image)) ? wp_get_attachment_url($cluster_image) : SLZEXPLOORE_CORE_MAKER_CLUSTER;

$zoom = !(empty($zoom))? $zoom:6;
if(empty($post_type)) {
	$post_type = 'slzexploore_hotel';
}

switch ($post_type) {
	case 'slzexploore_hotel':
		$map_marker = (!empty($map_marker)) ? wp_get_attachment_url( $map_marker ): SLZEXPLOORE_CORE_HOTEL_MAP_MAKER;
		$model = new Slzexploore_Core_Accommodation();
		break;
	case 'slzexploore_car':
		$map_marker = (!empty($map_marker)) ? wp_get_attachment_url( $map_marker ): SLZEXPLOORE_CORE_CAR_MAP_MAKER;
		$model = new Slzexploore_Core_Car();
		break;
	case 'slzexploore_cruise':
		$map_marker = (!empty($map_marker)) ? wp_get_attachment_url( $map_marker ): SLZEXPLOORE_CORE_CRUISE_MAP_MAKER;
		$model = new Slzexploore_Core_Cruise();
		break;
	case 'slzexploore_tour':
		$map_marker = (!empty($map_marker)) ? wp_get_attachment_url( $map_marker ): SLZEXPLOORE_CORE_TOUR_MAP_MAKER;
		$model = new Slzexploore_Core_Tour();
		break;
	default:
		break;
}
if(!empty($is_container)){
	$extra_class .= ' slz-map-location-full';
}
echo '<div class="sc-map-location '.esc_attr($extra_class).'">';
	$model->init();
	$address = $model->get_address_info();

	if(!empty($address)):?>
		<div class="map-block-wrapper" data-img-url="<?php echo esc_url($map_marker);?>" data-cluster="<?php echo esc_url($cluster_image);?>" data-zoom="<?php echo esc_attr($zoom);?>" >
			<div class="map-block">
				<?php
					$maps_data = $address;
					printf( '<div id="multi-marker" data-json=\'%1$s\'></div>',
						htmlentities( json_encode($maps_data), ENT_QUOTES, "utf-8" )
					);
				?>
			</div>
		</div><?php 
	endif;


	/* --------------Result-meta----------------------*/

	$result_meta = '';
	$result_count  = Slzexploore::get_option( 'slz-car-result-count' );
	$sort_by       = Slzexploore::get_option( 'slz-car-list-sort' );
	$show_result = false;
	$show_sort = false;
	$result_class = 'col-md-7';
	$sort_class = 'col-md-5';
	if( $result_count ) {
		$show_result = true;
	}
	else{
		$sort_class = 'col-md-12';
	}
	if( $sort_by ) {
		$show_sort = true;
	}
	else{
		$result_class = 'col-md-12';
	}


	/* -------------grid && search form----------------------*/

	$model = new Slzexploore_Core_Shortcode_Controller();
	$json_attr = array();
	$json_attr['columns'] = $columns;
	$json_attr['limit_post'] = $limit_post;
	$json_attr['offset_post'] = $offset_post;
	$json_attr['post_type'] = $post_type;
	$json_attr['allow_address_empty'] = 'yes';

	if(!empty($is_container)){
		echo '<div class="container">';
	}
		echo '<div class="result-page" data-asset_uri="'.SLZEXPLOORE_CORE_ASSET_URI.'">';
			switch ($post_type) {
				case 'slzexploore_hotel':
				$json_attr = json_encode($json_attr);
				echo '<div class="hotel-result-main"><div class="loading"></div><div class="result-body"><div class="hotel-map-search" data-attr=\''.$json_attr.'\'>';
						echo do_shortcode('[slzexploore_core_accommodation_search_sc search_page="yes" expand="true"]');
						echo '<div class="result-meta row">';
							if( $show_result ){
								printf('<div class="%s"><div class="result-count-wrapper"></div></div>',
										esc_attr( $result_class ) );
							}
							if( $show_sort ){
								echo '<div class="'. esc_attr( $sort_class ) .'"><div class="result-filter-wrapper">';
								 do_action('slzexploore_core_result_filter', 'hotel', 'enable');
								echo '</div></div>';
							}

						echo '</div>';
						echo '<div class="hotel-result-content"><div class="hide slz-pagination-base" data-base="accommodations"></div>';
							echo do_shortcode('[slzexploore_core_accommodation_grid_sc limit_post="'.$limit_post.'" offset_post="'.$offset_post.'" columns="'.$columns.'" allow_address_empty="yes" sort_by="'.$sort_hotel.'"]');
						echo '</div>';
				echo '</div></div></div>';
				
				break;

				case 'slzexploore_car':

					$json_attr['style'] = 'grid';
					$json_attr = json_encode($json_attr);
					echo '<div class="car-rent-result-main"><div class="loading"></div><div class="result-body"><div class="car-map-search" data-attr=\''.$json_attr.'\'>';
						echo do_shortcode('[slzexploore_core_car_search_sc search_page="yes" expand="true" ]');
						echo '<div class="result-meta row">';
							if( $show_result ){
								printf('<div class="%s"><div class="result-count-wrapper"></div></div>',
										esc_attr( $result_class ) );
							}
							if( $show_sort ){
								echo '<div class="'. esc_attr( $sort_class ) .'"><div class="result-filter-wrapper">';
								 do_action('slzexploore_core_result_filter','car','enable');
								echo '</div></div>';
							}

						echo '</div>';
						echo '<div class="car-result-content"><div class="hide slz-pagination-base" data-base="car"></div>';
							echo do_shortcode('[slzexploore_core_car_grid_sc style="grid" limit_post="'.$limit_post.'" offset_post="'.$offset_post.'" columns="'.$columns.'" allow_address_empty="yes" sort_by="'.$sort_car.'"]');
					echo '</div>';
					echo '</div></div></div>';

					break;

				case 'slzexploore_cruise':
					$json_attr = json_encode($json_attr);
					echo '<div class="cruise-result-main"><div class="loading"></div><div class="result-body"><div class="cruise-map-search" data-attr=\''.$json_attr.'\'>';
						echo do_shortcode('[slzexploore_core_cruise_search_sc search_page="yes" expand="true" ]');
						echo '<div class="result-meta row">';
							if( $show_result ){
								printf('<div class="%s"><div class="result-count-wrapper"></div></div>',
										esc_attr( $result_class ) );
							}
							if( $show_sort ){
								echo '<div class="'. esc_attr( $sort_class ) .'"><div class="result-filter-wrapper">';
								 do_action('slzexploore_core_result_filter', 'cruise','enable');
								echo '</div></div>';
							}

						echo '</div>';
						echo '<div class="cruise-result-content"><div class="hide slz-pagination-base" data-base="cruise"></div>';
							echo do_shortcode('[slzexploore_core_cruise_grid_sc limit_post="'.$limit_post.'" offset_post="'.$offset_post.'" columns="'.$columns.'" allow_address_empty="yes" sort_by="'.$sort_cruise.'"]');
						echo '</div>';
					echo '</div></div></div>';

					break;

				case 'slzexploore_tour':
					$json_attr = json_encode($json_attr);
					echo '<div class="tour-result-main"><div class="loading"></div><div class="result-body"><div class="tour-map-search" data-attr=\''.$json_attr.'\'>';
						echo do_shortcode('[slzexploore_core_tour_search_sc search_page="yes" expand="true"]');
						echo '<div class="result-meta row">';
							if( $show_result ){
								printf('<div class="%s"><div class="result-count-wrapper"></div></div>',
										esc_attr( $result_class ) );
							}
							if( $show_sort ){
								echo '<div class="'. esc_attr( $sort_class ) .'"><div class="result-filter-wrapper">';
								 do_action('slzexploore_core_result_filter', 'tour','enable');
								echo '</div></div>';
							}

						echo '</div>';
						echo '<div class="tour-result-content"><div class="hide slz-pagination-base" data-base="tour"></div>';
							echo do_shortcode('[slzexploore_core_tour_grid_sc limit_post="'.$limit_post.'" offset_post="'.$offset_post.'" columns="'.$columns.'" allow_address_empty="yes" sort_by="'.$sort_tour.'"]');
						echo '</div>';
					echo '</div></div></div>';

					break;

				default:
					break;
			}
		echo '</div>';
		
	if(!empty($is_container)){
		echo '</div>';
	}
echo '</div>';
/*-----------custom Css----------*/
$custom_css = '';
if ( !empty($height) ) {
	$custom_css .= sprintf( '.sc-map-location  .map-block  #multi-marker {height: %spx;}', esc_attr($height) );
}
if ( !empty($custom_css) ) {
	do_action( 'slzexploore_core_add_inline_style', $custom_css );
}