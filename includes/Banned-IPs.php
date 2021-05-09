<?php

/**
 * The core Banned-IPs class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      0.3.0
 * @package    banned-ips
 * @subpackage banned-ips/includes
 * @author     Your Name <email@example.com>
 */
class Banned_IPs {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      Banned_IPs_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * f2b-stats
	 */
	public $f2b_stats;
	
	/**
	 * f2b-graph
	 */
	public $f2b_graph;
	
	/**
	 * ab-stats
	 */
	public $ab_stats;
	
	/**
	 * bl-stats
	 */
	public $bl_stats;
	
	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	
	/**
	 * The current path of the plugin.
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	public $path;
	
	
	/**
	 * The current url of the plugin.
	 *
	 * @since    0.3.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	public $url;
	
	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    0.3.0
	 */
	public function __construct( $path, $url) {
		if ( defined( 'BANNED_IPS_VERSION' ) ) {
		    $this->version = BANNED_IPS_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'Banned-IPs';

		$this->path = $path;
		$this->url = $url;
		
		//$this->main = $this;
		
		$this->load_dependencies();
		$this->set_locale();
		$this->define_hooks();
		$this->define_admin_hooks();
		$this->define_public_hooks();
		
		$this->load_classes();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
	 * - Plugin_Name_i18n. Defines internationalization functionality.
	 * - Plugin_Name_Admin. Defines all hooks for the admin area.
	 * - Plugin_Name_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-Loader.php';
        
        
		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-I18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/Banned-IPs-Admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/Banned-IPs-Public.php';

		$this->loader = new Banned_IPs_Loader();
		
		//require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-Classes.php';
		//$this->classes = New Banned_IPs_Classes();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Plugin_Name_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function set_locale() {

	    $plugin_i18n = new Banned_IPs_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}
	/**
	 * Register all of the hooks related to the plugin functionality.
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function define_hooks(){

	    // New Cron Schedules
	    $this->loader->add_filter( 'cron_schedules', $this, 'cron_add_minute');
	    $this->loader->add_filter( 'cron_schedules', $this, 'cron_add_tenminutes');

	    
	    $this->loader->add_action( 'banned_ips_hook_everyminute_cronjob', $this, 'banned_ips_everyminute_cronjob' );
	    $this->loader->add_action( 'banned_ips_hook_tenminutes_cronjob', $this, 'banned_ips_tenminutes_cronjob' );
	    $this->loader->add_action( 'banned_ips_hook_hourly_cronjob', $this, 'banned_ips_hourly_cronjob' );
	    $this->loader->add_action( 'banned_ips_hook_daily_cronjob', $this, 'banned_ips_daily_cronjob' );
	    
	}
	
	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function define_admin_hooks() {

	    $plugin_admin = new Banned_IPs_Admin( $this->get_plugin_name(), $this->get_version(), $this );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		// Save/Update our plugin options
		$this->loader->add_action( 'admin_init', $plugin_admin, 'options_update' );
		
		// Add menu item
		$this->loader->add_action( 'admin_menu', $plugin_admin, 'add_plugin_admin_menu' );
		
		// Add Settings link to the plugin
		//$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$plugin_basename = plugin_basename( plugin_dir_path( __DIR__ ) . $this->plugin_name . '.php' );
		$this->loader->add_filter( 'plugin_action_links_' . $plugin_basename, $plugin_admin, 'add_action_links' );
        
		/*
		$this->loader->add_action( 'banned_ips_hook_everyminute_cronjob', $plugin_admin, 'banned_ips_everyminute_cronjob' );
		$this->loader->add_action( 'banned_ips_hook_tenminutes_cronjob', $plugin_admin, 'banned_ips_tenminutes_cronjob' );
		$this->loader->add_action( 'banned_ips_hook_hourly_cronjob', $plugin_admin, 'banned_ips_hourly_cronjob' );
		$this->loader->add_action( 'banned_ips_hook_daily_cronjob', $plugin_admin, 'banned_ips_daily_cronjob' );
		*/
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function define_public_hooks() {

	    $plugin_public = new Banned_IPs_Public( $this->get_plugin_name(), $this->get_version(), $this );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );
		
        /*
		$this->loader->add_action( 'banned_ips_hook_everyminute_cronjob', $plugin_public, 'banned_ips_everyminute_cronjob' );
		$this->loader->add_action( 'banned_ips_hook_tenminutes_cronjob', $plugin_public, 'banned_ips_tenminutes_cronjob' );
		$this->loader->add_action( 'banned_ips_hook_hourly_cronjob', $plugin_public, 'banned_ips_hourly_cronjob' );
		$this->loader->add_action( 'banned_ips_hook_daily_cronjob', $plugin_public, 'banned_ips_daily_cronjob' );
		*/
		
	}
	
	/**
	 * Load related Classes
	 *
	 * @since    0.3.0
	 * @access   private
	 */
	private function load_classes() {
	    
	    
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-F2B-Stats.php';
	    $this->f2b_stats = New Banned_IPs_F2B_Stats();
	    
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-F2B-Graph.php';
	    $this->f2b_graph = New Banned_IPs_F2B_Graph($this);
	    
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-AB-Stats.php';
	    $this->ab_stats = New Banned_IPs_AB_Stats($this);
	    
	    require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/Banned-IPs-BL-Stats.php';
	    $this->bl_stats = New Banned_IPs_BL_Stats($this);
	    
	    //$this->f2b_stats->banned_ips_cron_f2b_stats2db();
	    
	    
	}
	
	
	
	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    0.3.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     0.3.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     0.3.0
	 * @return    Banned_IPs_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     0.3.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Add new wp cron schedules
	 *
	 * @param $var $schedules
	 * @return $var
	 */
	function cron_add_minute($schedules)
	{
	    // Adds once every minute to the existing schedules.
	    $schedules['everyminute'] = array(
	        'interval' => 60,
	        'display' => __('Once Every Minute')
	    );
	    return $schedules;
	}
	
	function cron_add_tenminutes($schedules)
	{
	    // Adds once every minute to the existing schedules.
	    $schedules['tenminutes'] = array(
	        'interval' => 600,
	        'display' => __('Every Ten Minutes')
	    );
	    return $schedules;
	}
	
	
	/**
	 * Cron jobs
	 */
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
	
	/**
	 * Functions to be called by cron job hooks
	 */
	// Everyminute
	public function banned_ips_everyminute_cronjob()
	{

	    $this->f2b_stats->banned_ips_cron_f2b_stats2db();
	    $this->f2b_graph->banned_ips_cron_f2b_graph();
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

	    $this->ab_stats->banned_ips_cron_ab_stats2db();
	    $this->bl_stats->banned_ips_cron_bl_stats2db();
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
	
	/**
	 * Testing
	 */
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
