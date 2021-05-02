<?php
/**
 * Plugin Name: Banned IPs
 * Plugin URI: https://emha.koeln/banned-ips-plugin
 * Description: Display blocked IPs by fail2ban as Stats, Table or Grap. WP-Shortcode, WP-Widget or Standalone 
 * Version: 0.1.5.alpha16
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

// i18n
add_action('plugins_loaded', 'banned_ips_localization_init');

function banned_ips_localization_init()
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
    public $PATH_ETC;
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
    public $LOGLEVEL;
    public $LOGFILE;

    public function __construct()
    {
        $this->options = get_option('bannedips', []); // WP Options
                                                          
        // Paths
        $this->PATH = plugin_dir_path(__FILE__); // local Path
        $this->PATH_CLS = $this->PATH . 'cls/'; // local Paths
        $this->PATH_ETC = $this->PATH . 'etc/';
        $this->PATH_IMG = $this->PATH . 'img/';
        $this->PATH_SCR = $this->PATH . 'scr/';
        $this->PATH_SYS = $this->PATH . 'sys/';
        
        // URLs
        $this->URL = plugin_dir_url(__FILE__); // our URL
        //$this->URL_CLS = $this->URL . 'cls/'; 
        $this->URL_IMG = $this->URL . 'img/';
        $this->URL_SCR = $this->URL . 'scr/';
        $this->URL_SYS = $this->URL . 'sys/';
        
        //$this->LOGLEVEL = 'NONE'; 
        $this->LOGFILE = $this->PATH . 'bips.log';
        
        $this->log( __FUNCTION__ . PHP_EOL . $this->PATH_CLS, 'NOTICE');
        
        foreach ($this->options as $key => $value){
            
            $this->log("option['" . $key . "'] => '" . $value . "'",'DEBUG');
           // $this->log($option.get_ID() . '=> ');
            
        }
    }

    // DEBUG LOG - Developing...
    /**
     *
     * @param mixed $log
     * @return boolean
     */
    public function log($log, $loglevel = 'NONE')
    {
        if ( $this->LOGLEVEL !== 'NONE' ) {
            
            if (! file_exists($this->LOGFILE)) {
                $logfile = fopen($this->LOGFILE, "w");
            } else {
                $logfile = fopen($this->LOGFILE, "a");
            }
            
            if ($loglevel == 'NOTICE'
                && ( $this->LOGLEVEL == 'NOTICE' 
                    || $this->LOGLEVEL == 'INFO'
                    || $this->LOGLEVEL == 'DEBUG')){
                        fwrite($logfile, $loglevel . ' ' . date('Y:m:d H:i:s',time()) . ' ' . $log . PHP_EOL);
                        fclose($logfile);
            }elseif ($loglevel == 'INFO'
                && ( $this->LOGLEVEL == 'INFO'
                    || $this->LOGLEVEL == 'DEBUG')){
                        fwrite($logfile, $loglevel . ' ' . date('Y:m:d H:i:s',time()) . ' ' . $log . PHP_EOL);
                        fclose($logfile);
            }elseif ($loglevel == 'DEBUG'){
                        fwrite($logfile, $loglevel . ' ' . date('Y:m:d H:i:s',time()) . ' ' . $log . PHP_EOL);
                        fclose($logfile);
            }
            
            return True;
        } else {
            
            return False;
        }
    }
    
    public function set_loglevel( $loglevel ){
        
        $this->LOGLEVEL = strtoupper($loglevel);
        $this->log('loglevel changed to ' . $loglevel, 'NOTICE');
    }
    
    public function get_logs(){
        
        
        $return = '';
        
        if ($this->LOGLEVEL == 'DEBUG'){
            $return = readfile($this->LOGFILE);
        }else{
            $logs = fopen($this->LOGFILE,"r");
            while(! feof($logs))  {
                $result = fgets($logs);
                if ($this->LOGLEVEL == 'NOTICE'){
                    if ( stristr($result, $this->LOGLEVEL)){
                        $return .= $result;
                    }
                }elseif ($this->LOGLEVEL == 'INFO'){
                    if ( stristr($result, $this->LOGLEVEL)
                        || stristr( $result, 'NOTICE') ){
                        $return .= $result;
                    }
                }
            }
        }
        fclose($fn);
        
        
        return $return;
    }

    private function _return( $var ){
        $this->log($var, 'NOTICE');
        return $var;
        
    }
    
    // testing/learnig...
    public function tools_get_site_url()
    {
        $site_url = site_url();
        return $this->_return($site_url);
    }

    public function tools_get_current_url()
    {
        global $wp;
        $current_url = home_url(add_query_arg(array(), $wp->request));
        return $this->_return($current_url);
    }

    public function tools_get_current_slug()
    {
        global $wp;
        $current_slug = add_query_arg(array(), $wp->request);
        return $this->_return($current_slug);
    }

    public function tools_get_current_id()
    {
        $current_id = get_the_ID();
        return $this->_return($current_id);
    }

    public function tools_get_current_page()
    {
        $current_page = get_page_link();
        return $this->_return($current_page);
    }

    public function tools_get_current_post()
    {
        $current_post = get_post($this->tools_get_current_id());
        return $this->_return($current_post);
    }
}

$bips = new Bips();


// WP Plugin activation
include_once ( $bips->PATH_SYS . "activation.php");
include_once ( $bips->PATH_SYS . "cron.php");

register_activation_hook( __FILE__, 'banned_ips_register_activation');
function banned_ips_register_activation()
{
    banned_ips_activate_create_db();
    banned_ips_activate_cronjobs(); // ?
}

// WP Plugin deactivation
include_once ( $bips->PATH_SYS . "deactivation.php");

register_deactivation_hook( __FILE__, 'banned_ips_register_deactivation');
function banned_ips_register_deactivation()
{
    banned_ips_deactivate_cronjobs();
}

// WP Admin
if (is_admin()) { 
    require_once ( $bips->PATH . 'admin/admin.php');
}

// Shortcode
include_once ( $bips->PATH . 'cls/Banned_IPs_Shortcode.php');
$bips_shortcode = new Banned_IPs_Shortcode();

// Widget
include_once ( $bips->PATH . 'cls/Banned_IPs_Widget.php');
$bips_widget = new Banned_IPs_Widget();





