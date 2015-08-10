<?php


/**
 * Front Controller.
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

Steps:

	1. Determine the name of the module and method from the request.
	2. Load the class files required by the module.
	3. Build a list of actions available for the module.
	4. Instantiate the controller for the module.
	5. Execute the action on the module.
	
	The last two actually consist of creating an action object and assigning a reference to the context object, so the context contains the action and the controller has access to it.

  WAIT!
  
  Isn't the job of the fc to get the request and _choose_ an object mathing the request from the _list of actions_? The _request_ should not be represented as an _action_. The fc should not be _building_ an action!

*/

class FrontController {

    // {{{ properties
	
    /**
     * List of actions. 
	 * @var array
     */
	 
	var $action_list;
	
	/**
     * Current action.
     * @var object
     */
	var $action;
	
	// }}}
			
	/**
	 * Constructor
	 *
	 */
 
	function FrontController() {
	global $c; // cheat
	
	
	}
 
 
/**
 * 
 * private
 */

 // only for single loaded module
	function _start() {
	
	
		/**
		 * Use request to compare against action list
		 * to see if any actions match the request.
		 */
		
		//print "<p>Request Module: ". $c->request->get_module() .'</p>';
		//print "<p>Request Method: ". $c->request->get_method() .'</p>';
		
		/**
		 * Does the requested action match an existing action?
		 */
		
		if( ! $this->is_valid_action() ) {
			die("Error - Action uknown");
		}
		
		 
		/*
		
		If there is no action or actions defined for this module, then
		the fc must create a default action, or get one from the framework
		config.
		
		// setup default action is there is none		
		if( empty( $this->action ) ) {
			$action = new Action;
			$action->pattern = 'home/home'; // FIXME: change to set prop method
			$action->method = 'Home'; // FIXME: change to set prop method
			$action->class_name = 'Home'; // FIXME: change to set prop method
			$action->auth = 0; // FIXME: change to set prop method
			//$action->invoke();
		}
				print '<pre>';
		print_r( $this->action );
		print '</pre>';
	*/
		
	
		/**
		 * The front controller is responsible for loading the module.
		 */	
		$this->_start();
	
	
	
/**
 * It is the responsiblity of the front controller to find out
 * from the module if a particular method url pattern or method
 * requires authentication.
 */
 
// add auth state to context
//$c->require_auth = $auth_state;

// if auth required
if( $action->auth ) {
	if ( ! $a->checkAuth()) {
	
	/**
	 * redirect to login page
	 * and redirect to original requested page
	 * support is required.
	 * $location = "index.php?action=$module/$method";
	 *	header("Location: $location");
	 * I have a forward method, but will avoid it for now.
	 * It would be nice for redirections within the framework
	 * to be defined by their action (module/method) name or
	 * pattern. This is what the forward does.
	 */ 
	
	// needs to pass original page as parameter???
	/**
	I need to get the original requested action or url.
	Where?
	
	 * If the current module is loaded, this //$this->forward( 'list' );
	 * might work.
	 * But why not grab the module and method name?
	*/
	
$location = "http://www.phphelp.com/test/projects/sandbox/mvc/index.php?action=login/login&rebound=$target_module%2F$target_method";
header("Location: $location");
		exit;
	}
}

		/**
		 * The action invokes itself. I don't know if this should be down
		 * in the controller somewhere, or even allow actions to be invoked
		 * from anywhere. Calling invoke on an action instantiates the class
		 * controller and this automatically invokes the method.
		 */
		$action->invoke();
	

			$target_class = ucfirst($action->module); // should this come from the array?
			$target_class .= "Controller";
				
			log_err( __FILE__, __LINE__, "Instantiating Controller for Module: $target_module");
				
			log_err( __FILE__, __LINE__, "Action URL: $target_method Action Method: $v");

		if( empty( $this->action_list) ) {
			die("Error - empty action list");
		}
		
		
		
		
	}

	
function is_valid_action() { 
		/*
		Verify action is on list.
		Really unnecessary to loop thought to verify. Can just check if file exists? What about exploits using the action parameter?
		*/
		if( ! is_array( $this->action_list ) ) {
			die("Critical Error - action list must be an array.");
		}
		
		foreach( $this->action_list as $action ) {
		
			if( $action->method == $this->target_method ) {
				return 1;
			}
			return 0;
		}
}

/*


			$c->current_module = $action->module;
			$c->current_method = $action->method;
					
				$c = new $target_class( $c );
				exit;
				
				*/

	/**
	 * Get action
	 * returns object or false
	 */
	 function get_action() {
	 global $c; // Hack
	 
	 // debug
	//print "<pre>Action List (Front Controller)\n";
	//print_r( $this->action_list );
	//print "</pre>";
		
		// debug
		//print "<p>Request Module: ". $c->request->get_module() .'</p>';
		//print "<p>Request Action: ". $c->request->get_action() .'</p>';
		//print "<p>Request Pattern: ". $c->request->get_pattern() .'</p>';
			
			// hack, we do not support both modes, module/action and pattern
		$target_pattern = $c->request->get_module() .'/'. $c->request->get_action();
	
		// debug
		//print "<p>Target Pattern: $target_pattern</p>";
	
	if( ! is_array( $this->action_list ) ) {
		die("Critical Error - action list must be an array.");
	}
	
		foreach( $this->action_list as $action ) {
		
		// debug
	//print "<pre>LIST ACTION";
	//print_r( $action );
	//print "END</pre>";
		
		// debug
		//print "Action: ". $action->pattern ." Target: ". $target_pattern;
			if( $action->pattern == $target_pattern ) {
				return $action;
			}
		}
		return false;
	 }
	 


	/**
	 * Add action.
	 *
	 */
 
	function add_action( $pattern, $module, $method, $auth ) {
	
	//debug
//	print "<p>Adding action: $pattern, $module, $method, $auth</p>";


		$action = new Action(
			array(
			'pattern'	=> $pattern,
			'module'	=> $module,
			'method'	=> $method,
			'auth'		=> $auth
			)
		);

	
		// this would be used if more than one module is active at
		// the same time in the framework, which is something to
		// consider in the future
		//$this->action_list[$module][] = array( $pattern => $method );
		
		// would &= save anything?
		$this->action_list[] = $action;

		// debug
	//print '<pre style="border: 1px #ccc solid">Action List at Adding Action (Front Controller)';
	//print_r( $this->action_list );
	//print "</pre>";
				
	}
			

	/*
	 * Accessors
	 *
	 */
	 
	function get_action_list() {
		return $this->action_list;
	}
				

} // end class

?>