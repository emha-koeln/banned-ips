<?php
/**
 * banned.php
 * Part of banned-ips, also for standalone use
 * v 0.6.2021.04.08
 * (v 0.1.5.alpha)
 * (c) 2021 emha.koeln
 * License: GPLv2+
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */

include_once 'SqliteDB.php';

// $my*, defaults are not used if part of banned-ips plugin
$myDB                   = "";   // set fail2ban DB, leave empty for auto detection
$myLANG                 = "";       // set to "de" for german output

$myAB_LINKS             = 1;    // show abuseipdb link      
$myBL_LINKS             = 1;    // show blocklist link
$myShowTargetURL        = "";

//Accounts
$myAB_STATS             = 0;    //show AbuseIPDB stats      default: 0
$myAB_ACCOUNT_ID        = "";   //AbuseIPDB Number          default: ""
    

$myBL_STATS             = 0;    //show BlockList stats      default: 0
$myBL_ACCOUNT_SERVERID  = "";   //Blocklist ServerID        default: ""
$myBL_ACCOUNT_APIKEY    = "";   //Blocklist APIKey          awdefault: ""

//$options = array();
/*
echo 'attrs:<br />';
var_dump($attrs);
echo 'options:<br />'; 
var_dump($options);
*/
//echo '<br>';

// check if we are part of wp-plugin banned-ips
if (is_null($options)) { // not part of banned-ips plugin, use default $my*
    //echo 'New options array, no WP';
    
    $options = array( // used in standalone mode?
        'db'                    => $myDB,
        'lang'                  => $myLANG,
        'ab_links'              => $myAB_LINKS,
        'bl_links'              => $myBL_LINKS,
        'ab_stats'              => $myAB_STATS,
        'ab_account_id'         => $myAB_ACCOUNT_ID,
        'bl_stats'              => $myBL_STATS,
        'bl_account_serverid'   => $myBL_ACCOUNT_SERVERID,
        'bl_account_apikey'     => $myBL_ACCOUNT_APIKEY     
    );
    
   // $options = [0,1];
} else { // we are part of banned-ips plugin; set $my*
         // db
    //echo 'Use attrs or options, WP';     
    
    //if (isset($attrs['db'])) {
    //    $myDB = $attrs['db'];
    //} elseif (isset($options['db'])) {
        $myDB = $options['db'];
    //}
    // Lang
    //if (isset($attrs['lang'])) {
    //    $myLANG = $attrs['lang'];
    //} elseif (isset($options['lang'])) {
        $myLANG = $options['lang'];
    //    $myLANG = "de";
    //}
    // Link_AB
    //if (isset($attrs['ab_links'])) {
    //    $myAB_LINKS = $attrs['ab_links'];
    //} elseif (isset($options['ab_links'])) {
        $myAB_LINKS = $options['ab_links'];
    //}
    // Link_BL
    //if (isset($attrs['bl_links'])) {
    //    $myBL_LINKS = $attrs['bl_links'];
    //} elseif (isset($options['bl_links'])) {
        $myBL_LINKS = $options['bl_links'];
    //}
}
/*
echo '<br>';
echo 'attrs:<br />';
var_dump($attrs);
echo 'options:<br />';
var_dump($options);
*/


// myDB
if ($myDB == "") {
    if (PHP_OS == "Linux") {
        $myDB = "/var/lib/fail2ban/fail2ban.sqlite3"; // Fail2Ban DB on Linux
    } elseif (PHP_OS == "FreeBSD") {
        $myDB = "/var/db/fail2ban/fail2ban.sqlite3"; // Fail2Ban Db on FreeBSD
    } else {
        echo "Fail2Ban DB not set!";
    }
}
/*
echo '<br>DB autodetect<br>';
echo 'attrs:<br />';
var_dump($attrs);
echo 'options:<br />';
var_dump($options);
*/

// Open Fail2Ban DB
$db = new SqliteDB($myDB);
if (! $db) {
    echo $db->lastErrorMsg();
} else {
    // echo "Open database successfully\n";
    // echo "<br>\n";
}

// myLang
if ($myLANG == "de"
    || $myLANG) {
    $aLang = [
        "Hello" => "Hallo",
        "BadIPS" => "Vorgemerkte IPs:",
        "CurrentlyBanned" => "Zur Zeit gesperrt:",
        "OrderedBy" => "sortiert nach",
        "HideBannedURL" => "Gesperrte URLs verbergen (schneller)",
        "ShowBannedURL" => "Gesperrte URLs anzeigen (langsam)",
        "LinkWarning" => "Seien Sie gewarnt, diese Seiten zu besuchen!",
        "LastSeen" => "Datum",
        "BannedIP" => "IP",
        "Bans" => "Anzahl",
        "ViewOn" => "IP auf",
        "Asc" => "Aufsteigend",
        "Desc" => "Absteigend"
    ];
} else {
    $aLang = [
        "Hello" => "Hello",
        "BadIPS" => "Marked as bad IP:",
        "CurrentlyBanned" => "Currently banned:",
        "OrderedBy" => "orderd by",
        "HideBannedURL" => "Hide banned URLs (faster)",
        "ShowBannedURL" => "Show banned URLs (slow)",
        "LinkWarning" => "Be careful when visiting these sites! You have been warned!",
        "LastSeen" => "Last Seen",
        "BannedIP" => "IP",
        "Bans" => "Bans",
        "ViewOn" => "IP on",
        "Asc" => "Ascending",
        "Desc" => "Descending"
    ];
}


