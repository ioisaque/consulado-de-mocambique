<?php
/**
 * DateTime class.
 * 
 * @since 1.0
 */
class Slzexploore_Core_DateTime_Parser {
	/**
	 * @var boolean whether 'mbstring' PHP extension available. This static property introduced for
	 * the better overall performance of the class functionality. Checking 'mbstring' availability
	 * through static property with predefined status value is much faster than direct calling
	 * of function_exists('...').
	 * Intended for internal use only.
	 */
	private static $mbstring_available;

	/**
	 * Converts a date string to a timestamp.
	 *
	 * @param string $value the date string to be parsed
	 * @param string $pattern the pattern that the date string is following
	 * @param array $defaults the default values for year, month, day, hour, minute and second.
	 * @return integer timestamp for the date string. False if parsing fails.
	 */
	public static function parse( $value, $pattern = 'MM/dd/yyyy' , $defaults = array() ) {
		if( self::$mbstring_available === null ) {
			self::$mbstring_available = extension_loaded( 'mbstring' );
		}

		$tokens = self::tokenize( $pattern );
		$i = 0;
		$n = self::$mbstring_available ? mb_strlen( $value, get_bloginfo('charset') ) : strlen( $value );
		foreach( $tokens as $token ) {
			switch( $token ) {
				case 'yyyy':
					if( ( $year = self::parse_integer( $value, $i, 4, 4 ) ) === false ) {
						return false;
					}
					$i += 4;
					break;
				case 'yy':
					if( ( $year = self::parse_integer( $value, $i, 1, 2 ) ) === false ) {
						return false;
					}
					$i += strlen( $year );
					break;
				case 'MMMM':
					$month_name = '';
					if( ( $month = self::parse_month( $value, $i, 'wide', $month_mame ) ) === false ) {
						return false;
					}
					$i += self::$mbstring_available ? mb_strlen( $month_name, get_bloginfo( 'charset' ) ) : strlen( $month_name );
					break;
				case 'MMM':
					$month_name = '';
					if( ( $month = self::parse_month( $value, $i, 'abbreviated', $month_name ) ) === false ) {
						return false;
					}
					$i += self::$mbstring_available ? mb_strlen( $month_name, get_bloginfo( 'charset' ) ) : strlen($month_name);
					break;
				case 'MM':
					if( ( $month = self::parse_integer( $value, $i, 2, 2 ) ) === false ) {
						return false;
					}
					$i += 2;
					break;
				case 'M':
					if( ( $month = self::parse_integer( $value, $i, 1, 2 ) ) === false ) {
						return false;
					}
					$i += strlen( $month );
					break;
				case 'dd':
					if( ( $day = self::parse_integer( $value, $i, 2, 2 ) ) === false ) {
						return false;
					}
					$i += 2;
					break;
				case 'd':
					if( ( $day = self::parse_integer( $value, $i, 1, 2 ) ) === false ) {
						return false;
					}
					$i += strlen( $day );
					break;
				case 'h':
				case 'H':
					if( ( $hour = self::parse_integer( $value, $i, 1, 2 ) ) === false ) {
						return false;
					}
					$i += strlen( $hour );
					break;
				case 'hh':
				case 'HH':
					if( ( $hour = self::parse_integer( $value, $i, 2, 2 ) ) === false ) {
						return false;
					}
					$i += 2;
					break;
				case 'm':
					if( ( $minute = self::parse_integer( $value, $i, 1, 2 ) ) === false ) {
						return false;
					}
					$i += strlen( $minute );
					break;
				case 'mm':
					if( ( $minute = self::parse_integer( $value, $i, 2, 2 ) ) === false ) {
						return false;
					}
					$i += 2;
					break;
				case 's':
					if( ( $second = self::parse_integer( $value, $i, 1, 2 ) ) === false ) {
						return false;
					}
					$i += strlen( $second );
					break;
				case 'ss':
					if( ( $second = self::parse_integer( $value, $i, 2, 2 ) ) === false ) {
						return false;
					}
					$i += 2;
					break;
				case 'a':
					if( ( $ampm = self::parse_am_pm( $value, $i ) ) === false ) {
						return false;
					}
					if( isset( $hour ) ) {
						if( $hour == 12 && $ampm === 'am' ) {
							$hour = 0;
						} elseif( $hour < 12 && $ampm === 'pm' ) {
							$hour += 12;
						}
					}
					$i += 2;
					break;
				default:
					$tn = strlen( $token );
					if( $i >= $n || ( $token{0} != '?' && ( self::$mbstring_available ? mb_substr( $value, $i, $tn, get_bloginfo( 'charset' ) ) : substr( $value, $i, $tn ) ) !== $token ) ) {
						return false;
					}
					$i += $tn;
					break;
			}
		}
		if( $i < $n ) {
			return false;
		}

		if( ! isset( $year ) ) {
			$year = isset( $defaults['year'] ) ? $defaults['year'] : date( 'Y' );
		}
		if( ! isset( $month ) ) {
			$month = isset( $defaults['month'] ) ? $defaults['month'] : date( 'n' );
		}
		if( ! isset( $day ) ) {
			$day = isset( $defaults['day'] ) ? $defaults['day'] : date( 'j' );
		}

		if( strlen( $year ) === 2 ) {
			if( $year >= 70 ) {
				$year += 1900;
			} else {
				$year += 2000;
			}
		}

		$year = (int) $year;
		$month = (int) $month;
		$day = (int) $day;

		if(
			! isset( $hour ) && ! isset( $minute ) && ! isset( $second ) && 
			! isset( $defaults['hour'] ) && ! isset( $defaults['minute'] ) && ! isset( $defaults['second'] )
		){
			$hour = $minute = $second = 0;
		} else {
			if( ! isset( $hour ) ) {
				$hour = isset( $defaults['hour'] ) ? $defaults['hour'] : date( 'H' );
			}
			if( ! isset( $minute ) ) {
				$minute = isset( $defaults['minute'] ) ? $defaults['minute'] : date( 'i' );
			}
			if( ! isset( $second ) ) {
				$second = isset( $defaults['second'] ) ? $defaults['second'] : date( 's' );
			}
			$hour = (int) $hour;
			$minute = (int) $minute;
			$second = (int) $second;
		}

		if(Slzexploore_Core_Timestamp::is_valid_date( $year, $month, $day ) && Slzexploore_Core_Timestamp::is_valid_time( $hour, $minute, $second ) ) {
			return Slzexploore_Core_Timestamp::get_timestamp( $hour, $minute, $second, $month, $day, $year );
		} else {
			return false;
		}
	}

