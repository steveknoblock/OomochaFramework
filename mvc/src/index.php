<?php

/**********************************************************************
 *                          SweetPear Framework
 *                       Copyright 2006 Steve Knoblock
 *                                GPL License
 **********************************************************************/
  
/**
 * Front Flow Controller
 * Provides single entry point and flow control.
 * @author Steve Knoblock
 * @version 0
 * @date begin: 
 * @date revised: 
 */

//print '<pre>';
//print "POST:\n";
//print_r($_POST);
//print "GET:\n";
//print_r($_GET);
//print '</pre>';

/*
 * /remark
 * self.php contains all configuration that may change
 * according to the installation environment. For example,
 * the location of the configutation file may be outside the
 * public web tree on one installation and within the public
 * web tree on another. The main parts of the platform are
 * required following. It solves the problem of not knowing
 * where the configuration file is located.
 */

require_once 'self.php'; // Boot


/**
 * Require all base classes in the front controller instead
 * of a tangle of includes in each class file.
 COULD MOVE TO SELF
 */

// MVC
require_once LIBRARY_PATH . '/lib/mvc-model-class.php';
require_once LIBRARY_PATH . '/lib/mvc-view-class.php';
require_once LIBRARY_PATH . '/lib/mvc-controller-class.php';

// Front Controller
require_once LIBRARY_PATH . '/lib/front-controller-class.php';
require_once LIBRARY_PATH . '/lib/action-class.php';

// Application Support
require_once LIBRARY_PATH . '/lib/eventlog-class.php';
require_once LIBRARY_PATH . '/lib/context-class.php'; // Application context
require_once LIBRARY_PATH . '/lib/request-class.php'; // Requests
require_once LIBRARY_PATH . '/lib/ooform-class3.php'; // Forms

// PEAR
require_once 'DB.php'; // Database
require_once "Auth.php"; // Authentication

$error_level = 0;

/**
 * Front Controller
 * This looks at the request and
 * loads the appropriate MVC
 * for the module.
 */


/**
 * Create application context.
 *
 */
	
	$c = new Context;
	
	$c->dsn = $dsn;
	
/**
 * Create authentication object.
 * Probably should create an application wrapper class for Auth
 */

 
 /*
 Auth has a nasty habit of guessing you want to throw a login form by default. I need to instantiate the auth object here, but don't want to throw the login here, each method in a module needs to determine if login is necessary to view.

opt
FALSE		Login form is suppressed so getAuth can handle access
TRUE		Login form is displayed

 */
 
 /**
 
 Long Explanation: Each module contains methods displaying content to the public or to logged in users with varying access permissions. If Auth were allowed to throw a login block at $auth->start, all public or nuanced access to these displays would be lost. I need access permissions set on methods on a case by case base.
 
 For all modules _except_ for login, it is clear that having Auth display the login form must be made _optional_. This is so execution can reach the module code before throwing a login block.
 
 (There is an exception, possible non-logged in status could be detected here and shortcircuit to login form here, but ONLY if the framework maintains a map of method access permissions).

For the login module, things are different. A user must be able to request /login/login and be presented with a login form if they are not logged in. 

Maybe I should never use Auth's login callback?
 
 Okay, Auth cannot display a login form unless 
 
 So I can get rid of the callback completely?
 
 
 */
 
 /**

 NO. TURN OFF AUTO LOGIN. I AM NOT USING IT.
 $opt = $_REQUEST['opt']; // this is only temporary until I figure out how to get Auth to cooperate
 if (isset($_REQUEST['opt']) && $_REQUEST['opt'] == 1) {
     $opt = true;
} else {
     $opt = false;
}
*/

//print "Login opt: ".$opt; 
// dummy
function loginFunction() {
}

$dsn = "mysql://cityg_8:gBhpj4Xj@db72c.pair.com/cityg_dev";
$params = array(
            "dsn" => $dsn,
            "table" => "users",
            "usernamecol" => "username",
            "passwordcol" => "password"
            );
			
$a = new Auth("DB", $params, "loginFunction", FALSE);

// need to add member var for auth
$c->auth = $a;

//print "Auth: ".$c->auth;

// this does not seem to be working
//$c->auth->setSessionname('AUTHUSER');
//$a->setSessionname('AUTHUSER');

$a->setExpire( 3600 ); // 60mins in seconds 

$a->start();


