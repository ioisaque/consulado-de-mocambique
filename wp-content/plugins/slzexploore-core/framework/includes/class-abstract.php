<?php
/**
 * Abstract class.
 * 
 * @since 1.0
 */
class Slzexploore_Core_Abstract {
	/**
	 * Retrieve value of custom field.
	 *
	 * @param string $key The key of the custom field.
	 * @param mixed $def The default value to return if no result is found.
	 * @param bool $all Whether to return value found.
	 * Defaults to `false` which means that return first value of result.
	 * @return mixed.
	 */
	public function model( $key, $def = NULL, $all = false ) {
		static $data = false;

		if( false == $data ) {
			global $post;
			$data = get_post_custom( $post->ID );
		}

		if( $data && isset( $data[ $key ] ) ) {
			if( $all ) {
				return $data[ $key ];
			}
			else if( isset( $data[ $key ][0] ) ) {
				//return htmlspecialchars( $data[ $key ][0] );
				return $data[ $key ][0];
			}
		}

		return $def;
	}

	/**
	 * Retrieve attachment meta field for attachment ID.
	 *
	 * @param mixed $id The attachment ID.
	 * @return array.
	 */
	public function attachment( $id ) {
		if( empty( $id ) ) {
			return array();
		}

		if( is_array( $id ) ) {
			return array_map( array( $this, 'attachment' ), $id );
		}

		$attachment = wp_get_attachment_metadata( $id );
		if( ! empty( $attachment ) ) {
			$attachment['id'] = $id;
		}

		return $attachment;
	}

	/**
	 * Retrieve attachment meta path of post with size.
	 *
	 * @param array $post Post data.
	 * @param string $size Slug of size.
	 * @return string.
	 */
	public function attachment_url( $post, $size ) {
		$upload_dir = wp_upload_dir();
		$baseurl = trim( $upload_dir['baseurl'], '/' );
		if( ! empty( $post['sizes'][ $size ] ) ) {
			$subdir = dirname( $post['file'] );
			return $baseurl . '/' . trim( $subdir, '/' ) . '/' . $post['sizes'][ $size ]['file'];
		} else if( !empty( $post['file'] ) ) {
			return $baseurl . '/'  . $post['file'];
		}
	}

	/**
	 * Created or updated custom field to a specified post which could be of any post type.
	 *
	 * @param args[..].
	 */
	public function save() {
		global $post;
		foreach( func_get_args() as $field ) {
			$new = Slzexploore_Core::get_request_param( $field, NULL );

			if( NULL == $new || "" == $new ) {
				delete_post_meta( $post->ID, $field );
			} else {
				if( is_array( $new ) ) {
					$old = get_post_meta( $post->ID, $field );
					if( empty( $old ) ) {
						$old = array();
					}

					foreach( array_unique( array_diff( $old, $new ) ) as $o ) {
						delete_post_meta( $post->ID, $field, $o );
					}

					foreach( array_unique( array_diff( $new, $old ) ) as $n ) {
						add_post_meta( $post->ID, $field, $n, false );
					}
				} else {
					$old = get_post_meta( $post->ID, $field, true );
					if( $new != $old ) {
						update_post_meta( $post->ID, $field, $new );
					}
				}
			}
		}
	}

	/**
	 * Renders a view file.
	 *
	 * @param string $_view_file_ view file path
	 * @param array $_data_ optional data to be extracted as local view variables
	 * @param boolean $_return_ whether to return the rendering result instead of displaying it
	 * @return mixed the rendering result if required. Null otherwise.
	 */
	public function render( $_view_file_, $_data_ = null, $_return_ = false ) {
		if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { 
			return;
		}

		if( is_array ( $_data_ ) ) {
			extract( $_data_, EXTR_PREFIX_SAME, 'data' );
		} else {
			$data = $_data_;
		}

		if( $_return_ ) {
			ob_start();
			ob_implicit_flush( false );
			require( $this->get_view_file( $_view_file_ ) );
			return ob_get_clean();
		} else {
			require( $this->get_view_file( $_view_file_ ) );
		}
	}

