<?php
/**
 * abuseipdb_stats.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */

// $options = get_option ( 'bannedips', array () );
$myUSER = "";
$myUSER = $options ['ab_account_id'];

$myURL = "https://www.abuseipdb.com/user/" . $myUSER;

$attacks = bannedips_get_ab_stats ( $myURL );
// echo" <div style='font-size:80%'>\n";
echo "<b>AbuseIPDB stats:</b><br>";
// echo "Reports: " . $reports ."<br>";
echo "Attacks: " . $attacks . "<br>";
// echo "</div>";
function bannedips_get_ab_stats($URL) {
	// file_get_result doesn't seem to work here?
	// $result = file_get_contents($cachefile);
	// echo "Reports: ".$result."<br>";;
	// echo "Reports: ".var_dump($result)."<br>";
	
	// use curl
	// echo "curl output:";
	$result_fail = 0;
	$curl = curl_init ();
	$timeout = 5;
	
	curl_setopt ( $curl, CURLOPT_URL, $URL );
	curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 );
	curl_setopt ( $curl, CURLOPT_CONNECTTIMEOUT, $timeout );
	
	// Get URL.
	$curl_result = curl_exec ( $curl );
	
	// Close curl and release resources
	curl_close ( $curl );
	
	// Use only the first 800 chars...
	$result = substr ( strip_tags ( $curl_result, "<b>" ), 1, 800 );
	
	if (strpos ( $result, "reported" )) {
		$PosStart = stripos ( $result, "reported" ) + 9;
		$PosEnd = stripos ( $result, " IP addresses" );
		
		$result = substr ( $result, $PosStart, ($PosEnd - $PosStart) );
		
		// 8,999 to 8999
		$result = str_replace ( ",", "", $result );
		
		return $result;
	} else {
		return $result_fail;
	}
}

?>