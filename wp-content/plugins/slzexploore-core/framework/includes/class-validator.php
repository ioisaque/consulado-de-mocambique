<?php
/**
 * Validator class.
 * 
 * @since 1.0
 */

Slzexploore_Core::load_class( 'DateTime_Parser' );

class Slzexploore_Core_Validator {
	/**
	 * @var array list of attributes to be validated.
	 */
	public $attributes;

	/**
	 * @var string the user-defined error message. Different validators may define various
	 * placeholders in the message that are to be replaced with actual values. All validators
	 * recognize "{attribute}" placeholder, which will be replaced with the label of the attribute.
	 */
	public $message;

	/**
	 * @var boolean whether this validation rule should be skipped when there is already a validation
	 * error for the current attribute. Defaults to false.
	 * @since 1.0
	 */
	public $skip_on_error = false;

	/**
	 * @var array list of scenarios that the validator should be applied.
	 * Each array value refers to a scenario name with the same name as its array key.
	 */
	public $on;

	/**
	 * @var array list of scenarios that the validator should not be applied to.
	 * Each array value refers to a scenario name with the same name as its array key.
	 * @since 1.0
	 */
	public $except;

	/**
	 * @var array list of built-in validators (name=>class)
	 */
	public static $built_in_validators = array(
		'required' => 'Slzexploore_Core_Validator_Required',
		'filter'   => 'Slzexploore_Core_Validator_Filter',
		'match'    => 'Slzexploore_Core_Validator_Regular_Expression',
		'email'    => 'Slzexploore_Core_Validator_Email',
		'url'      => 'Slzexploore_Core_Validator_Url',
		'compare'  => 'Slzexploore_Core_Validator_Compare',
		'length'   => 'Slzexploore_Core_Validator_String',
		'in'       => 'Slzexploore_Core_Validator_Range',
		'numerical'=> 'Slzexploore_Core_Validator_Number',
		'default'  => 'Slzexploore_Core_Validator_Default_Value',
		'boolean'  => 'Slzexploore_Core_Validator_Boolean',
		'date'     => 'Slzexploore_Core_Validator_Date',
		'type'     => 'Slzexploore_Core_Validator_Type'
	);

	/**
	 * Creates a validator object.
	 *
	 * @param string $name the name or class of the validator
	 * @param Model $object the data object being validated that may contain the inline validation method
	 * @param mixed $attributes list of attributes to be validated. This can be either an array of
	 * the attribute names or a string of comma-separated attribute names.
	 * @param array $params initial values to be applied to the validator properties
	 * @return Validator the validator
	 */
	public static function create_validator( $name, $object, $attributes, $params = array() ) {
		if( is_string( $attributes ) ) {
			$attributes = preg_split( '/[\s,]+/', $attributes, -1, PREG_SPLIT_NO_EMPTY );
		}

		if( isset( $params['on'] ) ) {
			if( is_array( $params['on'] ) ) {
				$on = $params['on'];
			} else {
				$on = preg_split( '/[\s,]+/', $params['on'], -1, PREG_SPLIT_NO_EMPTY );
			}
		} else {
			$on = array();
		}

		if( isset( $params['except'] ) ) {
			if( is_array( $params['except'] ) ) {
				$except = $params['except'];
			} else {
				$except = preg_split( '/[\s,]+/', $params['except'], -1, PREG_SPLIT_NO_EMPTY );
			}
		} else {
			$except = array();
		}

		if( method_exists( $object, $name ) ) {
			$validator = new Slzexploore_Core_Validator_Inline;
			$validator->attributes = $attributes;			
			$validator->method = $name;
			
			$validator->params = $params;
			if( isset( $params['skip_on_error'] ) ) {
				$validator->skip_on_error = $params['skip_on_error'];
			}
		} else {
			$params['attributes'] = $attributes;
			
			if( isset( self::$built_in_validators[ $name ] ) )
				$className = self::$built_in_validators[ $name ];
			else
				$className = $name;
				
			$validator = new $className;
			
			foreach( $params as $name => $value ) {
				$validator->$name = $value;
			}
		}

		$validator->on = empty( $on ) ? array() : array_combine( $on, $on );
		$validator->except = empty( $except ) ? array() : array_combine( $except, $except );

		return $validator;
	}

