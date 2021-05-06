<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
class Banned_IPs_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.3.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'banned-ips',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