	/**
	 * Looks for the view file according to the given view name.
	 *
	 * @param string $view_name view name
	 * @return string the view file path, false if the view file does not exist
	 */
	public function get_view_file( $view_name ) {
		if( is_file( $view_name ) && file_exists( $view_name ) ) {
			return $view_name;
		}

		$class = new ReflectionClass( get_called_class() );
		return realpath( dirname( $class->getFileName() ) . "/views/{$view_name}.php" );
	}

	/**
	 * Generates a label tag.
	 *
	 * @param string $label label text. Note, you should HTML-encode the text if needed.
	 * @param string $for the ID of the HTML element that this label is associated with.
	 * @param array $html_options additional HTML attributes.
	 * @return string the generated label tag
	 */
	public function label( $label, $for, $html_options = array( )) {
		if( $for === false ) {
			unset( $html_options['for'] );
		} else {
			$html_options['for'] = $for;
		}

		return $this->tag( 'label', $html_options, $label );
	}

	/**
	 * Generates a text field input.
	 *
	 * @param string $name the input name
	 * @param string $value the input value
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated input field
	 */
	
	public function text_field( $name, $value='', $html_options = array() ) {
		return $this->input_field( 'text', $name, $value, $html_options );
	}

	public function editor( $value = '', $id, $options = array() ) {
		return wp_editor( $value, $id , $options );
	}
	
	public function button_field( $name, $value='', $html_options = array() ) {
		return $this->input_field( 'button', $name, $value, $html_options );
	}

	public function upload_video($name,$value='',$label, $desc = '')
	{
		$output = '';
		$output .= '<div class="desc">';
			$output .= '<label>';
			$output .= esc_html($label);
			$output .= '</label>';
			if($desc) {
				$output  .= '<p>' . esc_html($desc) . '</p>';
			}
		$output .= '</div>';
		$output .= '<div class="upload">';
			$output .= '<div class="upload-video">';
				$output .= '<div><input name="'.esc_attr($name).'" class="textbox-url-video" type="text" value="'.$value.'" /></div>';
				$output .= '<div class="button-upload"><input class="slz_upload_button" type="button" value="Browse" /></div>';
			$output .= '</div>';
		$output .= '</div>';
		print_r( '<div class="box-upload">%s</div>', $output );
	}

	/**
	 * Generates an input HTML tag.
	 * This method generates an input HTML tag based on the given input name and value.
	 *
	 * @param string $type the input type (e.g. 'text', 'radio')
	 * @param string $name the input name
	 * @param string $value the input value
	 * @param array $htmlOptions additional HTML attributes for the HTML tag (see {@link tag}).
	 * @return string the generated input tag
	 */
	public function input_field($type, $name, $value, $html_options) {
		$html_options['type']=$type;
		$html_options['value']=$value;
		$html_options['name']=$name;

		return $this->tag('input', $html_options);
	}

	/**
	 * Generates a hidden input.
	 *
	 * @param string $name the input name
	 * @param string $value the input value
	 * @param array $html_options additional HTML attributes (see {@link tag}).
	 * @return string the generated input field
	 */
	public function hidden_field( $name, $value='', $html_options = array() ) {
		return $this->input_field( 'hidden', $name, $value, $html_options );
	}

	/**
	 * Generates a password field input.
	 *
	 * @param string $name the input name
	 * @param string $value the input value
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated input field
	 */
	public function password_field( $name, $value='', $html_options = array() ) {
		return $this->input_field( 'password', $name, $value, $html_options );
	}

	/**
	 * Generates a file input.
	 *
	 * @param string $name the input name
	 * @param string $value the input value
	 * @param array $html_options additional HTML attributes (see {@link tag}).
	 * @return string the generated input field
	 */
	public function file_field( $name, $value='', $html_options = array() ) {
		return $this->input_field( 'file', $name, $value, $html_options );
	}

