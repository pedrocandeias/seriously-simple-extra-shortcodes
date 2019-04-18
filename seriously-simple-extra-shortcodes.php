<?php
/*
 * Plugin Name: Seriously Simple Extra Shortcodes
 * Version: 1.0.0
 * Plugin URI: https://wordpress.org/plugins/seriously-simple-extra-shortcodes
 * Description: Extends Seriously Simple Podcasting with extra shortcodes
 * Author: Pedro Candeias
 * Author URI: https://www.pedrocandeias.net
 * Requires at least: 4.4
 * Tested up to: 4.8.2
 *
 * Text Domain: seriously-simple-extra-shortcodes
 * Domain Path: /languages
 *
 * @package WordPress
 * @author Hugh Lashbrooke
 * @since 1.0.0
 *
 * @author Pedro Candeias
 * @since 1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'SSP_EXTRA_SHORTCODES_VERSION', '1.0.0' );

if ( ! function_exists( 'is_ssp_active' ) ) {
	require_once( 'ssp-includes/ssp-functions.php' );
}

if ( is_ssp_active( '1.13.1' ) ) {

	// Load plugin class files
	require_once( 'includes/class-ssp-extra-shortcodes.php' );

	/**
	 * Returns the main instance of SSP_Extra_Shortcodes to prevent the need to use globals.
	 *
	 * @since  1.0.0
	 * @return object SSP_Extra_Shortcodes
	 */
	function SSP_Extra_Shortcodes() {
		$instance = SSP_Extra_Shortcodes::instance( __FILE__, SSP_EXTRA_SHORTCODES_VERSION, '1.0.0' );

		return $instance;
	}

	SSP_Extra_Shortcodes();

}