	/**
	 * Validates the specified object.
	 *
	 * @param Model $object the data object being validated
	 * @param array $attributes the list of attributes to be validated. Defaults to null,
	 * meaning every attribute listed in {@link attributes} will be validated.
	 */
	public function validate( $object, $attributes = null ) {
		if( is_array( $attributes ) ) {
			$attributes = array_intersect( $this->attributes, $attributes );
		} else {
			$attributes = $this->attributes;
		}
		foreach( $attributes as $attribute ) {
			if( ! $this->skip_on_error || ! $object->has_errors( $attribute ) ) {
				$this->validate_attribute( $object, $attribute );
			}
		}
	}

	/**
	 * Adds an error about the specified attribute to the active record.
	 * This is a helper method that performs message selection and internationalization.
	 *
	 * @param Model $object the data object being validated
	 * @param string $attribute the attribute being validated
	 * @param string $message the error message
	 * @param array $params values for the placeholders in the error message
	 */
	protected function add_error( $object, $attribute, $message, $params = array() ) {
		$params['{attribute}'] = $object->get_attribute_label( $attribute );
		$object->add_error( $attribute, strtr( $message, $params ) );
	}

	/**
	 * Checks if the given value is empty.
	 * A value is considered empty if it is null, an empty array, or the trimmed result is an empty string.
	 * Note that this method is different from PHP empty(). It will return false when the value is 0.
	 *
	 * @param mixed $value the value to be checked
	 * @param boolean $trim whether to perform trimming before checking if the string is empty. Defaults to false.
	 * @return boolean whether the value is empty
	 */
	protected function is_empty( $value, $trim = false ) {
		return $value === null || $value === array() || $value === '' || $trim && is_scalar( $value ) && trim( $value ) === '';
	}
}

/**
 * Date Validator class.
 *
 * @since 1.0
 */ 
class Slzexploore_Core_Validator_Date extends Slzexploore_Core_Validator {
	/**
	 * @var mixed the format pattern that the date value should follow.
	 * This can be either a string or an array representing multiple formats.
	 * Defaults to 'MM/dd/yyyy'. Please see {@link DateTime} for details
	 * about how to specify a date format.
	 */
	public $format = 'MM/dd/yyyy';

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * @var string the name of the attribute to receive the parsing result.
	 * When this property is not null and the validation is successful, the named attribute will
	 * receive the parsing result.
	 */
	public $timestamp_attribute;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value=$object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		$formats = is_string( $this->format ) ? array( $this->format ) : $this->format;
		$valid = false;
		foreach( $formats as $format ) {
			$timestamp = Slzexploore_Core_DateTime_Parser::parse( $value, $format, array( 'month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0 ) );
			if( $timestamp !== false ) {
				$valid = true;
				if( $this->timestamp_attribute !== null ) {
					$object->{ $this->timestamp_attribute } = $timestamp;
				}
				break;
			}
		}

		if( ! $valid ) {
			$message = $this->message !== null ? $this->message : 'The format of {attribute} is invalid.';
			$this->add_error( $object, $attribute, $message );
		}
	}
}

/**
 * Type Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Type extends Slzexploore_Core_Validator {
	/**
	 * @var string the data type that the attribute should be. Defaults to 'string'.
	 * Valid values include 'string', 'integer', 'float', 'array', 'date', 'time' and 'datetime'.
	 */
	public $type = 'string';

	/**
	 * @var string the format pattern that the date value should follow. Defaults to 'MM/dd/yyyy'.
	 * Please see {@link CDateTimeParser} for details about how to specify a date format.
	 * This property is effective only when {@link type} is 'date'.
	 */
	public $date_format = 'MM/dd/yyyy';

	/**
	 * @var string the format pattern that the time value should follow. Defaults to 'hh:mm'.
	 * Please see {@link CDateTimeParser} for details about how to specify a time format.
	 * This property is effective only when {@link type} is 'time'.
	 */
	public $time_format = 'hh:mm';