	/**
	 * Generates a text area input.
	 *
	 * @param string $name the input name
	 * @param string $value the input value
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated text area
	 */
	public function text_area( $name, $value = '', $html_options = array() ) {
		$html_options['name'] = $name;
		return $this->tag( 'textarea', $html_options, isset( $html_options['encode'] ) && ! $html_options['encode'] ? $value : $this->encode( $value ) );
	}

	/**
	 * Generates a radio button.
	 *
	 * @param string $name the input name
	 * @param boolean $checked whether the radio button is checked
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated radio button
	 */
	public function radio_button( $name, $checked = false, $html_options = array() ) {
		if( $checked ) {
			$html_options['checked'] = 'checked';
		} else {
			unset( $html_options['checked'] );
		}
		$value = isset( $html_options['value'] ) ? $html_options['value'] : 1;

		if( array_key_exists( 'uncheckValue', $html_options ) ) {
			$uncheck = $html_options['uncheckValue'];
			unset( $html_options['uncheckValue'] );
		} else {
			$uncheck = null;
		}

		if( $uncheck !== null ) {
			// add a hidden field so that if the radio button is not selected, it still submits a value
			if( isset( $html_options['id'] ) && $html_options['id'] !== false ) {
				$uncheck_options = array( 'id' => $html_options['id'] );
			} else {
				$uncheck_options = array( 'id' => false );
			}
			$hidden = $this->hidden_field( $name, $uncheck, $uncheck_options );
		} else {
			$hidden='';
		}

		// add a hidden field so that if the radio button is not selected, it still submits a value
		return $hidden . $this->input_field( 'radio', $name, $value, $html_options );
	}
	/**
	 * Generates a radio button list.
	 *
	 * @param string $name name of the radio button list.
	 * @param string $select selection of the radio buttons.
	 * @param array $data value-label pairs used to generate the radio button list.
	 * @param array $html_options addtional HTML options.
	 *        The following special options are recognized:
	 *              template: string, specifies how each radio button is rendered.
	 *              separator: string, specifies the string that separates the generated radio buttons.
	 *              labelOptions: array, specifies the additional HTML attributes to be rendered for every label tag in the list.
	 *              container: string, specifies the radio buttons enclosing tag. Defaults to 'span'.
	 *              baseID: string, specifies the base ID prefix to be used for radio buttons in the list.
	 * @return string the generated radio button list
	 */
	public function radio_button_list( $name, $select, $data, $html_options = array() ) {
	
		$template  = isset( $html_options['template'] ) ? $html_options['template'] : '{input} {label}';
		$separator = isset( $html_options['separator'] ) ? $html_options['separator'] : "<br/>\n";
		$container = isset( $html_options['container'] ) ? $html_options['container'] : 'span';
		unset( $html_options['template'], $html_options['separator'], $html_options['container'] );
	
		$label_options = isset( $html_options['labelOptions'] ) ? $html_options['labelOptions'] : array();
		$label_selected_class = isset( $label_options['selected_class'] ) ? $label_options['selected_class'] : '';
		$label_class = isset( $label_options['class'] ) ? $label_options['class'] : '';
		unset( $label_options['selected_class'] );
		unset( $html_options['labelOptions'] );
	
		$items = array();
		$baseID = isset( $html_options['baseID'] ) ? $html_options['baseID'] : $this->get_id_by_name($name);
		unset( $html_options['baseID'] );
	
		$id = 0;
		foreach( $data as $value => $label ) {
			$checked = !strcmp( $value, $select );
			if( $checked ) {
				$label_options['class'] = $label_class . $label_selected_class;
			} else {
				$label_options['class'] = $label_class;
			}
			$html_options['value'] = $value;
			$html_options['id'] = $baseID . '_' . $id++;
			$option = $this->radio_button( $name, $checked, $html_options );
			$label = $this->label( $label, $html_options['id'], $label_options );
			$items[] = strtr( $template, array('{input}' => $option, '{label}' => $label ) );
		}
	
		if( empty( $container ) ) {
			return implode( $separator, $items );
		} else {
			return $this->tag( $container, array( 'id' => $baseID ), implode( $separator, $items ) );
		}
	}