// Stats from abuseipdb und/or blocklist

if (! isset($options['sys_cron'])
    && ($options['ab_stats'] 
        || $options['bl_stats'])){
    
    ob_start();
    echo "<table>";
    if (isset($options['ab_stats'])
        && ( $options['ab_stats'] == '1'
            ||  strtolower( $options['ab_stats']) == 'true' )) {
                
                echo "<td>";
                //include ( $this->main->path . 'scr/' . "abuseipdb_stats.php");
                include_once "abuseipdb_stats.php";
                echo "</td>";
    }
            
    if (isset($options['bl_stats'])
        && ( $options['bl_stats'] == '1'
            ||  strtolower( $options['bl_stats']) == 'true' )) {
                
                echo "<td>";
                //include ( $this->main->path . 'scr/' . "blocklist_stats.php");
                include_once  "blocklist_stats.php";
                echo "</td>";
    }
    echo "</table>";

    ob_flush();
    echo '<br>';
}

// Count BadIPs
$sql = "SELECT COUNT(*) as count FROM bips";
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);

echo '<div>';
echo $aLang["BadIPS"] . " " . $row['count'] . " IPs<br>";
echo '</div>';

// COUNT
$sql = "SELECT COUNT(*) as count FROM bips WHERE 1 = 1 AND (timeofban + bantime > " . time() . " OR bantime <= -1)";
$ret = $db->query($sql);
$row = $ret->fetchArray(SQLITE3_ASSOC);
echo '<div>';
echo $aLang["CurrentlyBanned"] . " " . $row['count'] . " IPs<br>";
echo '</div>';

// Ordered by
if (isset($_GET["orderby"])) {
    if ($_GET["orderby"] == "IP") {
        $myOrderby = "ip";
    } elseif ($_GET["orderby"] == "Last") {
        $myOrderby = "timeofban";
    } elseif ($_GET["orderby"] == "Bans") {
        $myOrderby = "bancount";
    }
} else {
    $_GET["orderby"] = "Last";
    $myOrderby = "timeofban";
}

// Scby
if (isset($_GET["scby"])) {
    if ($_GET["scby"] == "Asc") {
        $myScby = "ASC";
    } elseif ($_GET["scby"] == "Desc") {
        $myScby = "DESC";
    }
} else {
    $_GET["scby"] = "Desc";
    $myScby = "DESC";
}


// echo with or without banned URL
if (isset ( $_GET ["showTargetSite"] ) 
    && ($_GET["showTargetSite"] == "Yes") ){
        $myShowTargetURL = "Yes";
        echo '<div>';
        echo $aLang["LinkWarning"] . "<br>";
        // echo "<a href=\"" . $myURL . "/banned-ips/?orderby=". $_GET["orderby"] . "&scby=". $_GET["scby"]. "&showTargetSite=\">" . $aLang["HideBannedURL"] . "</a>";
        
        echo "<a href=\"?orderby=" . $_GET["orderby"] . "&scby=" . $_GET["scby"] . "&showTargetSite=\">" . $aLang["HideBannedURL"] . "</a>";
        echo '</div>';
    } 
else {
    $myShowTargetURL = "";
    // echo "<a href=\"" . $myURL . "/banned-ips/?orderby=". $_GET["orderby"] . "&scby=". $_GET["scby"]. "&showTargetSite=Yes\">" . $aLang["ShowBannedURL"]. "</a>";
    echo '<div>';
    echo "<a href=\"?orderby=" . $_GET["orderby"] . "&scby=" . $_GET["scby"] . "&showTargetSite=Yes\">" . $aLang["ShowBannedURL"] . "</a>";
    echo '</div>';
}


// sql statement
$sql = "SELECT ip, timeofban, bancount, data FROM bips WHERE 1 = 1 AND (timeofban + bantime > " . time() . " OR bantime <= -1) ORDER BY " . $myOrderby . " " . $myScby;
$ret = $db->query($sql);

// table head
echo "<table style='font-size:80%' >\n";
echo "<tr lign='left' >\n";

// Last Seen
if ($_GET["orderby"] == "Last") {
    if ($_GET["scby"] == "Asc") {
        echo "<th style=\"min-width:125px\">" . $aLang['LastSeen'] . " <a href=\"?orderby=Last&scby=Desc&showTargetSite=" . $myShowTargetURL . "\">&darr; </a></th>";
    } else {
        echo "<th style=\"min-width:125px\">" . $aLang['LastSeen'] . " <a href=\"?orderby=Last&scby=Asc&showTargetSite=" . $myShowTargetURL . "\">&uarr; </a></th>";
    }
} else {
    echo "<th style=\"min-width:125px\">" . $aLang['LastSeen'] . " <a href=\"?orderby=Last&scby=Desc&showTargetSite=" . $myShowTargetURL . "\">&darr;&uarr;</a></th>";
}

