<?php
/**
 * draw_graph.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
include BIPS_CLS . "/CoordImg.php";

global $wpdb;
$options = get_option ( 'bannedips', array () );

require_once (ABSPATH . 'wp-admin/includes/upgrade.php');

// fail2ban_stats
$table = $wpdb->prefix . "bannedips_f2bstats";

// set default
if (! isset ( $options ['graph_time'] )) {
	$options ['graph_time'] = "last seven days";
}

// time period
$searchtime = "*";
if ($options ['graph_time'] == "last hour") {
	$d = strtotime ( "-1 hours" );
	$searchtime = date ( "Y-m-d H:i:s", $d );
	// $results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= '. );
} elseif ($options ['graph_time'] == "last 24 hours") {
	$d = strtotime ( "-24 hours" );
	$searchtime = date ( "Y-m-d H:i:s", $d );
	// $results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= '. );
} elseif ($options ['graph_time'] == "last seven days") {
	$d = strtotime ( "-7 days" );
	$searchtime = date ( "Y-m-d H:i:s", $d );
	// $results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= '. );
} elseif ($options ['graph_time'] == "last thirty days") {
	$d = strtotime ( "-30 days" );
	$searchtime = date ( "Y-m-d H:i:s", $d );
	// $results = $wpdb->get_results ( 'SELECT * FROM ' . $table );
} else {
	// $results = $wpdb->get_results ( 'SELECT * FROM ' . $table );
}

// SQL results
$results = $wpdb->get_results ( 'SELECT * FROM ' . $table . ' WHERE time >= \'' . $searchtime . '\'' );
$result_maxbans = $wpdb->get_results ( 'SELECT MAX(bans) as maxbans FROM ' . $table . ' WHERE time >= \'' . $searchtime . '\'' );

// Buid Array
$aCoordImg = array ();
// array_push($aCoordImg, 1,2,3,4,5,6,7,8,9,10);
foreach ( $results as $result ) {
	$aCoordImg [$result->time] = $result->bans;
}

// Save Image
// $imgfile = BIPS_IMG . "/CoordImg.jpg";
$imgfile = BIPS_IMG . "/CoordImg.png";
$mapfile = BIPS_IMG . "/CoordImg.map";
// $im2 = new CoordImg ( 600, 400, $aCoordImg, 0, 0 );
$img = new CoordImg ( $options ['graph_width'], $options ['graph_height'], $aCoordImg );


//if ($options ['graph_color_bg'] == "White") {
// 	$img->set_BgColor ( 255, 255, 255 );
// } elseif ($options ['graph_color_bg'] == "Grey") {
// 	$img->set_BgColor ( 224, 224, 224 );
// } elseif ($options ['graph_color_bg'] == "Black") {
// 	$img->set_BgColor ( 0, 0, 0 );
// }
list($r, $g, $b) = sscanf($options ['graph_color_bg_hex'], "#%02x%02x%02x");
$img->set_BgColor ( $r, $g, $b );

// if ($options ['graph_color_graph'] == "Red") {
// 	$img->set_GraphColor ( 255, 0, 0 );
// } elseif ($options ['graph_color_graph'] == "Green") {
// 	$img->set_GraphColor ( 0, 255, 0 );
// } elseif ($options ['graph_color_graph'] == "Blue") {
// 	$img->set_GraphColor ( 28, 124, 124 );
// }
list($r, $g, $b) = sscanf($options ['graph_color_graph_hex'], "#%02x%02x%02x");
$img->set_GraphColor ( $r, $g, $b );

// $img->set_Debug(TRUE);

ob_start ();
ImageJPEG ( $img->get_Image () );
imagepng ( $img->get_Image () );
$data = ob_get_clean ();
file_put_contents ( $imgfile, $data );

ob_start ();
echo $img->get_HtmlMap ();
$data = ob_get_clean ();
file_put_contents ( $mapfile, $data );

// $imgurl = BIPS_DIR_URL . "img/CoordImg.jpg";
$imgurl = BIPS_DIR_URL . "img/CoordImg.png";
echo '<img width="" hight="" src="' . $imgurl . '" alt="Fail2Ban Stats" title="Fail2Ban Stats" usemap="#Map" >';
include (BIPS_IMG . "/CoordImg.map");
echo '<br />';
?>
