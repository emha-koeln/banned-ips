<?php
/**
 * Plugin Name: Banned IPs
 * Plugin URI: https://emha.koeln/banned-ips-plugin
 * Description: Display blocked IPs by fail2ban as Stats, Table or Grap. WP-Shortcode, WP-Widget or Standalone 
 * Version: 0.1.5.alpha16
 * Requires at least: 5.7
 * Requires PHP: 7.2+
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
 * You should have received a copy (license.txt) of the GNU General Public License
 * along with banned-ips. If not, see https://emha.koeln/wp-content/uploads/2021/04/gpl-2.0.txt.
 */

// Exit if accessed directly
if (! defined ( 'ABSPATH' )) {
	exit ();
}

// Define
define ( 'BANNED_IPs_VERSION', '0.1.5.alpha' );

// i18n
add_action('plugins_loaded', 'banned_ips_localization_init');
function banned_ips_localization_init()
{
    load_plugin_textdomain('banned-ips', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

//Banned_IPs
include_once ( plugin_dir_path(__FILE__) . 'cls/Banned_IPs.php');
$Bips = new Banned_IPs( plugin_dir_path(__FILE__), plugin_dir_url(__FILE__) );


// WP Plugin activation
include_once ( $Bips->PATH_SYS . "activation.php");
include_once ( $Bips->PATH_SYS . "cron.php");

register_activation_hook( __FILE__, 'banned_ips_register_activation');
function banned_ips_register_activation()
{
    banned_ips_activate_create_db();
    banned_ips_activate_cronjobs(); // ?
}

// WP Plugin deactivation
include_once ( $Bips->PATH_SYS . "deactivation.php");

register_deactivation_hook( __FILE__, 'banned_ips_register_deactivation');
function banned_ips_register_deactivation()
{
    banned_ips_deactivate_cronjobs();
}

// WP Admin
if (is_admin()) { 
    require_once ( $Bips->PATH . 'admin/admin.php');
}

// Shortcode
include_once ( $Bips->PATH . 'cls/Banned_IPs_Shortcode.php');
$Bips_shortcode = new Banned_IPs_Shortcode();

// Widget
include_once ( $Bips->PATH . 'cls/Banned_IPs_Widget.php');
$Bips_widget = new Banned_IPs_Widget();





