<?php

/**
 * draw_graph.php
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
if (! defined ( 'ABSPATH' )) {
    exit ();
}

function bannedips_cron_f2b_graph()
{
    global $Bips;
    
    include $Bips->PATH_CLS . "/CoordImg.php";
    
    _draw_image( $Bips->PATH_IMG . "/f2b_graph_1.png", "-1 hours");
    _draw_image( $Bips->PATH_IMG . "/f2b_graph_24.png", "-24 hours");
    _draw_image( $Bips->PATH_IMG . "/f2b_graph_week.png", "-7 days");
    _draw_image( $Bips->PATH_IMG . "/f2b_graph_month.png", "-30 days");
    _draw_image( $Bips->PATH_IMG . "/f2b_graph_year.png", "-365 days");
    _draw_image( $Bips->PATH_IMG . "/f2b_graph_all.png", "*");
    
    _draw_t_image( $Bips->PATH_IMG . "/t_f2b_graph_1.png", "-1 hours");
    _draw_t_image( $Bips->PATH_IMG . "/t_f2b_graph_24.png", "-24 hours");
    _draw_t_image( $Bips->PATH_IMG . "/t_f2b_graph_week.png", "-7 days");
    _draw_t_image( $Bips->PATH_IMG . "/t_f2b_graph_month.png", "-30 days");
    _draw_t_image( $Bips->PATH_IMG . "/t_f2b_graph_year.png", "-365 days");
    _draw_t_image( $Bips->PATH_IMG . "/t_f2b_graph_all.png", "*");
}

function _draw_image($imgfile, $period)
{
    
    global $wpdb;
    $options = get_option('bannedips', array());
    
    $table = $wpdb->prefix . "bannedips_f2bstats";
    
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
    
    ob_start();
    // imagepng($t_img);
    imagepng($img->get_Image());
    $t_data = ob_get_clean();
    file_put_contents('t_' . $imgfile, $t_data);
}

function _draw_t_image($imgfile, $period)
{
    
    global $wpdb;
    $options = get_option('bannedips', array());
    
    $table = $wpdb->prefix . "bannedips_f2bstats";
    
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

?>
