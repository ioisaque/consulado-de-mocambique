<?php
/**
 * Theme class.
 * 
 * @since 1.0
 */
class Slzexploore_Theme {
	/**
	 * @var array setting value.
	 */
	private $setting = array();

	/**
	 * Constructor.
	 * 
	 * Load all theme modification values for the current theme.
	 */
	public function __construct() {
		if( ( $mods = get_theme_mods() ) && isset( $mods['php_slz_theme'] ) ) {
			$this->setting = $mods['php_slz_theme'];
		}
	}

	/**
	 * Returns the value of setting.
	 *
	 * @param string $key key.
	 * @param string $def The default value to return if no result is found.
	 * @return string
	 */
	public function get( $key, $def = NULL ) {
		return ( isset( $this->setting[ $key ] ) ? $this->setting[ $key ] : $def );
	}

	/**
	 * Sets the value of setting.
	 *
	 * @param string $key key
	 * @param mixed $val value
	 */
	public function set( $key, $val ) {
		if( null == $val || '' == $val ) {
			unset( $this->setting[ $key ] );
		} else {
			$this->setting[ $key ] = $val;
		}
	}

	/**
	 * Creates or updates a modification setting for the current theme.
	 */
	public function save() {
		set_theme_mod( 'php_slz_theme', $this->setting );
	}
}