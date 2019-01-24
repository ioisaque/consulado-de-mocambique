<?php
/**
 * Form Model class.
 * 
 * @since 1.0
 */

Slzexploore_Core::load_class( 'Validator' );

class Slzexploore_Core_Model {

	// attribute name => array of errors
	private $errors = array();

	// validators
	private $validators;

	private static $names = array();

	/**
	 * Returns the validation rules for attributes.
	 *
	 * @return array validation rules to be applied when {@link validate()} is called.
	 * @see scenario
	 */
	public function rules() {
		return array();
	}

	/**
	 * Returns the attribute labels.
	 *
	 * Attribute labels are mainly used in error messages of validation.
	 * By default an attribute label is generated using {@link generateAttributeLabel}.
	 * This method allows you to explicitly specify attribute labels.
	 *
	 * Note, in order to inherit labels defined in the parent class, a child class needs to
	 * merge the parent labels with child labels using functions like array_merge().
	 *
	 * @return array attribute labels (name=>label)
	 * @see generate_attribute_label
	 */
	public function attribute_labels() {
		return array();
	}

	/**
	 * Performs the validation.
	 *
	 * @param array $attributes list of attributes that should be validated. Defaults to null,
	 * @param boolean $clear_errors whether to call {@link clear_errors} before performing validation
	 * @return boolean whether the validation is successful without any error.
	 */
	public function validate( $attributes = null, $clear_errors = true ) {
		if( $clear_errors ) {
			$this->clear_errors();
		}

		foreach( $this->get_validators() as $validator ) {
			$validator->validate( $this, $attributes );
		}

		return ! $this->has_errors();
	}

	/**
	 * Returns the validators applicable to the current.
	 *
	 * @param string $attribute the name of the attribute whose validators should be returned.
	 * @return array the validators applicable to the current.
	 */
	public function get_validators( $attribute = null ) {
		if( $this->validators === null )
			$this->validators = $this->create_validators();

		return $this->validators;
	}

	/**
	 * Creates validator objects based on the specification in {@link rules}.
	 *
	 * This method is mainly used internally.
	 * @return List validators built based on {@link rules()}.
	 */
	public function create_validators() {
		$validators = array();

		foreach( $this->rules() as $rule ) {
			if( isset( $rule[0], $rule[1] ) ) {// attributes, validator name
				$validators[] = Slzexploore_Core_Validator::create_validator( $rule[1], $this, $rule[0], array_slice( $rule, 2 ) );
			} else {
				throw new Exception( get_class($this) . ' has an invalid validation rule. The rule must specify attributes to be validated and the validator name.' );
			}
		}

		return $validators;
	}

	/**
	 * Returns the text label for the specified attribute.
	 *
	 * @param string $attribute the attribute name
	 * @return string the attribute label
	 * @see generate_attribute_label
	 * @see attributeLabels
	 */
	public function get_attribute_label( $attribute ) {
		$labels = $this->attribute_labels();

		if( isset( $labels[ $attribute ] ) ) {
			return $labels[ $attribute ];
		} else {
			return $this->generate_attribute_label( $attribute );
		}
	}

	/**
	 * Generates a user friendly attribute label.
	 * This is done by replacing underscores or dashes with blanks and
	 * changing the first letter of each word to upper case.
	 * For example, 'department_name' or 'DepartmentName' becomes 'Department Name'.
	 *
	 * @param string $name the column name
	 * @return string the attribute label
	 */
	public function generate_attribute_label( $name ) {
		return ucwords( trim( strtolower( str_replace( array( '-', '_', '.' ), ' ', preg_replace( '/(?<![A-Z])[A-Z]/', ' \0', $name ) ) ) ) );
	}

	/**
	 * Adds a new error to the specified attribute.
	 *
	 * @param string $attribute attribute name
	 * @param string $error new error message
	 */
	public function add_error( $attribute, $error ) {
		$this->errors[ $attribute ][] = $error;
	}

	/**
	 * Returns the first error of the specified attribute.
	 *
	 * @param string $attribute attribute name.
	 * @return string the error message. Null is returned if no error.
	 */
	public function get_error( $attribute ) {
		return isset( $this->errors[ $attribute ] ) ? reset( $this->errors[ $attribute ] ) : null;
	}

