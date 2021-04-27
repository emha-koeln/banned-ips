<?php
/**
 * cron.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */

// Cronjobs

// add custom interval
add_filter ( 'cron_schedules', 'cron_add_minute' );
function cron_add_minute($schedules) {
	// Adds once every minute to the existing schedules.
	$schedules ['everyminute'] = array (
			'interval' => 60,
			'display' => __ ( 'Once Every Minute' ) 
	);
	return $schedules;
}

add_filter ( 'cron_schedules', 'cron_add_tenminutes' );
function cron_add_tenminutes($schedules) {
	// Adds once every minute to the existing schedules.
	$schedules ['tenminutes'] = array (
			'interval' => 600,
			'display' => __ ( 'Every Ten Minutes' ) 
	);
	return $schedules;
}

// Everyminute
add_action ( 'bannedips_hook_everyminute_cronjob', 'bannedips_everyminute_cronjob' );
function bannedips_everyminute_cronjob() {
	// test
	// $recepients = 'root@localhost';
	// $subject = 'Hello from your Everyminute Cron Job';
	// $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	// let's send it
	// mail ( $recepients, $subject, $message );
	include_once (BIPS_ETC . "/cron/everyminute/f2b_stats.php");
	include_once (BIPS_ETC . "/cron/everyminute/f2b_graph.php");
	// TODO automaticly read folder
	bannedips_cron_f2b_stats2db ();
	bannedips_cron_f2b_graph ();
}

// Tenminutes
add_action ( 'bannedips_hook_tenminutes_cronjob', 'bannedips_tenminutes_cronjob' );
function bannedips_tenminutes_cronjob() {
	// test
	// $recepients = 'root@localhost';
	// $subject = 'Hello from your tenminutes Cron Job';
	// $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	// let's send it
	// mail ( $recepients, $subject, $message );
	include_once (BIPS_ETC . "/cron/tenminutes/ab_stats.php");
	include_once (BIPS_ETC . "/cron/tenminutes/bl_stats.php");
	
	// TODO automaticly read folder
	bannedips_cron_ab_stats2db ();
	bannedips_cron_bl_stats2db ();
}

// Hourly
add_action ( 'bannedips_hook_hourly_cronjob', 'bannedips_hourly_cronjob' );
function bannedips_hourly_cronjob() {
	// test
	// $recepients = 'root@localhost';
	// $subject = 'Hello from your Hourly Cron Job';
	// $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	// let's send it
	// mail ( $recepients, $subject, $message );
	
	// include_once (BIPS_ETC . "/cron/hourly");
	
	// TODO automaticly read folder
	// bips_cron_ab_stats2db();
}

// Daily
add_action ( 'bannedips_hook_daily_cronjob', 'bannedips_daily_cronjob' );
function bannedips_daily_cronjob() {
	// test
	// $recepients = 'root@localhost';
	// $subject = 'Hello from your Daily Cron Job';
	// $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	// let's send it
	// mail ( $recepients, $subject, $message );
	
	// include_once (BIPS_ETC . "/cron/daily");
	
	// TODO automaticly read folder
	// bips_cron_ab_stats2db();
}

?>