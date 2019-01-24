<?php
class Slzexploore_Core_Util {
	/**
	 * Get default image.
	 *
	 * Return html;
	 *
	 * @return string.
	 */
	public static function get_link( $url ) {
		$result = array();
		$url_title = '';
		$target = '';
		$link = '';
		if( !empty( $url ) ){
			$vc_link = vc_build_link( $url );
			$link = (!empty($vc_link['url']))? $vc_link['url'] :'';
			$url_title = (!empty($vc_link['title'])) ? 'title='.$vc_link['title']:'';
			$target = (!empty($vc_link['target']))? 'target='.$vc_link['target']:'';
			$result['link'] = $link;
			$result['url_title'] = $url_title;
			$result['target'] = $target;
		}
		return $result;
	}
	public static function get_default_image($thumb = '-thumbnail', $size_w = 100, $size_h = 100 ) {
		$wrap = '<img %IMG_CLASS% %SRC% %SIZE% %IMG_TITLE% %ALT% />';
		$default_img = SLZEXPLOORE_CORE_ASSET_URI . '/images/noimage' . $thumb . '.jpg';
		$output = str_replace(
				array(
					'%SRC%',
					'%IMG_CLASS%',
					'%SIZE%',
					'%ALT%',
					'%IMG_TITLE%'
				),
				array(
					'src="' . $default_img . '"',
					'class="attachment-100x100 wp-post-image"',
					'width="' . $size_w . '" height="' . $size_h . '"',
					'',
					''
				),
				$wrap
		);
		return $output;
	}
	public static function get_thumb_image( $opts ) {
		if ( has_post_thumbnail( $opts['post_id'] ) ) {
			$output = get_the_post_thumbnail($opts['post_id'], $opts['size'] );
		}
		else{
			$output = self::get_default_image();
		}
		return $output;
	}
	/**
	 * Debug method
	 *
	 * @param string $search_name
	 */
	public static function dump( $val_arr, $search_name = '' ) {
	
		echo '<pre class="warning-msg">';
		echo '<span>' . __METHOD__ . ' => ' . esc_html( $search_name ) . '</span></br>';
	
		if(is_array($val_arr) || is_object($val_arr)){
			print_r($val_arr);
		}else{
			print($val_arr);
		}
		echo '</pre>';
	
	}
	public static function get_public_thumnail( $post_id, $thumbnail_size = 'post-thumbnail', $options = array() ) {
		if( ! has_post_thumbnail( $post_id )) {
			$src = SLZEXPLOORE_CORE_ASSET_URI . '/images/no-image.png';
			return '<img src="' . $src . '" class="img-responsive" />';
		}
		return get_the_post_thumbnail( $post_id, $thumbnail_size, $options );
	}
	
	public static function set_default_data( $args, $arvals = array(), $default = array() ) {
		$result = array();
		foreach( $args as $item ) {
			$val = '';
			if( isset( $arvals[$item] ) ) {
				$val = $arvals[$item];
			}
			if( isset( $default[$item] ) && empty( $val ) ) {
				$val = $default[$item];
			}
			$result[$item] = $val;
		}
		return $result;
	}
	
	public static function is_valid_data( $obj, $field = '', $compare = '' ) {
		if( is_array( $obj ) ) {
			if( isset( $obj[$field] ) && strtolower( $obj[$field] ) === strtolower( $compare ) ) {
				return true;
			}
		}
		else if ( ! Slzexploore_Core::is_empty( $obj )) {
			return true;
		}
		return false;
	}
	
