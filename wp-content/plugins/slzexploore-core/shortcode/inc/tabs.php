<?php
$layout = array(
	esc_html__('Horizontal', 'slzexploore-core')           => '01',
	esc_html__('Vertical', 'slzexploore-core')             => '02',
);
$params = array(
		array(
			'type'        => 'dropdown',
			'heading'     => esc_html__( 'Layout', 'slzexploore-core' ),
			'param_name'  => 'layout',
			'value'       => $layout,
			'description' => esc_html__( 'Choose layout for tab.', 'slzexploore-core'  ),
		),
		array(
	   		'type'        => 'colorpicker',
	   		'heading'     => esc_html__( 'Color for Tab Active', 'slzexploore-core' ),
	   		'param_name'  => 'color_active',
	  		'value'       => '',
   		),
   		array(
	   		'type'        => 'colorpicker',
	   		'heading'     => esc_html__( 'Color for Tab Not Active', 'slzexploore-core' ),
	   		'param_name'  => 'color_normal',
	  		'value'       => '',
   		),
		array(
			'type'        => 'textfield',
			'heading'     => esc_html__( 'Extra class name', 'slzexploore-core' ),
			'param_name'  => 'tabs_el_class',
			'description' => esc_html__( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'slzexploore-core' ),
		),
);  

	$tab_id_1 = time() . '-1-' . rand( 0, 100 );
	$tab_id_2 = time() . '-2-' . rand( 0, 100 );	
	vc_map(array(
		'name'                    => esc_html__( 'SLZ Tabs', 'slzexploore-core' ),
		'base'                    => 'slzexploore_core_tabs_sc',
		'class'                   => 'slzexploore_core-sc slzexploore_core-shortcode-tabs',
		'icon'                    => 'icon-slzexploore_core_tabs_sc',  
		'description'             => esc_html__( 'Use shortcode tab of exploore theme instead use default.', 'slzexploore-core' ),
		'is_container'            => true, 
		'show_settings_on_create' => false,  
		'content_element'         => true,
		'controls'                => "full",
		'category'                => SLZEXPLOORE_CORE_SC_CATEGORY,
		'admin_enqueue_js'        =>  array(  SLZEXPLOORE_CORE_ASSET_URI.'/js/slzexploore-core-admin-vc.js' ),
		'admin_enqueue_css'       => array( SLZEXPLOORE_CORE_ASSET_URI.'/css/slzexploore-core-extend-admin.css' ),
		'params'                  => $params, 
		'custom_markup'           => '
			<div class="wpb_tabs_holder wpb_holder vc_container_for_children">
			<ul class="tabs_controls">
			</ul>
			%content%
			</div>'
			,
			'default_content'     => '
			[slzexploore_core_tab_sc title="' . esc_html__( 'Tab 1', 'slzexploore-core' ) . '" tab_id="' . $tab_id_1 . '"][/slzexploore_core_tab_sc]
			[slzexploore_core_tab_sc title="' . esc_html__( 'Tab 2', 'slzexploore-core' ) . '" tab_id="' . $tab_id_2 . '"][/slzexploore_core_tab_sc]
			',
			'js_view'             => 'VcIconTabsView'
			)
	); 
