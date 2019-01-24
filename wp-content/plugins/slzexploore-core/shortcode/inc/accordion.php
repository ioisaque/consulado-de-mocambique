<?php
$params = array(
	array(
		'type'          => 'textfield',
		'heading'       => esc_html__( 'Extra Class', 'slzexploore-core' ),
		'param_name'    => 'extra_class',
		'description'   => esc_html__( 'Add extra class to block.', 'slzexploore-core' )
	)
);
$custom_markup = ''.
	'<div class="vc_tta-container" data-vc-action="collapseAll">
		<div class="vc_general vc_tta vc_tta-accordion vc_tta-color-backend-accordion-white vc_tta-style-flat vc_tta-shape-rounded vc_tta-o-shape-group vc_tta-controls-align-left vc_tta-gap-2">
			<div class="vc_tta-panels vc_clearfix {{container-class}}">
				{{ content }}
				<div class="vc_tta-panel vc_tta-section-append">
					<div class="vc_tta-panel-heading">
						<h4 class="vc_tta-panel-title vc_tta-controls-icon-position-left">
							<a href="javascript:;" aria-expanded="false" class="vc_tta-backend-add-control">
								<span class="vc_tta-title-text">' .esc_html__( 'Add Section', 'slzexploore-core' ) . '</span>
								<i class="vc_tta-controls-icon vc_tta-controls-icon-plus"></i>
							</a>
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>';
vc_map(array(
	'name'                     => esc_html__( 'SLZ Accordion', 'slzexploore-core' ),
	'base'                     => 'slzexploore_core_accordion_sc',
	'class'                    => 'slzexploore_core-sc',
	'show_settings_on_create'  => false,
	'is_container'             => true,
	'category'                 => SLZEXPLOORE_CORE_SC_CATEGORY,
	'icon'                     => 'icon-slzexploore_core_accordion_sc',
	'description'              => esc_html__( 'Collapsible content panels', 'slzexploore-core' ),
	'params'                   => $params,
	'custom_markup'            => $custom_markup,
	'default_content'          => '[vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Section', 'slzexploore-core' ), 1 ) . '"][/vc_tta_section][vc_tta_section title="' . sprintf( '%s %d', esc_html__( 'Section', 'slzexploore-core' ), 2 ) . '"][/vc_tta_section]',
	'js_view'                  => 'VcBackendTtaAccordionView'
	)
);