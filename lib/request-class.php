<?php

class Request {

	var $request;
	var $request_method;
	var $get;
	var $post;
	
	var $params;
	var $uri;
	
	
	/**
	 * The request takes the requested URL and converts
	 * it to a form understandable by the framework.
	 * module name
	 * action name
	 * pattern
	 */
	var $module;
	var $action;
	var $pattern;
	
	/**
	 * Constructor
	 *
	 */
	function Request()
	{

		$this->request	= $_REQUEST;

		//$this->files $_FILES
		//$this->session $_SESSION
		//$this->server $_SERVER
		// as necessary
		// it might be nice to have the charset
		
		/**
		 * Support more than one url strategy.
		 * This allows you to specify either a default
		 * fallback strategy of using normal URL parameters
		 * with the module and method in the action parameter
		 * and the parameters as normal.
		 * Example: index.php?action=links/edit/&id=1
		 * This should always work.
		 */
		 
	if( $this->clean_url )
	{	
		/**
		 * Use a clean url strategy.
		 */
		 
		 log_err( __FILE__, __LINE__, "<p>Processing PATH INFO scheme</p>");
		
		$this->source = $_SERVER['PATH_INFO'];
		$this->_get_params();
		
	} else {
	
		/**
		 * The source for regular urls is
		 * action=module/method
		 * GET and POST params
		 */
		
		log_err( __FILE__, __LINE__, "Info: Processing action scheme");
		
		// by reference?
		$this->params = $_REQUEST;

		$this->source = $this->params['action'];
		//$this->source = $_REQUEST['action'];


		$pieces = preg_split('/\//', $this->source, -1, PREG_SPLIT_NO_EMPTY);

		
/**
 * Detect home page request.
 * I should be able to invoke the action for this
 * without a corresponding request and without
 * the action being on the action list.
 */
 

		if( empty($pieces[0]) || empty($pieces[1]) ) {
		// debug
		//print "No action specified";
			$this->params['action'] = 'home/home';
			$this->source = $this->params['action'];
			$_REQUEST['action'] = 'home/home';
			
	 		$this->module = 'home';
			$this->action = 'home';
		 } else {
	 		$this->module = $pieces[0];
			$this->action = $pieces[1];
		}
		
	}
				

	}
	
	
	/**
	 * Private Methods
	 */
	 

	function _get_params() {
	
	/**

	Some code from sitepoint, note how it skips the zeroeth and there are others
	that skip empty array elements, others strip leading and trailing slash (see CGI::Pathinfo).
	
	$array = explode('/',substr($_SERVER['PATH_INFO'],1));
foreach ($array as $key=>$value)
{
	if($key % 2 == 0) $_GET[$value] = next($array);
}

*/
		// remove the first two slash separated parts from the url
		$param_list = explode('/', $this->source);
		// remove zereoth array element and throw it away
		
		
		//$tmp = print_r( $param_list );
		//log_err( __FILE__, __LINE__, $tmp);

		array_shift( $param_list);
		$this->module = array_shift( $param_list);
		$this->method = array_shift( $param_list);
		
		
		//$tmp = print_r( $param_list );
		//log_err( __FILE__, __LINE__, $tmp);

		// now, get the params
		foreach ( $param_list as $pair ) {
		   list($key,$value) = split(',',$pair,2);
		   $this->params[$key] = stripslashes($value);
		}
		
//$tmp = "<pre>
//Module:";
//$tmp .= print_r( $this->module );
//$tmp .= "\nMethod:
//";
//$tmp .= print_r( $this->method );
//$tmp .= print_r( $this->params );
//$tmp .= "\n";
//$tmp .= "</pre>";
//log_err( __FILE__, __LINE__, $tmp);

	}
	
	/**
	 * Accessors
	 */
	 
	/**
	 * /remark
	 * An alternative would be to just wrap the super globals.
	 * Like this: 
	 * function get_post() {
	 *	return $_POST;
	 * }
	 * But then you can't access them as a property.
	 */
	 
	function get_post() {
		return $this->request;
	}
	
	function get_get() {
		return $_GET;
	}
	
	function get_request() {
		return $_REQUEST;
	}

	function get_pieces() {
        return $this->pieces;
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
		
			/*
		
		At some point in the front controller, we have to store the request in the context, I don't know if it is best to handle things here or in the controller. The parsing of the queyr string could be here or in the controller, except it must be here because we need to find out which module to load?
		*/

}
?>