	/**
	 * @var string the format pattern that the datetime value should follow. Defaults to 'MM/dd/yyyy hh:mm'.
	 * Please see {@link DateTimeParser} for details about how to specify a datetime format.
	 * This property is effective only when {@link type} is 'datetime'.
	 */
	public $datetime_format = 'MM/dd/yyyy hh:mm';

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * @var boolean whether the actual PHP type of attribute value should be checked.
	 * Defaults to false, meaning that correctly formatted strings are accepted for
	 * integer and float validators.
	 *
	 * @since 1.1.13
	 */
	public $strict = false;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param CModel $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( ! $this->validate_value( $value ) ) {
			$message = $this->message !== null ? $this->message : '{attribute} must be {type}.';
			$this->add_error( $object, $attribute, $message, array( '{type}' => $this->type ) );
		}
	}

	/**
	 * Validates a static value.
	 * Note that this method does not respect {@link allowEmpty} property.
	 * This method is provided so that you can call it directly without going through the model validation rule mechanism.
	 *
	 * @param mixed $value the value to be validated
	 * @return boolean whether the value is valid
	 * @since 1.1.13
	 */
	public function validate_value( $value ) {
		$type = $this->type === 'float' ? 'double' : $this->type;
		if($type === gettype( $value ) ) {
			return true;
		} else if( $this->strict || is_array( $value ) || is_object( $value ) || is_resource( $value ) || is_bool( $value ) ) {
			return false;
		}

		if( $type === 'integer' ) {
			return (boolean) preg_match( '/^[-+]?[0-9]+$/', trim( $value ) );
		} elseif( $type === 'double' ) {
			return (boolean) preg_match( '/^[-+]?([0-9]*\.)?[0-9]+([eE][-+]?[0-9]+)?$/', trim( $value ) );
		} elseif( $type === 'date' ) {
			return Slzexploore_Core_DateTime_Parser::parse( $value, $this->date_format, array( 'month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0 ) ) !== false;
		} elseif( $type === 'time' ) {
			return Slzexploore_Core_DateTime_Parser::parse( $value, $this->time_format ) !== false;
		} elseif( $type === 'datetime' ) {
			return Slzexploore_Core_DateTime_Parser::parse( $value, $this->datetime_format, array( 'month' => 1, 'day' => 1, 'hour' => 0, 'minute' => 0, 'second' => 0 ) ) !== false;
		}

		return false;
	}
}

/**
 * Boolean Validator class.
 *
 * @since 1.0
 */ 
class Slzexploore_Core_Validator_Inline extends Slzexploore_Core_Validator {
	/**
	 * @var string the name of the validation method defined in the active record class
	 */
	public $method;

	/**
	 * @var array additional parameters that are passed to the validation method
	 */
	public $params;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$method = $this->method;
		$object->$method( $attribute, $this->params );
	}
}

/**
 * Boolean Validator class.
 *
 * @since 1.0
 */ 
class Slzexploore_Core_Validator_Boolean extends Slzexploore_Core_Validator {
	/**
	 * @var mixed the value representing true status. Defaults to '1'.
	 */
	public $true_value = '1';

	/**
	 * @var mixed the value representing false status. Defaults to '0'.
	 */
	public $false_value = '0';

	/**
	 * @var boolean whether the comparison to {@link true_value} and {@link false_value} is strict.
	 * When this is true, the attribute value and type must both match those of {@link true_value} or {@link false_value}.
	 * Defaults to false, meaning only the value needs to be matched.
	 */
	public $strict = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;

		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if(!$this->strict && $value != $this->true_value && $value != $this->false_value
			|| $this->strict && $value !== $this->true_value && $value !== $this->false_value)
		{
			$message = $this->message !== null ? $this->message : '{attribute} must be either {true} or {false}.';

			$this->add_error( $object, $attribute, $message, array(
				'{true}' => $this->true_value,
				'{false}' => $this->false_value,
			));
		}
	}
}

/**
 * Compare Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Compare extends Slzexploore_Core_Validator {
	/**
	 * @var string the name of the attribute to be compared with
	 */
	public $compare_attribute;

	/**
	 * @var string the constant value to be compared with
	 */
	public $compare_value;

	/**
	 * @var boolean whether the comparison is strict (both value and type must be the same.)
	 * Defaults to false.
	 */
	public $strict = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to false.
	 * If this is true, it means the attribute is considered valid when it is empty.
	 */
	public $allow_empty = false;

	/**
	 * @var string the operator for comparison. Defaults to '='.
	 * The followings are valid operators:
	 */
	public $operator = '=';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( $this->compare_value !== null ) {
			$compare_to = $compare_value = $this->compare_value;
		} else {
			$compare_attribute = $this->compare_attribute === null ? $attribute.'_repeat' : $this->compare_attribute;
			$compare_value = $object->$compare_attribute;
			$compare_to = $object->get_attribute_label( $compare_attribute );
		}

		switch( $this->operator ) {
			case '=':
			case '==':
				if( ( $this->strict && $value !== $compare_value ) || ( ! $this->strict && $value != $compare_value ) ) {
					$message = ( $this->message !== null ) ? $this->message : '{attribute} must be repeated exactly.';
				}
				break;
			case '!=':
				if( ( $this->strict && $value === $compare_value ) || ( ! $this->strict && $value == $compare_value ) ) {
					$message = ( $this->message !== null ) ? $this->message : '{attribute} must not be equal to "{compare_value}".';
				}
				break;
			case '>':
				if( $value <= $compare_value ) {
					$message = ( $this->message !== null ) ? $this->message : '{attribute} must be greater than "{compare_value}".';
				}
				break;
			case '>=':
				if( $value < $compare_value ) {
					$message = $this->message !== null ? $this->message : '{attribute} must be greater than or equal to "{compare_value}".';
				}
				break;
			case '<':
				if( $value >= $compare_value ) {
					$message = ( $this->message !== null ) ? $this->message : '{attribute} must be less than "{compare_value}".';
				}
				break;
			case '<=':
				if( $value > $compare_value ) {
					$message = ( $this->message !== null ) ? $this->message : '{attribute} must be less than or equal to "{compare_value}".';
				}
				break;
			default:
				throw new Exception( 'Invalid operator "' . $this->operator . '".' );
		}
		if( ! empty( $message ) ) {
			$this->add_error( $object, $attribute, $message, array( '{compare_attribute}' => $compare_to, '{compare_value}' => $compare_value ) );
		}
	}
}

