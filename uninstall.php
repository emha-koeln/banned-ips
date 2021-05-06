<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option('banned-ips');

// Drop db tables
global $wpdb;

// Drop fail2ban stats
$bpis_current_table = $wpdb->prefix . 'banned_ips_f2bstats';
$wpdb->query("DROP TABLE IF EXISTS $bpis_current_table");

// Drop urls
$bpis_current_table = $wpdb->prefix . 'banned_ips_urls';
$wpdb->query("DROP TABLE IF EXISTS $bpis_current_table");

// Drop abuseipdb
$bpis_current_table = $wpdb->prefix . 'banned_ips_abuseipdb';
$wpdb->query("DROP TABLE IF EXISTS $bpis_current_table");

// Drop blocklist
$bpis_current_table = $wpdb->prefix . 'banned_ips_blocklist';
$wpdb->query("DROP TABLE IF EXISTS $bpis_current_table");


//Cron hooks
if (! wp_next_scheduled('banned_ips_hook_everyminute_cronjob')) {
    wp_schedule_event(time(), 'everyminute', 'banned_ips_hook_everyminute_cronjob');
}

if (! wp_next_scheduled('banned_ips_hook_tenminutes_cronjob')) {
    wp_schedule_event(time(), 'tenminutes', 'banned_ips_hook_tenminutes_cronjob');
}

if (! wp_next_scheduled('banned_ips_hook_hourly_cronjob')) {
    wp_schedule_event(time(), 'hourly', 'banned_ips_hook_hourly_cronjob');
}

if (! wp_next_scheduled('banned_ips_hook_daily_cronjob')) {
    wp_schedule_event(time(), 'daily', 'banned_ips_hook_daily_cronjob');
}
