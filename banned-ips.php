<?php
/**
 * Plugin Name: Banned IPs
 * Plugin URI: https://emha.koeln/banned-ips-plugin
 * Description: Display blocked IPs by fail2ban as Stats, Table or Grap. WP-Shortcode, WP-Widget or Standalone 
 * Version: 0.1.5.alpha15
 * Requires at least: 5.7
 * Requires PHP: 7.2+
 * License: GPLv2 or later
 * Text Domain: banned-ips
 * Domain Path: /languages
 * Author: emha.koeln
 * Author URI: https://emha.koeln
 */
/**
 *
 * @package banned-ips
 * @author emha.koeln
 */
/**
 * banned-ips is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * It is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy (license.txt) of the GNU General Public License
 * along with banned-ips. If not, see https://emha.koeln/wp-content/uploads/2021/04/gpl-2.0.txt.
 */

// Exit if accessed directly

if (! defined ( 'ABSPATH' )) {
	exit ();
}

// Define
define ( 'BIPS_VERSION', '0.1.5.alpha' );

// most define deprecated as of OOP ?
define ( 'BIPS_PATH', rtrim ( plugin_dir_path ( __file__ ), "/" ) ); // local path

define ( 'BIPS_SYS', BIPS_PATH . "/sys" ); // bips system           
define ( 'BIPS_ETC', BIPS_PATH . "/etc" ); // bips config
define ( 'BIPS_SCR', BIPS_PATH . "/scr" ); // bips scripts
define ( 'BIPS_IMG', BIPS_PATH . "/img" ); // bips images
define ( 'BIPS_CLS', BIPS_PATH . "/cls" ); // bips classes

define ( 'BIPS_DIR_URL', plugin_dir_url ( __file__ ) ); // local url
define ( 'BIPS_DIR_NAME', str_replace ( "/banned-ips.php", "", plugin_basename ( __FILE__ ) ) ); // plugin dir name


// Define
define('BIPS_VERSION', '0.1.5.alpha');

// most define deprecated as of OOP ?
define('BIPS_PATH', rtrim(plugin_dir_path(__file__), "/")); // local path

define('BIPS_SYS', BIPS_PATH . "/sys"); // bips system
define('BIPS_ETC', BIPS_PATH . "/etc"); // bips config
define('BIPS_SCR', BIPS_PATH . "/scr"); // bips scripts
define('BIPS_IMG', BIPS_PATH . "/img"); // bips images
define('BIPS_CLS', BIPS_PATH . "/cls"); // bips classes

define('BIPS_DIR_URL', plugin_dir_url(__file__)); // local url
define('BIPS_DIR_NAME', str_replace("/banned-ips.php", "", plugin_basename(__FILE__))); // plugin dir name
                                                                                                 
// include sys
include_once (BIPS_SYS . "/activation.php");
include_once (BIPS_SYS . "/deactivation.php");
include_once (BIPS_SYS . "/cron.php");

// WP Plugin activation
register_activation_hook(__FILE__, 'bannedips_register_activation');

function bannedips_register_activation()
{
    bannedips_activate_create_db();
    bannedips_activate_cronjobs(); // ?
}

// WP Plugin deactivation
register_deactivation_hook(__FILE__, 'bannedips_register_deactivation');

function bannedips_register_deactivation()
{
    bannedips_deactivate_cronjobs();
}

// My Text Domain
/*
 * add_filter( 'load_textdomain_mofile', 'bannedips_load_my_own_textdomain', 10, 2 );
 * function bannedips_load_my_own_textdomain( $mofile, $domain ) {
 * if ( 'banned-ips' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
 * $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
 * $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/languages/' . $domain . '-' . $locale . '.mo';
 * }
 * return $mofile;
 * }
 */
add_action('plugins_loaded', 'bannedips_localization_init');

function bannedips_localization_init()
{
    load_plugin_textdomain('banned-ips', false, dirname(plugin_basename(__FILE__)) . '/languages/');
}

// (B)annd(I)(P)(s) class
class Bips
{

    public $options;

    // Path
    public $PATH;
    public $PATH_CLS;
    public $PATH_IMG;
    public $PATH_SCR;
    public $PATH_SYS;

    // URLs
    public $URL;
    public $URL_CLS;
    public $URL_IMG;
    public $URL_SCR;
    public $URL_SYS;

    // Debug
    public $DEBUG;
    public $LOGFILE;

    public function __construct()
    {
        $this->options = get_option('bannedips', []); // WP Options
                                                          
        // Paths
        $this->PATH = plugin_dir_path(__FILE__); // local Path
        $this->PATH_CLS = $this->PATH . 'cls/'; // local Paths
        $this->PATH_IMG = $this->PATH . 'img/';
        $this->PATH_SCR = $this->PATH . 'scr/';
        $this->PATH_SYS = $this->PATH . 'sys/';
        
        // URLs
        $this->URL = plugin_dir_url(__FILE__); // our URL
        $this->URL_CLS = $this->URL . 'cls/'; // URL_Paths
        $this->URL_IMG = $this->URL . 'img/';
        $this->URL_SCR = $this->URL . 'scr/';
        $this->URL_SYS = $this->URL . 'sys/';
        
        $this->DEBUG = TRUE; // TRUE on alpha, beta and theta
        $this->LOGFILE = $this->PATH . 'bips.log';
    }

    // DEBUG LOG - Developing...
    /**
     *
     * @param mixed $log
     * @return boolean
     */
    public function log($log)
    {
        if ($this->DEBUG) {
            
            if (! file_exists($this->LOGFILE)) {
                $logfile = fopen($this->LOGFILE, "w");
            } else {
                $logfile = fopen($this->LOGFILE, "a");
            }
            
            fwrite($logfile, time() . ' ' . $log . PHP_EOL);
            fclose($logfile);
            
            return True;
        } else {
            
            return False;
        }
    }

    // testing/learnig...
    public function tools_get_site_url()
    {
        $site_url = site_url();
        return $site_url;
    }

    public function tools_get_current_url()
    {
        global $wp;
        $current_url = home_url(add_query_arg(array(), $wp->request));
        return $current_url;
    }

    public function tools_get_current_slug()
    {
        global $wp;
        $current_slug = add_query_arg(array(), $wp->request);
        return $current_slug;
    }

    public function tools_get_current_id()
    {
        $current_id = get_the_ID();
        return $current_id;
    }

    public function tools_get_current_page()
    {
        $current_page = get_page_link();
        return $current_page;
    }

    public function tools_get_current_post()
    {
        $current_post = get_post($this->tools_get_current_id());
        return $current_post;
    }
}

// WP Shortcode
if (is_admin()) {
    include BIPS_PATH . '/admin/admin.php';
}

$bips = new Bips();

// Widget
include_once ($bips->PATH_CLS . '/BannedIPs_Widget.php');
$bips_widget = new BannedIPs_Widget();

// Shortcode
include_once ($bips->PATH_CLS . '/BannedIPs_Shortcode.php');
$bips_shortcode = new BannedIPs_Sortcode();