	public function radio_image_label( $data, $image_uri, $html_options = array() ) {
		if( $data ) {
			foreach( $data as $key => $value ) {
				$src = $image_uri . $value;
				$data[$key] = $this->image($src, '', $html_options );
			}
		}
		return $data;
	}
	/**
	 * Generates a check box.
	 *
	 * @param string $name the input name
	 * @param boolean $checked whether the check box is checked
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated check box
	 */
	public function check_box( $name, $checked = false, $html_options = array() ) {
		if( $checked ) {
			$html_options['checked'] = 'checked';
		} else {
			unset( $html_options['checked'] );
		}
		$value = isset( $html_options['value'] ) ? $html_options['value'] : 1;

		if( array_key_exists( 'uncheckValue', $html_options ) ) {
			$uncheck = $html_options['uncheckValue'];
			unset( $html_options['uncheckValue'] );
		} else {
			$uncheck = null;
		}

		if( $uncheck !== null ) {
			// add a hidden field so that if the check box is not checked, it still submits a value
			if( isset( $html_options['id'] ) && $html_options['id'] !== false ) {
				$uncheck_options = array('id' => $html_options['id'] );
			} else {
				$uncheck_options = array( 'id' => false );
			}

			$hidden = $this->hidden_field( $name, $uncheck, $uncheck_options );
		} else {
			$hidden='';
		}

		// add a hidden field so that if the check box is not checked, it still submits a value
		return $hidden . $this->input_field( 'checkbox', $name, $value, $html_options );
	}

	/**
	 * Generates a check box list.
	 *
	 * @param string $name name of the check box list.
	 * @param string $select selection of the check box.
	 * @param array $data value-label pairs used to generate the check box list.
	 * @param array $html_options addtional HTML options.
	 *        The following special options are recognized:
	 *              template: string, specifies how each check box is rendered.
	 *              separator: string, specifies the string that separates the generated check boxs.
	 *              labelOptions: array, specifies the additional HTML attributes to be rendered for every label tag in the list.
	 *              container: string, specifies the check boxs enclosing tag. Defaults to 'span'.
	 *              baseID: string, specifies the base ID prefix to be used for check boxs in the list.
	 * @return string the generated check box list
	 */
	public function check_box_list( $name, $select, $data, $html_options = array() ) {
	
		$template  = isset( $html_options['template'] ) ? $html_options['template'] : '{input} {label}';
		$separator = isset( $html_options['separator'] ) ? $html_options['separator'] : "<br/>\n";
		$container = isset( $html_options['container'] ) ? $html_options['container'] : 'span';
		unset( $html_options['template'], $html_options['separator'], $html_options['container'] );
	
		$label_options = isset( $html_options['labelOptions'] ) ? $html_options['labelOptions'] : array();
		$label_selected_class = isset( $label_options['selected_class'] ) ? $label_options['selected_class'] : '';
		$label_class = isset( $label_options['class'] ) ? $label_options['class'] : '';
		unset( $label_options['selected_class'] );
		unset( $html_options['labelOptions'] );
	
		$items = array();
		$baseID = isset( $html_options['baseID'] ) ? $html_options['baseID'] : $this->get_id_by_name($name);
		unset( $html_options['baseID'] );
	
		$id = 0;
		foreach( $data as $value => $label ) {
			if( is_array( $select ) ){
				$checked = in_array( $value, $select );
			}
			else{
				$checked = !strcmp( $value, $select );
			}
			if( $checked ) {
				$label_options['class'] = $label_class . $label_selected_class;
			} else {
				$label_options['class'] = $label_class;
			}
			$html_options['value'] = $value;
			$html_options['id'] = $baseID . '_' . $id++;
			$option = $this->check_box( $name, $checked, $html_options );
			$label = $this->label( $label, $html_options['id'], $label_options );
			$items[] = strtr( $template, array('{input}' => $option, '{label}' => $label ) );
		}
	
		if( empty( $container ) ) {
			return implode( $separator, $items );
		} else {
			return $this->tag( $container, array( 'id' => $baseID ), implode( $separator, $items ) );
		}
	}

