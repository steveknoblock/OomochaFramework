<?php

/**
 * 
 */
class Action
{
	
	/* TODO: Move to Controller/Router
	protected $module;
	protected $action;
	protected $pattern;
	*/

	function __construct()
	{
		
	}

		private function parseRequest() {

		$param_list = explode('/', $this->source);

		// remove zereoth array element 
		array_shift( $param_list );
		// get module name (controller)
		$this->module = array_shift( $param_list );
		// get method name (action)
		$this->method = array_shift( $param_list );

		$this->route = $this->module . DIRECTORY_SEPARATOR . $this->method;

		foreach ( $param_list as $pair ) {
		   list($key, $value) = split(',', $pair, 2);
		   // set parameters
		   $this->params[$key] = stripslashes($value);
		}
	}

}



	
	/**
	 * These enable access to the framework
	 * comprehensible interpretation of the
	 * requested url action.
	 */
	
	function get_module() {
		return $this->module;
	}
	
	function get_action() {
		return $this->action;
	}
	
	function get_pattern() {
		return $this->pattern;
	}
	