/**
 * Email Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Email extends Slzexploore_Core_Validator {
	/**
	 * @var string the regular expression used to validate the attribute value.
	 * @see http://www.regular-expressions.info/email.html
	 */
	public $pattern='/^[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?$/';

	/**
	 * @var string the regular expression used to validate email addresses with the name part.
	 * This property is used only when {@link allow_name} is true.
	 * @see allow_name
	 */
	public $full_pattern='/^[^@]*<[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&\'*+\\/=?^_`{|}~-]+)*@(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?>$/';

	/**
	 * @var boolean whether to allow name in the email address. Defaults to false.
	 * @see fullPattern
	 */
	public $allow_name = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( ! $this->validate_value( $value ) ) {
			$message = ( $this->message !== null ) ? $this->message : '{attribute} is not a valid email address.';
			$this->add_error( $object, $attribute, $message );
		}
	}

	/**
	 * Validates a static value to see if it is a valid email.
	 *
	 * @param mixed $value the value to be validated
	 * @return boolean whether the value is a valid email
	 * @since 1.1.1
	 */
	public function validate_value( $value ) {
		return ( is_string( $value ) && strlen( $value ) <= 254 && ( preg_match( $this->pattern , $value ) || $this->allow_name && preg_match( $this->full_pattern, $value ) ) );
	}
}

/**
 * Default Value Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Default_Value extends Slzexploore_Core_Validator {
	/**
	 * @var mixed the default value to be set to the specified attributes.
	 */
	public $value;

	/**
	 * @var boolean whether to set the default value only when the attribute value is null or empty string.
	 * Defaults to true. If false, the attribute will always be assigned with the default value,
	 * even if it is already explicitly assigned a value.
	 */
	public $set_on_empty = true;

	/**
	 * Validates the attribute of the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		if( ! $this->set_on_empty ) {
			$object->$attribute = $this->value;
		} else {
			$value = $object->$attribute;
			if( $value === null || $value === '' ) {
				$object->$attribute = $this->value;
			}
		}
	}
}

/**
 * Filter Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Filter extends Slzexploore_Core_Validator {
	/**
	 * @var callback the filter method
	 */
	public $filter;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		if( $this->filter === null || ! is_callable( $this->filter ) ) {
			throw new Exception('The "filter" property must be specified with a valid callback.');
		}

		$object->$attribute = call_user_func_array( $this->filter, array( $object->$attribute ) );
	}
}

