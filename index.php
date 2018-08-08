<?php
/*
* Plugin Name: Vimeo Video Post PRO - Theme Vlogger compatibility
* Plugin URI: https://codeflavors.com/vimeo-video-post/
* Description: Add-on plugin for Vimeo Video Post PRO - Vimeo videos WordPress importer which introduces compatibility with theme Vlogger
* Author: CodeFlavors
* Version: 1.0
* Author URI: https://codeflavors.com
*/

class CVM_Vlogger_Compatibility{
	/**
	 * Holds compatible theme name
	 */
	const THEME = 'Vlogger';
	/**
	 * Holds class instance
	 * @var CVM_Vlogger_Compatibility|null
	 */
	private static $instance = null;

	/**
	 * CVM_Vlogger_Compatibility constructor.
	 */
	private function __construct() {
		add_action( 'init', array( $this, 'on_init' ) );
	}

	/**
	 * @return CVM_Vlogger_Compatibility|null
	 */
	public static function get_instance(){
		if( null === self::$instance ){
			self::$instance = new CVM_Vlogger_Compatibility();
		}
		return self::$instance;
	}

	/**
	 * Hook "init" callback, verifies that plugin is loaded and
	 * that loaded theme is the right theme
	 */
	public function on_init(){
		if( !class_exists( 'CVM_Vimeo_Videos' ) ){
			return;
		}
		$theme = $this->get_theme();
		if( !$theme || !in_array( $theme->get('Name'), array( self::THEME, 'vlogger-child' ) ) ){
			return;
		}

		require_once plugin_dir_path( __FILE__ ) . '/includes/class.cvm-vlogger-compatibility.php';
		new CVM_Vlogger_Actions_Compatibility( self::THEME );
	}

	/**
	 * Get currently installed parent theme
	 * @return bool|false|WP_Theme
	 */
	private function get_theme(){
		// get template details
		$theme = wp_get_theme();
		if( is_a( $theme, 'WP_Theme' ) ){
			// check if it's child theme
			if( is_a( $theme->parent(), 'WP_Theme' ) ){
				// set theme to parent
				$theme = $theme->parent();
			}
		}else{
			$theme = false;
		}
		return $theme;
	}
}
CVM_Vlogger_Compatibility::get_instance();
