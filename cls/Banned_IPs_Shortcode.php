<?php
/**
 * BannedIPs_Shortcode.php
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

// defines the functionality for the location shortcode
class Banned_IPs_Shortcode
{

    // on initialize
    public function __construct()
    {
        add_action('init', array(                       // shortcodes
            $this,
            'register_shortcodes'
        )); 
    }

    // location shortcode
    public function register_shortcodes()
    {
        add_shortcode('bannedips', array(
            $this,
            'shortcode_output'
        ));
        add_shortcode('banned-ips', array(
            $this,
            'shortcode_output'
        ));
    }

    // shortcode display
    public function shortcode_output($atts, $content = '', $tag)
    {
        
        // get the global class
        global $bips;
        
        // build default arguments
        // $arguments = shortcode_atts(array(
        // 'location_id' => '',
        // 'number_of_locations' => -1)
        // ,$atts,$tag);
        
        // uses the main output function of the location class
        // $html = $wp_simple_locations->get_locations_output($arguments);
        
        $options = $bips->options;
        $file = $bips->PATH_SCR . "banned.php";
        
        // Use SQL with conjob for WP
        if (isset($options['sys_cron']) && (isset($options['ab_stats']) || isset($options['bl_stats']))) {
            
            global $wpdb;
            
            ob_start();
            echo "<table>";
            if (isset($options['ab_stats'])) {
                $table = $wpdb->prefix . "bannedips_abuseipdb";
                // $result = array( 'attacks' => 0 );
                $result = $wpdb->get_results('SELECT attacks FROM ' . $table . ' ORDER BY ID DESC LIMIT 1');
                // var_dump($result);
                echo "<td>";
                echo "<b>AbuseIPDB Stats:</b><br>";
                if (! isset($result['0']->attacks)) {
                    echo "Attacks: No stats<br>";
                } else {
                    echo "Attacks: " . $result['0']->attacks . "<br>";
                }
                echo "</td>";
            }
            
            if (isset($options['bl_stats'])) {
                $table = $wpdb->prefix . "bannedips_blocklist";
                $result = $wpdb->get_results('SELECT attacks, reports FROM ' . $table . ' ORDER BY ID DESC LIMIT 1');
                echo "<td>";
                echo "<b>Blocklist Stats:</b><br>";
                if (! isset($result['0']->attacks)) {
                    echo "Attacks: No stats, ";
                    echo "Reports: No stats<br>";
                } else {
                    echo "Attacks: " . $result['0']->attacks . ", ";
                    echo "Reports: " . $result['0']->reports . "<br>";
                }
                echo "</td>";
            }
            echo "</table>";
            ob_flush();
            
            // Use scripts
        } else {
            ob_start();
            echo "<table>";
            if (isset($options['ab_stats'])) {
                echo "<td>";
                include ($bips->PATH_SCR . "abuseipdb_stats.php");
                echo "</td>";
            }
            
            if (isset($options['bl_stats'])) {
                echo "<td>";
                include ($bips->PATH_SCR . "blocklist_stats.php");
                echo "</td>";
            }
            echo "</table>";
            ob_flush();
        }
        
        // TODO: Use Theme Colors and Widths
        // echo "Test Colors: " . get_some_textcolor;
        // echo "Test Colors: " . get_some_theme_info;
        
        // create stats graph image
        if (isset($options['show_graph']) && isset($options['sys_cron'])) {
            ob_start();
            echo '<div>';
            include ($bips->PATH_SCR . "draw_graph.php");
            echo '</div>';
            ob_flush();
        }
        
        
        // TODO: Load as Frontpage
        if ($bips->tools_get_current_slug() == '') {

            function callback($buffer)
            {
                global $bips;
                //get_the_ID()
                return (str_replace("?orderby", "?page_id=" . $bips->tools_get_current_id() . "&?orderby", $buffer));
                //return (str_replace("?orderby", "?page_id=" . get_the_ID() . "?orderby", $buffer));
                // return (str_replace("orderby", "uorderby", $buffer));
            }
            
            ob_start('callback');
        } else {
            ob_start();
        }
        
        include ($file);
        ob_end_flush();
        // var_dump($bips);
        $buffer = ob_get_clean();
        return $buffer;
    }
}
