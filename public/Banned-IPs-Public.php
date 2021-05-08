<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.3.0
 *
 * @package    banned-ips
 * @subpackage banned-ips/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    banned-ips
 * @subpackage banned-ips/public
 * @author     emha.koeln
 */
class Banned_IPs_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    0.3.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    0.3.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Store plugin main class to allow public access.
	 *
	 * @since    0.3.0
	 * @var object      The main class.
	 */
	public $main;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.3.0
	 * @param      string    $plugin_name    The name of the plugin.
	 * @param      string    $version        The version of this plugin.
	 * @param      object    $main_plugin    The main_plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_main ) {

	    $this->main = $plugin_main;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

		// Shortcode
		include_once ( $this->main->path . 'includes/Banned-IPs-Shortcode.php');
		$plugin_shortcode = new Banned_IPs_Shortcode( $this->main );
		
		// Widget
		include_once ( $this->main->path . 'includes/Banned-IPs-Widget.php');
		$plugin_widget = new Banned_IPs_Widget( $this->main );
		
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    0.3.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    0.3.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Plugin_Name_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Plugin_Name_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-public.js', array( 'jquery' ), $this->version, false );

	}
	
	/**
	 * Cron jobs
	 */
	/*
	public function activate_wp_cron(){
	    // WP Cron
	    
	    if (! wp_next_scheduled('banned_ips_hook_everyminute_cronjob')) {
	        wp_schedule_event(time(), 'everyminute', 'banned_ips_hook_everyminute_cronjob');
	    }
	    
	    if (! wp_next_scheduled('banned_ips_hook_tenminutes_cronjob')) {
	        wp_schedule_event(time(), 'tenminutes', 'banned_ips_hook_tenminutes_cronjob');
	    }
	    
	    if (! wp_next_scheduled('banned_ips_hook_hourly_cronjob')) {
	        wp_schedule_event(time(), 'hourly', 'banned_ips_hook_hourly_cronjob');
	    }
	    
	    if (! wp_next_scheduled('banned_ips_hook_daily_cronjob')) {
	        wp_schedule_event(time(), 'daily', 'banned_ips_hook_daily_cronjob');
	    }
	    
	}
	public function deactivate_wp_cron(){
	    // WP Cron
	    
	    // WP Cron
	    
	    // everyminute
	    $timestamp = wp_next_scheduled('banned_ips_hook_everyminute_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_everyminute_cronjob');
	    // tenminutes
	    $timestamp = wp_next_scheduled('banned_ips_hook_tenminutes_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_tenminutes_cronjob');
	    // Hourly
	    $timestamp = wp_next_scheduled('banned_ips_hook_hourly_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_hourly_cronjob');
	    // Daily
	    $timestamp = wp_next_scheduled('banned_ips_hook_daily_cronjob');
	    wp_unschedule_event($timestamp, 'banned_ips_hook_daily_cronjob');
	    
	}
	*/
	/**
	 * Functions to be called by cron job hooks
	 */
	/*
	// Everyminute
	public function banned_ips_everyminute_cronjob()
	{
	    //global $Bips;
	    // include_once ( $Bips->PATH_ETC . "/cron/everyminute/f2b_stats.php");
	    //include_once ( $Bips->PATH_ETC . "/cron/everyminute/f2b_graph.php");
	    // TODO automaticly read folder
	    //bannedips_cron_f2b_stats2db();
	    //bannedips_cron_f2b_graph();
	    $this->main->f2b_stats->banned_ips_cron_f2b_stats2db();
	    $this->main->f2b_graph->banned_ips_cron_f2b_graph();
	    // test
	    //$recepients = 'root@localhost';
	    //$subject = 'Hello from your Everyminute Cron Job';
	    //$message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    // mail ( $recepients, $subject, $message );
	    
	    
	}
	// Tenminutes
	public function banned_ips_tenminutes_cronjob()
	{
	    //global $Bips;
	    //include_once ( $Bips->PATH_ETC . "/cron/tenminutes/ab_stats.php");
	    //include_once ( $Bips->PATH_ETC . "/cron/tenminutes/bl_stats.php");
	    
	    // TODO automaticly read folder
	    $this->main->ab_stats->banned_ips_cron_ab_stats2db();
	    $this->main->bl_stats->banned_ips_cron_bl_stats2db();
	    //bannedips_cron_ab_stats2db();
	    //bannedips_cron_bl_stats2db();
	    // test
	    //$recepients = 'root@localhost';
	    //$subject = 'Hello from your Tenminutes Cron Job';
	    // $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    //mail ( $recepients, $subject, $message );
	}
	// Hourly
	public function banned_ips_hourly_cronjob()
	{
	    // test
	    //$recepients = 'root@localhost';
	    //$subject = 'Hello from your Hourly Cron Job';
	    //$message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    //mail ( $recepients, $subject, $message );
	}
	// Daily
	public function banned_ips_daily_cronjob()
	{
	    // test
	    //$recepients = 'root@localhost';
	    //$subject = 'Hello from your Daily Cron Job';
	    //$message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    //mail ( $recepients, $subject, $message );
	}
	
	*/

}
