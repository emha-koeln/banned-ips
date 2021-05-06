<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://example.com
 * @since      0.3.0
 *
 * @package    Banned_IPs
 * @subpackage Banned_IPs/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Banned_IPs
 * @subpackage Banned_IPs/admin
 * @author     emha.koeln
 */
class Banned_IPs_Admin {

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
	 * @since    20180622
	 * @var object      The main class.
	 */
	public $main;
	
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    0.3.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $plugin_main ) {

	    $this->main = $plugin_main;
		$this->plugin_name = $plugin_name;
		$this->version = $version;

        $this->set_defaults();
	}

	/**
	 * Register the administration menu for this plugin into the WordPress Dashboard menu.
	 *
	 * @since    0.3.0
	 */
	public function add_plugin_admin_menu() {
	    
	    /**
	     * Add a settings page for this plugin to the Settings menu.
	     *
	     * NOTE:  Alternative menu locations are available via WordPress administration menu functions.
	     *
	     *        Administration Menus: http://codex.wordpress.org/Administration_Menus
	     *
	     * add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function);
	     *
	     * @link https://codex.wordpress.org/Function_Reference/add_options_page
	     *
	     * If you want to list plugin options page under a custom post type, then change 'plugin.php' to e.g. 'edit.php?post_type=your_custom_post_type'
	     */
	    //add_submenu_page( 'plugins.php', 'Plugin settings page title', 'Admin area menu slug', 'manage_options', $this->plugin_name, array($this, 'display_plugin_setup_page' ) );
	    add_options_page('Banned IPs', 'Banned IPs', 'administrator', $this->plugin_name, array($this, 'display_plugin_setup_page' ) );
	}
	
	/**
	 * Add settings action link to the plugins page.
	 *
	 * @since    0.3.0
	 */
	public function add_action_links( $links ) {
	    
	    /**
	     * Documentation : https://codex.wordpress.org/Plugin_API/Filter_Reference/plugin_action_links_(plugin_file_name)
	     * The "plugins.php" must match with the previously added add_submenu_page first option.
	     * For custom post type you have to change 'plugins.php?page=' to 'edit.php?post_type=your_custom_post_type&page='
	     */
	    $settings_link = array( '<a href="' . admin_url( 'plugins.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );
	    
	    // -- OR --
	    
	    // $settings_link = array( '<a href="' . admin_url( 'options-general.php?page=' . $this->plugin_name ) . '">' . __( 'Settings', $this->plugin_name ) . '</a>', );
	    
	    return array_merge(  $settings_link, $links );
	    
	}
	
	/**
	 * Render the settings page for this plugin.
	 *
	 * @since    1.0.0
	 */
	public function display_plugin_setup_page() {
	    
	    include_once( 'partials/' . $this->plugin_name . '-Admin-Display.php' );
	    
	}
	
	/**
	 * Validate fields from admin area plugin settings form ('exopite-lazy-load-xt-admin-display.php')
	 * @param  mixed $input as field form settings form
	 * @return mixed as validated fields
	 */
	public function validate($input) {
	    
	    // $valid = array();
	    
	    // $valid['example_checkbox'] = ( isset( $input['example_checkbox'] ) && ! empty( $input['example_checkbox'] ) ) ? 1 : 0;
	    // $valid['example_text'] = ( isset( $input['example_text'] ) && ! empty( $input['example_text'] ) ) ? esc_attr( $input['example_text'] ) : 'default';
	    // $example_textarea['example_textarea'] = ( isset( $input['example_textarea'] ) && ! empty( $input['example_textarea'] ) ) ? sanitize_textarea_field( $input['example_textarea'] ) : 'default';
	    // $valid['example_select'] = ( isset($input['example_select'] ) && ! empty( $input['example_select'] ) ) ? esc_attr($input['example_select']) : 1;
	    
	    // return $valid;
	    
	    // -- OR --
	    
	    $options = get_option( $this->plugin_name );
	    
	    $options['example_checkbox'] = ( isset( $input['example_checkbox'] ) && ! empty( $input['example_checkbox'] ) ) ? 1 : 0;
	    $options['example_text'] = ( isset( $input['example_text'] ) && ! empty( $input['example_text'] ) ) ? esc_attr( $input['example_text'] ) : 'default';
	    $options['example_textarea'] = ( isset( $input['example_textarea'] ) && ! empty( $input['example_textarea'] ) ) ? sanitize_textarea_field( $input['example_textarea'] ) : 'default';
	    $options['example_select'] = ( isset($input['example_select'] ) && ! empty( $input['example_select'] ) ) ? esc_attr($input['example_select']) : 1;
	    
	    return $options;
	    
	}
	
	public function options_update() {
	    
	    register_setting( $this->plugin_name, $this->plugin_name, array(
	        'sanitize_callback' => array( $this, 'validate' ),
	    ) );
	    
	}
	
	
	public function set_defaults(){
	    $options = get_option( $this->plugin_name );
	    // default fail2ban DB, 'autodetect'/select DB
	    if (! isset($options['db']) || $options['db'] == "" || ! file_exists($options['db'])) {
	        if (PHP_OS == "Linux") {
	            $options['db_autodetect'] = "/var/lib/fail2ban/fail2ban.sqlite3";
	            if (! file_exists($options['db_autodetect'])) {
	                $options['db_autodetect'] = "Error: autodetect failed; Fail2Ban DB is not set!";
	            }
	        } elseif (PHP_OS == "FreeBSD") {
	            $options['db_autodetect'] = "/var/db/fail2ban/fail2ban.sqlite3";
	            if (! file_exists($options['db_autodetect'])) {
	                $options['db_autodetect'] = "Error: autodetect failed; Fail2Ban DB is not set!";
	            }
	        } else {
	            $options['db_autodetect'] = "Error: autodetect failed; Fail2Ban DB is not set!";
	        }
	        if (! isset($options['db']) || $options['db'] == "") {
	            $options['db'] = $options['db_autodetect'];
	            update_option('banned-ips', $options);
	        } else {
	            // $options ['db'] = $options ['db_autodetect'];
	            update_option('banned-ips', $options);
	        }
	    }
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/plugin-name-admin.js', array( 'jquery' ), $this->version, false );

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
	    $recepients = 'root@localhost';
	    $subject = 'Hello from your Tenminutes Cron Job';
	    $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    mail ( $recepients, $subject, $message );
	}
	// Hourly
	public function banned_ips_hourly_cronjob()
	{
	    // test
	    $recepients = 'root@localhost';
	    $subject = 'Hello from your Hourly Cron Job';
	    $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    mail ( $recepients, $subject, $message );
	}
	// Daily
	public function banned_ips_daily_cronjob()
	{
	    // test
	    $recepients = 'root@localhost';
	    $subject = 'Hello from your Daily Cron Job';
	    $message = 'This is a test mail sent by bannedips automatically as per your schedule.';
	    // let's send it
	    mail ( $recepients, $subject, $message );
	}

}