/**
 * Number Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Number extends Slzexploore_Core_Validator {
	/**
	 * @var boolean whether the attribute value can only be an integer. Defaults to false.
	 */
	public $integer_only = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * @var integer|float upper limit of the number. Defaults to null, meaning no upper limit.
	 */
	public $max;

	/**
	 * @var integer|float lower limit of the number. Defaults to null, meaning no lower limit.
	 */
	public $min;

	/**
	 * @var string user-defined error message used when the value is too big.
	 */
	public $too_big;

	/**
	 * @var string user-defined error message used when the value is too small.
	 */
	public $too_small;

	/**
	 * @var string the regular expression for matching integers.
	 * @since 1.1.7
	 */
	public $integer_pattern = '/^\s*[+-]?\d+\s*$/';

	/**
	 * @var string the regular expression for matching numbers.
	 * @since 1.1.7
	 */
	public $number_pattern = '/^\s*[-+]?[0-9]*\.?[0-9]+([eE][-+]?[0-9]+)?\s*$/';

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if($this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( $this->integer_only ) {
			if( ! preg_match( $this->integer_pattern, "$value" ) ) {
				$message = $this->message !== null ? $this->message : '{attribute} must be an integer.';
				$this->add_error( $object, $attribute, $message );
			}
		} else {
			if( ! preg_match( $this->number_pattern, "$value" ) ) {
				$message = $this->message !== null ? $this->message : '{attribute} must be a number.';
				$this->add_error( $object, $attribute, $message );
			}
		}

		if( $this->min !== null && $value < $this->min ) {
			$message = $this->too_small !== null ? $this->too_small : '{attribute} is too small (minimum is {min}).';
			$this->add_error( $object, $attribute, $message, array( '{min}' => $this->min ) );
		}

		if( $this->max !== null && $value > $this->max ) {
			$message = $this->too_big !== null ? $this->too_big : '{attribute} is too big (maximum is {max}).';
			$this->add_error( $object, $attribute, $message, array( '{max}' => $this->max ) );
		}
	}
}

/**
 * Range Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Range extends Slzexploore_Core_Validator {
	/**
	 * @var array list of valid values that the attribute value should be among
	 */
	public $range;

	/**
	 * @var boolean whether the comparison is strict (both type and value must be the same)
	 */
	public $strict = false;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * @var boolean whether to invert the validation logic. Defaults to false. If set to true,
	 * the attribute value should NOT be among the list of values defined via {@link range}.
	 * @since 1.1.5
	 **/
	public $not = false;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( ! is_array( $this->range ) ) {
			throw new Exception('The "range" property must be specified with a list of values.');
		}

		if( ! $this->not && ! in_array( $value, $this->range, $this->strict ) ) {
			$message = $this->message !== null ? $this->message : '{attribute} is not in the list.';
			$this->add_error( $object, $attribute, $message );
		} else if( $this->not && in_array( $value, $this->range, $this->strict ) ) {
			$message = $this->message !== null ? $this->message : '{attribute} is in the list.';
			$this->add_error( $object, $attribute, $message );
		}
	}
}

/**
 * Regular Expression Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Regular_Expression extends Slzexploore_Core_Validator {
	/**
	 * @var string the regular expression to be matched with
	 */
	public $pattern;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * @var boolean whether to invert the validation logic. Defaults to false. If set to true,
	 * the regular expression defined via {@link pattern} should NOT match the attribute value.
	 * @since 1.1.5
	 **/
	public $not = false;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( $this->pattern === null ) {
			throw new Exception('The "pattern" property must be specified with a valid regular expression.');
		}

		if( ( ! $this->not && ! preg_match( $this->pattern, $value ) ) || ( $this->not && preg_match( $this->pattern, $value ) ) ) {
			$message = $this->message !== null ? $this->message : '{attribute} is invalid.';
			$this->add_error( $object, $attribute, $message );
		}
	}
}

/**
 * RegularExpression Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Required extends Slzexploore_Core_Validator {
	/**
	 * @var mixed the desired value that the attribute must have.
	 * If this is null, the validator will validate that the specified attribute does not have null or empty value.
	 * If this is set as a value that is not null, the validator will validate that
	 * the attribute has a value that is the same as this property value.
	 * Defaults to null.
	 */
	public $required_value;

	/**
	 * @var boolean whether the comparison to {@link required_value} is strict.
	 * When this is true, the attribute value and type must both match those of {@link required_value}.
	 * Defaults to false, meaning only the value needs to be matched.
	 * This property is only used when {@link required_value} is not null.
	 */
	public $strict = false;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->required_value !== null ) {
			if( ! $this->strict && $value != $this->required_value || $this->strict && $value !== $this->required_value ) {
				$message = $this->message !== null ? $this->message : "{attribute} must be {$this->required_value}.";
				$this->add_error( $object, $attribute, $message );
			}
		} else if($this->is_empty( $value, true ) ) {
			$message = $this->message !== null ? $this->message : '{attribute} cannot be blank.';
			$this->add_error( $object, $attribute, $message );
		}
	}
}

