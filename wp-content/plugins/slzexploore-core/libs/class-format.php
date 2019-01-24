<?php
class Slzexploore_Core_Format {
	public static function format_number( $number ) {
		$locale = localeconv();
		$number = str_replace( '.', $locale['decimal_point'], strval( $number ) );
		if ( ! is_float( $number ) ) {
			$number = sanitize_text_field( $number );
		}
		$number = floatval( $number );
		return $number;
	}
	public static function format_datetime( $format, $date, $time = '' ) {
		$time_format = $date_val = '';
		if( empty($format) ) {
			$format = get_option( 'date_format' );
			$time_format = get_option('time_format');
		}
		if( $date ) {
			$date = date('Y/m/d', strtotime($date));
			if( !empty( $time ) ) {
				if( !empty( $time_format )) {
					$format = $format . ' ' . $time_format;
				}
				$date = $date . ' ' . $time;
			}
			$date_val = mysql2date( $format, $date, true );
		}
		return $date_val;
	}
	public static function compare_datetime( $datefrom, $dateto, $timefrom, $timeto ) {
		$format = 'Ymd His';
	
		$datefrom = self::format_datetime( $format, $datefrom, $timefrom );
		$dateto = self::format_datetime( $format, $dateto, $timeto );

		if( $datefrom <= $dateto ) {
			return true;
		}
		return false;
	}
	public static function compare_date( $datefrom, $dateto ) {
		$format = 'Ymd';
	
		$datefrom = self::format_datetime( $format, $datefrom );
		$dateto = self::format_datetime( $format, $dateto );
		if( $datefrom <= $dateto ) {
			return true;
		}
		return false;
	}
}