	/**
	 * Generates a drop down list.
	 *
	 * @param string $name the input name
	 * @param string $select the selected value
	 * @param array $data data for generating the list options (value=>display).
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated drop down list
	 */
	public function drop_down_list( $name, $select, $data, $html_options = array() ) {
		$html_options['name'] = $name;
		$options = "\n" . $this->list_options( $select, $data, $html_options );
		return $this->tag( 'select', $html_options, $options );
	}

	/**
	 * Generates a list box.
	 *
	 * @param string $name the input name
	 * @param mixed $select the selected value(s). This can be either a string for single selection or an array for multiple selections.
	 * @param array $data data for generating the list options (value=>display)
	 * @param array $html_options additional HTML attributes. Besides normal HTML attributes, a few special
	 * @return string the generated list box
	 */
	public function list_box( $name, $select, $data, $html_options = array() ) {
		if( ! isset ( $html_options['size'] ) ) {
			$html_options['size'] = 4;
		}
		if( isset( $html_options['multiple'] ) ) {
			if( substr( $name, -2 ) !== '[]' ) {
				$name.='[]';
			}
		}

		return $this->drop_down_list( $name, $select, $data, $html_options);
	}

	/**
	 * Generates an image tag.
	 * @param string $src the image URL
	 * @param string $alt the alternative text display
	 * @param array $html_options additional HTML attributes (see {@link tag}).
	 * @return string the generated image tag
	 */
	public function image( $src, $alt='', $html_options=array() ) {
		$html_options['src'] = $src;
		$html_options['alt'] = $alt;
		return $this->tag( 'img', $html_options );
	}

	/**
	 * Generates an HTML element.
	 *
	 * @param string $tag the tag name
	 * @param array $html_options the element attributes. The values will be HTML-encoded using {@link encode()}.
	 * @param mixed $content the content to be enclosed between open and close element tags. It will not be HTML-encoded.
	 * @param boolean $close_tag whether to generate the close tag.
	 * @return string the generated HTML element tag
	 */
	public function tag($tag, $html_options = array(), $content = false, $close_tag = true) {
		$html = '<' . $tag . $this->render_attributes( $html_options );

		if( false === $content ) {
			return $close_tag ? $html .' />' : $html . '>';
		} else {
			return $close_tag ? $html . '>' . $content . '</' . $tag . '>' : $html . '>' . $content;
		}
	}

	/**
	 * Generates an open HTML element.
	 *
	 * @param string $tag the tag name
	 * @param array $html_options the element attributes. The values will be HTML-encoded using {@link encode()}.
	 * @return string the generated HTML element tag
	 */
	public function open_tag( $tag, $html_options = array() ) {
		return '<' . $tag . $this->render_attributes( $html_options ) . '>';
	}

	/**
	 * Generates a close HTML element.
	 *
	 * @param string $tag the tag name
	 * @return string the generated HTML element tag
	 */
	public function close_tag( $tag ) {
		return '</'.$tag.'>';
	}

	/**
	 * Encloses the given string within a CDATA tag.
	 *
	 * @param string $text the string to be enclosed
	 * @return string the CDATA tag with the enclosed content.
	 */
	public function cdata($text) {
		return '<![CDATA[' . $text . ']]>';
	}