	/**
	 * Returns the errors for all attribute or a single attribute.
	 *
	 * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
	 * @return array errors for all attributes or the specified attribute. Empty array is returned if no error.
	 */
	public function get_errors( $attribute = null) {
		if( $attribute === null ) {
			return $this->errors;
		} else {
			return isset( $this->errors[ $attribute ] ) ? $this->errors[ $attribute ] : array();
		}
	}

	/**
	 * Returns a value indicating whether there is any validation error.
	 *
	 * @param string $attribute attribute name. Use null to check all attributes.
	 * @return boolean whether there is any error.
	 */
	public function has_errors( $attribute = null ) {
		if( $attribute === null ) {
			return ( $this->errors !== array() );
		} else {
			return isset( $this->errors[ $attribute ] );
		}
	}

	/**
	 * Removes errors for all attributes or a single attribute.
	 *
	 * @param string $attribute attribute name. Use null to remove errors for all attribute.
	 */
	public function clear_errors( $attribute = null ) {
		if( $attribute === null ) {
			$this->errors = array();
		} else {
			unset( $this->errors[ $attribute ] );
		}
	}

	public function attribute_names() {
		$class_name = get_class($this);
		if( ! isset( self::$names[ $class_name ] ) ) {
			$class = new ReflectionClass( get_class( $this ) );
			$names = array();
			foreach( $class->getProperties() as $property) {
				$name = $property->getName();
				if( $property->isPublic() && ! $property->isStatic() ) {
					$names[] = $name;
				}
			}
			return self::$names[ $class_name ] = $names;
		} else {
			return self::$names[ $class_name ];
		}
	}

	/**
	 * Returns all attribute values.
	 *
	 * @param array $names list of attributes whose value needs to be returned.
	 *              Defaults to null, meaning all attributes as listed in {@link attributeNames} will be returned.
	 *              If it is an array, only the attributes in the array will be returned.
	 * @return array attribute values (name=>value).
	 */
	public function get_attributes( $names = null ) {
		$values = array();
		foreach( $this->attribute_names() as $name ) {
			$values[ $name ] = $this->$name;
		}

		if( is_array( $names ) ) {
			$values2 = array();
			foreach( $names as $name ) {
				$values2[ $name ] = isset( $values[ $name ] ) ? $values[ $name ] : null;
			}
			return $values2;
		} else {
			return $values;
		}
	}

	/**
	 * Sets the attribute values in a massive way.
	 *
	 * @param array $values attribute values (name=>value) to be set.
	 * @param boolean $safeOnly whether the assignments should only be done to the safe attributes.
	 *                A safe attribute is one that is associated with a validation rule in the current {@link scenario}.
	 * @see getSafeAttributeNames
	 * @see attributeNames
	 */
	public function set_attributes( $values ) {
		if( ! is_array( $values ) ) {
			return;
		}

		$attributes = $this->attribute_names();

		foreach( $values as $name => $value ) {
			if( in_array( $name, $attributes ) ) {
				$this->$name = $value;
			}
		}
	}

	/**
	 * Overwirte.
	 */
	public function __get($name) {
		$getter = 'get_'.$name;
		if( method_exists( $this, $getter ) ) {
			return $this->$getter();
		}

		throw new Exception( strtr( 'Property "{class}.{property}" is not defined.', array( '{class}' => get_class( $this ), '{property}' => $name ) ) );
	}

	/**
	 * Overwirte.
	 */
	public function __set( $name, $value ) {
		$setter = 'set_'.$name;
		if( method_exists( $this, $setter ) ) {
			return $this->$setter( $value );
		}

		if( method_exists( $this, 'get' . $name ) ) {
			throw new Exception( strtr( 'Property "{class}.{property}" is read only.', array( '{class}' => get_class( $this ), '{property}' => $name ) ) );
		} else {
			throw new Exception( strtr( 'Property "{class}.{property}" is not defined.', array( '{class}' => get_class( $this ), '{property}' => $name ) ) );
		}
	}
}