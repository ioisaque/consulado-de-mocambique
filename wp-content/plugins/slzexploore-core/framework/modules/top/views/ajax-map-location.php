<?php

switch ($atts['post_type']) {
	case 'slzexploore_hotel':
		$model = new Slzexploore_Core_Accommodation();
		break;
	case 'slzexploore_car':
		$model = new Slzexploore_Core_Car();
		break;
	case 'slzexploore_cruise':
		$model = new Slzexploore_Core_Cruise();
		break;
	case 'slzexploore_tour':
		$model = new Slzexploore_Core_Tour();
		break;
	default:
		$model = new Slzexploore_Core_Accommodation();
		break;
}

$model->init( $atts, $query_args );
$address = $model->get_address_info();

if(!empty($address)):?>
	<div class="map-block-ajax">
		<div class="map-block load-ajax">
			<?php
				$maps_data = $address;
				printf( '<div id="multi-marker" data-json=\'%1$s\'"></div>',
					htmlentities( json_encode($maps_data), ENT_QUOTES, "utf-8" )
				);
			?>
		</div>
	</div><?php 
endif;