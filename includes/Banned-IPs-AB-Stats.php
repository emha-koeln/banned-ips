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

defined('ABSPATH')
    or die();

//global $$Bipsips;



class Banned_IPs_AB_Stats{

    /**
     * Store plugin main class to allow public access.
     *
     * @since    20180622
     * @var object      The main class.
     */
    public $main;
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    0.3.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct(  $plugin_main ) {
        
        $this->main = $plugin_main;
        
    }
    
    
    
    public function banned_ips_cron_ab_stats2db()
    {
        global $wpdb;
        $options = get_option('banned-ips', array());
        
        include_once "ConnectAbuseIPDB.php";
        
        $connectab = new ConnectAbuseIPDB($options['ab_account_id']);
        $attacks = $connectab->get_Attacks();
        
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // fail2ban_stats
        $table = $wpdb->prefix . "banned_ips_abuseipdb";
        
        $sql = "INSERT INTO $table (
    				time,
    				attacks
        			)
    			VALUES (
    				now(),
    				$attacks)
    				";
        // echo $sql;
        
        dbDelta($sql);
    }
}
?>
