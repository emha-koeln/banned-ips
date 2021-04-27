<?php
/**
 * ab_stats.php
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
include BIPS_CLS . "/ConnectBlocklist.php";
<<<<<<< HEAD
function bannedips_cron_bl_stats2db() {
=======
<<<<<<< Upstream, based on origin/main
function bips_cron_bl_stats2db() {
=======
function bannedips_cron_bl_stats2db() {
>>>>>>> b9a6b74 v 0.1.5-alpha
>>>>>>> refs/remotes/origin/main
	global $wpdb;
	$options = get_option ( 'bannedips', array () );
	
	$connectBlocklist = new ConnectBlocklist ( $options ['bl_account_serverid'], $options ['bl_account_apikey'] );
	$result = $connectBlocklist->get_Attacks ();
	
	$attacks = $result ['attacks'];
	$reports = $result ['reports'];
	
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// fail2ban_stats
	$table = $wpdb->prefix . "bannedips_blocklist";
	
	$sql = "INSERT INTO $table (
				time,
				attacks,
				reports
    			)
			VALUES (
				now(),
				$attacks,
				$reports
				)
				";
	// echo $sql;
	
	dbDelta ( $sql );
}

?>
