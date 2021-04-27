<?php
/**
 * activation.php
 * Part of banned-ips, also for standalone use
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */

// DB
<<<<<<< Upstream, based on origin/main
function bips_activate_create_db() {
	global $wpdb;
	
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// create tables in wp database if not exists
	
	$charset_collate = $wpdb->get_charset_collate ();
	
	// fail2ban_stats
	$table = $wpdb->prefix . "bannedips_f2bstats";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`markedbad` int(10) NOT NULL,
          		`bans` int(10) NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
	
	// urls
	$table = $wpdb->prefix . "bannedips_urls";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`ip` varchar(30) NOT NULL,
          		`url` text NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
	
	// abuseipdb
	$table = $wpdb->prefix . "bannedips_abuseipdb";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`attacks` int(30) NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
	
	// blocklist
	$table = $wpdb->prefix . "bannedips_blocklist";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`attacks` int(30) NOT NULL,
				`reports` int(30) NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
}

// Cron
function bips_activate_cronjobs() {
	
	// WP Cron
	// include_once (BIPS_SYS . "/cron.php");
	if (! wp_next_scheduled ( 'bips_hook_everyminute_cronjob' )) {
		wp_schedule_event ( time (), 'everyminute', 'bips_hook_everyminute_cronjob' );
	}
	
	if (! wp_next_scheduled ( 'bips_hook_tenminutes_cronjob' )) {
		wp_schedule_event ( time (), 'tenminutes', 'bips_hook_tenminutes_cronjob' );
	}
	
	if (! wp_next_scheduled ( 'bips_hook_hourly_cronjob' )) {
		wp_schedule_event ( time (), 'hourly', 'bips_hook_hourly_cronjob' );
	}
	
	if (! wp_next_scheduled ( 'bips_hook_daily_cronjob' )) {
		wp_schedule_event ( time (), 'daily', 'bips_hook_daily_cronjob' );
	}
	// $recepients = 'root@localhost';
	// $subject = 'Hello from your Bannedips Sys Cron Job: activated';
	// $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	// let's send it
	// mail ( $recepients, $subject, $message );
}
add_action ( 'wp', 'bips_activate_cronjobs' );
=======
function bannedips_activate_create_db() {
	global $wpdb;
	
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// create tables in wp database if not exists
	
	$charset_collate = $wpdb->get_charset_collate ();
	
	// fail2ban_stats
	$table = $wpdb->prefix . "bannedips_f2bstats";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`markedbad` int(10) NOT NULL,
          		`bans` int(10) NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
	
	// urls
	$table = $wpdb->prefix . "bannedips_urls";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`ip` varchar(30) NOT NULL,
          		`url` text NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
	
	// abuseipdb
	$table = $wpdb->prefix . "bannedips_abuseipdb";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`attacks` int(30) NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
	
	// blocklist
	$table = $wpdb->prefix . "bannedips_blocklist";
	$sql = "CREATE TABLE IF NOT EXISTS $table (
         		`id` int(11) NOT NULL AUTO_INCREMENT,
      		    `time` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
          		`attacks` int(30) NOT NULL,
				`reports` int(30) NOT NULL,
    			UNIQUE (`id`)
    		) $charset_collate;";
	dbDelta ( $sql );
}

// Cron
add_action ( 'wp', 'bannedips_activate_cronjobs' );
function bannedips_activate_cronjobs() {
	
	// old
	if (wp_next_scheduled ( 'bips_hook_everyminute_cronjob' )) {
		$timestamp = wp_next_scheduled ( 'bips_hook_everyminute_cronjob' );
		wp_unschedule_event ( $timestamp, 'bips_hook_everyminute_cronjob' );
	}
	// tenminutes
	if (wp_next_scheduled ( 'bips_hook_tenminutes_cronjob' )) {
		$timestamp = wp_next_scheduled ( 'bips_hook_tenminutes_cronjob' );
		wp_unschedule_event ( $timestamp, 'bips_hook_tenminutes_cronjob' );
	}
	// Hourly
	if (wp_next_scheduled ( 'bips_hook_hourly_cronjob' )) {
		$timestamp = wp_next_scheduled ( 'bips_hook_hourly_cronjob' );
		wp_unschedule_event ( $timestamp, 'bips_hook_hourly_cronjob' );
	}
	// Daily
	if (wp_next_scheduled ( 'bips_hook_daily_cronjob' )) {
		$timestamp = wp_next_scheduled ( 'bips_hook_daily_cronjob' );
		wp_unschedule_event ( $timestamp, 'bips_hook_daily_cronjob' );
	}
	
	
	
	// WP Cron
	// include_once (BIPS_SYS . "/cron.php");
	if (! wp_next_scheduled ( 'bannedips_hook_everyminute_cronjob' )) {
		wp_schedule_event ( time (), 'everyminute', 'bannedips_hook_everyminute_cronjob' );
	}
	
	if (! wp_next_scheduled ( 'bannedips_hook_tenminutes_cronjob' )) {
		wp_schedule_event ( time (), 'tenminutes', 'bannedips_hook_tenminutes_cronjob' );
	}
	
	if (! wp_next_scheduled ( 'bannedips_hook_hourly_cronjob' )) {
		wp_schedule_event ( time (), 'hourly', 'bannedips_hook_hourly_cronjob' );
	}
	
	if (! wp_next_scheduled ( 'bannedips_hook_daily_cronjob' )) {
		wp_schedule_event ( time (), 'daily', 'bannedips_hook_daily_cronjob' );
	}
	// $recepients = 'root@localhost';
	// $subject = 'Hello from your Bannedips Sys Cron Job: activated';
	// $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	// let's send it
	// mail ( $recepients, $subject, $message );
}

>>>>>>> b9a6b74 v 0.1.5-alpha

?>