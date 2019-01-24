<?php
$model = new Slzexploore_Core_Tour();
$get_data = array();
if( isset( $_GET['search-tour'] ) ) {
	foreach( $_GET as $key=>$value ) {
		if( !empty($value) ){
			$get_data[$key] = $value;
		}
	}
}
$template = slzexploore_is_custom_post_type_archive();
$cls_result = '';
$form_atts = sprintf( 'action=%s', esc_url( $model->get_search_link() ) );
if( !empty( $template ) && $template == 'tour'  ||  $search_page == 'yes' ){
	$form_atts = '';
	$cls_result = 'result-page';
}
$tour_filter = Slzexploore::get_option('slz-tour-filter');
printf('<form class="find-tour slz-search-widget slz-shortcode sidebar-widget %1$s %2$s" %3$s>',
		esc_attr($cls_result),
		esc_attr($extra_class),
		esc_attr($form_atts)
	);
?>
<?php if( isset( $tour_filter['enabled']['find-tour-box'] ) ): ?>
<div class="col-2">
	<div class="find-widget tour-template find-flight-widget widget" data-placeholder="<?php esc_html_e( 'Choose Location', 'slzexploore-core' ); ?>">
		<h4 class="title-widgets"><?php esc_html_e( 'find your tour', 'slzexploore-core' ); ?></h4>
		<div class="content-widget">
			<div class="text-input small-margin-top">
				<div class="text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Keyword?', 'slzexploore-core' ); ?>
					</label>
					<div class="input-group">
					<?php
						echo ( $this->text_field( 'keyword',
												$this->get_field( $get_data, 'keyword', '' ),
												array(
													'class' => 'tb-input',
													'placeholder' => esc_html__( 'Enter a keyword', 'slzexploore-core' )
												) ) );
					?>
					</div>
				</div>
				<div class="text-box-wrapper">
					<label class="tb-label">
						<?php esc_html_e( 'Where?', 'slzexploore-core' ); ?>
					</label>
					<div class="input-group">
					<?php
						echo ( $this->drop_down_list( 'location',
												$this->get_field( $get_data, 'location', '' ),
												$location,
												array( 'class' => 'tb-input select2' ) ) );
					?>
					</div>
				</div>
				<div class="input-daterange">
					<div class="text-box-wrapper half left">
						<label class="tb-label">
							<?php esc_html_e( 'Start Date', 'slzexploore-core' ); ?>
						</label>

						<div class="input-group">
							<?php
								echo ( $this->text_field( 'start_date',
															$this->get_field( $get_data, 'start_date', '' ),
															array(
																'class' => 'tb-input',
																'placeholder' => esc_html__( 'YYYY-MM-DD', 'slzexploore-core' ),
																'readonly' => 'readonly'
															) ) );
							?>
							<i class="tb-icon fa fa-calendar input-group-addon"></i>
						</div>
					</div>
				</div>
			</div>
			<button type="submit" data-hover="<?php esc_html_e( 'search now', 'slzexploore-core' ); ?>" class="btn btn-slide small-margin-top" name="search-tour" value="1"><span class="text"><?php esc_html_e( 'search', 'slzexploore-core' ); ?></span><span class="icons fa fa-long-arrow-right"></span></button>
		</div>
	</div>
</div>
<?php endif; ?>

<?php if( $expand == true){
$expand_text = esc_html__('More Filter','slzexploore-core');
echo '<div class="search-expand" data-expand="'.esc_attr($expand_text).'" data-collapse="'.esc_html__('Less Filter','slzexploore-core').'"><span>'.($expand_text).'</span></div>';
echo '<div class="content-expand">';

}?>