// IPs
if ($_GET["orderby"] == "IP") {
    if ($_GET["scby"] == "Asc") {
        // echo "<th >Banned IP <a href=\"" . $myURL . "/banned-ips/?orderby=IP&scby=Desc&showTargetSite=". $myShowTargetURL . "\">&darr; </a></th>";
        echo "<th style=\"max-width:160px\">" . $aLang['BannedIP'] . " <a href=\"?orderby=IP&scby=Desc&showTargetSite=" . $myShowTargetURL . "\">&darr;</a></th>";
    } else {
        // echo "<th >Banned IP <a href=\"" . $myURL . "/banned-ips/?orderby=IP&scby=Asc&showTargetSite=". $myShowTargetURL . "\">&uarr; </a></th>";
        echo "<th style=\"max-width:160px\">" . $aLang['BannedIP'] . " <a href=\"?orderby=IP&scby=Asc&showTargetSite=" . $myShowTargetURL . "\">&uarr;</a></th>";
    }
} else {
    echo "<th style=\"max-width:160px\">" . $aLang['BannedIP'] . " <a href=\"?orderby=IP&scby=Desc&showTargetSite=" . $myShowTargetURL . "\">&darr;&uarr;</a></th>";
}

// Bans
if ($_GET["orderby"] == "Bans") {
    if ($_GET["scby"] == "Asc") {
        echo "<th style=\"min-width:98px\">" . $aLang['Bans'] . " <a href=\"?orderby=Bans&scby=Desc&showTargetSite=" . $myShowTargetURL . "\">&darr; </a></th>";
    } else {
        echo "<th style=\"min-width:98px\">" . $aLang['Bans'] . " <a href=\"?orderby=Bans&scby=Asc&showTargetSite=" . $myShowTargetURL . "\">&uarr; </a></th>";
    }
} else {
    echo "<th style=\"min-width:98px\">" . $aLang['Bans'] . " <a href=\"?orderby=Bans&scby=Desc&showTargetSite=" . $myShowTargetURL . "\">&darr;&uarr;</a></th>";
}

// abuseipdb
if ( strtolower($myAB_LINKS) == 'true'
    || strtolower($myAB_LINKS) == 1) {
    echo "<th style=\"min-width:155px\">abuseipdb.com </th>";
}
// blocklist
if ( strtolower($myBL_LINKS) == 'true'
    || strtolower($myBL_LINKS) == 1) {
    echo "<th style=\"min-width:150px\">blocklist.de </th>";
}
echo "</tr>\n";

// table rows
while ($row = $ret->fetchArray(SQLITE3_ASSOC)) {
    
    echo "<tr align='middle'>\n";
    
    // Last Seen
    echo "<td>\n";
    echo date("d.M.Y", $row['timeofban']);
    echo "</td>";
    
    // IPs
    echo "<td>\n";
    echo $row['ip'] . "<br>\n";
    if ($myShowTargetURL == "Yes") {
        $targetURL = gethostbyaddr($row['ip']);
        echo "<a href=\"http://" . $targetURL . "\" target=\"_blank\">" . $targetURL . "</a>";
    }
    echo "</td>";
    
    // Bans
    echo "<td>\n";
    echo $row['bancount'];
    echo "</td>";
    
    // if ($options ['link_ab']) {
    if ( strtolower($myAB_LINKS) == 'true'
        || strtolower($myAB_LINKS) == 1) {
        // abuseipdb
        if ($_GET["showTargetSite"] == "Yes") {
            echo "<td>\n";
            echo "<a href=\"https://www.abuseipdb.com/check/" . $row['ip'] . "\" target=\"_blank\">" . $aLang['ViewOn'] . "<br>AbuseIPDB </a>";
            echo "</td>";
        } else {
            echo "<td>\n";
            echo "<a href=\"https://www.abuseipdb.com/check/" . $row['ip'] . "\" target=\"_blank\">" . $aLang['ViewOn'] . " AbuseIPDB </a>";
            echo "</td>";
        }
    }
    // if ($options ['link_bl']) {
    if ( strtolower($myBL_LINKS) == 'true'
        || strtolower($myBL_LINKS) == 1){
        // blocklist
        if ($_GET["showTargetSite"] == "Yes") {
            echo "<td>";
            echo "<a href=\"https://www.blocklist.de/en/view.html?ip=" . $row['ip'] . "\" target=\"_blank\">" . $aLang['ViewOn'] . "<br> BlockList </a>";
            echo "</td>\n";
        } else {
            echo "<td>";
            echo "<a href=\"https://www.blocklist.de/en/view.html?ip=" . $row['ip'] . "\" target=\"_blank\">" . $aLang['ViewOn'] . " BlockList </a>";
            echo "</td>\n";
        }
    }
    echo "</tr>\n";
}
// DB close
$db->close();

echo "</table>\n";

?>
