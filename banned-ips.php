<?php
/**
 * Plugin Name: Banned IPs
 * Plugin URI: https://emha.koeln/banned-ips-plugin
 * Description: Shortcode [bannedips] for showing the current blocked IPs by fail2ban.
 * Version: 0.1.5.alpha11
 * License: GPLv2 or later
 * Text Domain: banned-ips
 * Domain Path: /languages
 * Author: emha.koeln
 * Author URI: https://emha.koeln
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */
/**
 * banned-ips is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * It is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with banned-ips. If not, see https://emha.koeln/wp-content/uploads/2021/04/gpl-2.0.txt.
 */

// Define
define ( 'BIPS_VERSION', '0.1.5.alpha' );

define ( 'BIPS_PATH', rtrim ( plugin_dir_path ( __file__ ), "/" ) ); // local path

define ( 'BIPS_SYS', BIPS_PATH . "/sys" ); // bips system
define ( 'BIPS_ETC', BIPS_PATH . "/etc" ); // bips config
define ( 'BIPS_SCR', BIPS_PATH . "/scr" ); // bips scripts
define ( 'BIPS_IMG', BIPS_PATH . "/img" ); // bips images
define ( 'BIPS_CLS', BIPS_PATH . "/cls" ); // bips classes

define ( 'BIPS_DIR_URL', plugin_dir_url ( __file__ ) ); // local url
define ( 'BIPS_DIR_NAME', str_replace ( "/banned-ips.php", "", plugin_basename ( __FILE__ ) ) ); // plugin dir name
                                                                                                 
// Exit if accessed directly
if (! defined ( 'ABSPATH' )) {
	exit ();
}

// include sys
include_once (BIPS_SYS . "/activation.php");
include_once (BIPS_SYS . "/deactivation.php");
include_once (BIPS_SYS . "/cron.php");

// WP Plugin activation
register_activation_hook ( __FILE__, 'bannedips_register_activation' );
function bannedips_register_activation() {
	bips_activate_create_db ();
	// bips_activate_cronjobs ();
}

// WP Plugin deactivation
register_deactivation_hook ( __FILE__, 'bannedips_register_deactivation' );
function bannedips_register_deactivation() {
	bips_deactivate_cronjobs ();
}

// My Text Domain
add_filter( 'load_textdomain_mofile', 'bannedips_load_my_own_textdomain', 10, 2 );
function bannedips_load_my_own_textdomain( $mofile, $domain ) {
	if ( 'banned-ips' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
		$locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
		$mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
	}
	return $mofile;
}



// WP Shortcode
if (is_admin ()) {
	include BIPS_PATH . '/admin/admin.php';
} else {
	
	// Shortcode
	add_shortcode ( 'bannedips', 'bannedips_call' );
	function bannedips_call($attrs, $content = null) {
		$options = get_option ( 'bannedips', [ ] );
		
		$file = BIPS_PATH . "/banned.php";
		
		// Table abuseipdb and/or blocklist

		// Use SQL with conjob for WP
		if (isset ( $options ['sys_cron'] ) 
				&& (isset ( $options ['ab_stats'] ) || isset ( $options ['bl_stats'] ))) {
			
			global $wpdb;
			
			ob_start ();
			echo "<table>";
			if (isset ( $options ['ab_stats'] )) {
				$table = $wpdb->prefix . "bannedips_abuseipdb";
				// $result = array( 'attacks' => 0 );
				$result = $wpdb->get_results ( 'SELECT attacks FROM ' . $table . ' ORDER BY ID DESC LIMIT 1' );
				// var_dump($result);
				echo "<td>";
				echo "<b>AbuseIPDB Stats:</b><br>";
				if (! isset ( $result ['0']->attacks )) {
					echo "Attacks: Not stats<br>";
				} else {
					echo "Attacks: " . $result ['0']->attacks . "<br>";
				}
				echo "</td>";
			}
			
			if (isset ( $options ['bl_stats'] )) {
				$table = $wpdb->prefix . "bannedips_blocklist";
				$result = $wpdb->get_results ( 'SELECT attacks, reports FROM ' . $table . ' ORDER BY ID DESC LIMIT 1' );
				echo "<td>";
				echo "<b>Blocklist Stats:</b><br>";
				if (! isset ( $result ['0']->attacks )) {
					echo "Attacks: Not stats, ";
					echo "Reports: Not stats<br>";
				} else {
					echo "Attacks: " . $result ['0']->attacks . ", ";
					echo "Reports: " . $result ['0']->reports . "<br>";
				}
				echo "</td>";
			}
			echo "</table>";
			ob_flush ();

		// Use direct request for standalone use
		} else {
			ob_start ();
			echo "<table>";
			if (isset ( $options ['ab_stats'] )) {
				echo "<td>";
				include (BIPS_SCR . "/abuseipdb_stats.php");
				echo "</td>";
			}
			
			if (isset ( $options ['bl_stats'] )) {
				echo "<td>";
				include (BIPS_SCR . "/blocklist_stats.php");
				echo "</td>";
			}
			echo "</table>";
			ob_flush ();
		}
		
		// TODO: Use Theme Colors and Widths
		// echo "Test Colors: " . get_some_textcolor;
		// echo "Test Colors: " . get_some_theme_info;
		
		// create stats graph image
		if (isset ( $options ['show_graph']) && isset(  $options ['sys_cron'])) {
			ob_start ();
			include (BIPS_SCR . "/draw_graph.php");
			ob_flush ();
		}
		
		ob_start ();
		include ($file);
		ob_flush ();
		
		$buffer = ob_get_clean ();
		return $buffer;
	}
}