<div class="col-2">
	<?php if( isset( $tour_filter['enabled']['price'] ) ): ?>
	<div class="col-1">
		<div class="price-widget widget tour">
			<div class="title-widget">
				<div class="title"><?php esc_html_e( 'price', 'slzexploore-core' ); ?></div>
			</div>
			<div class="content-widget">
				<div><a href="javascript:void(0);" title="" class="btn-reset hide">
					<?php esc_html_e( 'Clear', 'slzexploore-core' ); ?>
				</a></div>
				<div class="price-wrapper">
					<?php
						$min_value = 0;
						$max_value = Slzexploore::get_option('slz-tour-price-max');
						printf('
							<div data-range_min="%1$s" data-range_max="%2$s" data-cur_min="%1$s" data-cur_max="%2$s" class="nstSlider">
								<div class="leftGrip indicator">
									<div class="number"></div>
								</div>
								<div class="rightGrip indicator">
									<div class="number"></div>
								</div>
							</div>
							<div class="leftLabel">%1$s</div>
							<div class="rightLabel">%2$s</div>
							<input type="hidden" name="price" class="sliderValue" value="" />',
							$min_value,
							$max_value
						);
					?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php if( isset( $tour_filter['enabled']['rating'] ) ): ?>
	<div class="col-1">
		<div class="rating-widget widget">
			<div class="title-widget">
				<div class="title"><?php esc_html_e( 'review rating', 'slzexploore-core' ); ?></div>
			</div>
			<div class="content-widget">
				<div><a href="javascript:void(0);" title="" class="btn-reset hide">
					<?php esc_html_e( 'Clear', 'slzexploore-core' ); ?>
				</a></div>
				<div class="radio-selection">
					<?php
						$number_star = 5;
						$get_rating = $this->get_field( $get_data, 'review_rating', '' );
						for( $i = $number_star; $i >= 1; $i-- ) {
							$checked = '';
							if( $get_rating == $i ){
								$checked = 'checked';
							}
							printf('
								<div class="radio-btn-wrapper">
									<input type="checkbox" name="review_rating" value="%1$s" %3$s id="%1$sstars" class="radio-btn">
									<label for="%1$sstars" class="radio-label stars stars%1$s">%1$sstars</label>
									<span class="count">%2$s</span>
								</div>',
								$i,
								count( $model->get_post_by_rating( $i ) ),
								$checked
							);
						}
					?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php
		$taxonomy = 'slzexploore_tour_location';
		$arr_taxs = Slzexploore_Core_Com::get_tax_options2name( $taxonomy );
		if( ( count( $arr_taxs ) > 0 ) && (  isset( $tour_filter['enabled']['cities'] )  ) ) :
	?>
	<div class="col-1">
		<div class="city-widget widget">
			<div class="title-widget">
				<div class="title"><?php esc_html_e( 'locations', 'slzexploore-core' ); ?></div>
			</div>
			<div class="content-widget">
				<div><a href="javascript:void(0);" title="" class="btn-reset hide">
					<?php esc_html_e( 'Clear', 'slzexploore-core' ); ?>
				</a></div>
				<div class="radio-selection">
				<?php
					foreach( $arr_taxs as $slug => $name ) {
						$obj_term = get_term_by( 'slug', $slug, $taxonomy );
						$cls_checked = '';
						$get_location = $this->get_field( $get_data, 'location', '' );
						if( is_tax( $taxonomy, $obj_term ) || $get_location == $slug ) {
							$cls_checked = 'checked';
						}
						printf('
							<div class="radio-btn-wrapper">
								<input type="checkbox" name="location" %5$s value="%1$s" id="%2$s" class="radio-btn">
								<label for="%2$s" class="radio-label">%3$s</label>
								<span class="count">%4$s</span>
							</div>',
							esc_attr($slug),
							esc_attr(sanitize_title($name)),
							esc_html($name),
							esc_html($obj_term->count),
							esc_attr($cls_checked)
						);
					}
				?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
	<?php
		$taxonomy = 'slzexploore_tour_cat';
		if( is_tax( 'slzexploore_tour_cat' ) ){
			$queried_object = get_queried_object();
			printf('<input type="hidden" name="category" value="%1$s">', $queried_object->slug);
		}
		$arr_cats = Slzexploore_Core_Com::get_tax_options2name( $taxonomy );
		if( ( count( $arr_cats ) > 0 ) && (  isset( $tour_filter['enabled']['category'] )  ) ) :
	?>
	<div class="col-1">
		<div class="city-widget widget">
			<div class="title-widget">
				<div class="title"><?php esc_html_e( 'Categories', 'slzexploore-core' ); ?></div>
			</div>
			<div class="content-widget">
				<div><a href="javascript:void(0);" title="" class="btn-reset hide">
					<?php esc_html_e( 'Clear', 'slzexploore-core' ); ?>
				</a></div>
				<div class="radio-selection">
				<?php
					foreach( $arr_cats as $slug => $name ) {
						$obj_term = get_term_by( 'slug', $slug, $taxonomy );
						$cls_checked = '';
						$get_cat = $this->get_field( $get_data, 'category', '' );
						if( is_tax( $taxonomy, $obj_term ) || $get_cat == $slug ) {
							$cls_checked = 'checked';
						}
						printf('
							<div class="radio-btn-wrapper">
								<input type="checkbox" name="category" %5$s value="%1$s" id="%2$s" class="radio-btn">
								<label for="%2$s" class="radio-label">%3$s</label>
								<span class="count">%4$s</span>
							</div>',
							esc_attr($slug),
							esc_attr(sanitize_title($name)),
							esc_html($name),
							esc_html($obj_term->count),
							esc_attr($cls_checked)
						);
					}
				?>
				</div>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>

<?php if( $expand == true){
	echo '</div>';
}?>

</form>