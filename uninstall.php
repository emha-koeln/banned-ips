<?php
/**
 * uninstall.php
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
// If uninstall is not called from WordPress, exit
if (! defined ( 'WP_UNINSTALL_PLUGIN' )) {
	exit ();
}

delete_option ( 'bannedips' );

// Old Images
/*
$imgf = BIPS_IMG . "/testfile.jpg";
if (file_exists ( $imgf )) {
	unlink ( $imgf );
}
$imgf = BIPS_IMG . "/CoordImg.jpg";
if (file_exists ( $imgf )) {
	unlink ( $imgf );
}
$imgf = BIPS_IMG . "/CoordImg.png";
if (file_exists ( $imgf )) {
	unlink ( $imgf );
}
$imgf = BIPS_IMG . "/CoordImg.map";
if (file_exists ( $imgf )) {
	unlink ( $imgf );
}
*/
// Drop db tables
global $wpdb;

// Drop fail2ban stats
$bpis_current_table = $wpdb->prefix . 'bannedips_f2bstats';
$wpdb->query ( "DROP TABLE IF EXISTS $bpis_current_table" );

// Drop urls
$bpis_current_table = $wpdb->prefix . 'bannedips_urls';
$wpdb->query ( "DROP TABLE IF EXISTS $bpis_current_table" );

// Drop abuseipdb
$bpis_current_table = $wpdb->prefix . 'bannedips_abuseipdb';
$wpdb->query ( "DROP TABLE IF EXISTS $bpis_current_table" );

// Drop blocklist
$bpis_current_table = $wpdb->prefix . 'bannedips_blocklist';
$wpdb->query ( "DROP TABLE IF EXISTS $bpis_current_table" );

?>
