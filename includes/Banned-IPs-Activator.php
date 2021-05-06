<?php
/**
 * Banned-IPs-Activator.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
class Banned_IPs_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.3.0
	 */
	public static function activate() {
	    
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-DB.php';
	    $Banned_ips_db = new Banned_IPs_DB();
	    $Banned_ips_db::create_db();

	}

}
