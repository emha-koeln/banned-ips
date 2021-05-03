<?php
/**
 * Banned_IPs.php
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

// Exit if accessed directly

// (B)annd(I)(P)(s) class
class Banned_IPs
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
    private $LOGLEVELS = array(
        'NONE' => 0,
        'NOTICE' => 1,
        'INFO' => 2,
        'DEBUG' => 3
    );

    public function __construct( $plugindir, $pluginurl )
    {
        $this->options = get_option('bannedips', []); // WP Options
                                                          
        // Paths
        $this->PATH = $plugindir; // local Path
        $this->PATH_CLS = $this->PATH . 'cls/'; // local Paths
        $this->PATH_ETC = $this->PATH . 'etc/';
        $this->PATH_IMG = $this->PATH . 'img/';
        $this->PATH_SCR = $this->PATH . 'scr/';
        $this->PATH_SYS = $this->PATH . 'sys/';
        
        // URLs
        $this->URL = $pluginurl; // our URL
        //$this->URL_CLS = $this->URL . 'cls/'; 
        $this->URL_IMG = $this->URL . 'img/';
        $this->URL_SCR = $this->URL . 'scr/';
        //$this->URL_SYS = $this->URL . 'sys/';
        
        //$this->LOGLEVEL = 'NONE'; 
        $this->LOGFILE = $this->PATH . 'bips.log';
        
        $this->log( "logging started");
        $this->log( 'PATH_CLS: '. $this->PATH_CLS, 'NOTICE');
        
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
    public function log($log, $loglevel = 'NOTICE')
    {
        //if ( $this->LOGLEVEL !== 'NONE' ) {
            
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
        //} else {
            
        //    return False;
        //}
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
            fclose($logs);
        }
        
        
        
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