	/*
	 * @param string $pattern the pattern that the date string is following
	 */
	private static function tokenize( $pattern ) {
		if( ! ( $n = strlen( $pattern ) ) ) {
			return array();
		}
		$tokens = array();

		for( $c0 = $pattern[0], $start = 0, $i = 1; $i < $n ; ++$i ) {
			if( ( $c = $pattern[$i] ) !== $c0 ) {
				$tokens[] = substr( $pattern, $start, $i - $start );
				$c0 = $c;
				$start = $i;
			}
		}

		$tokens[] = substr( $pattern, $start, $n - $start );
		return $tokens;
	}

	/**
	 * @param string $value the date string to be parsed
	 * @param integer $offset starting offset
	 * @param integer $minLength minimum length
	 * @param integer $maxLength maximum length
	 * @return string parsed integer value
	 */
	protected static function parse_integer( $value, $offset, $min_length, $max_length) {
		for( $len = $max_length; $len >= $min_length; --$len ) {
			$v = self::$mbstring_available ? mb_substr( $value, $offset, $len, get_bloginfo( 'charset' ) ) : substr( $value, $offset, $len );
			if( ctype_digit( $v ) && ( self::$mbstring_available ? mb_strlen( $v, get_bloginfo( 'charset' ) ) : strlen( $v ) ) >= $min_length ) {
				return $v;
			}
		}

		return false;
	}