/**
 * String Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_String extends Slzexploore_Core_Validator {
	/**
	 * @var integer maximum length. Defaults to null, meaning no maximum limit.
	 */
	public $max;

	/**
	 * @var integer minimum length. Defaults to null, meaning no minimum limit.
	 */
	public $min;

	/**
	 * @var integer exact length. Defaults to null, meaning no exact length limit.
	 */
	public $is;

	/**
	 * @var string user-defined error message used when the value is too short.
	 */
	public $too_short;

	/**
	 * @var string user-defined error message used when the value is too long.
	 */
	public $too_long;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * @var string the encoding of the string value to be validated (e.g. 'UTF-8').
	 * This property is used only when mbstring PHP extension is enabled.
	 * The value of this property will be used as the 2nd parameter of the
	 * mb_strlen() function. If this property is not set, the application charset
	 * will be used.
	 * If this property is set false, then strlen() will be used even if mbstring is enabled.
	 * @since 1.1.1
	 */
	public $encoding;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 *
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( function_exists( 'mb_strlen' ) && $this->encoding !== false ) {
			$length = mb_strlen( $value, $this->encoding ? $this->encoding : get_bloginfo('charset'));
		} else {
			$length = strlen( $value );
		}

		if( $this->min !== null && $length < $this->min ) {
			$message = $this->too_short !== null ? $this->too_short : '{attribute} is too short (minimum is {min} characters).';
			$this->add_error( $object, $attribute, $message, array( '{min}' => $this->min ) );
		} 

		if( $this->max !== null && $length > $this->max ) {
			$message = $this->too_long !== null ? $this->too_long : '{attribute} is too long (maximum is {max} characters).';
			$this->add_error( $object, $attribute, $message, array( '{max}' => $this->max ) );
		}

		if( $this->is !== null && $length !== $this->is ) {
			$message = $this->message !== null ? $this->message : '{attribute} is of the wrong length (should be {length} characters).';
			$this->add_error( $object, $attribute, $message, array( '{length}' => $this->is ) );
		}
	}
}

/**
 * Url Validator class.
 *
 * @since 1.0
 */
class Slzexploore_Core_Validator_Url extends Slzexploore_Core_Validator {
	/**
	 * @var string the regular expression used to validate the attribute value.
	 * Since version 1.1.7 the pattern may contain a {schemes} token that will be replaced
	 * by a regular expression which represents the {@see validSchemes}.
	 */
	public $pattern = '/^{schemes}:\/\/(([A-Z0-9][A-Z0-9_-]*)(\.[A-Z0-9][A-Z0-9_-]*)+)/i';

	/**
	 * @var array list of URI schemes which should be considered valid. By default, http and https
	 * are considered to be valid schemes.
	 * @since 1.1.7
	 **/
	public $valid_schemes = array( 'http', 'https' );

	/**
	 * @var string the default URI scheme. If the input doesn't contain the scheme part, the default
	 * scheme will be prepended to it (thus changing the input). Defaults to null, meaning a URL must
	 * contain the scheme part.
	 * @since 1.0
	 **/
	public $default_scheme;

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allow_empty = true;

	/**
	 * Validates the attribute of the object.
	 * If there is any error, the error message is added to the object.
	 * @param Model $object the object being validated
	 * @param string $attribute the attribute being validated
	 */
	protected function validate_attribute( $object, $attribute ) {
		$value = $object->$attribute;
		if( $this->allow_empty && $this->is_empty( $value ) ) {
			return;
		}

		if( ( $value = $this->validate_value( $value ) ) !== false ) {
			$object->$attribute = $value;
		} else {
			$message = $this->message !== null ? $this->message : '{attribute} is not a valid URL.';
			$this->add_error( $object, $attribute, $message );
		}
	}

	/**
	 * Validates a static value to see if it is a valid URL.
	 * Note that this method does not respect {@link allowEmpty} property.
	 * This method is provided so that you can call it directly without going through the model validation rule mechanism.
	 * @param mixed $value the value to be validated
	 * @return mixed false if the the value is not a valid URL, otherwise the possibly modified value ({@see defaultScheme})
	 * @since 1.1.1
	 */
	public function validate_value( $value ) {
		if( is_string( $value ) && strlen( $value ) < 2000 ) {
			if( $this->default_scheme !== null && strpos( $value, '://' ) === false ) {
				$value = $this->default_scheme . '://' . $value;
			}

			if( strpos( $this->pattern, '{schemes}' ) !== false ) {
				$pattern = str_replace( '{schemes}', '('. implode( '|', $this->valid_schemes ) . ')' , $this->pattern );
			} else {
				$pattern = $this->pattern;
			}

			if( preg_match( $pattern, $value ) ) {
				return $value;
			}
		}

		return false;
	}
}