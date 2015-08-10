<?php


/**
 * Represents a framework action.
 *
 * PHP Version 4
 *
 * LICENSE: This source file is subject to version 2 of the GNU General
 * Public License available at http://www.gnu.org/licenses/gpl.txt and if
 * you did not recieve a copy of the license or are unable to obtain it
 * through the web, please contact one of the authors.
 *
 * @category   Framework
 * @package    Framework
 * @author     Steve Knoblock <builder@phphelp.com>
 * @copyright  2006 Steve Knoblock
 * @license    http://www.gnu.org/licenses/gpl.txt GNU Public License
 * @version    [cvs id here]
 * @link       http://www.phphelp.com
 */
 
 
/**
 * Represents a framework action.
 * Usage:
 * $action = new Action(
 *	array(
 *		'pattern' => 'links/list',
 *		'module' => 'links',
 *		'method' => 'list',
 *		'auth' => 0
 *  )
 * );
 * $action->invoke();
 */

class Action {

	/**
	 * Properties
	 *
	 */
	 
	var $pattern;
	var $module;
	var $method;
	var $auth;

	
	/**
	 * Constructor
	 *
	 * @param options
	 */
	 
	function Action( $options ) {
		if( is_array( $options ) ) {
			$this->pattern = $options['pattern'];
			$this->module = $options['module'];
			$this->method = $options['method'];
			$this->auth = $options['auth'];
		} else {
			die("Critical Error: Action options must be an array.");
		}
	}

	function invoke() {
	global $c; // Hack
	
	// although technically a class name at
	// this point, I chose to avoid class
	// given it is a PHP special word
		$module = $this->module;
		$method = $this->method;
		// would prefer to do
		//$c->controller = new $module( $method );
		
		
		// Form standard framework class name
		$target_class = ucfirst($this->module);
		$target_class .= "Controller";
		
		// debug
		//print "Target Controller Class: ". $target_class;
		
			log_err( __FILE__, __LINE__, "Instantiating Controller for Module: $target_module");
				
			log_err( __FILE__, __LINE__, "Action URL: $target_method Action Method: $v");
		/*
		 Somehow this does not seem right here. It seems that you would
		 pass the action to the module controller and then it would
		 do $action->invoke().
		*/
		$controller = new $target_class( $c );
	}	
	
	
	/*
	 * Accessors
	 *
	 */
	function get_pattern() {
		return $this->pattern;
	}
		
	function get_module() {
		return $this->module;
	}
	
	function get_method() {
		return $this->method;
	}
	
	function get_auth() {
		return $this->auth;
	}
	
	
	/*
	 * Modifiers
	 *
	 */
		
	function set_pattern( $pattern ) {
		$this->pattern = $pattern;
	}
	
	function set_module( $module ) {
		$this->module = $module;
	}
	
	function set_action( $method ) {
		$this->method = $method;
	}
	
	function set_auth( $auth ) {
		$this->auth = $auth;
	}
	
}

?>