	/**
	 * @param string $value the date string to be parsed
	 * @param integer $offset starting offset
	 * @return string parsed day period value
	 */
	protected static function parse_am_pm( $value, $offset ) {
		$v = strtolower( self::$mbstring_available ? mb_substr( $value, $offset, 2, get_bloginfo( 'charset' ) ) : substr( $value, $offset, 2 ) );
		return $v === 'am' || $v === 'pm' ? $v : false;
	}

	/**
	 * @param string $value the date string to be parsed.
	 * @param integer $offset starting offset.
	 * @param string $width month name width. It can be 'wide', 'abbreviated' or 'narrow'.
	 * @param string $month_name extracted month name. Passed by reference.
	 * @return string parsed month name.
	 */
	protected static function parse_month( $value, $offset, $width, &$month_name) {
		$value_length = self::$mbstring_available ? mb_strlen( $value, get_bloginfo( 'charset' ) ) : strlen( $value );
		for( $len = 1; $offset + $len <= $value_length; $len++ ) {
			$month_name = self::$mbstring_available ? mb_substr( $value, $offset, $len, get_bloginfo( 'charset' ) ) : substr( $value, $offset, $len );
			if( ! preg_match( '/^\p{L}+$/u', $month_name ) ) {
				$month_name = self::$mbstring_available ? mb_substr( $month_name, 0, -1, get_bloginfo( 'charset' ) ) : substr( $month_name, 0, -1 );
				break;
			}
		}
		$month_name = self::$mbstring_available ? mb_strtolower( $month_name, get_bloginfo( 'charset' ) ) : strtolower( $month_name );

		$month_names = self::get_month_names( $width, false );
		foreach( $month_names as $k => $v ) {
			$month_names[ $k ] = rtrim( self::$mbstring_available ? mb_strtolower( $v, get_bloginfo( 'charset' ) ) : strtolower( $v ), '.' );
		}

		$month_names_stand_alone = self::get_month_names( $width, true );
		foreach( $month_names_stand_alone as $k => $v ) {
			$month_names_stand_alone[ $k ] = rtrim( self::$mbstring_available ? mb_strtolower( $v, get_bloginfo( 'charset' ) ) : strtolower( $v ), '.' );
		}

		if( ( $v = array_search( $month_name, $month_names ) ) === false && ( $v = array_search( $month_name, $month_names_stand_alone ) ) === false ) {
			return false;
		}

		return $v;
	}

	private static function get_month_names( $width = 'wide', $stand_alone = false ) {
		$locale = array(
			'month_names' => array(
				'wide' => array( 1 => 'January', 2 => 'February', 3 => 'March', 4 => 'April', 5 => 'May', 6 => 'June', 7 => 'July', 8 => 'August', 9 => 'September', 10 => 'October', 11 => 'November', 12 => 'December' ),
				'abbreviated' => array( 1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'May', 6 => 'Jun', 7 => 'Jul', 8 => 'Aug', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dec' ),
			),
			'month_names_sa' => array(
				'narrow' => array( 1 => 'J', 2 => 'F',3 => 'M', 4 => 'A', 5 => 'M', 6 => 'J', 7 => 'J', 8 => 'A', 9 => 'S', 10 => 'O', 11 => 'N', 12 => 'D')
			)
		);

		if( $stand_alone ) {
			return isset( $locale['month_names_sa'][ $width ] ) ? $locale['month_names_sa'][ $width ] : $locale['month_names'][ $width ];
		} else {
			return isset( $locale['month_names'][ $width ] ) ? $locale['month_names'][ $width ] : $locale['month_names_sa'][ $width ];
		}
	}
}

/**
 * Timestamp class.
 *
 * @since 1.0
 */ 
class Slzexploore_Core_Timestamp {
	/**
	 * Gets day of week, 0 = Sunday,... 6=Saturday.
	 * Algorithm from PEAR::Date_Calc
	 *
	 * @param integer $year year
	 * @param integer $month month
	 * @param integer $day day
	 * @return integer day of week
	 */
	public static function get_day_of_week( $year, $month, $day ) {
		/*
		Pope Gregory removed 10 days - October 5 to October 14 - from the year 1582 and
		proclaimed that from that time onwards 3 days would be dropped from the calendar
		every 400 years.

		Thursday, October 4, 1582 (Julian) was followed immediately by Friday, October 15, 1582 (Gregorian).
		*/
		if( $year <= 1582 ) {
			if( $year < 1582 ||
				( $year == 1582 && ( $month < 10 || ( $month == 10 && $day < 15 ) ) ) )
			{
				$greg_correction = 3;
			} else {
				$greg_correction = 0;
			}
		} else {
			$greg_correction = 0;
		}

		if( $month > 2 ) {
			$month -= 2;
		} else {
			$month += 10;
			$year--;
		}

		$day =  floor( ( 13 * $month - 1 ) / 5 ) +
		        $day + ( $year % 100 ) +
		        floor( ( $year % 100 ) / 4 ) +
		        floor( ( $year / 100) / 4 ) - 2 *
		        floor( $year / 100 ) + 77 + $greg_correction;

		return $day - 7 * floor( $day / 7 );
	}

