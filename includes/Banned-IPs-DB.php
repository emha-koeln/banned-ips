<?php

/**
 * Banned-IPs-DB.php
 * Part of banned-ips, also for standalone use
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */

class Banned_IPs_DB{
    // DB
    public static function create_db()
    {
        global $wpdb;
        
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // create tables in wp database if not exists
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // fail2ban_stats
        $table = $wpdb->prefix . "banned_ips_f2bstats";
        $sql = "CREATE TABLE IF NOT EXISTS $table (
             		`id` int(11) NOT NULL AUTO_INCREMENT,
          		    `time` timestamp NOT NULL DEFAULT '0000.00.00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
              		`markedbad` int(10) NOT NULL,
              		`bans` int(10) NOT NULL,
        			UNIQUE (`id`)
        		) $charset_collate;";
        dbDelta($sql);
        
        // urls
        $table = $wpdb->prefix . "banned_ips_urls";
        $sql = "CREATE TABLE IF NOT EXISTS $table (
             		`id` int(11) NOT NULL AUTO_INCREMENT,
          		    `time` timestamp NOT NULL DEFAULT '0000.00.00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
              		`ip` varchar(30) NOT NULL,
              		`url` text NOT NULL,
        			UNIQUE (`id`)
        		) $charset_collate;";
        dbDelta($sql);
        
        // abuseipdb
        $table = $wpdb->prefix . "banned_ips_abuseipdb";
        $sql = "CREATE TABLE IF NOT EXISTS $table (
             		`id` int(11) NOT NULL AUTO_INCREMENT,
          		    `time` timestamp NOT NULL DEFAULT '0000.00.00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
              		`attacks` int(30) NOT NULL,
        			UNIQUE (`id`)
        		) $charset_collate;";
        dbDelta($sql);
        
        // blocklist
        $table = $wpdb->prefix . "banned_ips_blocklist";
        $sql = "CREATE TABLE IF NOT EXISTS $table (
             		`id` int(11) NOT NULL AUTO_INCREMENT,
          		    `time` timestamp NOT NULL DEFAULT '0000.00.00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
              		`attacks` int(30) NOT NULL,
    				`reports` int(30) NOT NULL,
        			UNIQUE (`id`)
        		) $charset_collate;";
        dbDelta($sql);
    }
}
?>