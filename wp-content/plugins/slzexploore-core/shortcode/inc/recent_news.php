<?php
$category = Slzexploore_Core_Com::get_category2slug_array();
$tag = Slzexploore_Core_Com::get_tax_options2slug( 'post_tag', array('empty' => esc_html__( '-All tags-', 'slzexploore-core' ) ) );
$author = Slzexploore_Core_Com::get_user_login2id(array(), array('empty' => esc_html__( '-All authors-', 'slzexploore-core' ) ) );
$orderby = Slzexploore_Core::get_params('sort-blog');
$column = Slzexploore_Core::get_params('blog-column');
$show_meta = array(
	esc_html__('No', 'slzexploore-core') => '',
	esc_html__('Yes', 'slzexploore-core')=> 'yes',
);
$autoplay = array(
	esc_html__('No', 'slzexploore-core') => '',
	esc_html__('Yes', 'slzexploore-core')=> 'yes',
);
$params = array(
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Title One', 'slzexploore-core' ),
		'param_name'  => 'title_one',
		'value'       => '',
		'description' => esc_html__( 'Enter title one, display small text.', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Title Two', 'slzexploore-core' ),
		'param_name'  => 'title_two',
		'value'       => '',
		'description' => esc_html__( 'Enter title two, display large text. ', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Limit Posts', 'slzexploore-core' ),
		'param_name'  => 'limit_post',
		'value'       => 6,
		'description' => esc_html__( 'Enter limit of posts per page. If it blank the limit posts will be the number from Wordpress settings -> Reading. If you want show all, enter "-1".', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Excerpt length', 'slzexploore-core' ),
		'param_name'  => 'excerpt_length',
		'value'       => '',
		'description' => esc_html__( 'Enter limit of text will be truncated. If it is empty, the default is not cut. It will trim word', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Offset Posts', 'slzexploore-core' ),
		'param_name'  => 'offset_post',
		'value'       => '',
		'description' => esc_html__( 'Enter offset to pass over posts. If you want to start on record 6, using offset 5.', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Sort By', 'slzexploore-core' ),
		'param_name'  => 'sort_by',
		'value'       => $orderby,
		'description' => esc_html__( 'Choose criteria to display.', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Tags', 'slzexploore-core' ),
		'param_name'  => 'show_tag',
		'value'       => '',
		'description' => esc_html__( 'Display tag if input greater than 0. The number entered will be displayed tag number. Enter 0 or leave it blank if you do not want to display the tag.', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Meta', 'slzexploore-core' ),
		'param_name'  => 'show_meta',
		'value'       => $show_meta,
		'std'         => 'yes',
		'description' => esc_html__( 'Show info author, date time.', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'  => 'extra_class',
		'description' => esc_html__( 'Enter extra class.', 'slzexploore-core' )
	),
	array(
		'type'        => 'dropdown',
		'heading'     => esc_html__( 'Auto Play', 'slzexploore-core' ),
		'param_name'  => 'auto_play',
		'value'       => $autoplay,
		'description' => esc_html__( 'Carousel automatic play when select yes.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Carousel', 'slzexploore-core' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Auto Play Speed', 'slzexploore-core' ),
		'param_name'  => 'auto_speed',
		'value'       => '6000',
		'description' => esc_html__( 'Set the running time of a turn. Unit is milliseconds', 'slzexploore-core' ),
		'group'       => esc_html__( 'Carousel', 'slzexploore-core' ),
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Speed', 'slzexploore-core' ),
		'param_name'  => 'speed',
		'value'       => '700',
		'description' => esc_html__( 'Set the speed of a turn. Unit is milliseconds', 'slzexploore-core' ),
		'group'       => esc_html__( 'Carousel', 'slzexploore-core' ),
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Category', 'slzexploore-core' ),
		'param_name' => 'category_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Category', 'slzexploore-core' ),
				'param_name'  => 'category_slug',
				'value'       => $category,
				'description' => esc_html__( 'Choose special category to filter', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by category.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter', 'slzexploore-core' )
	),
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Tag', 'slzexploore-core' ),
		'param_name' => 'tag_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Tag', 'slzexploore-core' ),
				'param_name'  => 'tag_slug',
				'value'       => $tag,
				'description' => esc_html__( 'Choose special tag to filter', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by tag.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter', 'slzexploore-core' )
	),
	
	array(
		'type'       => 'param_group',
		'heading'    => esc_html__( 'Author', 'slzexploore-core' ),
		'param_name' => 'author_list',
		'params'     => array(
			array(
				'type'        => 'dropdown',
				'admin_label' => true,
				'heading'     => esc_html__( 'Add Author', 'slzexploore-core' ),
				'param_name'  => 'author',
				'value'       => $author,
				'description' => esc_html__( 'Choose special author to filter', 'slzexploore-core'  )
			),
		),
		'value'       => '',
		'description' => esc_html__( 'Default no filter by author.', 'slzexploore-core' ),
		'group'       => esc_html__( 'Filter', 'slzexploore-core' )
	),
	array(
		'type'        => 'textfield',
		'heading'     => esc_html__( 'Button Text', 'slzexploore-core' ),
		'param_name'  => 'button_text',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Set empty If you do not show button.', 'slzexploore-core' )
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Color', 'slzexploore-core' ),
		'param_name'  => 'button_color',
		'value'       => '',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter text color for button.', 'slzexploore-core' )
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Hover Color', 'slzexploore-core' ),
		'param_name'  => 'button_hv_color',
		'value'       => '',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter color when hover in button.', 'slzexploore-core' )
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Text Color', 'slzexploore-core' ),
		'param_name'  => 'button_text_color',
		'value'       => '',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter color of text ( button ).', 'slzexploore-core' )
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Text Hover Color', 'slzexploore-core' ),
		'param_name'  => 'button_text_hv_color',
		'value'       => '',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter color of text ( button ) when hover.', 'slzexploore-core' )
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Border Color', 'slzexploore-core' ),
		'param_name'  => 'button_border_color',
		'value'       => '',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter color of border ( button ).', 'slzexploore-core' )
	),
	array(
		'type'        => 'colorpicker',
		'heading'     => esc_html__( 'Button Border Hover Color', 'slzexploore-core' ),
		'param_name'  => 'button_border_hv_color',
		'value'       => '',
		'group'       => esc_html__( 'Read More Button', 'slzexploore-core' ),
		'description' => esc_html__( 'Enter color of border ( button ) when hover.', 'slzexploore-core' )
	),
);
vc_map(array(
	'name'            => esc_html__( 'SLZ Recent News', 'slzexploore-core' ),
	'base'            => 'slzexploore_core_recent_news_sc',
	'class'           => 'slzexploore_core-sc',
	'icon'            => 'icon-slzexploore_core_blog_sc',
	'category'        => SLZEXPLOORE_CORE_SC_CATEGORY,
	'description'     => esc_html__( 'A list post will be displayed as carousel.', 'slzexploore-core' ),
	'params'          => $params
	)
);