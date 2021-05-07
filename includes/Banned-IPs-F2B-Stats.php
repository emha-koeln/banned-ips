<?php
/**
 * Banned-IPs-F2B-Stats.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Get local fail2ban Stats
 *
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Get local fail2ban Stats
 *
 * This class gets local fail2ban Stats.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
if (! defined ( 'ABSPATH' )) {
    exit ();
}
// SQLite Fail2Ban DB
class f2bDB extends SQLite3
{

    function __construct($_myDB)
    {
        $this->open($_myDB);
    }
}
        
class Banned_IPs_F2B_Stats {
    
    public function banned_ips_cron_f2b_stats2db()
    {
        global $wpdb;
        $options = get_option('banned-ips', array());
    
        
        // Open Fail2Ban DB
        $db = new f2bDB($options['db']);
        if (! $db) {
            echo $db->lastErrorMsg();
        } else {
            // echo "Open database successfully\n";
            // echo "<br>\n";
        }
        
        $sql = "SELECT COUNT(*) as count FROM bips";
        $ret = $db->query($sql);
        $row = $ret->fetchArray(SQLITE3_ASSOC);
        $bad = $row['count'];
        
        $sql = "SELECT COUNT(*) as count FROM bips WHERE 1 = 1 AND (timeofban + bantime > " . time() . " OR bantime <= -1)";
        $ret = $db->query($sql);
        $row = $ret->fetchArray(SQLITE3_ASSOC);
        $count = $row['count'];
        
        require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
        
        // fail2ban_stats
        $table = $wpdb->prefix . "banned_ips_f2bstats";
        
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
        
        dbDelta($sql);
    }
}
?>
