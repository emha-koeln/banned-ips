<?php
/**
 * Banned-IPs-Shortcode.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Public Shortcode
 *
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Public Shortcode
 *
 * This class is the Public Shortcode.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
// defines the functionality for the location shortcode
class Banned_IPs_Shortcode
{
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
        
        add_action('init', array(                       // shortcodes
            $this,
            'register_shortcodes'
        )); 
        
        $this->main = $plugin_main;
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
        //global $Bips;
        
        // build default arguments
        // $arguments = shortcode_atts(array(
        // 'location_id' => '',
        // 'number_of_locations' => -1)
        // ,$atts,$tag);
        
        // uses the main output function of the location class
        // $html = $wp_simple_locations->get_locations_output($arguments);
        
        $options = get_option( $this->main->get_plugin_name() );
        
        $file = $this->main->path . 'scr/' . "banned.php";
        
        // Use SQL with conjob for WP
        if (isset($options['sys_cron']) && (isset($options['ab_stats']) || isset($options['bl_stats']))) {
            
            global $wpdb;
            
            ob_start();
            echo "<table>";
            if (isset($options['ab_stats'])) {
                $table = $wpdb->prefix . "banned_ips_abuseipdb";
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
                $table = $wpdb->prefix . "banned_ips_blocklist";
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
                include ( $this->main->path . 'scr/' . "abuseipdb_stats.php");
                echo "</td>";
            }
            
            if (isset($options['bl_stats'])) {
                echo "<td>";
                include ( $this->main->path . 'scr/' . "blocklist_stats.php");
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
            include ( $this->main->path . 'includes/' . "Banned-IPs-Draw-Graph.php");
            $draw = New Banned_IPs_Draw_Graph( $this->main);
            $draw->draw_graph();
            echo '</div>';
            ob_flush();
        }
        
        
        // TODO: Load as Frontpage
        if ($this->main->tools_get_current_slug() == '') {

            function callback($buffer)
            {
                global $Bips;
                //get_the_ID()
                return (str_replace("?orderby", "?page_id=" . get_the_ID() . "&?orderby", $buffer));
                
                //return (str_replace("?orderby", "?page_id=" . $Bips->tools_get_current_id() . "&?orderby", $buffer));
                
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
