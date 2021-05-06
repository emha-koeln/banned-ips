<?php
/**
 * Banned-IPs-Draw-Graph.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Create the Banned-IPs Graph for the Shortcode
 *
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Create the Banned-IPs Graph for the Shortcode
 *
 * This class creates the Banned-IPs Graph for the Shortcode.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */

if (! defined ( 'ABSPATH' )) {
    exit ();
}

//global $plugin;

//include $plugin->path . 'includes/' . "CoordImg.php";

class Banned_IPs_Draw_Graph {
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
    
    public function draw_graph(){
    
    global $wpdb;
    $options = get_option('banned-ips', array());
    
    require_once (ABSPATH . 'wp-admin/includes/upgrade.php');
    
    // fail2ban_stats
    $table = $wpdb->prefix . "banned_ips_f2bstats";
    
    // set default
    if (! isset($options['graph_time'])) {
        $options['graph_time'] = "last seven days";
    }
    
    // time period
    $searchtime = "*";
    if ($options['graph_time'] == "last hour") {
        $d = strtotime("-1 hours");
        $searchtime = date("Y-m-d H:i:s", $d);
        // $results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= '. );
    } elseif ($options['graph_time'] == "last 24 hours") {
        $d = strtotime("-24 hours");
        $searchtime = date("Y-m-d H:i:s", $d);
        // $results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= '. );
    } elseif ($options['graph_time'] == "last seven days") {
        $d = strtotime("-7 days");
        $searchtime = date("Y-m-d H:i:s", $d);
        // $results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= '. );
    } elseif ($options['graph_time'] == "last thirty days") {
        $d = strtotime("-30 days");
        $searchtime = date("Y-m-d H:i:s", $d);
        // $results = $wpdb->get_results ( 'SELECT * FROM ' . $table );
    } else {
        // $results = $wpdb->get_results ( 'SELECT * FROM ' . $table );
    }
    
    // SQL results
    $results = $wpdb->get_results('SELECT * FROM ' . $table . ' WHERE time >= \'' . $searchtime . '\'');
    $result_maxbans = $wpdb->get_results('SELECT MAX(bans) as maxbans FROM ' . $table . ' WHERE time >= \'' . $searchtime . '\'');
    
    // Buid Array
    $aCoordImg = array();
    // array_push($aCoordImg, 1,2,3,4,5,6,7,8,9,10);
    foreach ($results as $result) {
        $aCoordImg[$result->time] = $result->bans;
    }
    
    // Save Image
    
    $imgfile = $this->main->path . 'img/' . "CoordImg.png";
    $mapfile = $this->main->path . 'img/' . "CoordImg.map";
    // $im2 = new CoordImg ( 600, 400, $aCoordImg, 0, 0 );
    include_once $this->main->path . 'includes/' . "CoordImg.php";
    $img = new CoordImg($options['graph_width'], $options['graph_height'], $aCoordImg);
    
    list ($r, $g, $b) = sscanf($options['graph_color_bg_hex'], "#%02x%02x%02x");
    $img->set_BgColor($r, $g, $b);
    
    list ($r, $g, $b) = sscanf($options['graph_color_graph_hex'], "#%02x%02x%02x");
    $img->set_GraphColor($r, $g, $b);
    
    // $img->set_Debug(TRUE);
    
    ob_start();
    ImageJPEG($img->get_Image());
    imagepng($img->get_Image());
    $data = ob_get_clean();
    file_put_contents($imgfile, $data);
    
    ob_start();
    echo $img->get_HtmlMap();
    $data = ob_get_clean();
    file_put_contents($mapfile, $data);
    
    
    $imgurl = $this->main->url . 'img/' . "CoordImg.png";
    echo '<img width="" hight="" src="' . $imgurl . '" alt="Fail2Ban Stats" title="Fail2Ban Stats" usemap="#Map" >';
    include $this->main->path . 'img/' . "CoordImg.map";
    echo '<br />';

    }
}
?>
