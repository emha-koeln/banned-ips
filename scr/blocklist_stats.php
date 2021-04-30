<?php
/**
 * blocklist_stats.php
 * Part of banned-ips
 * v 0.1.5.alpha
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */

// $options = get_option ( 'bannedips', array () );
$mySERVERID = ""; // blocklist server id
$myAPIKEY = ""; // blocklist apikey
                
// if (isset... for WP use
$mySERVERID = $options['bl_account_serverid'];
$myAPIKEY = $options['bl_account_apikey'];

$result = bannedips_get_bl_stats($mySERVERID, $myAPIKEY);

$attacks = $result['attacks'];
$reports = $result['reports'];

// echo "<div style='font-size:80%'>\n";
echo "<b>Blocklist stats:</b><br>";
echo "Attacks: " . $attacks . ", ";
echo "Reports: " . $reports . "<br>";
;

// echo "</div>";
function bannedips_get_bl_stats($SERVERID, $APIKEY)
{
    $result_fail = array(
        'attacks' => 0,
        'reports' => 0
    );
    $url = "http://api.blocklist.de/api.php?server=" . $SERVERID . "&apikey=" . $APIKEY . "&start=1&format=php";
    
    // echo $url."<br>";
    
    // Don't use unserialize...
    // TODO: Don't use unserialize
    $result = file_get_contents($url);
    if (! strpos($result, "error")) {
        $result = unserialize(file_get_contents($url));
        return $result;
    } else {
        return $result_fail;
    }
}

?>