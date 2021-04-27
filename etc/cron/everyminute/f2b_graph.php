<?php
/**
 * draw_graph.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */

function bannedips_cron_f2b_graph() {
	
	include BIPS_CLS . "/CoordImg.php";
	
	_draw_image(BIPS_IMG . "/f2b_graph_24.png", "-24 hours");
	_draw_image(BIPS_IMG . "/f2b_graph_week.png", "-7 days");
	_draw_image(BIPS_IMG . "/f2b_graph_month.png", "-30 days");
	_draw_image(BIPS_IMG . "/f2b_graph_year.png", "-365 days");
	_draw_image(BIPS_IMG . "/f2b_graph_all.png", "*");
	
}

function _draw_image($imgfile, $period){
	
	//include BIPS_CLS . "/CoordImg.php";
	
	global $wpdb;
	$options = get_option ( 'bannedips', array () );
	
	$table = $wpdb->prefix . "bannedips_f2bstats";
	
	// set period
	$d = strtotime ( $period );
	$searchtime = date ( "Y-m-d H:i:s", $d );
	
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
	//$imgfile = BIPS_IMG . "/f2b_graph_24.png";
	//$mapfile = BIPS_IMG . "/CoordImg.map";
	// $im2 = new CoordImg ( 600, 400, $aCoordImg, 0, 0 );
	//$img = new CoordImg ( $options ['graph_width'], $options ['graph_height'], $aCoordImg );
	$img = new CoordImg ( 600, 400, $aCoordImg );
	
	if ( isset ($options ['graph_color_bg']) ){
		if ($options ['graph_color_bg'] == "White") {
			$img->set_BgColor ( 255, 255, 255 );
		} elseif ($options ['graph_color_bg'] == "Grey") {
			$img->set_BgColor ( 224, 224, 224 );
		} elseif ($options ['graph_color_bg'] == "Black") {
			$img->set_BgColor ( 0, 0, 0 );
		}
	} else {
		$img->set_BgColor ( 255, 255, 255 );
	}
	
	if (isset ($options ['graph_color_graph'])) {
		if ($options ['graph_color_graph'] == "Red") {
			$img->set_GraphColor ( 255, 0, 0 );
		} elseif ($options ['graph_color_graph'] == "Green") {
			$img->set_GraphColor ( 0, 255, 0 );
		} elseif ($options ['graph_color_graph'] == "Blue") {
			$img->set_GraphColor ( 0, 0, 255 );
		}
	}else {
		$img->set_GraphColor ( 255, 0, 0 );
	}
	// $img->set_Debug(TRUE);
	
	ob_start ();
	//ImageJPEG ( $img->get_Image () );
	imagepng ( $img->get_Image () );
	$data = ob_get_clean ();
	file_put_contents ( $imgfile, $data );

}

?>