	/**
	 * Renders the HTML tag attributes.
	 *
	 * @param array $html_options attributes to be rendered
	 * @return string the rendering result
	 */
	public function render_attributes( $html_options ) {
		static $special_attributes = array(
			'checked'	=> 1,
			'declare'	=> 1,
			'defer'		=> 1,
			'disabled'	=> 1,
			'ismap'		=> 1,
			'multiple'	=> 1,
			'nohref'	=> 1,
			'noresize'	=> 1,
			'readonly'	=> 1,
			'selected'	=> 1,
		);

		if( empty( $html_options ) ) {
			return '';
		}

		$html = '';
		if( isset( $html_options['encode'] ) ) {
			$raw = ! $html_options['encode'];
			unset( $html_options['encode'] );
		} else {
			$raw = false;
		}

		if( $raw ) {
			foreach( $html_options as $name => $value ) {
				if ( isset( $special_attributes[ $name ] ) ) {
					if( $value ) {
						$html .= ' ' . $name . '="' . $name . '"';
					}
				} else if( $value !== null ) {
					$html .= ' ' . $name . '="' . $value . '"';
				}
			}
		} else {
			foreach( $html_options as $name => $value ) {
				if( isset( $special_attributes[ $name ] ) ) {
					if( $value ) {
						$html .= ' ' . $name . '="' . $name . '"';
					}
				} else if( $value !== null ) {
					$html .= ' ' . $name . '="' . $this->encode( $value ) . '"';
				}
			}
		}

		return $html;
	}

	/**
	 * Generates the list options.
	 *
	 * @param mixed $selection the selected value(s). This can be either a string for single selection or an array for multiple selections.
	 * @param array $list_data the option data (see {@link list_data})
	 * @param array $html_options additional HTML attributes. The following two special attributes are recognized:
	 * @return string the generated list options
	 */
	public function list_options( $selection, $list_data, &$html_options ) {
		$raw = isset( $html_options['encode'] ) && ! $html_options['encode'];
		$content='';

		if( isset( $html_options['prompt'] ) ) {
			$content .= '<option value="">' . strtr( $html_options['prompt'], array( '<'=>'&lt;', '>'=>'&gt;' ) ) . "</option>\n";
			unset( $html_options['prompt'] );
		}

		if( isset( $html_options['empty'] ) ) {
			if( ! is_array( $html_options['empty'] ) ) {
				$html_options['empty'] = array( '' => $html_options['empty'] );
			}

			foreach( $html_options['empty'] as $value => $label ) {
				$content .= '<option value="' . $this->encode( $value ) . '">' . strtr( $label, array( '<'=>'&lt;', '>'=>'&gt;' ) ) . "</option>\n";
			}

			unset( $html_options['empty'] );
		}

		if( isset( $html_options['options'] ) ) {
			$options = $html_options['options'];
			unset( $html_options['options'] );
		} else {
			$options = array();
		}

		$key = isset( $html_options['key'] ) ? $html_options['key'] : 'primaryKey';
		if( is_array( $selection ) ) {
			foreach( $selection as $i => $item ) {
				if( is_object( $item ) ) {
					$selection[ $i ] = $item->$key;
				}
			}
		} else if( is_object( $selection ) ) {
			$selection = $selection->$key;
		}

		foreach( $list_data as $key => $value ) {
			if( is_array( $value ) ) {
				$content .= '<optgroup label="' . ( $raw ? $key : $this->encode( $key ) ) . "\">\n";
				$dummy = array( 'options' => $options );

				if( isset( $html_options['encode'] ) ) {
					$dummy['encode'] = $html_options['encode'];
				}

				$content .= $this->list_options( $selection, $value, $dummy );
				$content .= '</optgroup>'."\n";
			} else {
				$attributes = array( 'value' => (string) $key, 'encode' => ! $raw );
				if( ! is_array( $selection ) && ! strcmp( $key, $selection ) || is_array( $selection ) && in_array( $key, $selection ) ) {
					$attributes['selected'] = 'selected';
				}

				if( isset( $options[ $key ] ) ) {
					$attributes = array_merge( $attributes, $options[ $key ] );
				}

				$content .= $this->tag( 'option', $attributes, $raw ? (string) $value : $this->encode( (string) $value) ) . "\n";
			}
		}

		unset($html_options['key']);
		return $content;
	}

