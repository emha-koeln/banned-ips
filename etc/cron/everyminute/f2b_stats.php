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
<<<<<<< HEAD
function bannedips_cron_f2b_stats2db() {
=======
<<<<<<< Upstream, based on origin/main
function bips_cron_f2b_stats2db() {
=======
function bannedips_cron_f2b_stats2db() {
>>>>>>> b9a6b74 v 0.1.5-alpha
>>>>>>> refs/remotes/origin/main
	global $wpdb;
	$options = get_option ( 'bannedips', array () );
	
	// SQLite Fail2Ban DB
	class MyDB extends SQLite3 {
		function __construct($_myDB) {
			$this->open ( $_myDB );
		}
	}
	// Open Fail2Ban DB
	$db = new MyDB ( $options ['db'] );
	if (! $db) {
		echo $db->lastErrorMsg ();
	} else {
		// echo "Open database successfully\n";
		// echo "<br>\n";
	}
	
	$sql = "SELECT COUNT(*) as count FROM bips";
	$ret = $db->query ( $sql );
	$row = $ret->fetchArray ( SQLITE3_ASSOC );
	$bad = $row ['count'];
	
	$sql = "SELECT COUNT(*) as count FROM bips WHERE 1 = 1 AND (timeofban + bantime > " . time () . " OR bantime <= -1)";
	$ret = $db->query ( $sql );
	$row = $ret->fetchArray ( SQLITE3_ASSOC );
	$count = $row ['count'];
	
	require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
	
	// fail2ban_stats
	$table = $wpdb->prefix . "bannedips_f2bstats";
	
	$sql = "INSERT INTO $table (
				time,
				markedbad,
          		bans
    			)
			VALUES (
				now(),
				$bad,
				$count)
				";
	// echo $sql;
	
	dbDelta ( $sql );
}

?>
