<?php
/**
 * Banned-IPs-F2B-Graph.php
 * Part of banned-ips
 * v 0.3
 * (c) 2021 emha.koeln
 * License: GPLv2+
 *
 * Create the Banned-IPs Graphs for the Widget
 *
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/includes
 */

/**
 * Create the Banned-IPs Graphs for the Widget
 *
 * This class creates the Banned-IPs Graphs for the Widget.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     emha.koeln
 */
if (! defined ( 'ABSPATH' )) {
    exit ();
}

class Banned_IPs_F2B_Graph {
    
    
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
    
    
    
    public function banned_ips_cron_f2b_graph()
    {
        
        $this->_draw_image( $this->main->path . "img/f2b_graph_1.png", "-1 hours");
        $this->_draw_image( $this->main->path . "img/f2b_graph_24.png", "-24 hours");
        $this->_draw_image( $this->main->path . "img/f2b_graph_week.png", "-7 days");
        $this->_draw_image( $this->main->path . "img/f2b_graph_month.png", "-30 days");
        $this->_draw_image( $this->main->path . "img/f2b_graph_year.png", "-365 days");
        $this->_draw_image( $this->main->path . "img/f2b_graph_all.png", "*");
        
        $this->_draw_t_image( $this->main->path . "img/t_f2b_graph_1.png", "-1 hours");
        $this->_draw_t_image( $this->main->path . "img/t_f2b_graph_24.png", "-24 hours");
        $this->_draw_t_image( $this->main->path . "img/t_f2b_graph_week.png", "-7 days");
        $this->_draw_t_image( $this->main->path . "img/t_f2b_graph_month.png", "-30 days");
        $this->_draw_t_image( $this->main->path . "img/t_f2b_graph_year.png", "-365 days");
        $this->_draw_t_image( $this->main->path . "img/t_f2b_graph_all.png", "*");
    }
    
    function _draw_image($imgfile, $period)
    {
        include_once "CoordImg.php";
        
        global $wpdb;
        $options = get_option('banned-ips', array());
        
        $table = $wpdb->prefix . "banned_ips_f2bstats";
        
        // set period
        $d = strtotime($period);
        $searchtime = date("Y-m-d H:i:s", $d);
        
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
        $img = new CoordImg(600, 300, $aCoordImg);
        
        // Colors
        list ($r, $g, $b) = sscanf($options['graph_color_bg_hex'], "#%02x%02x%02x");
        $img->set_BgColor($r, $g, $b);
        
        list ($r, $g, $b) = sscanf($options['graph_color_graph_hex'], "#%02x%02x%02x");
        $img->set_GraphColor($r, $g, $b);
        
        // $img->set_Debug(TRUE);
        
        ob_start();
        // ImageJPEG ( $img->get_Image () );
        imagepng($img->get_Image());
        $data = ob_get_clean();
        file_put_contents($imgfile, $data);
        
        //ob_start();
        // imagepng($t_img);
        //imagepng($img->get_Image());
        //$t_data = ob_get_clean();
        //file_put_contents( $imgfile, $t_data);
    }
    
    function _draw_t_image($imgfile, $period)
    {
        
        global $wpdb;
        $options = get_option('banned-ips', array());
        
        $table = $wpdb->prefix . "banned_ips_f2bstats";
        
        // set period
        $d = strtotime($period);
        $searchtime = date("Y-m-d H:i:s", $d);
        
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
        $img = new CoordImg(600, 300, $aCoordImg);
        
        // Colors
        list ($r, $g, $b) = sscanf($options['graph_color_bg_hex'], "#%02x%02x%02x");
        $img->set_BgColor($r, $g, $b);
        
        list ($r, $g, $b) = sscanf($options['graph_color_graph_hex'], "#%02x%02x%02x");
        $img->set_GraphColor($r, $g, $b);
        
        // $img->set_Debug(TRUE);
        
        $img->set_transparency();
        
        ob_start();
        // ImageJPEG ( $img->get_Image () );
        imagepng($img->get_Image());
        $data = ob_get_clean();
        file_put_contents($imgfile, $data);
    }
}
?>