	/**
	 * Generates a valid HTML ID based on name.
	 * @param string $name name from which to generate HTML ID
	 * @return string the ID generated based on name.
	 */
	public static function get_id_by_name($name)
	{
		return str_replace(array('[]', '][', '[', ']', ' '), array('', '_', '_', '', '_'), $name);
	}

	/**
	 * Encodes special characters into HTML entities.
	 *
	 * @param string $text data to be encoded
	 * @return string the encoded data
	 */
	public function encode( $text ) {
		return htmlspecialchars( $text, ENT_QUOTES, 'UTF-8' );
	}

	/**
	 * Decodes special HTML entities back to the corresponding characters.
	 *
	 * @param string $text data to be decoded
	 * @return string the decoded data
	 */
	public function decode( $text ) {
		return htmlspecialchars_decode( $text, ENT_QUOTES );
	}

	/**
	 * Encodes special characters in an array of strings into HTML entities.
	 *
	 * @param array $data data to be encoded
	 * @return array the encoded data
	 */
	public function encode_array( $data ) {
		$d = array();
		foreach( $data as $key => $value ) {
			if( is_string( $key ) ) {
				$key = htmlspecialchars( $key, ENT_QUOTES, 'UTF-8' );
			}

			if( is_string( $value ) ) {
				$value = htmlspecialchars( $value, ENT_QUOTES, 'UTF-8' );
			} elseif( is_array( $value ) ) {
				$value = $this->encode_array( $value );
			}

			$d[ $key ] = $value;
		}

		return $d;
	}
	public function get_field( $data_arr, $key, $def = '' ) {
		if( $data_arr ) {
			if( isset ( $data_arr[$key] ) ) {
				return $data_arr[$key];
			}
			return null;
		}
		if( is_array($def) ) {
			if( isset( $def[$key] ) ) {
				return $def[$key];
			}
			return null;
		}
		return $def;
	}
	/**
	 * Show tooltip
	 * 
	 * @param string $content
	 * @param string $position (left, top, right, bottom)
	 */
	public function tooltip_html( $content, $position = 'right' ) {
		echo '<a href="#" class="slzexploore_core-tooltip" tabindex="-1" data-position="' . esc_attr( $position ) . '" data-content-as-html="true" title="' . esc_attr($content) . '">?</a>';
	}
	/**
	 * Generates a gallery block.
	 *
	 * @param string $name
	 * @param string $images_id
	 */
	
	public function gallery( $name, $images_id ) {
		echo '<div id="slzexploore_core_gallery_container">
				<ul class="gallery_images">';
		if( !empty ($images_id) ){
			$ids = explode(',', $images_id);
			foreach( $ids as $id){
				if( !empty($id) ){
					$image_src = wp_get_attachment_image_src($id);
					if($image_src){
						printf('<li class="image" data-attachment_id="%s">
									<img src="%s" alt="" />
									<ul class="actions">
										<li>
											<a href="#" class="delete" title="%s">&times;</a>
										</li>
									</ul>
								</li>',
								$id,
								$image_src[0],
								esc_html__('Delete image', 'slzexploore-core')
							);
					}
				}
			}
		}
		echo '	</ul>';
		printf('<input type="hidden" id="slzexploore_core_gallery_image_ids" name="%s" value="%s" />',
				 esc_attr($name),
				 esc_attr($images_id)
			);
		echo '</div>';
		echo '<p class="add_gallery_image hide-if-no-js">';
		printf('<a href="#" data-delete="%2$s" data-title="%3$s" data-btn-text="%4$s" class="btn-open-gallery" title="%1$s">%1$s</a>',
				esc_html__('Add gallery images', 'slzexploore-core'),
				esc_html__('Delete image', 'slzexploore-core'),
				esc_html__('Gallery Images', 'slzexploore-core'),
				esc_html__('Add to gallery', 'slzexploore-core')
			);
		echo '</p>';
	}
	/**
	 * Generates a attachmen block.
	 *
	 * @param string $name
	 * @param string $images_id
	 */
	