	public static function set_empty_options( &$data, $options = array() ) {
		if( empty( $options ) ) {
			$options = Slzexploore_Core::get_config( 'mapping', 'special_options' );
		}
		foreach( $options as $key ) {
			if( ! isset( $data[$key] ) ) {
				$data[$key] = '';
			}
		}
	}
	public static function validate_int( $val ) {
		$valid = filter_var($val, FILTER_VALIDATE_INT);
		if( $valid && $valid > 0 ) {
			return $valid;
		}
		return false;
	}
	public static function get_numeric_value( $val, $def='' ) {
		$result = '';
		if( ! Slzexploore_Core::is_empty($val) ) {
			if( preg_match('/\d+\.?\d*/', $val, $matches) ) {
				$result = $matches[0];
			}
		}
		if( Slzexploore_Core::is_empty( $result ) ) {
			$result = $def;
		}
		return $result;
	}
	/**
	 * List vc param group.
	 * 
	 * @param $obj
	 * @param $field_list (Ex: list_category)
	 * @param $field_item (Ex: category_slug)
	 * @return array($field_list, $field_item)
	 */
	public static function get_list_vc_param_group( $obj, $field_list, $field_item ) {
		$list_params = '';
		$params = array();
		if( isset( $obj[$field_list] ) && ! empty( $obj[$field_list] ) ) {
			$list_params = (array) vc_param_group_parse_atts($obj[$field_list] );
			if( $list_params ) {
				foreach( $list_params as $param ) {
					if( isset( $param[$field_item] ) ) {
						$params[] = $param[$field_item];
					}
				}
			}
		}
		return array( $list_params, $params );
	}
	public static function get_list_vc_param_group_arr( $obj, $field_list ) {
		$list_params = '';
		if( isset( $obj[$field_list] ) && ! empty( $obj[$field_list] ) ) {
			$list_params = (array) vc_param_group_parse_atts($obj[$field_list] );
		}
		return $list_params;
	}
	public static function get_single_postmeta( $post_id, $key, $def = '' ) {
		if( $post_id && $data = get_post_meta( $post_id, $key, true ) ) {
			return $data;
		}
		return $def;
	}
	public static function get_thumb_size( $sizes, $options= array(), $theme_prefix = SLZEXPLOORE_CORE_THEME_PREFIX ) {
		
		$thumb_size = array(
			'large' => 'full',
		);
		if( !isset($options['column'])) {
			$options['column'] = '';
		}
		$small_column = 'small-' . $options['column'];
		if( $sizes ) {
			foreach( $sizes as $key => $value ) {
				$prefix = 'thumb-';
				$ext = '.gif';
				if( $key == 'large' || $key == 'small' || $key == $small_column ) {
					$prefix = $theme_prefix . '-thumb-';
					$ext = '';
				}
				$thumb_size[$key] = $prefix . $value . $ext;
			}
			if( ! isset( $thumb_size['no-image'] ) ) {
				$thumb_size['no-image'] = 'thumb-' . $sizes['large'] . '.gif';
			}
		} else {
			$thumb_size['no-image'] = 'thumb-no-image.gif';
		}
		// no small size => small size = large size
		if( ! isset( $thumb_size['small'] ) ) {
			$thumb_size['small'] = $thumb_size['large'];
		}
		if( isset( $thumb_size[$small_column] ) ) {
			$thumb_size['small'] = $thumb_size[$small_column];
			if( isset( $thumb_size['no-image-' . $small_column] ) ) {
				$thumb_size['no-image-small'] = $thumb_size['no-image-' . $small_column];
			}
		}
		return $thumb_size;
	}
	public static function get_no_image( $atts = array(), $post = null, $thumb_type = 'large', $options = array() ){
		$alt = '';
		if( $post ) {
			$alt = trim( strip_tags( $post->post_title ) );;
		}
		if( isset($atts['no-image-' . $thumb_type])) {
			$no_image = $atts['no-image-' . $thumb_type];
		} else {
			$no_image = $atts['no-image'];
		}
		$filename = SLZEXPLOORE_CORE_NO_IMG_DIR . $no_image;
		if( ! file_exists( $filename ) ) {
			$no_image = SLZEXPLOORE_CORE_NO_IMG_REC;
		} else {
			$no_image = SLZEXPLOORE_CORE_NO_IMG_URI . $no_image;
		}
		$thumb_class = Slzexploore_Core::get_value( $options, 'thumb_class', 'img-responsive' );
		$thumb_img = sprintf('<img src="%1$s" alt="%2$s" class="%3$s" />', $no_image, $alt, $thumb_class );
		return $thumb_img;
	}
	public static function get_icon_for_extension( $ext ) {
		switch ( $ext ) {
			/* PDF */
			case 'pdf' :
				return '<i class="fa fa-file-pdf-o"></i>';
			
			/* Images */
			case 'jpg' :
			case 'png' :
			case 'gif' :
			case 'bmp' :
			case 'jpeg' :
			case 'tiff' :
			case 'tif' :
				return '<i class="fa fa-file-image-o"></i>';
			
			/* Text */
			case 'txt' :
			case 'log' :
			case 'tex' :
				return '<i class="fa fa-file-text-o"></i>';
			
			/* Documents */
			case 'doc' :
			case 'odt' :
			case 'msg' :
			case 'docx' :
			case 'rtf' :
			case 'wps' :
			case 'wpd' :
			case 'pages' :
				return '<i class="fa fa-file-word-o"></i>';
			
			/* Spread Sheets */
			case 'csv' :
			case 'xlsx' :
			case 'xls' :
			case 'xml' :
			case 'xlr' :
				return '<i class="fa fa-file-excel-o"></i>';
			
			/* Zip */
			case 'zip' :
			case 'rar' :
			case '7z' :
			case 'zipx' :
			case 'tar.gz' :
			case 'gz' :
			case 'pkg' :
				return '<i class="fa fa-file-zip-o"></i>';
			
			/* Audio */
			case 'mp3' :
			case 'wav' :
			case 'm4a' :
			case 'aif' :
			case 'wma' :
			case 'ra' :
			case 'mpa' :
			case 'iff' :
			case 'm3u' :
				return '<i class="fa fa-file-audio-o"></i>';
			
			/* Video */
			case 'avi' :
			case 'flv' :
			case 'm4v' :
			case 'mov' :
			case 'mp4' :
			case 'mpg' :
			case 'rm' :
			case 'swf' :
			case 'wmv' :
				return '<i class="fa fa-file-video-o"></i>';
			
			/* Others */
			default :
				return '<i class="fa fa-file-o"></i>';
		}
	}
	public static function get_single_attachments($attachment){
		$out = '';
		$format = '<li class="att-item %4$s"><a target="_blank" href="%3$s" title="'.esc_html__('Click here to download', 'slzexploore-core').'" download>%1$s%2$s</a></li>';
			if( !empty($attachment ) ) {
				$attach = explode(',', $attachment);
				if( $attach ) {
					foreach( $attach as $attachment_id ){
						$file_path = wp_get_attachment_url( $attachment_id );
						if( $file_path ){
							$file_type = wp_check_filetype( $file_path );
							$out .= sprintf($format,
									Slzexploore_Core_Util::get_icon_for_extension( $file_type['ext'] ),
									get_the_title( $attachment_id ),
									$file_path,
									$file_type['ext']
							);
						}
					}
				}
			}
			if( !empty($out)) {
				$out = sprintf('<ul class="list-unstyled list-inline attachments-list">%s</ul>', $out);
			}
		return $out;
	}
	// merge two array
	public static function merge_array( $array1 = array(), $array2 = array() )
	{
		$output = array();
		if( !empty( $array1 ) ){
			foreach( $array1 as $key => $value) {
				if ( isset( $array2[$key] ) && !empty( $array2[$key] ) ){
					$output[$key] = $array2[$key];
				}
				else{
					$output[$key] = $value;
				}
			}
		}
		return $output;
	}
	public static function join_date_string( $start, $end ){
		if( !empty($start) && !empty($end)){
			$start_date = date('Y-m-d',strtotime($start));
			$end_date = date('Y-m-d',strtotime($end));
			$ret = str_replace('-', '', $start_date);
			while( $start_date < $end_date){
				try{
					$start_date = date('Y-m-d',strtotime($start_date . ' +1 days'));
					$ret .= ',' .str_replace('-', '', $start_date);
				}catch(Exception $e){
					break;
				}
			}
			return $ret;
		}
		return '';
	}
}