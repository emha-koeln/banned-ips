<?php
/**
 * Banned-IPs-BL-Stats.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Get Blocklist.de Stats
 *
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Get Blocklist.de Stats
 *
 * This class gets the Blocklist.de Stats.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
if (! defined ( 'ABSPATH' )) {
    exit ();
}

//global $Bips;



class Banned_IPs_BL_Stats {

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
    
    public function banned_ips_cron_bl_stats2db()
    {
        global $wpdb;
        $options = get_option('banned-ips', array());
  
        include_once "ConnectBlocklist.php";
        $connectBlocklist = new ConnectBlocklist($options['bl_account_serverid'], $options['bl_account_apikey']);
        $result = $connectBlocklist->get_Attacks();
        
        $attacks = $result['attacks'];
        $reports = $result['reports'];
        
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // fail2ban_stats
        $table = $wpdb->prefix . "banned_ips_blocklist";
        
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
        
        dbDelta($sql);
    }
}
?>