	public function upload_attachment( $name, $attachments_ids ) {
		echo '<div id="slzexploore_core_attachment_container">
				<ul class="attachment_images">';
		if( !empty ($attachments_ids) ){
			$ids = explode(',', $attachments_ids);
			foreach( $ids as $id){
				if( !empty($id) ){
					$image_src = wp_get_attachment_image_src($id, 'thumbnail' , true);
					$url = wp_get_attachment_url($id);
					$type = get_post_mime_type($id);
					$file_name = get_the_title($id);
					if($image_src){
						printf('<li class="image" data-attachment_id="%1$s">
									<div class="media-left">
										<img src="%2$s" alt="" />
									</div>
									<div class="media-right">
										<a href="%7$s" class="title" title="%3$s">%3$s</a>
										<div class="attachment_type">%4$s</div>
										<a href="#" class="delete" title="%5$s">
											<i class="fa fa-times"></i>%6$s
										</a>
									</div>
								</li>',
								esc_attr( $id ),
								esc_url( $image_src[0] ),
								esc_attr( $file_name ),
								esc_attr( $type ),
								esc_html__('Remove  attachment', 'slzexploore-core'),
								esc_html__('Remove', 'slzexploore-core'),
								esc_url( $url )
							);
					}
				}
			}
		}
		echo '	</ul>';
		printf('<input type="hidden" id="slzexploore_core_attachment_image_ids" name="%s" value="%s" />',
				 esc_attr($name),
				 esc_attr($attachments_ids)
			);
		echo '</div>';
		echo '<p class="add_media hide-if-no-js">';
		printf('<a href="#" data-delete="%2$s" data-title="%3$s" data-btn-text="%4$s" class="btn-open-attachment button" title="%1$s">%1$s</a>',
				esc_html__('Add Media', 'slzexploore-core'),
				esc_html__('Remove', 'slzexploore-core'),
				esc_html__('Attachments', 'slzexploore-core'),
				esc_html__('Add media', 'slzexploore-core')
			);
		echo '</p>';
	}
	public function single_image( $name, $value, $html_options = array() ) {
		$img_src = SLZEXPLOORE_CORE_ASSET_URI . '/images/placeholder.png';
		$img_show = 'hide';
		if( ! empty( $value )) {
			$attachment_image = wp_get_attachment_image_src($value, 'medium');
			$img_src = $attachment_image[0];
			$img_show = '';
		}
		$output = '<div class="screenshot '.$img_show.'" ><img src="'.esc_url( $img_src ).'"/></div>';
		$img_id_name = $name . '_id';
		if( !empty( $prefix ) ) {
			$img_id_name = $prefix . '[' . $img_id_name . ']';
		}
		$output .= '
				<input type="button" data-rel="' . $html_options['data-rel'] .'" class="button slz-btn-upload" value="'. esc_html__( 'Upload Image', 'slzexploore-core' ) .'" />
				<input type="button" data-rel="' . $html_options['data-rel'] .'" class="button slz-btn-remove '.$img_show.'" value="'. esc_html__( 'Remove', 'slzexploore-core' ) .'" />
				';
		unset($html_options['data-rel']);
		$output .= $this->hidden_field($name, $value, $html_options);
		return $output;
	}

	public function date_picker( $name, $value='', $html_options = array() ) {
		$classes = array();
		$classes[] = 'datepicker';
		if( isset( $html_options['class'] ) ) {
			$classes[] = $html_options['class'];
		}
		$classes = array_map( 'esc_attr', $classes );
		$html_options['class'] = join(' ' , array_unique( $classes ) );
		return $this->input_field( 'text', $name, $value, $html_options );
	}
	
	public function time_picker( $name, $value='', $html_options = array() ) {
		$classes = array();
		$classes[] = 'timepicker';
		if( isset( $html_options['class'] ) ) {
			$classes[] = $html_options['class'];
		}
		$classes = array_map( 'esc_attr', $classes );
		$html_options['class'] = join(' ' , array_unique( $classes ) );
		return $this->input_field( 'text', $name, $value, $html_options );
	}
}