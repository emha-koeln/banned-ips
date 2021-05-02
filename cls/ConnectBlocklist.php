<?php

/**
 * ConnectAbuseIPDB.php
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

class ConnectBlocklist
{

    var $bl_serverid;

    var $bl_apikey;

    /**
     * Get User Statistics from Blocklist.de
     *
     * @param string $ServerID
     * @param string $APIKey
     */
    public function __construct(string $ServerID, string $APIKey)
    {
        $this->bl_serverid = $ServerID;
        $this->bl_apikey = $APIKey;
    }

    /**
     *
     * @return mixed|number[]
     */
    public function get_Attacks()
    {
        $result_fail = array(
            'attacks' => 0,
            'reports' => 0
        );
        $url = "http://api.blocklist.de/api.php?server=" . $this->bl_serverid . "&apikey=" . $this->bl_apikey . "&start=1&format=php";
        echo $url . "<br>";
        
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
}