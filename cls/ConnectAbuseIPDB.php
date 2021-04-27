<?php
/**
 * ConnectAbuseIPDB.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
class ConnectAbuseIPDB {
	var $ab_userid;
	
	/**
	 * Get User Statistics from AbuseIPDB.com
	 *
	 * @param string $UserID
	 */
	public function __construct(string $UserID) {
		$this->ab_userid = $UserID;
	}
	/**
	 *
	 * @return mixed|number
	 */
	public function get_Attacks() {
		$url = "https://www.abuseipdb.com/user/" . $this->ab_userid;
		// $url = "https://www.abuseipdb.com/user/53720" ;
		$result_fail = 0;
		$curl = curl_init ();
		$timeout = 5;
		
		curl_setopt ( $curl, CURLOPT_URL, $url );
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
}