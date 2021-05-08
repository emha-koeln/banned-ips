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


class SqliteDB extends SQLite3
{

    function __construct($_myDB)
    {
        $this->open($_myDB);
    }
}

?>