$username = $a->getUsername();
//print "<p>Username: $username</p>";
//log_err( __FILE__, __LINE__, "Status: ". $a->getStatus());
//print "Status: ". $a->getStatus();


		// assign dynamic data
		// All modules on the site could benefit from having
		// the name of the currently logged in user. This needs
		// to be displayed on nearly every page in an application.
		// this depends on auth module, should auth method name
		// change, this must be changed, be nice to have a wrapper
		// object around auth
		// yep, fails, context is not available here
 		//$this->assign('AUTH_USER_NAME', $this->c->auth-getUsername());
		
		define('AUTH_USER_NAME', $a->getUsername());
		
/**
 * Helper classes.
 *
 */

/**
 * Form handler
 * Note: plugins is part of context to organize all misc. helper functions under one property, so a bunch of properties do not have to be created ad hoc, for each helper.
Note: Creating the form handler object here could leave open the possiblity of it being reused for more than one form. Perhaps it should be created to the controller, assigned to the context there and then passed on to the model and view? If you create the object and then allow various code to access it, it could end up with properties from two forms and potential security issues and bugs. Since there is only one controller exeucting for each request, it could be created there more safely? Or perhaps since there is only one invocation of the framework for each requiest, this is not a problem? There will likely only be one form associated with a request, maybe it is good to create it here and discourage framework users from creating it.
 */
 
 /**
  * /remark the array is the only way I know of in PHP4 for dynamic class assignement, i.e. you can't say $c->plugins->form and expect it to know which plugin you want.
  */
$c->plugins[form] = new ooForm;


/**
 * Setup helper plugins.
 */
 
 require_once LIBRARY_PATH . '/lib/helpers.php';
 

/**
 * Setup actions.
 *
 */	

log_err( __FILE__, __LINE__, "Adding Actions");

// Debugging
//$tmp = '<pre>$c->actions' . "\n";
//$tmp = print_r( $c->actions );
//$tmp = "</pre>";
//log_err( __FILE__, __LINE__, $tmp);


// Debugging
// print "Params";
// print "<pre>";
// print_r( $c->request->params );
// print "</pre>";




 /**
  * Load the module specified in the
  * request, then pass the whole thing
  * or parts on to the controller.
  */


  $fc = new FrontController();
  
  
$target_module = $c->request->get_module();
$target_method = $c->request->get_action();


 
/**
 * Load controller module.
 *
 */
if( file_exists(MODULE_PATH ."/modules/$target_module/controller/$target_module.php") ) {

	require_once(MODULE_PATH ."/modules/$target_module/controller/$target_module.php");
} else {
	log_err( __FILE__, __LINE__, "Error: Module does not exist.");
	
	
	
/**
 A view _can_ be created and used without respect to 
 a controller or a framework external request. I wonder
 if the forward mechanism could be used to do this, some
 way of invoking a module completely internally, to do
 work like this?
	 $this->view = new MVC_View();
But I'm not sure I want to create another copy of Smarty to just output an
error message from the framework. There has to be a better way to do this. Can
I get hold of a view here? No, the view object is created within a controller.
Also, some mechanism would be needed to load "system" modules by default, which
breaks the assumption there is only one module active at any time, corresponding
to the invocation corresponding with the request.
 */	
	 
	// FIXME: replace with template page
	die("Critical Error: Module does not exist.");
	
	/*
	output 404 page
	header("HTTP/1.0 404 Not Found");
		
	*/
	
}

//$action_list = $c->get_actions($target_module);
  
  	// debug
	//print "<p>Request Module: ". $c->request->get_module() .'</p>';
	//print "<p>Request Method: ". $c->request->get_action() .'</p>';

/**
 * Include MVC for module.
 */
	require_once MODULE_PATH .'/modules/'. $c->request->get_module() .'/model/'. $c->request->get_module() .'.php';
	require_once MODULE_PATH .'/modules/'. $c->request->get_module() .'/view/'. $c->request->get_module() .'.php';

// fc now has action list

//print "<pre>";
//print_r( $fc );
//print "</pre>";




$action = $fc->get_action();

// debug
//print "<pre>Actions index.php";
//print_r( $action );
//print "END</pre>";

if( ! is_object( $action ) ) {
	die("Critical Error - action is not an object: $action");
}

$c->action = $action;


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
		
		$location = 'http://'. WEB_ROOT ."/index.php?action=login/login&rebound=$target_module%2F$target_method";
		
		// debug
		//print "Location: $location";
		
		header("Location: $location");
		exit;
	}
}

$c->action->invoke();

?>