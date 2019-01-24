<?php
if ( $atts['posttype'] == 'slzexploore_tour' ) {
	$model = new Slzexploore_Core_Tour();
}
elseif ( $atts['posttype'] == 'slzexploore_car' ) {
	$model = new Slzexploore_Core_Car();
}
elseif ( $atts['posttype'] == 'slzexploore_cruise' ) {
	$model = new Slzexploore_Core_Cruise();
}
elseif ( $atts['posttype'] == 'post' ) {
	$model = new Slzexploore_Core_Block();
}
else {
	$model = new Slzexploore_Core_Accommodation();
}
$model->init( $atts, $query_args );

$html = '
		<div class="special-offer-layout %3$s">
			<div class="image-wrapper">
				%1$s
				%2$s
				%4$s
			</div>
		</div>
	';

$html_options = array(
	'html_format'      => $html,
	'title_format'     => '<div class="title-wrapper"><a href="%2$s" class="title">%1$s</a><i class="icons flaticon-circle"></i></div>',
	'thumb_href_class' => 'link',
	'is_limit'         => true,
);
if( $model->query->have_posts() ):
?>
<div class="slz-shortcode special-offer <?php echo esc_attr( $atts['extra_class'] ); ?>">
	<?php
		if ( !empty($atts['title']) ) {
			echo '<h3 class="title-style-2">'.esc_html($atts['title']).'</h3>';
		}
	 ?>
	<div class="special-offer-list">
		<?php $model->render_carousel($html_options); ?>
	</div>
</div>
<?php endif;?>