	/**
	 * Checks for leap year, returns true if it is. No 2-digit year check. Also
	 * handles julian calendar correctly.
	 *
	 * @param integer $year year to check
	 * @return boolean true if is leap year
	 */
	public static function is_leap_year( $year ) {
		$year = self::digit_check( $year );
		if( $year % 4 != 0 ) {
			return false;
		}

		if( $year % 400 == 0 ) {
			return true;
		}
		// if gregorian calendar (>1582), century not-divisible by 400 is not leap
		else if( $year > 1582 && $year % 100 == 0 ) {
			return false;
		}
		return true;
	}

	/**
	 * Fix 2-digit years. Works for any century.
	 * Assumes that if 2-digit is more than 30 years in future, then previous century.
	 *
	 * @param integer $y year
	 * @return integer change two digit year into multiple digits
	 */
	protected static function digit_check($y) {
		if( $y < 100 ){
			$yr = (integer) date( "Y" );
			$century = (integer) ( $yr /100 );

			if( $yr%100 > 50 ){
				$c1 = $century + 1;
				$c0 = $century;
			}else{
				$c1 = $century;
				$c0 = $century - 1;
			}
			$c1 *= 100;
			// if 2-digit year is less than 30 years in future, set it to this century
			// otherwise if more than 30 years in future, then we set 2-digit year to the prev century.
			if( ( $y + $c1 ) < $yr + 30 ) {
				$y = $y + $c1;
			} else {
				$y = $y + $c0 * 100;
			}
		}
		return $y;
	}

	/**
	 * Returns 4-digit representation of the year.
	 *
	 * @param integer $y year
	 * @return integer 4-digit representation of the year
	 */
	public static function get_4_digit_year( $y ) {
		return self::digit_check( $y );
	}

	/**
	 * @return integer get local time zone offset from GMT
	 */
	public static function get_gmt_diff() {
		static $tz;
		if( isset( $tz ) ) {
			return $tz;
		}

		$tz = mktime( 0, 0, 0, 1, 2, 1970 ) - gmmktime( 0, 0, 0, 1, 2, 1970 );
		return $tz;
	}

	/**
	 * Returns the getdate() array.
	 * @param integer|boolean $d original date timestamp. False to use the current timestamp.
	 * @param boolean $fast false to compute the day of the week, default is true
	 * @param boolean $gmt true to calculate the GMT dates
	 * @return array an array with date info.
	 */
	public static function get_date( $d = false, $fast = false, $gmt = false ) {
		if( $d === false ) {
			$d = time();
		}

		if( $gmt ) {
			$tz = date_default_timezone_get();
			date_default_timezone_set( 'GMT' );
			$result = getdate( $d );
			date_default_timezone_set( $tz );
		} else {
			$result = getdate( $d );
		}
		return $result;
	}

	/**
	 * Checks to see if the year, month, day are valid combination.
	 *
	 * @param integer $y year
	 * @param integer $m month
	 * @param integer $d day
	 * @return boolean true if valid date, semantic check only.
	 */
	public static function is_valid_date( $y, $m, $d ) {
		return checkdate( $m, $d, $y );
	}

