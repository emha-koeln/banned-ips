<?php
/**
 * options.php
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
if (function_exists ( 'load_plugin_textdomain' )) {
	load_plugin_textdomain ( 'banned-ips', false, BIPS_PATH . '/languages' );
}

include_once (BIPS_PATH . '/admin/options_functions.php');

// Save options
if (isset ( $_POST ['_wpnonce'] ) && wp_verify_nonce ( $_POST ['_wpnonce'], 'save' )) {
	if (isset ( $_POST ['save'] )) {
		if (isset ( $_POST ['options'] )) {
			$options = stripslashes_deep ( $_POST ['options'] );
			update_option ( 'bannedips', $options );
		} else {
			update_option ( 'bannedips', array () );
		}
	}
} else {
	$options = get_option ( 'bannedips', array () );
}

// Cron
if (isset ( $options ['sys_cron'] )) {
	bannedips_activate_cronjobs ();
} else {
	bannedips_deactivate_cronjobs ();
}

if (! isset ( $options ['sys_cron_methode'] )) {
	$options ['sys_cron_methode'] = "WordPress Cron";
}

// Graph Colors 
if (! isset ( $options ['graph_color_bg'] )) {
	$options ['graph_color_bg'] = "Grey";
}
//if ( ! ($options ['graph_color_bg']  == "#Hex") &&
//		($options ['graph_color_bg_hex'] == "#ffffff" ||
//		$options ['graph_color_bg_hex'] == "#D3D3D3" ||	
//		$options ['graph_color_bg_hex'] == "#00000" )){
//	;
//}
if( $options ['graph_color_bg']  == "White") {
	$options ['graph_color_bg_hex'] = "#ffffff";
	//update_option('graph_color_bg_hex', "#ffffff");
	update_option ( 'bannedips', $options );
}elseif( $options ['graph_color_bg']  == "Grey") {
	$options ['graph_color_bg_hex'] = "#D3D3D3";
	//update_option('graph_color_bg_hex', "#D3D3D3");
	update_option ( 'bannedips', $options );
}elseif( $options ['graph_color_bg']  == "Black") {
	$options ['graph_color_bg_hex'] = "#000000";
	//update_option('graph_color_bg_hex', "#000000");
	update_option ( 'bannedips', $options );
}else{
	$options ['graph_color_bg'] == "#Hex";
}



if (! isset ( $options ['graph_color_graph'] )) {
	$options ['graph_color_graph'] = "Black";
}

if ( $options ['graph_color_graph'] == "Black") {
	$options ['graph_color_graph_hex'] = "#000000";
	update_option ( 'bannedips', $options );
}elseif ( $options ['graph_color_graph'] == "Red") {
	$options ['graph_color_graph_hex'] = "#FF0000";
	update_option ( 'bannedips', $options );
}elseif ( $options ['graph_color_graph'] == "Green") {
	$options ['graph_color_graph_hex'] = "#00FF00";
	update_option ( 'bannedips', $options );
}elseif ( $options ['graph_color_graph'] == "Blue") {
	$options ['graph_color_graph_hex'] = "#0000FF";
	update_option ( 'bannedips', $options );
}elseif ( $options ['graph_color_graph'] == "White") {
	$options ['graph_color_graph_hex'] = "#FFFFFF";
	update_option ( 'bannedips', $options );
}else{
	$options ['graph_color_graph'] == "#Hex";
}



// Graph Siue
if (! isset ( $options ['graph_width'] )) {
	$options ['graph_width'] = "400";
	update_option ( 'bannedips', $options );
}
if (! isset ( $options ['graph_height'] )) {
	$options ['graph_height'] = "200";
	update_option ( 'bannedips', $options );
}
if (! isset ( $options ['graph_time'] )) {
	$options ['graph_time'] = "last seven days";
	update_option ( 'bannedips', $options );
}

// Accounts
if (! isset ( $options ['ab_account_id'] )) {
	$options ['ab_account_id'] = "";
}
if (! isset ( $options ['bl_account_serverid'] )) {
	$options ['bl_account_serverid'] = "";
}
if (! isset ( $options ['bl_account_apikey'] )) {
	$options ['bl_account_apikey'] = "";
}

// default fail2ban DB, 'autodetect'/select DB
if ( ! isset ( $options ['db'] ) 
        || $options ['db'] == ""
        || ! file_exists ( $options ['db'] )){
	if (PHP_OS == "Linux") {
	    $options ['db_autodetect'] = "/var/lib/fail2ban/fail2ban.sqlite3";
	    if (! file_exists ( $options ['db_autodetect'] )) {
	        $options ['db_autodetect'] = "Error: autodetect failed; Fail2Ban DB is not set!";
		}
	} elseif (PHP_OS == "FreeBSD") {
	    $options ['db_autodetect'] = "/var/db/fail2ban/fail2ban.sqlite3";
	    if (! file_exists ( $options ['db_autodetect'] )) {
	        $options ['db_autodetect'] = "Error: autodetect failed; Fail2Ban DB is not set!";
		}
	} else {
	    $options ['db_autodetect'] = "Error: autodetect failed; Fail2Ban DB is not set!";
	}
	if ( ! isset ( $options ['db'] )
	    || $options ['db'] == ""){
	   $options ['db'] = $options ['db_autodetect'];
	   update_option ( 'bannedips', $options );
	}else{
	   //$options ['db'] = $options ['db_autodetect'];
	   update_option ( 'bannedips', $options );
	}
}



// css
echo "<style>";
echo "include BIPS_PATH.'/admin/admin.css'";
echo "</style>";


echo '<div class="wrap">';

	echo '<h2><b>Banned IPs</b> ';
	_e ( 'Configuration', 'banned-ips' );
	echo '</h2>';
	
	echo '<form action="" method="post">';
        wp_nonce_field('save');
        
        echo '<table class="form-table">';

        bannedips_options_f2bdb();

		bannedips_options_language();

		bannedips_options_links();
		
		bannedips_options_stats();
		
		
		bannedips_options_accounts();
		
		bannedips_options_cron();
		
		bannedips_options_graph();
		
		echo '</table>';
		echo '<p class="submit">';
		echo '<input class="button button-primary" type="submit" name="save" value="';
		_e ( "Save", "banned-ips" );
		echo '">';
		echo '</p>';
			
		echo '	</form>';
	echo '</div>';
?>
