<?php

/**
 * Fired during plugin deactivation
 *
 * @link       http://example.com
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
class Banned_IPs_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    0.3.0
	 */
	public static function deactivate() {

	    // WP Cron
	    
	    // everyminute
	    $timestamp = wp_next_scheduled('banned_ips_hook_everyminute_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_everyminute_cronjob');
	    // tenminutes
	    $timestamp = wp_next_scheduled('banned_ips_hook_tenminutes_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_tenminutes_cronjob');
	    // Hourly
	    $timestamp = wp_next_scheduled('banned_ips_hook_hourly_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_hourly_cronjob');
	    // Daily
	    $timestamp = wp_next_scheduled('banned_ips_hook_daily_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_daily_cronjob');
	    
	}

}
