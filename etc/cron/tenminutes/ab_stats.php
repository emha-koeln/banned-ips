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
include BIPS_CLS . "/ConnectAbuseIPDB.php";
<<<<<<< HEAD
function bannedips_cron_ab_stats2db() {
=======
<<<<<<< Upstream, based on origin/main
function bips_cron_ab_stats2db() {
=======
function bannedips_cron_ab_stats2db() {
>>>>>>> b9a6b74 v 0.1.5-alpha
>>>>>>> refs/remotes/origin/main
	global $wpdb;
	$options = get_option ( 'bannedips', array () );
	
	$connectab = new ConnectAbuseIPDB ( $options ['ab_account_id'] );
	$attacks = $connectab->get_Attacks ();
	
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// fail2ban_stats
	$table = $wpdb->prefix . "bannedips_abuseipdb";
	
	$sql = "INSERT INTO $table (
				time,
				attacks
    			)
			VALUES (
				now(),
				$attacks)
				";
	// echo $sql;
	
	dbDelta ( $sql );
}

?>
