<?php

/**
 * Plugin Name: Banned-IPs
 * Plugin URI: https://emha.koeln/banned-ips-plugin
 * Description: Display blocked IPs by fail2ban as Stats, Table or Grap. WP-Shortcode, WP-Widget or Standalone 
 * Version: 0.3.0
 * Requires at least: 5.7
 * Requires PHP: 7.2+
 * License: GPLv2 or later
 * Text Domain: banned-ips
 * Domain Path: /languages
 * Author: emha.koeln
 * Author URI: https://emha.koeln
 *
 * based on boilerplate
 *
 * @package banned-ips
 * @author emha.koeln
 * 
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


// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BANNED_IPS_VERSION', '0.3.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-plugin-name-activator.php
 */
function activate_banned_ips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Banned-IPs-Activator.php';
	Banned_IPs_Activator::activate();
}
register_activation_hook( __FILE__, 'activate_banned_ips' );

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-plugin-name-deactivator.php
 */
function deactivate_banned_ips() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Banned-IPs-Deactivator.php';
	Banned_IPs_Deactivator::deactivate();
}
register_deactivation_hook( __FILE__, 'deactivate_banned_ips' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/Banned-IPs.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    0.3.0
 */
function run_banned_ips() {

    $plugin = new Banned_IPs( plugin_dir_path(__FILE__), plugin_dir_url(__FILE__) ); 
	$plugin->run();

}
run_banned_ips();



