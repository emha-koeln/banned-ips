<?php

/**
 * Register all actions and filters for the plugin
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 */

/**
 * Register all actions and filters for the plugin.
 *
 * Maintain a list of all hooks that are registered throughout
 * the plugin, and register them with the WordPress API. Call the
 * run function to execute the list of actions and filters.
 *
 * @package    Plugin_Name
 * @subpackage Plugin_Name/includes
 * @author     Your Name <email@example.com>
 */
class Banned_IPs_Classes {

	/**
	 * The array of actions registered with WordPress.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      array    $actions    The actions registered with WordPress to fire when the plugin loads.
	 */
	protected $classes;

	/**
	 * Initialize the collections used to maintain the actions and filters.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->classes = array();

	}

	/**
	 * Add a new action to the collection to be registered with WordPress.
	 *
	 * @since    1.0.0
	 * @param    string               $name            The name of the WordPress action that is being registered.
	 * @param    object               $class        A reference to the instance of the object on which the action is defined.
*/
	public function add_class( $name, $class) {
	    $this->classes = $this->add( $this->classes, $name, $class);
	}

	
	/**
	 * A utility function that is used to register the actions and hooks into a single
	 * collection.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @param    array                $classes            The collection of hooks that is being registered (that is, actions or filters).
	 * @param    string               $name             The name of the WordPress filter that is being registered.
	 * @param    object               $class        A reference to the instance of the object on which the filter is defined.
     * @return   array                                  The collection of actions and filters registered with WordPress.
	 */
	private function add( $classes, $name, $class ) {

		$classes[] = array(
		    $name     => $class
		   	    
		);

		return $classes;

	}



}
