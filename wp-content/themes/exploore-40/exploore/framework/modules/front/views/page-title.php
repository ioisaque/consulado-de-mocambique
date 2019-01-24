<?php
$post_type = get_post_type();
if ( Slzexploore::get_option('slz-page-title-show') != '1' ) {
	return;
}
$post_id = get_the_ID();
$show_title = Slzexploore::get_option('slz-show-title');
$price = '';
$star = '';
//title
$page_options = get_post_meta( get_the_ID(), 'slzexploore_page_options', true);
if( empty($page_options['title_custom_content']) ) {
	$title = get_the_title();
}
else {
	$title = $page_options['title_custom_content'];
}
$is_title = false;
$pt_class = '';
if ( is_search() ) {
	$title = esc_html__( 'Search results', 'exploore' );
}else if( is_archive() && ! slzexploore_is_custom_post_type_archive() ) {
	if ( is_month() ) {
		$title = sprintf( '%s' , get_the_date( _x( 'F Y', 'monthly archives date format', 'exploore' ) ) );
	} elseif ( is_day() ) {
		$title = sprintf( '%s' , get_the_date( _x( 'F j, Y', 'daily archives date format', 'exploore' ) ) );
	} else{
		$title = get_the_archive_title();
	}
	if( SLZEXPLOORE_WOOCOMMERCE_ACTIVE ) {
		if( is_shop() ) {
			if( isset($page_options['title_custom_content']) && $page_options['title_custom_content'] ) {
				$title = $page_options['title_custom_content'];
			} else {
				$title = wc_get_page_id( 'shop' ) ? get_the_title( wc_get_page_id( 'shop' ) ) : '';
					
				if ( ! $title ) {
					$product_post_type = get_post_type_object( 'product' );
					$title = $product_post_type->labels->singular_name;
				}
			}
		}
	}
} else {
	
	if( $post_type == 'post' ) {
		$custom_title = Slzexploore::get_value($page_options, 'title_custom_content');
		if ( Slzexploore::get_option('slz-blog-show-title') == 'category' ) {
			$current_cat = current( get_the_category( $post_id ) );
			if( $current_cat ) {
				$title = $current_cat->name;
			}
		} else{
			if ( empty($page_options['page_title_default']) ) {
				$title = $custom_title;
			} else if ( Slzexploore::get_option('slz-blog-show-title') == 'hide' ) {
				$show_title = '';
			} else {
				$title = get_the_title();
			}
		}
	}else {
		//product
		if ($post_type == 'product'){
			$show_pd_title = Slzexploore::get_option('slz-shop-show-title');
			$pd_custom_title = Slzexploore::get_option('slz-shop-custom-title');
			if ($show_pd_title == '1'){
				if(!empty($pd_custom_title)){
					$title = $pd_custom_title;
				}
			}else{
				$show_title = '';
			}
		}
		// hotel & tour
		if( SLZEXPLOORE_CORE_IS_ACTIVE ) {
			$custom_post_type = array('hotel' => 'slzexploore_hotel', 'tour' =>'slzexploore_tour', 'car' => 'slzexploore_car', 'cruises' => 'slzexploore_cruise' );
			foreach($custom_post_type as $k => $val ) {
				if( $post_type == $val) {
					$pt_class = 'exploore';
					$show_title = Slzexploore::get_option('slz-'.$k.'-show-title');
					if( $show_title ) {
						if ( slzexploore_is_custom_post_type_archive() ){
							// archive pages
							$custom_title = Slzexploore::get_option('slz-'.$k.'-custom-title');
							if( empty($custom_title ) ) {
								$term = $GLOBALS['wp_query']->get_queried_object();
								if($term && !empty($term->labels->singular_name)) {
									$custom_title = $term->labels->singular_name;
								}
							}
						} else {
							if( $k == 'tour') {
								$model = new Slzexploore_Core_Tour();
							} else if( $k == 'car'){
								$model = new Slzexploore_Core_Car();
							} else if( $k == 'cruises'){
								$model = new Slzexploore_Core_Cruise();
							} else {
								$model = new Slzexploore_Core_Accommodation();
							}
							$model->loop_index();
							// single page
							$custom_title = Slzexploore::get_option('slz-'.$k.'-detail-custom-title');
							if( empty( $custom_title ) ) {
								$custom_title = $model->title;
							}
							
							if( Slzexploore::get_option('slz-'.$k.'-show-price') == '1' ) {
								$html_options = array(
									'price_format'     => '<div class="price"><span class="text">'.esc_html__('from', 'exploore').'</span>%1$s</div>',
									'sign_price_format' => '<sup class="unit" >%1$s</sup>',
								);
								$model->set_default_options($html_options);
								$price = $model->get_price();
							}
							if($k == 'hotel' && Slzexploore::get_option('slz-hotel-show-star') == '1'){
								$star = $model->get_star_rating();
							}
							$model->reset();
						}
						$title = $custom_title;
					}
				}
			}
		}
	}
}
?>
<!-- Page Title -->
<section class="<?php echo esc_attr($pt_class)?> page-title">
	<div class="container">
		<div class="page-title-wrapper">
			<div class="page-title-content">
				<?php if ( Slzexploore::get_option('slz-show-breadcrumb') == '1' ):
				$breadcrumb_html = '';
				if ( $breadcrumb = slzexploore_get_breadcrumb() ) {
					foreach ( $breadcrumb as $key => $crumb ) {
						if( ! empty( $crumb[1] ) && sizeof( $breadcrumb ) !== $key + 1){
							$class = 'link';
							if( $key == 0 ) {
								$class = 'link home';
							}
							$breadcrumb_html .= '<li><a href="' . esc_url( $crumb[1] ) . '" class="'.esc_attr($class).'">' . esc_html( $crumb[0] ) . '</a></li>';
						}		
						else {
							if( ! empty( $crumb[0] ) ) {
								$class = 'active';
								if( sizeof( $breadcrumb ) !== $key + 1 ) {
									$class = '';
								}
								$breadcrumb_html .= '<li class="'.esc_attr($class).'"><a href="' . esc_url( $crumb[1] ) . '" class="link">' . esc_html( $crumb[0] ) . '</a></li>';
							}
						}
					}
				}else{
					$breadcrumb_html = '<li class="active"><a href="' . esc_url( home_url( '/' ) ) . '" title="' . esc_attr__('Home', 'exploore') . ' "class="link home">' . esc_html__('Home', 'exploore') . '</a></li>';
				}
				printf('<ol class="breadcrumb">%s</ol>', $breadcrumb_html );
				endif;//show_breadcrumb?>
				<div class="clearfix"></div>
				<?php if ( !empty($show_title) ): ?>
					<h2 class="captions">
						<?php
						if( ! $is_title ) {
							echo wp_kses_post($title);
						}?>
					</h2>
					<?php if($price):?>
					<?php echo wp_kses_post($price);?>
					<?php endif;?>
					<?php if($star):?>
					<div class="page-title-star">
					<?php echo wp_kses_post($star);?>
					</div>
					<?php endif;?>
				<?php endif; // show_title ?>
			</div>
		</div>
	</div>
</section>