	/**
	 * Checks to see if the hour, minute and second are valid.
	 *
	 * @param integer $h hour
	 * @param integer $m minute
	 * @param integer $s second
	 * @param boolean $hs24 whether the hours should be 0 through 23 (default) or 1 through 12.
	 * @return boolean true if valid date, semantic check only.
	 */
	public static function is_valid_time( $h, $m, $s, $hs24 = true ) {
		if($hs24 && ($h < 0 || $h > 23) || !$hs24 && ($h < 1 || $h > 12)) return false;
		if($m > 59 || $m < 0) return false;
		if($s > 59 || $s < 0) return false;
		return true;
	}

	/**
	 * Formats a timestamp to a date string.
	 *
	 * @param string $fmt format pattern
	 * @param integer|boolean $d timestamp
	 * @param boolean $is_gmt whether this is a GMT timestamp
	 * @return string formatted date based on timestamp $d
	 */
	public static function format_date( $fmt, $d = false, $is_gmt = false ) {
		if( $d === false ) {
			return ( $is_gmt ) ? @gmdate( $fmt ): @date( $fmt );
		}

		// check if number in 32-bit signed range
		if( ( abs( $d ) <= 0x7FFFFFFF ) ) {
			// if windows, must be +ve integer
			if( $d >= 0 ) {
				return ( $is_gmt )? @gmdate( $fmt, $d ): @date( $fmt, $d );
			}
		}

		$_day_power = 86400;

		$arr = self::get_date( $d, true, $is_gmt );

		$year = $arr['year'];
		$month = $arr['mon'];
		$day = $arr['mday'];
		$hour = $arr['hours'];
		$min = $arr['minutes'];
		$secs = $arr['seconds'];

		$max = strlen( $fmt );
		$dates = '';

		/*
			at this point, we have the following integer vars to manipulate:
			$year, $month, $day, $hour, $min, $secs
		*/
		for( $i = 0; $i < $max; $i++ ) {
			switch( $fmt[$i] ) {
				case 'T':
					$dates .= date('T');
					break;
				case 'L':
					// YEAR
					$dates .= $arr['leap'] ? '1' : '0';
					break;
				case 'r': 
					// Thu, 21 Dec 2000 16:01:07 +0200
					// 4.3.11 uses '04 Jun 2004'
					// 4.3.8 uses  ' 4 Jun 2004'
					$dates .= gmdate( 'D', $_day_power * ( 3 + self::get_day_of_week( $year, $month, $day ) ) ) . ', ' . 
					              ( $day < 10 ? '0' . $day : $day ) . ' ' . date( 'M', mktime( 0, 0, 0, $month, 2, 1971 ) ) . ' ' . $year . ' ';

					if( $hour < 10 ) {
						$dates .= '0'.$hour;
					} else {
						$dates .= $hour;
					}

					if( $min < 10 ) {
						$dates .= ':0'.$min;
					} else {
						$dates .= ':'.$min;
					}

					if( $secs < 10 ) {
						$dates .= ':0'.$secs;
					} else {
						$dates .= ':'.$secs;
					}

					$gmt = self::get_gmt_diff();
					$dates .= sprintf( ' %s%04d', ( $gmt <= 0 ) ? '+' : '-', abs( $gmt ) / 36 );
					break;

				case 'Y':
					$dates .= $year;
					break;
				case 'y':
					$dates .= substr( $year, strlen( $year ) - 2, 2 );
					break;
				case 'm':
					// MONTH
					if( $month < 10 ) {
						$dates .= '0' . $month;
					} else {
						$dates .= $month;
					}
					break;
				case 'Q': 
					$dates .= ( $month + 3 ) >> 2;
					break;
				case 'n':
					$dates .= $month;
					break;
				case 'M':
					$dates .= date( 'M', mktime( 0, 0, 0, $month, 2, 1971 ) );
					break;
				case 'F':
					$dates .= date( 'F', mktime( 0, 0, 0, $month, 2, 1971 ) );
					break;
				case 't':
					// DAY
					$dates .= $arr['ndays'];
					break;
				case 'z':
					$dates .= $arr['yday'];
					break;
				case 'w':
					$dates .= self::get_day_of_week( $year, $month, $day );
					break;
				case 'l':
					$dates .= gmdate( 'l', $_day_power * ( 3 + self::get_day_of_week( $year, $month, $day ) ) );
					break;
				case 'D':
					$dates .= gmdate( 'D', $_day_power * ( 3 + self::get_day_of_week( $year, $month, $day ) ) );
					break;
				case 'j':
					$dates .= $day;
					break;
				case 'd':
					if( $day < 10 ) {
						$dates .= '0' . $day;
					} else {
						$dates .= $day;
					}
					break;
				case 'S':
					$d10 = $day % 10;
					if( $d10 == 1 ) {
						$dates .= 'st';
					} elseif( $d10 == 2 && $day != 12 ) {
						$dates .= 'nd';
					} elseif( $d10 == 3 ) {
						$dates .= 'rd';
					} else {
						$dates .= 'th';
					}
					break;

				case 'Z':
					// HOUR
					$dates .= ( $is_gmt ) ? 0 : -self::get_gmt_diff();
					break;
				case 'O':
					$gmt = ( $is_gmt ) ? 0 : self::get_gmt_diff();
					$dates .= sprintf( '%s%04d',( $gmt <= 0 ) ? '+' : '-', abs( $gmt ) / 36 );
					break;

				case 'H':
					if( $hour < 10 ) {
						$dates .= '0' . $hour;
					} else {
						$dates .= $hour;
					}
					break;
				case 'h':
					if( $hour > 12 ) {
						$hh = $hour - 12;
					} else{
						if( $hour == 0 ) {
							$hh = '12';
						} else {
							$hh = $hour;
						}
					}

					if( $hh < 10 ) {
						$dates .= '0' . $hh;
					} else {
						$dates .= $hh;
					}
					break;

				case 'G':
					$dates .= $hour;
					break;

				case 'g':
					if($hour > 12) {
						$hh = $hour - 12;
					} else {
						if( $hour == 0 ) {
							$hh = '12';
						} else {
							$hh = $hour;
						}
					}
					$dates .= $hh;
					break;
				case 'i':
					// MINUTES
					if( $min < 10 ) {
						$dates .= '0' . $min;
					} else {
						$dates .= $min;
					}
					break;
				case 'U':
					// SECONDS
					$dates .= $d;
					break;
				case 's':
					if( $secs < 10 ) {
						$dates .= '0'.$secs;
					} else {
						$dates .= $secs;
					}
					break;
				case 'a':
					// AM/PM
					// Note 00:00 to 11:59 is AM, while 12:00 to 23:59 is PM
					if( $hour >= 12 ) {
						$dates .= 'pm';
					} else {
						$dates .= 'am';
					}
					break;
				case 'A':
					if( $hour >= 12 ) {
						$dates .= 'PM';
					} else {
						$dates .= 'AM';
					}
					break;
				case "\\":
					// ESCAPE
					$i++;
					if( $i < $max ) {
						$dates .= $fmt[$i];
					}
					break;
				default:
					$dates .= $fmt[$i];
					break;
			}
		}
		return $dates;
	}

	/**
	 * Generates a timestamp.
	 * This is the same as the PHP function {@link mktime http://php.net/manual/en/function.mktime.php}.
	 *
	 * @param integer $hr hour
	 * @param integer $min minute
	 * @param integer $sec second
	 * @param integer|boolean $mon month
	 * @param integer|boolean $day day
	 * @param integer|boolean $year year
	 * @param boolean $is_gmt whether this is GMT time. If true, gmmktime() will be used.
	 * @return integer|float a timestamp given a local time.
	 */
	public static function get_timestamp( $hr, $min, $sec, $mon = false , $day = false , $year = false , $is_gmt = false ) {
		if( $mon === false ) {
			return $is_gmt ? @gmmktime( $hr, $min, $sec ): @mktime( $hr, $min, $sec );
		}
		return $is_gmt ? @gmmktime( $hr, $min, $sec, $mon, $day, $year ) : @mktime( $hr, $min, $sec, $mon, $day, $year );
	}
}